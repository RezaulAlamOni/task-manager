<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReminderSettingsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder Settings Cron Job';

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
        \Log::info("Reminder Cron is working fine!");
        $result = app('App\Http\Controllers\ReminderSettingsController')->sendDueDateEmail();
        $this->info('Reminder:Cron Command Run successfully!');
    }
}
