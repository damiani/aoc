<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class SevenA extends Command
{
    protected $description = 'Day 7a: Recursive Circus';
    protected $signature = 'aoc:7a {input}';

    public function handle()
    {
        $programs = $this->getInput()->mapWithKeys(function ($program) {
            return $this->parseInput($program);
        });

        $this->info($this->getTopOfTree($programs));
    }

    protected function getTopOfTree($programs)
    {
        return $programs->each(function($row) use ($programs) {
            $row->get('children')->map(function ($child) use ($programs) {
                return $programs->pull($child);
            });
        })->keys()[0];
    }

    protected function parseInput($program)
    {
        $program = collect(explode(' ', str_replace(['(', ')', '-> ', ','], '', $program)));

        return [
            $program->shift() => collect([
                'weight' => $program->shift(),
                'children' => $program,
            ])
        ];
    }

    protected function getInput()
    {
        return collect(explode("\n", $this->argument('input')));
    }
}
