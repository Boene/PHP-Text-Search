<?php

class TokenPipeline
{
    /// ### Public Properties ### ///

    /// ### Private Properties ### ///

    private array $processors = [];

    /// ### Constructor ### ///

    public function __construct(array $processors)
    {
        $this->processors = $processors;
    }

    /// ### Public Functions ### ///

    public function add(Tokenprocessor $processor)
    {
        $this->processors[] = $processor;
    }

    public function run(array $tokens): array
    {
        foreach ($this->processors as $processor) {
            $tokens = $processor->token_process($tokens);
        }

        return $tokens;
    }

    /// ### Private Functions ### ///
}
