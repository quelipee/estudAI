<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Course extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    use RefreshDatabase;
    protected $fillable = [
        'title',
        'description',
        'category'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'courses_users')->withPivot('user_id')->withTimestamps();
    }
}
