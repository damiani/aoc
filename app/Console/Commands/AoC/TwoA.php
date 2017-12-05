<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class TwoA extends Command
{
    protected $description = 'Day 2a: Corruption Checksum';
    protected $signature = 'aoc:2a {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return collect($this->getRows())
            ->map(function ($row) {
                $sorted_row = $this->getDigits($row)->sort();

                return $sorted_row->last() - $sorted_row->first();
            })->sum();
    }

    protected function getDigits($row)
    {
        return collect(explode("\t", $row));
    }

    protected function getRows()
    {
        return explode("\n", $this->argument('input'));
    }
}
