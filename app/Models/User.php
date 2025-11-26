<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship dengan role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Method untuk cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    // Alternatif: cek berdasarkan role_id (jika role_id 1 = admin)
    public function isAdminAlternative()
    {
        return $this->role_id === 1;
    }

    // Relationship dengan tickets yang dibuat user
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    // Relationship dengan tickets yang ditugaskan ke user
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Relationship dengan comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
