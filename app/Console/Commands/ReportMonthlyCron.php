<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReportMonthlyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly Report Email Cron Job';

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
        \Log::info("Monthly Report Email Cron is working fine!");
        $result = app('App\Http\Controllers\ReportEmailController')->monthly();
        $this->info('Monthly-Report:Cron Command Run successfully!');
    }
}
