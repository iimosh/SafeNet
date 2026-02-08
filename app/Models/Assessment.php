<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    public function user() { return $this->belongsTo(User::class); }
    public function questionnaire() { return $this->belongsTo(Questionnaire::class); }
    public function answers() { return $this->hasMany(AssessmentAnswer::class); }

}
