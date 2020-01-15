<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReportDailyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Report Email Cron Job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Daily Report Email Cron is working fine!");
        $result = app('App\Http\Controllers\ReportEmailController')->daily();
        $this->info('Daily-Report:Cron Command Run successfully!');
    }
}
