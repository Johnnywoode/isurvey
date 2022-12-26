<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use HasFactory, SoftDeletes;

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
    protected $hidden = ['deleted_at'];

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
    protected $with = [];

    /**
     * Get the questions for the survey.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'survey_id', 'id');
    }

    /**
     * Get the user that created the survey.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

  public static function boot()
  {
    parent::boot();
    self::deleting(function ($survey) {
      $survey->questions()->each(function ($question) {
        $question->delete();
      });
    });
  }
}
