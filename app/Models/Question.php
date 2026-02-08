<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'category'
    ];
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

}
