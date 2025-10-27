<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poll;

class ActivatePoll extends Command
{
    protected $signature = 'poll:activate';
    protected $description = 'Activates the first poll found in the database';

    public function handle()
    {
        $poll = Poll::first();
        if ($poll) {
            $poll->update(['is_active' => true]);
            $this->info('Poll activated successfully.');
        } else {
            $this->error('No poll found in the database.');
        }
    }
}
