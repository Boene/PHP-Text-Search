<?php

function normalize(string $text)
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
