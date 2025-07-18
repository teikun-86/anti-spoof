<?php

namespace Teikun86\AntiSpoof\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isSpoofed(\Illuminate\Http\Request $request)
 * @method static array getSpoofData(\Illuminate\Http\Request $request)
 * @method static void validate(\Illuminate\Http\Request $request)
 */
class AntiSpoof extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'anti-spoof';
    }
}