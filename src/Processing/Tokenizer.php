<?php

class Tokenizer
{
    /// ### Public Properties ### ///

    /// ### Constructor ### ///

    public function __construct()
    {

    }

    /// ### Public Functions ### ///

    public function tokenize(string $input): array
    {
        $words = explode(" ", $input);
        return $words;
    }

    /// ### Private Functions ### ///

}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function Tokenize(string $input)
{
    $words = explode(" ", $input);
    return $words;
}
