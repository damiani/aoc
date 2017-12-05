<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class ThreeB extends Command
{
    protected $description = 'Day 3b: Spiral Memory';
    protected $signature = 'aoc:3b {input} {--P|print}';

    public $column = 0;
    public $direction = 'east';
    public $row = 0;
    public $spiral;
    public $value = 1;
    public $width = 1;

    public function handle()
    {
        $result = $this->calculate();

        if ($this->option('print')) {
            $this->printSpiral();
        }

        $this->info($result);
    }

    protected function calculate()
    {
        $this->initializeSpiral();
        $this->buildSpiralTo($this->argument('input'));

        return $this->value;
    }

    protected function printSpiral()
    {
        $this->spiral->each(function ($row) {
            $this->comment($row);
        });
    }

    protected function initializeSpiral()
    {
        $this->spiral = collect([collect([1])]);
    }

    protected function buildSpiralTo($end)
    {
        do {
            $this->moveToNextPosition();
            $this->setValue();
        } while ($this->value <= $end);

        return $this->value;
    }

    protected function moveToNextPosition()
    {
        switch ($this->direction) {
            case 'east':
                $this->column++;

                if ($this->column + 1 > $this->width) {
                    $this->width = $this->width + 2;
                    $this->addNewEmptyRing();
                    $this->direction = 'north';
                }

                break;

            case 'north':
                $this->row--;

                if ($this->row == 0) {
                    $this->direction = 'west';
                }

                break;

            case 'west':
                $this->column--;

                if ($this->column == 0) {
                    $this->direction = 'south';
                }

                break;

            case 'south':
                $this->row++;

                if ($this->row + 1 == $this->width) {
                    $this->direction = 'east';
                }

                break;
        }
    }

    protected function addNewEmptyRing()
    {
        $this->addColumnsToLeftAndRight();
        $this->addRowsToTopAndBottom();
    }

    protected function addColumnsToLeftAndRight()
    {
        $this->spiral = $this->spiral->each(function ($row) {
            $row->prepend(0)->push(0);
        });
        $this->column++;
    }

    protected function addRowsToTopAndBottom()
    {
        $this->spiral
            ->prepend(collect()->pad($this->width, 0))
            ->push(collect()->pad($this->width, 0));
        $this->row++;
    }

    protected function setValue()
    {
        $this->calculateValue();
        $this->spiral[$this->row][$this->column] = $this->value;
    }

    protected function calculateValue()
    {
        $this->value = $this->getAdjacentValue(0, -1) +
            $this->getAdjacentValue(-1, -1) +
            $this->getAdjacentValue(-1, 0) +
            $this->getAdjacentValue(-1, 1) +
            $this->getAdjacentValue(0, 1) +
            $this->getAdjacentValue(1, 1) +
            $this->getAdjacentValue(1, 0) +
            $this->getAdjacentValue(1, -1);
    }

    protected function getAdjacentValue($x, $y)
    {
        $row = $this->row + $y;
        $column = $this->column + $x;

        if (! $this->spiral->has($row)) {
            return;
        }
        if ($this->spiral[$row]->has($column)) {
            return (int) $this->spiral[$row][$column];
        }
    }
}
