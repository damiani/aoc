<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class FiveB extends Command
{
    protected $description = 'Day 5b: A Maze of Twisty Trampolines, All Alike';
    protected $signature = 'aoc:5b {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        $maze = $this->getMaze();
        $length = count($maze);
        $position = 0;
        $steps = 0;

        do {
            $next_position = $position + $maze[$position];
            $maze[$position] = $maze[$position] >= 3 ? $maze[$position] - 1 : $maze[$position] + 1;
            $position = $next_position;
            $steps++;
        } while ($position > -1 && $position < $length);

        return $steps;
    }

    protected function getMaze()
    {
        return explode("\n", $this->argument('input'));
    }
}
