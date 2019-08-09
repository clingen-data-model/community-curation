<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateVolunteerUser
{
    use Dispatchable, Queueable;

    protected $data;
    protected $defaultData = [
        'name' => null,
        'email' => null,
        'password' => null,
        'address' => null,
        'volunteer_type_id' => 1
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        
        $this->data = array_merge($this->defaultData, $data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $user = User::create([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => $this->data['email'] ?? \Hash::make(uniqid())
        ]);
        $user->assignRole('volunteer');
        $user->volunteer()->create([
            'address' => $this->data['address'],
            'volunteer_type_id' => $this->data['volunteer_type']
        ]);
    }
}
