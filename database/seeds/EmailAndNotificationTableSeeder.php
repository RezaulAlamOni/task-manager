<?php

use App\EmailAndNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailAndNotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_and_notifications')->truncate();
        DB::table('notification_user')->truncate();
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => 'Email frequency', 'unique_id' => 'emailFreq']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Every Update', 'unique_id' => 'emailFreq_everydayUpdate']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Daily Report', 'unique_id' => 'emailFreq_dailyReport']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Weekly Report', 'unique_id' => 'emailFreq_weeklyReport']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Monthly Report', 'unique_id' => 'emailFreq_monthlyReport']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Never', 'unique_id' => 'emailFreq_never']);
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => 'What would you like to be emailed - Each item will have yes or no', 'unique_id' => 'email']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Email me with anything I am on', 'unique_id' => $parent->unique_id.'_IAmOn']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Email me everything', 'unique_id' => $parent->unique_id.'_everything']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When you are added to a task', 'unique_id' => $parent->unique_id.'_whenAddedToTask']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When you are removed from a task', 'unique_id' => $parent->unique_id.'_whenRemovedFromTask']);
//        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => "When a task you're on is updated", 'unique_id' => 'emailTask']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Title changes', 'unique_id' => $parent->unique_id.'_titleChanged']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Description added or updated', 'unique_id' => $parent->unique_id.'_descriptionUpdated']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Comment left', 'unique_id' => $parent->unique_id.'_commentLeft']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => "When you are direct messaged (@ in a comment)", 'unique_id' => $parent->unique_id.'_commentMention']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is added', 'unique_id' => $parent->unique_id.'_taskAdded']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is updated', 'unique_id' => $parent->unique_id.'_taskUpdated']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is commented on', 'unique_id' => $parent->unique_id.'_taskCommented']);
    }
}
