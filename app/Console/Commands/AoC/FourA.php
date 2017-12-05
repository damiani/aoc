<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class FourA extends Command
{
    protected $description = 'Day 4a: High-Entropy Passphrases';
    protected $signature = 'aoc:4a {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return $this->getRows()
            ->map(function ($row) {
                $words = $this->getWords($row);

                return $words->unique()->count() == $words->count() ? 1 : 0;
            })->sum();
    }

    protected function getWords($row)
    {
        return collect(explode(" ", $row));
    }

    protected function getRows()
    {
        return collect(explode("\n", $this->argument('input')));
    }
}
