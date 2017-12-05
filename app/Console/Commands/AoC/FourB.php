<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class FourB extends Command
{
    protected $description = 'Day 4b: High-Entropy Passphrases';
    protected $signature = 'aoc:4b {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return $this->getRows()
            ->map(function ($row) {
                $words = $this->getUnAnagramedWords($row);

                return $words->unique()->count() == $words->count() ? 1 : 0;
            })->sum();
    }

    protected function getUnAnagramedWords($row)
    {
        return $this->getWords($row)->map(function ($word) {
            return implode('', $this->sortLettersInWord($word));
        });
    }

    protected function sortLettersInWord($word)
    {
        return collect(str_split($word))->sort()->toArray();
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
