<?php

class Pipeline
{
    /// ### Public Properties ### ///

    /// ### Private Properties ### ///

    private array $processors = [];

    /// ### Constructor ### ///

    /// ### Public Functions ### ///

    public function add($processor)
    {
        $this->processors[] = $processor;
    }

    public function run(array $tokens): array
    {
        foreach ($this->processors as $processor) {
            $tokens = $processor->process($tokens);
        }

        return $tokens;
    }

    /// ### Private Functions ### ///
}
