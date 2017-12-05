<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class OneB extends Command
{
    protected $description = 'Day 1b: Reverse Captcha, part two';
    protected $signature = 'aoc:1b {input}';

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
        return $this->argument('input')[$this->getHalfwayIndex($index)];
    }

    protected function getHalfwayIndex($index)
    {
        $size = strlen($this->argument('input'));

        return ($index + $size/2) % $size;
    }
}
