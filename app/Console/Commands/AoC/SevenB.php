<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class SevenB extends Command
{
    protected $description = 'Day 7b: Recursive Circus';
    protected $signature = 'aoc:7b {input}';

    public function handle()
    {
        $programs = $this->getInput()->mapWithKeys(function ($program) {
            return $this->parseInput($program);
        });

        $top = $this->getTopOfTree(clone($programs));
        $this->addNodeToTree($programs->get($top), $programs);
    }

    protected function getTopOfTree($programs)
    {
        return $programs->each(function($row) use ($programs) {
            $row->get('children')->map(function ($child) use ($programs) {
                return $programs->pull($child);
            });
        })->keys()[0];
    }

    protected function addNodeToTree($node, $programs)
    {
        $node = $node
            ->put('children', $node->get('children')
                ->map(function ($child) use ($programs) {
                    return $this->addNodeToTree($programs->get($child), $programs);
                }))
            ->put('total_weight', $node->get('weight') + $node->get('children')->sum('total_weight'));

        return $this->checkForImbalance($node) ?: $node;
    }

    protected function checkForImbalance($node)
    {
        $children = $node->get('children');
        $total_weights = $children->pluck('total_weight');
        $imbalance = $total_weights->max() - $total_weights->min();

        if ($imbalance) {
            $this->getIdealWeight($children, $imbalance);
        }
    }

    protected function getIdealWeight($children, $imbalance)
    {
        $sorted = $children->sortBy('total_weight')->values();
        $outlier_is_high =
            $sorted[0]->get('total_weight') ==
            $sorted[1]->get('total_weight');

        dd($outlier_is_high ?
            $sorted->last()->get('weight') - $imbalance :
            $sorted->first()->get('weight') + $imbalance);
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
