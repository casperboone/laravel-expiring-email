<?php

namespace CasperBoone\LaravelExpiringEmail\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ExpiringEmailAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ExpiringEmailAttachment $expiringEmail) {
            $expiringEmail->random_identifier = Str::random(64);
        });
    }

    public function expiringEmail(): BelongsTo
    {
        return $this->belongsTo(ExpiringEmail::class);
    }

    public function url(): string
    {
        return URL::temporarySignedRoute(
            'expiring_mail.attachments.show',
            now()->addMinutes(30),
            ['attachmentIdentifier' => $this->random_identifier]
        );
    }
}
