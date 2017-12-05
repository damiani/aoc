<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class TwoB extends Command
{
    protected $description = 'Day 2b: Corruption Checksum';
    protected $signature = 'aoc:2b {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return collect($this->getRows())
            ->map(function ($row) {
                return $this->getRowQuotient($this->getDigits($row));
            })->sum();
    }

    protected function getRowQuotient($row)
    {
        return $row->reduce(function ($carry, $dividend) use ($row) {
            $quotient = $this->getEvenlyDisibleQuotient($dividend, $row);

            return $quotient ?: $carry;
        });
    }

    protected function getEvenlyDisibleQuotient($dividend, $row)
    {
        return $row->reduce(function ($carry, $divisor) use ($dividend) {
            return $this->dividesEvenly($dividend, $divisor) ? $dividend / $divisor : $carry;
        });
    }

    protected function dividesEvenly($dividend, $divisor)
    {
        return $dividend != $divisor && $dividend % $divisor == 0;
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
