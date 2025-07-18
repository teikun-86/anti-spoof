# AntiSpoof — Laravel Middleware to Detect Request Spoofing

**AntiSpoof** is a Laravel package that protects your API against spoofed IPs, forged headers, and suspicious User-Agents.  
It’s plug-and-play, built with middleware + actions + events, and works beautifully with any Laravel-based API.

---

## Features

- Detect spoofed `X-Forwarded-For` headers
- Detect suspicious or blacklisted User-Agents
- Event-driven architecture (customizable responses)
- Fully configurable

---

## Installation

```bash
composer require teikun-86/anti-spoof
```

## Publish the Config File
To customize the package settings, publish the configuration file:

```bash
php artisan vendor:publish --tag=anti-spoof-config
```

## Configuration
The configuration file is located at `config/anti-spoof.php`. You can customize the following settings:
```php
<?php

return [
    /**
     * List of trusted proxies.
     * Requests coming from these IPs will not be checked for spoofing.
     * This is useful for load balancers or reverse proxies that handle the real IP.
     * You can specify IPs or CIDR ranges.
     */
    'trusted_proxies' => [
        // Example: '192.168.0.1', '10.0.0.1'
    ],

    /**
     * Determine if spoofing attempts should block access.
     * If true, a 403 response will be returned when spoofing is detected.
     * If false, spoofing attempts will be logged but not blocked.
     */
    'block' => true,

    /**
     * Message to return when spoofing is detected and blocking is enabled.
     * This message will be shown in the 403 response.
     * You can customize it to provide more context or instructions to the user.
     */
    'message' => 'Access denied.',

    'user_agent' => [
        /**
         * Enable or disable user agent spoofing detection.
         * If false, user agent checks will be skipped.
         */
        'enabled' => true,

        /**
         * Allowed user agent patterns.
         * If empty, all user agents are allowed except those in the 'blocked' list.
         */
        'allowed' => [
            // 'Mozilla/', 'Chrome/', 'Safari/', etc.
        ],

        /**
         * Block these patterns even if allowed list is empty.
         * This pattern takes priority over the allowed list.
         * If a user agent matches any of these patterns, it will be considered suspicious even if it is in the allowed list.
         * You can add common bot or script user agents here to prevent them from accessing your application.
         * Examples include 'curl', 'bot', 'python', 'scrapy', 'node-fetch', etc.
         */
        'blocked' => [
            'curl',
            'bot',
            'python',
            'scrapy',
            'node-fetch',
        ],
    ],
];
```

## Usage
### Middleware
#### Global Middleware
Register the middleware in your application's route or global middleware stack.
```php
// In app/Http/Kernel.php if you're using Laravel v10
protected $routeMiddleware = [
    // ...
    'anti-spoof' => \Teikun86\AntiSpoof\Http\Middleware\AntiSpoofingMiddleware::class,
];
```
```php
// in bootstrap/app.php if you're using Laravel v11 or later.
return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\Teikun86\AntiSpoof\Http\Middleware\AntiSpoofingMiddleware::class);
    })
    // ...
    ->create();
```
#### Route Level Middleware
```php
// In your routes/web.php or routes/api.php or other route files.
Route::middleware(['anti-spoof'])->group(function () {
    // Your routes here
});
```
### Actions
You can also use the `DetectSpoofing` action directly in your controllers or service classes:
```php
use Teikun86\AntiSpoof\Actions\DetectSpoofing;

public function someControllerMethod()
{
    $spoofed = DetectSpoofing::run();
    
    if ($spoofed) {
        // Spoofing detected, handle accordingly
    } else {
        // No spoofing detected, proceed with the request
    }
}
```

### Helper Class
#### AntiSpoof Class
You can use the `AntiSpoof` helper class to check for spoofing:
```php
use Teikun86\AntiSpoof\AntiSpoof;

...
$antiSpoof = app(AntiSpoof::class);
if ($antiSpoof->isSpoofed()) {
    // Handle spoofing
}
...

$spoofData = $antiSpoof->getSpoofData();
// $spoofData contains 'real_ip', 'forwarded_for', and 'user_agent
// You can log it, send an alert, or take any action you need

$realIp = $spoofData['real_ip'];
$forwardedFor = $spoofData['forwarded_for'];
$userAgent = $spoofData['user_agent'];
```
#### AntiSpoof Facade
You can also use the `AntiSpoof` facade for convenience:
```php
use Teikun86\AntiSpoof\Facades\AntiSpoof;
...
if (AntiSpoof::isSpoofed()) {
    // Handle spoofing
}
$spoofData = AntiSpoof::getSpoofData();
// $spoofData contains 'real_ip', 'forwarded_for', and 'user_agent
// You can log it, send an alert, or take any action you need
$realIp = $spoofData['real_ip'];
$forwardedFor = $spoofData['forwarded_for'];
$userAgent = $spoofData['user_agent'];
```

## Events
You can listen to these in your ServiceProvider or anywhere you like.

| Event                                          | Description                         |
| ---------------------------------------------- | ----------------------------------- |
| `Teikun86\AntiSpoof\Events\SpoofAttemptDetected`   | Fired when IP spoofing is detected  |
| `Teikun86\AntiSpoof\Events\ShadyUserAgentDetected` | Fired when a blocked UA is detected |

## Listening to Events
You can listen to these events in your `EventServiceProvider`:
```php
use Teikun86\AntiSpoof\Events\SpoofAttemptDetected;
use Teikun86\AntiSpoof\Events\ShadyUserAgentDetected;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SpoofAttemptDetected::class => [
            // Handle spoofing attempts
            App\Listeners\HandleSpoofAttempt::class,
        ],
        ShadyUserAgentDetected::class => [
            // Handle shady user agents
            App\Listeners\HandleShadyUserAgent::class,
        ],
    ];
}
```
or listen manually in other service providers:
```php
use Teikun86\AntiSpoof\Events\SpoofAttemptDetected;
use Teikun86\AntiSpoof\Events\ShadyUserAgentDetected;
use Illuminate\Support\Facades\Event;

...

Event::listen(SpoofAttemptDetected::class, function (SpoofAttemptDetected $event) {
    // Handle spoofing attempt
    // $event->realIp, $event->forwardedFor, $event->userAgent
});
Event::listen(ShadyUserAgentDetected::class, function (ShadyUserAgentDetected $event) {
    // Handle shady user agent
    // $event->userAgent
});
```

## Testing
```bash
./vendor/bin/phpunit
```
>  You can use Pest or PHPUnit. Test coverage includes IP spoofing and UA detection.

## Contributing
We welcome contributions! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## License
Developed by [Aziz Febriyanto](https://azizfsama.vercel.app/) with passion and paranoia 💀  
Security is not a feature — it’s a responsibility.

This package is open-sourced software licensed under the [MIT license](LICENSE).

