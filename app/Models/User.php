<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
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
            // 'created_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Full admin: can manage (CRUD) master data.
     * Applies to: admin(is_admin=1), staff(is_admin=1)
     */
    public function canManageMasterData(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Can view master data pages.
     * Applies to: admin, foreman, staff (all is_admin values). Blocks manager.
     */
    public function canViewMasterData(): bool
    {
        return in_array($this->role, ['admin', 'foreman', 'staff']);
    }

    /**
     * Can write (create/edit/delete) non-master-data.
     * Applies to everyone except manager.
     */
    public function canWrite(): bool
    {
        return (bool) $this->is_admin || $this->role !== 'manager';
    }

    /**
     * Can write (CRUD) non-user master data (model items, process, DT).
     * Allows: is_admin=1 (any role), or role=staff (any is_admin).
     * Blocks: foreman, manager.
     */
    public function canManageNonUserMasterData(): bool
    {
        return (bool) $this->is_admin || $this->role === 'staff';
    }
}
