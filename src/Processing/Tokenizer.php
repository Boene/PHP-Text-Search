<?php

class Tokenizer
{
    /// ### Public Properties ### ///

    /// ### Constructor ### ///

    public function __construct()
    {

    }

    /// ### Public Functions ### ///

    public function preprocess(string $input): array
    {
        $words = explode(" ", $input);
        return $words;
    }

    /// ### Private Functions ### ///

}
