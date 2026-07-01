<?php

class Preprocessor
{
    /// ### Public Properties ### ///

    public bool $replace_numbers;

    /// ### Private Properties ### ///

    private array $Stopwords;

    /// ### Constructor ### ///

    public function __construct(array $Stopwords, bool $replace_numbers = false)
    {
        $this->replace_numbers = $replace_numbers;
        $this->Stopwords = $Stopwords;
    }

    /// ### Public Functions ### ///

    public function preprocess(string $text): string
    {
        $text = $this->normalize($text);
        $text = $this->removePunctuation($text);
        $text = $this->trimWhitspaces($text);
        $text = $this->removeStopwords($text);
        $text = $this->trimWhitspaces($text);

        return $text;
    }

    /// ### Private Functions ### ///

    private function normalize(string $text): string
    {
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

    private function removeStopwords(string $text): string
    {
        $words = explode(" ", $text);
        $return_text = [];
        foreach ($words as $word) {
            if ((in_array($word, $this->Stopwords["stopwords"])) || (in_array($word, $this->Stopwords["context_words"]))) {
                continue;
            }
            $return_text[] = $word;

        }
        return implode(" ", $return_text);
    }
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


function normalize(string $text): string
{
    return mb_strtolower($text);
}

function removePunctuationWithSpaces(string $text, bool $replace_numbers = false)
{
    if ($replace_numbers == false) {
        return preg_replace("/[^a-z0-9ßäöü]+/i", " ", $text); // Replace one or more non-alphanumeric characters with a single space.
    } else {
        return preg_replace("/[^a-zßäöü]+/i", " ", $text);  // Replace one or more non-alphanumeric characters and numbers with a single space.
    }

}

function trimWhitespaces(string $text)
{
    return trim($text);
}

function removeStopwords(string $text, array $swords)
{
    $words = explode(" ", $text);
    $return_text = "";
    foreach ($words as $word) {
        if ((in_array($word, $swords["stopwords"])) || (in_array($word, $swords["context_words"]))) {

        } else {
            $return_text = $return_text . " " . $word;
        }
    }
    return $return_text;
}

function Preprocess(string $text, array $swords)
{
    $text = normalize($text);
    $text = removePunctuationWithSpaces($text, $replace_numbers = true);
    $text = trimWhitespaces($text);
    $text = removeStopwords($text, $swords);
    $text = trimWhitespaces($text);
    return $text;
}
