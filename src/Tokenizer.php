<?php

function Tokenize(string $input)
{
    $words = explode(" ", $input);
    return $words;
}
