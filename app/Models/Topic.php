<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'courseTopics';
    protected $fillable = [
      'title',
      'topic',
      'course_id'
    ];

    protected $casts = [
        'topic' => 'string'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function messageHistory()
    {
        return $this->hasMany(MessageHistory::class);
    }
}
