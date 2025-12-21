<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        // Логируем в файл
        Log::info('Job выполнен: ' . $this->message);

        // Также пишем в конкретный файл
        $logFile = base_path('job_test.txt');
        file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $this->message . PHP_EOL, FILE_APPEND);
    }
}
