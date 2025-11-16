<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Document::class, 'favorites')->withTimestamps();
    }

    public function documentViews()
    {
        return $this->hasMany(DocumentView::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // Helper methods for roles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    public function canEdit(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }
}
