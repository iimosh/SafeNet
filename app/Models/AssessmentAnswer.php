<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    public function assessment() { return $this->belongsTo(Assessment::class); }
    public function question() { return $this->belongsTo(Question::class); }
    public function option() { return $this->belongsTo(Option::class); }

}
