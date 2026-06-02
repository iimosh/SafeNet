<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1e293b;
            margin: 40px;
        }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 16px; margin-top: 24px; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .meta { color: #666; font-size: 12px; margin-bottom: 24px; }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            text-align: left;
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        th { background: #f9fafb; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 11px; color: #999; text-align: center; }
    </style>
</head>
<body>

<h1>Извештај за дигитална безбедност</h1>
<p class="meta">
    Ученик: <strong>{{ $student->name }}</strong> &nbsp;|&nbsp;
    Датум: {{ now()->format('d.m.Y') }}
</p>

<h2>Ниво на ризик</h2>
<p>
    Вкупни поени: <strong>{{ $assessment->total_points }}</strong> &nbsp;|&nbsp;
    Ниво: <span class="badge {{ $assessment->risk_level }}">{{ ucfirst($assessment->risk_level) }}</span>
</p>
<h2>Распределба по категории</h2>
@if($breakdown->isNotEmpty())
    <table>
        <thead>
        <tr>
            <th>Категорија</th>
            <th>Поени</th>
            <th>Ризик</th>
            <th>Препорака</th>
        </tr>
        </thead>
        <tbody>
        @foreach($breakdown as $item)
            <tr>
                <td>{{ $item['category_name'] ?? '-' }}</td>
                <td>{{ $item['score'] ?? 0 }} / {{ $item['max_score'] ?? 0 }}</td>
                <td>{{ ucfirst($item['risk_level'] ?? '-') }}</td>
                <td>{{ $item['recommendation'] ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>Нема податоци по категории.</p>
@endif

@if($pairedAssessment)
    @php
        $studentAssessment = ($assessment->questionnaire?->target_role === 'student') ? $assessment : $pairedAssessment;
        $parentAssessment  = ($assessment->questionnaire?->target_role === 'parent')  ? $assessment : $pairedAssessment;

        $studentRows = collect($studentAssessment->category_breakdown ?? [])->keyBy('category_name');
        $parentRows  = collect($parentAssessment->category_breakdown ?? [])->keyBy('category_name');
        $allCategories = $studentRows->keys()->merge($parentRows->keys())->unique();
    @endphp

    <h2>Споредба: ученик vs родител</h2>
    <p>
        Глобално ниво — Ученик:
        <span class="badge {{ $studentAssessment->risk_level }}">{{ ucfirst($studentAssessment->risk_level) }}</span>
        &nbsp;|&nbsp; Родител:
        <span class="badge {{ $parentAssessment->risk_level }}">{{ ucfirst($parentAssessment->risk_level) }}</span>
    </p>
    <table>
        <thead>
        <tr>
            <th>Категорија</th>
            <th>Ученик (поени)</th>
            <th>Ученик ризик</th>
            <th>Родител (поени)</th>
            <th>Родител ризик</th>
        </tr>
        </thead>
        <tbody>
        @foreach($allCategories as $categoryName)
            @php $s = $studentRows->get($categoryName); $p = $parentRows->get($categoryName); @endphp
            <tr>
                <td>{{ $categoryName }}</td>
                <td>{{ $s ? ($s['score'].' / '.$s['max_score']) : '—' }}</td>
                <td>{{ $s ? ucfirst($s['risk_level']) : '—' }}</td>
                <td>{{ $p ? ($p['score'].' / '.$p['max_score']) : '—' }}</td>
                <td>{{ $p ? ucfirst($p['risk_level']) : '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<h2>Глобална препорака</h2>
<p>{{ $assessment->global_recommendation ?? 'Нема достапна препорака.' }}</p>

<div class="footer">
    SafeNet — Извештај генериран на {{ now()->format('d.m.Y H:i') }}
</div>

</body>
</html>
