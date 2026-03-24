<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'category_id',
        'audience',
        'risk_level',
        'is_global',
        'title',
        'text',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
