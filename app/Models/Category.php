<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}
