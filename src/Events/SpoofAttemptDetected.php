<?php

namespace Teikun86\AntiSpoof\Events;

use Illuminate\Queue\SerializesModels;

class SpoofAttemptDetected
{
    use SerializesModels;

    public string $realIp;
    public string $forwardedIp;
    public ?string $userAgent;

    public function __construct(string $realIp, string $forwardedIp, ?string $userAgent = null)
    {
        $this->realIp = $realIp;
        $this->forwardedIp = $forwardedIp;
        $this->userAgent = $userAgent;
    }
}
