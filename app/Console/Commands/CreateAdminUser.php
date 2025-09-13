<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Enter admin name');
        $email = $this->ask('Enter admin email');
        $password = $this->secret('Enter admin password');
        
        // Get admin role
        $adminRole = Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('Admin role not found. Please run the database seeder first.');
            return 1;
        }
        
        // Create admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $adminRole->id,
        ]);
        
        $this->info('Admin user created successfully!');
        $this->info('Email: ' . $email);
        
        return 0;
    }
}
