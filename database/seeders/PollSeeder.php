<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poll;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Poll::create([
            'question' => 'Seberapa bermanfaat situs website ini?',
            'options_with_votes' => [
                ['id' => 1, 'text' => 'Sangat Bermanfaat', 'votes' => 0],
                ['id' => 2, 'text' => 'Cukup Bermanfaat', 'votes' => 0],
                ['id' => 3, 'text' => 'Kurang Bermanfaat', 'votes' => 0],
            ],
            'is_active' => true,
        ]);
    }
}
