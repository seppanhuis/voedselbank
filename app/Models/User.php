<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_DIRECTIE = 'directie';

    public const ROLE_MAGAZIJNMEDEWERKER = 'magazijnmedewerker';

    public const ROLE_VRIJWILLIGER = 'vrijwilliger';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

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

    /**
     * Determine whether the user has a given role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Determine whether the user is directie.
     */
    public function isDirectie(): bool
    {
        return $this->hasRole(self::ROLE_DIRECTIE);
    }

    /**
     * Determine whether the user is magazijnmedewerker.
     */
    public function isMagazijnmedewerker(): bool
    {
        return $this->hasRole(self::ROLE_MAGAZIJNMEDEWERKER);
    }

    /**
     * Determine whether the user is vrijwilliger.
     */
    public function isVrijwilliger(): bool
    {
        return $this->hasRole(self::ROLE_VRIJWILLIGER);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
