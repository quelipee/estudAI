<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class MessageHistory extends Model
{
    use HasFactory, HasApiTokens, HasUuids;
    protected $table = 'message_history';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'message',
        'role',
        'user_id',
        'course_id'
    ];

    public function courses(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
