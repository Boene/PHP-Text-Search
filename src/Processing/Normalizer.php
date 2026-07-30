<?php

class Normalizer
{
    /// ### Public Properties ### ///

    public bool $replace_numbers;

    /// ### Private Properties ### ///

    /// ### Constructor ### ///

    public function __construct(bool $replace_numbers)
    {
        $this->replace_numbers = $replace_numbers;
    }

    /// ### Public Functions ### ///

    public function preprocess(string $text): string
    {
        $text = $this->normalize($text);
        $text = $this->removePunctuation($text);
        $text = $this->trimWhitspaces($text);

        return $text;
    }

    /// ### Private Functions ### ///

    private function normalize(string $text): string
    {
        $text = str_replace(["ß", "ä", "ö", "ü"], ["ss", "ae", "oe", "ue"], $text);
        return mb_strtolower($text);
    }

    private function removePunctuation(string $text): string
    {
        if ($this->replace_numbers) {
            $text = preg_replace("/[^a-zßäöü]+/i", " ", $text);
        } else {
            $text = preg_replace("/[^a-z0-9ßäöü]+/i", " ", $text);
        }
        return $text;
    }

    private function trimWhitspaces(string $text): string
    {
        return trim($text);
    }
}
