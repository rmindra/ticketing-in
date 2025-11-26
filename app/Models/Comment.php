<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'message',
        'user_id',
        'ticket_id',
        'is_admin',
        'is_system'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_system' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Method untuk cek apakah komentar dari admin
    public function isFromAdmin()
    {
        return $this->is_admin;
    }

    // Method untuk cek apakah komentar sistem
    public function isSystemMessage()
    {
        return $this->is_system;
    }

    // Method untuk format tampilan komentar
    public function getDisplayContent()
    {
        if ($this->is_system) {
            return '<em>' . $this->content . '</em>';
        }
        return $this->content;
    }

    /**
     * Accessor for 'content' to read the underlying 'message' column.
     */
    public function getContentAttribute()
    {
        return $this->attributes['message'] ?? null;
    }

    /**
     * Mutator for 'content' to write into the underlying 'message' column.
     */
    public function setContentAttribute($value)
    {
        $this->attributes['message'] = $value;
    }
}
