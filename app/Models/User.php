<?php

namespace App\Models;

use App\Support\AdminMenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
        'superadmin' => 'Super Admin',
        'admin' => 'Admin',
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /** Admin dengan akses semua menu (Super Admin selalu true). */
    public function hasFullAccess(): bool
    {
        return $this->isSuperAdmin() || $this->permissions === null;
    }

    /** Boleh membuka menu dengan kode $key (lihat App\Support\AdminMenu)? */
    public function canAccess(string $key): bool
    {
        return $this->hasFullAccess() || in_array($key, $this->permissions ?? [], true);
    }

    /** Label menu yang boleh diakses, untuk ditampilkan di daftar pengguna. */
    public function accessLabels(): array
    {
        $items = AdminMenu::items();

        return array_values(array_filter(array_map(fn ($key) => $items[$key]['label'] ?? null, $this->permissions ?? [])));
    }
}
