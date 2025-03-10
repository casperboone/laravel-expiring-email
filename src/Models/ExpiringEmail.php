<?php

namespace CasperBoone\LaravelExpiringEmail\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ExpiringEmail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ExpiringEmail $expiringEmail) {
            $expiringEmail->random_identifier = Str::random(64);
        });
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExpiringEmailAttachment::class);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function url(): string
    {
        return URL::temporarySignedRoute(
            'expiring_mail.show',
            $this->expires_at,
            ['expiringEmailIdentifier' => $this->random_identifier]
        );
    }
}
