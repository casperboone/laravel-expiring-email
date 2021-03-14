<?php

namespace CasperBoone\LaravelExpiringEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ExpiringEmailModel extends Model
{
    use HasFactory;

    protected $table = 'expiring_emails';
    protected $guarded = [];
    protected $dates = ['expires_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ExpiringEmailModel $expiringEmail) {
            $expiringEmail->random_identifier = Str::random(64);
        });
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
