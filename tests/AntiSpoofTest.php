<?php

use Illuminate\Http\Request;
use Teikun86\AntiSpoof\AntiSpoof;
use Orchestra\Testbench\TestCase;

class AntiSpoofTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('anti-spoof.trusted_proxies', ['127.0.0.1']);
        $app['config']->set('anti-spoof.user_agent.enabled', true);
        $app['config']->set('anti-spoof.user_agent.blocked', ['curl', 'bot']);
        $app['config']->set('anti-spoof.user_agent.allowed', []);
    }

    public function test_detects_spoofed_ip()
    {
        $request = Request::create('/spoofed', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.0.1',
            'HTTP_X_FORWARDED_FOR' => '8.8.8.8',
        ]);

        $spoof = new AntiSpoof($request);
        $this->assertTrue($spoof->hasForgedIp());
    }

    public function test_allows_trusted_proxy()
    {
        $request = Request::create('/trusted', 'GET', [], [], [], [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_X_FORWARDED_FOR' => '127.0.0.1',
        ]);

        $spoof = new AntiSpoof($request);
        $this->assertFalse($spoof->hasForgedIp());
    }

    public function test_detects_blocked_user_agent()
    {
        $request = Request::create('/ua', 'GET');
        $request->headers->set('User-Agent', 'curl/7.81.0');

        $spoof = new AntiSpoof($request);
        $this->assertTrue($spoof->hasSuspiciousUserAgent());
    }

    public function test_allows_clean_user_agent()
    {
        $request = Request::create('/ua', 'GET');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 10.0)');

        $spoof = new AntiSpoof($request);
        $this->assertFalse($spoof->hasSuspiciousUserAgent());
    }

    public function test_is_spoofed_combines_checks()
    {
        $request = Request::create('/combo', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.0.1',
            'HTTP_X_FORWARDED_FOR' => '8.8.8.8',
        ]);
        $request->headers->set('User-Agent', 'curl/7.81.0');

        $spoof = new AntiSpoof($request);
        $this->assertTrue($spoof->isSpoofed($request));
    }
}
