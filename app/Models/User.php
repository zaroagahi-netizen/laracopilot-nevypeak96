<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

/**
 * ZARO Platform User Model
 * 
 * @property string $relation_type Anne, Baba veya Öğretmen
 * @property array|null $children_data Maksimum 6 çocuk bilgisi
 * @property bool $kvkk_accepted KVKK onay durumu
 * @property string|null $birth_date Kullanıcı doğum tarihi
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'relation_type',
        'children_data',
        'kvkk_accepted',
        'birth_date',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'children_data' => 'array',
            'kvkk_accepted' => 'boolean',
            'birth_date' => 'date',
        ];
    }

    /**
     * Spatie Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'relation_type', 'children_data'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Filament panel access control
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Tüm kayıtlı kullanıcılar admin paneline erişebilir
        // İlerleyen aşamalarda role-based access eklenebilir
        return true;
    }

    /**
     * Kullanıcının 18 yaşından büyük olup olmadığını kontrol eder
     */
    public function isAdult(): bool
    {
        if (!$this->birth_date) {
            return false;
        }

        return $this->birth_date->age >= 18;
    }

    /**
     * Çocuk sayısını döndürür
     */
    public function getChildrenCount(): int
    {
        return is_array($this->children_data) ? count($this->children_data) : 0;
    }

    /**
     * Maksimum çocuk limitine ulaşılıp ulaşılmadığını kontrol eder
     */
    public function hasReachedChildrenLimit(): bool
    {
        return $this->getChildrenCount() >= 6;
    }
}