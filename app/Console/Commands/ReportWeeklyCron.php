<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReportWeeklyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly Report Email Cron Job';

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
        \Log::info("Weekly Report Email Cron is working fine!");
        $result = app('App\Http\Controllers\ReportEmailController')->weekly();
        $this->info('Weekly-Report:Cron Command Run successfully!');
    }
}
