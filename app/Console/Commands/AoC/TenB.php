<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class TenB extends Command
{
    protected $description = 'Day 10b: Knot Hash';
    protected $signature = 'aoc:10b {input}';
    public $hash_length = 256;

    public function handle()
    {
        $hash = collect(range(0, $this->hash_length - 1));
        $ascii = $this->convertToAscii($this->argument('input'));

        for ($i=0; $i < 64; $i++) {
            $hash = $ascii->reduce(function ($hash, $length) use (&$position, &$skip) {
                $hash = $this->reverseSection($hash, $position, $length);
                $position = ($position + $length + $skip) % $this->hash_length;
                $skip++;

                return $hash;
            }, $hash);
        }

        $hex_hash = implode('', $this->getDenseHash($hash)->map(function ($block) {
            return str_pad(dechex($block), 2, "0", STR_PAD_LEFT);
        })->toArray());

        $this->info($hex_hash);
    }

    protected function getDenseHash($hash)
    {
        return $hash->chunk(16)->map(function ($chunk) {
            return $this->getXorDigits($chunk);
        });
    }

    protected function getXorDigits($digits)
    {
        return collect($digits)->reduce(function ($carry, $item) {
            return $carry ^ $item;
        });
    }

    protected function convertToAscii($input)
    {
        return collect(str_split($input))->map(function ($character) {
            return ord($character);
        })->merge([17, 31, 73, 47, 23]);
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
}
