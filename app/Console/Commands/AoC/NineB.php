<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class NineB extends Command
{
    protected $description = 'Day 9b: Stream Processing';
    protected $signature = 'aoc:9b {input}';

    public function handle()
    {
        $this->info($this->countGarbage(
            $this->removeExclamations($this->argument('input'))
        ));
    }

    protected function removeExclamations($stream)
    {
        return preg_replace('/!./', '', $stream);
    }

    protected function countGarbage($stream)
    {
        preg_replace_callback('/,*<.*?>,*/', function($matches) use (&$garbage_count) {
            $garbage_count = $garbage_count + strlen(trim($matches[0], ',')) - 2;
        }, $stream);

        return $garbage_count;
    }
}
