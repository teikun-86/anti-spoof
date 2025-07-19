<?php

namespace Teikun86\AntiSpoof\Actions;

use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsObject;
use Teikun86\AntiSpoof\Events\SpoofAttemptDetected;

class DetectSpoofing
{
    use AsObject;

    /**
     * Run the spoof detection action.
     * ---
     * returns true if the request is considered safe.
     * 
     * @return bool|never
     */
    public function handle(): bool
    {
        /**
         * @var \Teikun86\AntiSpoof\AntiSpoof
         */
        $antiSpoof = app('anti-spoof');

        if ($antiSpoof->isSpoofed()) {
            $spoofData = $antiSpoof->getSpoofData();
            Log::warning('[AntiSpoof] Possible spoofing detected', $spoofData);

            event(new SpoofAttemptDetected(...array_values($spoofData)));

            if (config('anti-spoof.block', false)) {
                abort(403, config('anti-spoof.message', 'Access denied.'));
            }
        }

        return true;
    }
}
