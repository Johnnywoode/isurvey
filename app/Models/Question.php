<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'survey_id',
      'question',
      'options',
      'type',
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
      'options' => 'array',
  ];

  /**
   * Get the survey that the question belongs to.
   */
  public function survey()
  {
    return $this->belongsTo(Survey::class, 'survey_id', 'id');
  }

  /**
   * Get the survey that the question belongs to.
   */
  public function answers()
  {
    return $this->hasMany(Answer::class);
  }

  public static function boot()
  {
    parent::boot();
    self::deleting(function ($question) {
      $question->answers()->each(function ($answer) {
        $answer->delete();
      });
    });
  }
}
