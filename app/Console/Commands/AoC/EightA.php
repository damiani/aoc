<?php

namespace App\Console\Commands\AoC;

use Illuminate\Console\Command;

class EightA extends Command
{
    protected $description = 'Day 8a: I Heard You Like Registers';
    protected $signature = 'aoc:8a {input}';

    public function handle()
    {
        $registers = $this->getInput()->reduce(function ($carry, $input) {
            return $carry = $this->processInstruction($this->parseInstruction($input), $carry);
        }, []);

        $this->info(max($registers));
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
