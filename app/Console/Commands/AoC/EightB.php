<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class EightB extends Command
{
    protected $description = 'Day 8b: I Heard You Like Registers';
    protected $signature = 'aoc:8b {input}';

    public function handle()
    {
        $this->getInput()->reduce(function ($carry, $input) use (&$max) {
            $carry = $this->processInstruction($this->parseInstruction($input), $carry);
            $max = max(max($carry), $max);

            return $carry;
        }, [0]);

        $this->info($max);
    }

    protected function processInstruction($instruction, $registers)
    {
        if ($this->comparisonIsTrue($instruction, $registers)) {
            $registers = $this->modifyRegister($instruction, $registers);
        }

        return $registers;
    }

    protected function modifyRegister($instruction, $registers)
    {
        $registers[$instruction['register']] =
            array_get($registers, $instruction['register'], 0) +
            $this->getDirection($instruction['direction']) * $instruction['value'];

        return $registers;
    }

    protected function getDirection($direction)
    {
        return $direction == 'inc' ? 1 : -1;
    }

    protected function comparisonIsTrue($instruction, $registers)
    {
        $register = array_get($registers, $instruction['comparison_register'], 0);

        switch ($instruction['comparison']) {
            case '>': return $register > $instruction['comparison_value'];
            case '>=': return $register >= $instruction['comparison_value'];
            case '<': return $register < $instruction['comparison_value'];
            case '<=': return $register <= $instruction['comparison_value'];
            case '==': return $register == $instruction['comparison_value'];
            case '!=': return $register != $instruction['comparison_value'];
        }
    }

    protected function parseInstruction($input)
    {
        $instruction = [];

        list(
            $instruction['register'],
            $instruction['direction'],
            $instruction['value'],
            $instruction['comparison_register'],
            $instruction['comparison'],
            $instruction['comparison_value']
        ) = collect(explode(' ', str_replace('if ', '', $input)));

        return $instruction;
    }

    protected function getInput()
    {
        return collect(explode("\n", $this->argument('input')));
    }
}
