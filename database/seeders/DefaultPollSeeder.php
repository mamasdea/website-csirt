<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Poll; // Pastikan model Poll ada dan benar

class DefaultPollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel polls sudah ada sebelum seeder ini dijalankan
        if (DB::table('polls')->count() == 0) {

            $defaultOptions = [
                ['id' => 1, 'text' => 'Sangat Bermanfaat', 'votes' => 0],
                ['id' => 2, 'text' => 'Cukup Bermanfaat', 'votes' => 0],
                ['id' => 3, 'text' => 'Kurang Bermanfaat', 'votes' => 0],
            ];

            Poll::create([
                'question' => 'Seberapa bermanfaat situs website ini?',
                'options_with_votes' => $defaultOptions,
                'is_active' => true,
            ]);

            $this->command->info('Default Poll berhasil ditambahkan.');
        } else {
            $this->command->warn('Tabel polls sudah berisi data. Seeder dilewati.');
        }
    }
}
