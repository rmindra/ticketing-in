<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to',
        'category_id',
        'department_id',
        'user_confirmed',
        'admin_confirmed'
    ];

    protected $casts = [
        'user_confirmed' => 'boolean',
        'admin_confirmed' => 'boolean',
    ];

    // Relationship dengan user yang membuat ticket
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan admin yang ditugaskan
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relationship dengan kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship dengan department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship dengan comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // METHOD BARU: Cek apakah ticket sudah resolved
    public function isResolved()
    {
        return $this->status === 'Resolved';
    }

    // METHOD BARU: Cek apakah konfirmasi sudah lengkap
    public function isFullyConfirmed()
    {
        return $this->user_confirmed && $this->admin_confirmed;
    }

    // METHOD BARU: Cek apakah user sudah konfirmasi
    public function isUserConfirmed()
    {
        return (bool) $this->user_confirmed;
    }

    // METHOD BARU: Cek apakah admin sudah konfirmasi
    public function isAdminConfirmed()
    {
        return (bool) $this->admin_confirmed;
    }

    // METHOD BARU: Cek apakah ticket bisa dikonfirmasi oleh user
    public function canBeConfirmedByUser($userId)
    {
        return $this->isResolved() &&
            $userId == $this->user_id &&
            !$this->user_confirmed;
    }

    // METHOD BARU: Cek apakah ticket bisa dikonfirmasi oleh admin
    public function canBeConfirmedByAdmin($user)
    {
        return $this->isResolved() &&
            $user instanceof User &&
            $user->isAdmin() &&
            !$this->admin_confirmed;
    }

    // METHOD BARU: Cek apakah bisa menambah komentar
    // Method untuk cek apakah bisa menambah komentar
    public function canAddComments()
    {
        // Bisa menambah komentar jika ticket belum closed
        return !in_array($this->status, ['Closed']);
    }

    // Method untuk menambah komentar
    public function addComment($content, $user, $isSystem = false)
    {
        if (!$this->canAddComments()) {
            return false;
        }

        return $this->comments()->create([
            'content' => $content,
            'user_id' => $user->id,
            'is_admin' => $user->isAdmin(),
            'is_system' => $isSystem
        ]);
    }

    // METHOD BARU: Cek apakah ticket milik user tertentu
    public function isOwnedBy($userId)
    {
        return $this->user_id == $userId;
    }

    // METHOD BARU: Cek apakah ticket bisa dilihat oleh user
    public function canBeViewedBy($user)
    {
        if ($user instanceof User) {
            return $this->isOwnedBy($user->id) || $user->isAdmin();
        }
        return false;
    }

    // METHOD BARU: Cek apakah ticket sedang open
    public function isOpen()
    {
        return $this->status === 'Open';
    }

    // METHOD BARU: Cek apakah ticket sedang in progress
    public function isInProgress()
    {
        return $this->status === 'In Progress';
    }

    // METHOD BARU: Cek apakah ticket sudah closed
    public function isClosed()
    {
        return $this->status === 'Closed';
    }
}
