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

    public function findPaired(): ?self
    {
        if (! $this->questionnaire) {
            return null;
        }

        $oppositeRole = $this->questionnaire->target_role === 'student' ? 'parent' : 'student';

        $pairedQuestionnaire = Questionnaire::where('target_role', $oppositeRole)->latest()->first();

        if (! $pairedQuestionnaire) {
            return null;
        }

        return static::where('filled_for_user_id', $this->filled_for_user_id)
            ->where('questionnaire_id', $pairedQuestionnaire->id)
            ->where('id', '!=', $this->id)
            ->with('questionnaire')
            ->latest()
            ->first();
    }
}
