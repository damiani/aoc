<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class OneA extends Command
{
    protected $description = 'Day 1a: Reverse Captcha';
    protected $signature = 'aoc:1a {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return collect(str_split($this->argument('input')))
            ->map(function ($digit, $index) {
                return $digit == $this->getMatchDigit($digit, $index) ? $digit : 0;
            })->sum();
    }

    protected function getMatchDigit($digit, $index)
    {
        return $this->argument('input')[$this->getNextIndex($index)];
    }

    protected function getNextIndex($index)
    {
        $size = strlen($this->argument('input'));

        return $index + 1 >= $size ? 0 : $index + 1;
    }
}
