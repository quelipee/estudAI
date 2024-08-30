<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'course_id',
        'topic_id'
    ];

    protected $hidden = [
        'user_id',
        'course_id',
        'topic_id'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class,'course_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class,'topic_id');
    }
}
