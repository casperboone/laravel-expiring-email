<?php

use CasperBoone\LaravelExpiringEmail\ExpiringEmailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/secure-mail/{expiringEmailIdentifier}', function (Request $request, $expiringEmailIdentifier) {
    abort_if(!$request->hasValidSignature(), 404);

    $email = ExpiringEmailModel::where('random_identifier', $expiringEmailIdentifier)->firstOrFail();

    abort_if($email->isExpired(), 404);

    return "<strong>{$email->subject}</strong><br />Expires at {$email->expires_at} {$email->body}";
})->name('expiring_mail.show');
