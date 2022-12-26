<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = [ ];

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
  protected $hidden = [
        'deleted_at'
  ];

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
    return $this->belongsTo(Question::class, 'question_id', 'id');
  }

  /**
   * Get the user that the answer belongs to.
   */
  public function answerer()
  {
    return $this->hasOne(User::class, 'answered_by', 'id');
  }
}
