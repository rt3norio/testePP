<?php

use App\Services\LogOnlyAuthorizatorService;
use App\Services\WebMockyAuthorizatorService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertTrue;

class AuthorizatorServiceTest extends TestCase
{
    private $payer;
    private $payee;

    private function refreshTestData()
    {
        $this->payer = new stdClass();
        $this->payer->id = 1;
        $this->payee = new stdClass();
        $this->payee->id = 2;
    }

    public function testIfWebMockyImplementationTriesToCallHttpServer()
    {
        $this->refreshTestData();
        Http::shouldReceive('get')
            ->once()
            ->andReturn(['message' => 'Autorizado']);
        Log::shouldReceive('info')
            ->once()
            ->andReturn([]);
        $wmas = new WebMockyAuthorizatorService();
        assertTrue($wmas->authorizeTransaction($this->payer, $this->payee, 3));
    }

    public function testIfLogOnlyTriesToLog()
    {
        $this->refreshTestData();
        Log::shouldReceive('info')
            ->once()
            ->andReturn([]);

        $loas = new LogOnlyAuthorizatorService();
        assertTrue($loas->authorizeTransaction($this->payer, $this->payee, 3));
    }
}
