<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable //implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'email_verified_at',
    'two_factor_secret',
    'two_factor_recovery_codes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  /**
   * Get the surveys for the user.
   */
  public function surveys()
  {
    return $this->hasMany(Survey::class, 'created_by');
  }

  /**
   * Get the answers for the user.
   */
  public function answers()
  {
    return $this->hasMany(Answer::class, 'answered_by');
  }

  public static function boot()
  {
    parent::boot();
    self::deleting(function ($user) {
      $user->surveys()->each(function ($survey) {
        $survey->delete();
      });
    });
  }
}
