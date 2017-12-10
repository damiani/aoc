<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class TenA extends Command
{
    protected $description = 'Day 10a: Knot Hash';
    protected $signature = 'aoc:10a {input}';
    public $hash_length = 256;

    public function handle()
    {
        $result = $this->getInput()->reduce(function ($hash, $length) use (&$position, &$skip) {
            $hash = $this->reverseSection($hash, $position, $length);
            $position = ($position + $length + $skip) % $this->hash_length;
            $skip++;

            return $hash;
        }, collect(range(0, $this->hash_length - 1)));

        $this->info($result[0] * $result[1]);
    }

    protected function reverseSection($hash, $position, $length)
    {
        $shifted_hash = $this->shiftHash($hash, $position);
        $hash_with_reversed_section = $shifted_hash->splice(0, $length)->reverse()->merge($shifted_hash);

        return $this->shiftHash($hash_with_reversed_section, - $position);
    }

    protected function shiftHash($hash, $start_at)
    {
        return $hash->splice($start_at)->merge($hash);
    }

    protected function getInput()
    {
        return collect(explode(',', $this->argument('input')));
    }
}
