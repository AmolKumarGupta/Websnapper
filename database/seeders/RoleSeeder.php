<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    protected $roles = [
        "admin",
        "client"
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = Role::select('name')->get()
            ->map(fn ($r) => $r->name)
            ->toArray();

        collect($this->roles)->each(function ($role) use($models) {
            if (in_array($role, $models)) {
                return;
            }

            Role::create(['name' => $role]);
        });
    }
}
