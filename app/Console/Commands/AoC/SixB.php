<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class SixB extends Command
{
    protected $description = 'Day 6b: Memory Reallocation';
    protected $signature = 'aoc:6b {input}';
    public $memory_size;
    public $old_sets;

    public function handle()
    {
        $this->old_sets = collect();
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        $banks = $this->initializeBanks();

        do {
            $banks = $this->redistributeBlocks($banks);
            $this->storeOldSets($banks);
        } while ($this->setIsNew($banks));

        return $this->getIndexOfLastSet() - $this->getFirstIndexOfOldSet($banks);
    }

    protected function redistributeBlocks($banks)
    {
        $starting_bank = $this->getFirstIndexOfLargestBank($banks);
        $blocks_to_redistribute = $banks[$starting_bank];
        $banks[$starting_bank] = 0;

        return $banks->map(function ($blocks, $index) use ($starting_bank, $blocks_to_redistribute) {
            $steps_from_start = $index - $starting_bank + ($index >= $starting_bank ? 0 : $this->memory_size);
            $add_to_every_bank = floor($blocks_to_redistribute / $this->memory_size);
            $remaining_blocks = $blocks_to_redistribute % $this->memory_size;

            return $blocks +
                $add_to_every_bank +
                $this->addToRemainingBanks($steps_from_start, $remaining_blocks);
        });
    }

    protected function addToRemainingBanks($steps_from_start, $remaining_blocks)
    {
        return $steps_from_start > 0 && $steps_from_start <= $remaining_blocks ? 1 : 0;
    }

    protected function setIsNew($banks)
    {
        return $this->getFirstIndexOfOldSet($banks) == $this->getIndexOfLastSet();
    }

    protected function getFirstIndexOfLargestBank($banks)
    {
        $max = $banks->max();

        return $banks->search(function ($block) use ($max) {
            return $block == $max;
        });
    }

    protected function getFirstIndexOfOldSet($banks)
    {
        $current_set = $this->stringify($banks);

        return $this->old_sets->search(function ($set) use ($current_set) {
            return $set == $current_set;
        });
    }

    protected function getIndexOfLastSet()
    {
        return $this->old_sets->count() - 1;
    }

    protected function initializeBanks()
    {
        $banks = $this->getInput();
        $this->memory_size = $banks->count();
        $this->storeOldSets($banks);

        return $banks;
    }

    protected function getInput()
    {
        return collect(explode(' ', $this->argument('input')));
    }

    protected function storeOldSets($banks)
    {
        $this->old_sets->push($this->stringify($banks));
    }

    protected function stringify($banks)
    {
        return implode('|', $banks->toArray());
    }
}
