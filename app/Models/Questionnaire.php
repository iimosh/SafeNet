<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_role',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
