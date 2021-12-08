<?php

use App\Jobs\NotificationJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationJobTest extends TestCase
{

    public function testTransactionRelationshipWithUser()
    {
        $job = new NotificationJob('123', '50');

        Http::shouldReceive('get')
            ->once()
            ->andReturn([]);
        Log::shouldReceive('info')
            ->twice()
            ->andReturn([]);
        $job->handle();
    }
}
