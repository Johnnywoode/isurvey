<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $with = [
      'answerer'
    ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'answered_by',
    'survey_id',
    'question_id',
    'answer',
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
   * Get the question that the answer belongs to.
   */
  public function question()
  {
    return $this->hasOne(Question::class, 'id');
  }

  /**
   * Get the survey that the answer belongs to.
   */
  public function survey()
  {
    return $this->hasOne(Survey::class, 'id');
  }

  /**
   * Get the user that the answer belongs to.
   */
  public function answerer()
  {
    return $this->hasOne(User::class, 'id', 'answered_by');
  }
}
