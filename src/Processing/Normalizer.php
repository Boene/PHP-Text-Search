<?php

class Normalizer
{
    /// ### Public Properties ### ///

    public bool $replace_numbers;           /// true: Numbers are collectively removed with punctuation || false: Numbers are kept like normal characters
    public bool $remove_hyphened_terms;         /// true: "IT-Security" becomes "IT Security" || false: "IT-Security" stays as is

    /// ### Private Properties ### ///

    /// ### Constructor ### ///

    public function __construct(bool $replace_numbers, bool $remove_hyphened_terms)
    {
        $this->replace_numbers = $replace_numbers;
        $this->remove_hyphened_terms = $remove_hyphened_terms;
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
            if ($this->remove_hyphened_terms) {
                $text = preg_replace("/[^a-zßäöü]+/iu", " ", $text);
            } else {
                $text = preg_replace("/(?<=[a-zäöüß])-(?=[a-zäöüß])/iu", "###HYPHEN###", $text);
                $text = preg_replace("/[^a-zßäöü]+/iu", " ", $text);
                $text = str_replace("###HYPHEN###", "-", $text);
            }
        } else {
            if ($this->remove_hyphened_terms) {
                $text = preg_replace("/[^a-z0-9ßäöü]+/iu", " ", $text);
            } else {
                $text = preg_replace("/(?<=[a-z0-9äöüß])-(?=[a-z0-9äöüß])/iu", "###HYPHEN###", $text);
                $text = preg_replace("/[^a-z0-9ßäöü]+/iu", " ", $text);
                $text = str_replace("###HYPHEN###", "-", $text);
            }
        }
        return $text;
    }

    private function trimWhitspaces(string $text): string
    {
        return trim($text);
    }
}
