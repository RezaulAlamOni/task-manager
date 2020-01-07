<?php

use App\EmailAndNotification;
use Illuminate\Database\Seeder;

class EmailAndNotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => 'Email frequency', 'unique_id' => 'emailFreq']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Every Update', 'unique_id' => 'emailFreq.everydayUpdate']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Daily Report', 'unique_id' => 'emailFreq.dailyReport']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Weekly Report', 'unique_id' => 'emailFreq.weeklyReport']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Monthly Report', 'unique_id' => 'emailFreq.monthlyReport']);
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => 'What would you like to be emailed - Each item will have yes or no', 'unique_id' => 'email']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Email me with anything I am on', 'unique_id' => $parent->unique_id.'.IAmOn']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Email me everything', 'unique_id' => $parent->unique_id.'.everything']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When you are added to a task', 'unique_id' => $parent->unique_id.'.whenAddedToTask']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When you are removed from a task', 'unique_id' => $parent->unique_id.'.whenRemovedFromTask']);
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => "When a task you're on is updated", 'unique_id' => 'emailTask']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Title changes', 'unique_id' => $parent->unique_id.'.titleChanged']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Description added or updated', 'unique_id' => $parent->unique_id.'.descriptionUpdated']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'Comment left', 'unique_id' => $parent->unique_id.'.commentLeft']);
        $parent = EmailAndNotification::create(['parent_id' => null, 'title' => "When you are direct messaged (@ in a comment)", 'unique_id' => 'directMessage']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is added', 'unique_id' => $parent->unique_id.'.taskAdded']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is updated', 'unique_id' => $parent->unique_id.'.taskUpdated']);
        EmailAndNotification::create(['parent_id' => $parent->id, 'title' => 'When any task is commented on', 'unique_id' => $parent->unique_id.'.taskCommented']);
    }
}
