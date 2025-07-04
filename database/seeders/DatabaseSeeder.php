<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $agent1 = User::create([
            'name' => 'Agent',
            'email' => 'agent@agent.com',
            'password' => Hash::make('password'),
            'role' => 'support_agent'
        ]);

        $agent2 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@support.com',
            'password' => Hash::make('password'),
            'role' => 'support_agent'
        ]);

        $agent3 = User::create([
            'name' => 'Carol Wilson',
            'email' => 'carol@support.com',
            'password' => Hash::make('password'),
            'role' => 'support_agent'
        ]);


        $user1 = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        Ticket::create([
            'subject' => 'Login Issue',
            'description' => 'Cannot login to my account',
            'priority' => 'High',
            'status' => 'assigned',
            'assigned_to' => $agent1->id,
            'user_id' => $user1->id,
        ]);

        Ticket::create([
            'subject' => 'Feature Request',
            'description' => 'Would like to see dark mode',
            'priority' => 'Low',
            'status' => 'open',
            'user_id' => $user1->id,
        ]);

        Ticket::create([
            'subject' => 'Bug Report',
            'description' => 'Page not loading correctly',
            'priority' => 'Medium',
            'status' => 'assigned',
            'assigned_to' => $agent2->id,
            'user_id' => $user2->id,
        ]);
    }
}
