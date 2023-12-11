<?php

namespace Database\Seeders;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = Plan::whereIn('name', array_keys(config('plans')))
            ->get()
            ->map(fn ($m) => $m->name)
            ->toArray();

        $data = collect(config('plans'))
            ->map(function ($plan, $key) {
                return [
                    "name" => $key, 
                    "data" => json_encode($plan),
                    "created_at" => Carbon::now(),
                ];
            })
            ->filter(fn ($plan) => !in_array($plan['name'], $names))
            ->toArray();

        Plan::insert(array_values($data));
    }
}
