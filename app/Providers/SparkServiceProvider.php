<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Compltit',
        'product' => 'Your Product',
        'street' => 'PO Box 111',
        'location' => 'Your Town, NY 12345',
        'phone' => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = "cs@compltit.com";

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        "eeyonw@gmail.com", "bnatter123@gmail.com"
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        Spark::useStripe()->noCardUpFront()->teamTrialDays(10);

        Spark::freeTeamPlan()
            ->price(0)
            ->maxTeamMembers(1)
            ->features([
                'First', 'Second', 'Third'
            ]);

        Spark::teamPlan('(2) Team Members', 'cit-users-2')
            ->price(10)
            ->maxTeamMembers(2)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(3) Team Members', 'cit-users-3')
            ->price(20)
            ->maxTeamMembers(3)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(4) Team Members', 'cit-users-4')
            ->price(30)
            ->maxTeamMembers(4)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(5) Team Members', 'cit-users-5')
            ->price(40)
            ->maxTeamMembers(5)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(6) Team Members', 'cit-users-6')
            ->price(50)
            ->maxTeamMembers(6)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(7) Team Members', 'cit-users-7')
            ->price(60)
            ->maxTeamMembers(7)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(8) Team Members', 'cit-users-8')
            ->price(70)
            ->maxTeamMembers(8)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(9) Team Members', 'cit-users-9')
            ->price(80)
            ->maxTeamMembers(9)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(10) Team Members', 'cit-users-10')
            ->price(90)
            ->maxTeamMembers(10)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(11) Team Members', 'cit-users-11')
            ->price(100)
            ->maxTeamMembers(11)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(12) Team Members', 'cit-users-12')
            ->price(110)
            ->maxTeamMembers(12)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(13) Team Members', 'cit-users-13')
            ->price(120)
            ->maxTeamMembers(13)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(14) Team Members', 'cit-users-14')
            ->price(130)
            ->maxTeamMembers(14)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(15) Team Members', 'cit-users-15')
            ->price(140)
            ->maxTeamMembers(15)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(16) Team Members', 'cit-users-16')
            ->price(150)
            ->maxTeamMembers(16)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(17) Team Members', 'cit-users-17')
            ->price(160)
            ->maxTeamMembers(17)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(18) Team Members', 'cit-users-18')
            ->price(170)
            ->maxTeamMembers(18)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(19) Team Members', 'cit-users-19')
            ->price(180)
            ->maxTeamMembers(19)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(20) Team Members', 'cit-users-20')
            ->price(190)
            ->maxTeamMembers(20)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(21) Team Members', 'cit-users-21')
            ->price(200)
            ->maxTeamMembers(21)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(22) Team Members', 'cit-users-22')
            ->price(210)
            ->maxTeamMembers(22)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(23) Team Members', 'cit-users-23')
            ->price(220)
            ->maxTeamMembers(23)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(24) Team Members', 'cit-users-24')
            ->price(230)
            ->maxTeamMembers(24)
            ->features([
                'First', 'Second', 'Third'
            ]);
        
        Spark::teamPlan('(25) Team Members', 'cit-users-25')
            ->price(240)
            ->maxTeamMembers(25)
            ->features([
                'First', 'Second', 'Third'
            ]);
    }
}
