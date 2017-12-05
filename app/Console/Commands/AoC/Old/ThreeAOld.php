<?php

namespace App\Console\Commands\AoC\Old;

use Illuminate\Console\Command;

class ThreeAOld extends Command
{
    protected $description = 'Day 3a: Spiral Memory';
    protected $signature = 'aoc:3a-old {input}';

    public function handle()
    {
        $this->info($this->calculate());
    }

    protected function calculate()
    {
        return $this->getManhattanDistanceTo($this->argument('input'));
    }

    protected function getManhattanDistanceTo($end)
    {
        $completed_square_length = $this->getCompletedSquareLength($end);

        if ($end == $completed_square_length ** 2) {
            return $completed_square_length - 1;
        }

        $outer_square_length = $completed_square_length + 2;

        return $this->getDistanceFromEdgeToCenter($outer_square_length) +
            $this->getDistanceAlongEdgeToCenter($outer_square_length, $end);
    }

    protected function getCompletedSquareLength($end)
    {
        $closest_root = (int) floor(sqrt($end));

        return $closest_root - ($closest_root % 2 ? 0 : 1);
    }

    protected function getDistanceFromEdgeToCenter($outer_square_length)
    {
        return (int) floor($outer_square_length / 2);
    }

    protected function getDistanceAlongEdgeToCenter($outer_square_length, $end)
    {
        $steps_along_outer_square = $end - $this->getFirstDigitOfOuterSquare($outer_square_length);
        $side = $this->getEndingSide($steps_along_outer_square, $outer_square_length);

        return abs($this->getMiddleDigitOfSide($side, $outer_square_length) - $end);
    }

    protected function getFirstDigitOfOuterSquare($outer_square_length)
    {
        return ($outer_square_length - 2) ** 2 + 1;
    }

    protected function getEndingSide($steps_along_outer_square, $outer_square_length)
    {
        $digits_per_side = $outer_square_length - 1;

        return (int) floor($steps_along_outer_square / $digits_per_side);
    }

    protected function getMiddleDigitOfSide($side, $outer_square_length)
    {
        return ($this->getFirstDigitOfOuterSquare($outer_square_length) - 1) +
            ($this->getDistanceFromEdgeToCenter($outer_square_length) * ($side * 2 + 1));
    }
}
