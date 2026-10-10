<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email?}';
    protected $description = 'Create or promote a SutharTech admin account';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email') ?? $this->ask('Admin email')));
        $name = $this->ask('Admin name');
        $password = $this->secret('Password (minimum 12 characters)');
        $confirmation = $this->secret('Confirm password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12 || !hash_equals($password, $confirmation) || !$name) {
            $this->error('Enter a valid email, name, and password of at least 12 characters.');
            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info("Admin account ready for {$email}.");
        return self::SUCCESS;
    }
}
