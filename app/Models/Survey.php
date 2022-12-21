<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by',
        'title',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
    * The relationships that should always be loaded.
    *
    * @var array
    */
    protected $with = ['creator'];

    /**
     * Get the questions for the survey.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the user that created the survey.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
