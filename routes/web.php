<?php

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmailAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/secure-mail/{expiringEmailIdentifier}', function (Request $request, $expiringEmailIdentifier) {
    abort_if(!$request->hasValidSignature(), 404);

    $email = ExpiringEmail::where('random_identifier', $expiringEmailIdentifier)->firstOrFail();

    abort_if($email->isExpired(), 404);

    return view('expiring-email::expiring_email', compact('email'));
})->name('expiring_mail.show');


Route::get('/secure-mail/attachments/{attachmentIdentifier}', function (Request $request, $attachmentIdentifier) {
    abort_if(!$request->hasValidSignature(), 404);

    $attachment = ExpiringEmailAttachment::where('random_identifier', $attachmentIdentifier)->firstOrFail();

    abort_if($attachment->expiringEmail->isExpired(), 404);

    return response()->streamDownload(
        function () use ($attachment) {
            echo $attachment->contents;
        },
        $attachment->filename,
        ['Content-Type' => $attachment->mime]
    );
})->name('expiring_mail.attachments.show');
