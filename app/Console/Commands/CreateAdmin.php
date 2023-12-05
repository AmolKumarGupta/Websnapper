<?php

namespace App\Console\Commands;

use App\Events\Admin;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(label: 'Enter name of admin:', required: true);

        $email = text(
            label: 'Enter email of admin:', 
            required: true, 
            validate: fn (string $val) => match (true) {
                (User::where('email', $val)->first() != null) => "The email already exists!", 
                default => null
            }
        );

        $pwd = password(
            label: 'Enter password of admin:', 
            required: true, 
            validate: fn (string $value) => match (true) {
                strlen($value) < 4 => 'The password must be at least 3 characters.',
                strlen($value) > 255 => 'The password must not exceed 255 characters.',
                default => null
            }
        );

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pwd),
        ]);

        event(new Admin($user));
    }
}
