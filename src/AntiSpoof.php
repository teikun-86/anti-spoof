<?php

namespace Teikun86\AntiSpoof;

use Illuminate\Http\Request;
use Teikun86\AntiSpoof\Events\ShadyUserAgentDetected;

class AntiSpoof
{
    public function __construct(private Request $request) {}

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function isSpoofed(): bool
    {
        $request = $this->request;
        return $this->hasForgedIp($request) || $this->hasSuspiciousUserAgent($request);
    }

    public function hasForgedIp(): bool
    {
        $request = $this->request;
        $realIp = $request->ip();
        $forwarded = $request->header('X-Forwarded-For');

        return $forwarded
            && $forwarded !== $realIp
            && !in_array($forwarded, config('anti-spoof.trusted_proxies', []));
    }

    public function hasSuspiciousUserAgent(): bool
    {
        $request = $this->request;
        if (!config('anti-spoof.user_agent.enabled', true)) {
            return false;
        }

        $ua = strtolower($request->userAgent() ?? '');

        $allowed = array_map('strtolower', config('anti-spoof.user_agent.allowed', []));
        $blocked = array_map('strtolower', config('anti-spoof.user_agent.blocked', []));

        // Blocked patterns take priority. So, even if the UA is in allowed list, if it matches a blocked pattern, it's considered suspicious.
        foreach ($blocked as $bad) {
            if (str_contains($ua, $bad)) {
                event(new ShadyUserAgentDetected(...array_values($this->getSpoofData())));
                return true;
            }
        }

        // If allowed list is not empty, match only those.
        if (!empty($allowed)) {
            foreach ($allowed as $good) {
                if (str_contains($ua, $good)) {
                    return false;
                }
            }

            event(new ShadyUserAgentDetected(...array_values($this->getSpoofData())));
            return true;
        }

        return false;
    }

    public function getSpoofData(): array
    {
        $request = $this->request;
        return [
            'real_ip' => $request->ip(),
            'forwarded_for' => $request->header('X-Forwarded-For'),
            'user_agent' => $request->userAgent(),
        ];
    }

    public function validate(): void
    {
        $request = $this->request;
        if ($this->isSpoofed($request)) {
            abort(403, config('anti-spoof.message', 'Access denied.'));
        }
    }

    public function getUserAgentFlag(): string
    {
        $request = $this->request;
        $ua = strtolower($request->userAgent() ?? '');

        foreach (config('anti-spoof.user_agent.blocked', []) as $bad) {
            if (str_contains($ua, strtolower($bad))) {
                return "Blocked pattern: $bad";
            }
        }

        $allowed = config('anti-spoof.user_agent.allowed', []);
        if (!empty($allowed)) {
            foreach ($allowed as $good) {
                if (str_contains($ua, strtolower($good))) {
                    return 'Matched allowed pattern';
                }
            }

            return 'No allowed pattern matched';
        }

        return 'Clean or not validated';
    }
}