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
}
