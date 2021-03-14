# 📬 Auto-expiring email notifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/casperboone/laravel-expiring-email.svg?style=flat-square)](https://packagist.org/packages/casperboone/laravel-expiring-email)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/casperboone/laravel-expiring-email/run-tests?label=tests)](https://github.com/casperboone/laravel-expiring-email/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/casperboone/laravel-expiring-email/Check%20&%20fix%20styling?label=code%20style)](https://github.com/casperboone/laravel-expiring-email/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/casperboone/laravel-expiring-email.svg?style=flat-square)](https://packagist.org/packages/casperboone/laravel-expiring-email)


This package allows you to easily send expiring emails.
This is useful for cases where you do not want to send sensitive data over email directly and you want to make that no sensitive information is kept in a recipient's mailbox without having control over that data.

By using the expiring email driver of this package instead of the regular email driver in a notification, all that is sent to the receiver is a signed and expiring link to the original content of the email.

## Installation

You can install the package via composer:

```bash
composer require casperboone/laravel-expiring-email
```

You should publish and run the migrations with:

```bash
php artisan vendor:publish --provider="CasperBoone\LaravelExpiringEmail\LaravelExpiringEmailServiceProvider" --tag="laravel-expiring-email-migrations"
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="CasperBoone\LaravelExpiringEmail\LaravelExpiringEmailServiceProvider" --tag="laravel-expiring-email-config"
```

You can optionally publish the views to customize the replacement email with:
```bash
php artisan vendor:publish --provider="CasperBoone\LaravelExpiringEmail\LaravelExpiringEmailServiceProvider" --tag="laravel-expiring-email-views"
```

## Usage

The basic usage of this package is very easy, all you need to do is replace `'email'` in your `via()` method of a notification to the expiring email notification channel.

```diff
 public function via($notifiable): array
 {
-    return ['mail'];
+    return [ExpiringEmailChannel::class];
 }
```

This sends an email to the original receiver with a secret expiring link to the content of the original email.
The default expiration date is set in the config file but can be set on a case-by-case basis in the notification using `ExpiringMailMessage`:

```diff
 public function toMail($notifiable): MailMessage
 {
-    return (new MailMessage)
+    return (new ExpiringMailMessage)
         ->subject('Secret document')
+        ->expiresInDays($this->expiresInDays)
         ->markdown('secret_document_email');
 }
```


## Development

**Testing**
```bash
composer test
```

**Static analysis**
```bash
composer psalm
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Casper Boone](https://github.com/casperboone)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
