<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'user_id',
        'filled_for_user_id',
        'questionnaire_id',
        'total_points',
        'max_points',
        'risk_level',
        'global_recommendation',
        'category_breakdown',
    ];

    protected $casts = [
        'category_breakdown' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filledFor()
    {
        return $this->belongsTo(User::class, 'filled_for_user_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    /**
     * Find the counterpart assessment for the same child filled in from the
     * opposite perspective. When $viewer is a parent, the pair is scoped to
     * what *that* parent is allowed to see (their own submissions + the child's
     * own self-submission); other parents' submissions are excluded.
     */
    public function findPaired(?User $viewer = null): ?self
    {
        if (! $this->questionnaire) {
            return null;
        }

        $oppositeRole = $this->questionnaire->target_role === 'student' ? 'parent' : 'student';

        $pairedQuestionnaire = Questionnaire::where('target_role', $oppositeRole)->latest()->first();

        if (! $pairedQuestionnaire) {
            return null;
        }

        $query = static::where('filled_for_user_id', $this->filled_for_user_id)
            ->where('questionnaire_id', $pairedQuestionnaire->id)
            ->where('id', '!=', $this->id);

        if ($viewer && $viewer->isParent()) {
            $query->where(function ($q) use ($viewer) {
                $q->where('user_id', $viewer->id)                   // viewer's own
                  ->orWhereColumn('user_id', 'filled_for_user_id'); // child's own
            });
        }

        return $query->with('questionnaire')->latest()->first();
    }
}
