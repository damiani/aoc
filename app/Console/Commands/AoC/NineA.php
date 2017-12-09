<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class NineA extends Command
{
    protected $description = 'Day 9a: Stream Processing';
    protected $signature = 'aoc:9a {input}';

    public function handle()
    {
        $this->info($this->getValueOfGroup(
            $this->convertToArray(
                $this->removeGarbage(
                    $this->removeExclamations($this->argument('input'))
                )
            )
        ));
    }

    protected function getValueOfGroup($group, $depth = 1)
    {
        return collect($group)->reduce(function ($total, $group) use ($depth) {
            return $total + $this->getValueOfGroup($group, $depth + 1);
        }, $depth);
    }

    protected function removeExclamations($stream)
    {
        return preg_replace('/!./', '', $stream);
    }

    protected function removeGarbage($stream)
    {
        return preg_replace('/,*<.*?>,*/', '', $stream);
    }

    protected function convertToArray($stream)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $stream));
    }
}
