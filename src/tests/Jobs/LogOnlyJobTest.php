<?php

use App\Jobs\LogOnlyJob;
use App\Jobs\NotificationJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LogOnlyJobTest extends TestCase
{

    public function testTransactionRelationshipWithUser()
    {
        $job = new LogOnlyJob('123', '50');
        Log::shouldReceive('info')
            ->twice()
            ->andReturn([]);
        $job->handle();
    }
}
