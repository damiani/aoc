<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class ElevenA extends Command
{
    protected $description = 'Day 11a: Hex Ed';
    protected $signature = 'aoc:11a {input}';
    public $hex_neighbors = [
        'n' => [
            'opposite' => 's',
            'adjacent' => 'se',
            'collapse_to' => 'ne',
        ],
        's' => [
            'opposite' => 'n',
            'adjacent' => 'nw',
            'collapse_to' => 'sw',
        ],
        'ne' => [
            'opposite' => 'sw',
            'adjacent' => 's',
            'collapse_to' => 'se',
        ],
        'se' => [
            'opposite' => 'nw',
            'adjacent' => 'sw',
            'collapse_to' => 's',
        ],
        'nw' => [
            'opposite' => 'se',
            'adjacent' => 'ne',
            'collapse_to' => 'n',
        ],
        'sw' => [
            'opposite' => 'ne',
            'adjacent' => 'n',
            'collapse_to' => 'nw',
        ],
    ];

    public function handle()
    {
        $this->info($this->getShortestDistance($this->getInput()));
    }

    protected function getShortestDistance($path)
    {
        $directions = $this->getDirectionCounts($path);

        do {
            $distance = $directions->sum();
            $directions = $this->simplifyPath($directions);
        } while ($distance != $directions->sum());

        return $distance;
    }

    protected function getDirectionCounts($path)
    {
        return $path->mapToGroups(function ($item, $key) {
            return [$item => $item];
        })->map(function ($group) {
            return $group->count();
        });
    }

    protected function simplifyPath($directions)
    {
        return $this->collapseAdjacentDirections(
            $this->cancelOppositeDirections($directions)
        );
    }

    protected function cancelOppositeDirections($directions)
    {
        collect($this->hex_neighbors)->each(function ($neighbors, $hex) use ($directions) {
            extract($neighbors);

            if ($common = min([$directions->get($hex), $directions->get($opposite)])) {
                $directions->put($hex, $directions->get($hex) - $common);
                $directions->put($opposite, $directions->get($opposite) - $common);
            }
        });

        return $directions;
    }

    protected function collapseAdjacentDirections($directions)
    {
        collect($this->hex_neighbors)->each(function ($neighbors, $hex) use ($directions) {
            extract($neighbors);

            if ($common = min([$directions->get($hex), $directions->get($adjacent)])) {
                $directions->put($hex, $directions->get($hex) - $common);
                $directions->put($adjacent, $directions->get($adjacent) - $common);
                $directions->put($collapse_to, $directions->get($collapse_to) + $common);
            }
        });

        return $directions;
    }

    protected function getInput()
    {
        return collect(explode(',', $this->argument('input')));
    }
}
