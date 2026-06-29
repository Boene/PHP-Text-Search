<?php

$filePath_swords = __DIR__."/../content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$swords = json_decode($json_file_swords, true);


function normalize(string $text)
{
    return mb_strtolower($text);
}

function removePunctuationWithSpaces(string $text)
{
    return preg_replace("/[^a-z0-9ßäöü]+/i", " ", $text); // Replace one or more non-alphanumeric characters with a single space.
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
        if (in_array($word, $swords["stopwords"])) {

        } else {
            $return_text = $return_text . " " . $word;
        }
    }
    return $return_text;
}

function Preprocess(string $text, array $swords)
{
    $text = normalize($text);
    $text = removePunctuationWithSpaces($text);
    $text = trimWhitespaces($text);
    $text = removeStopwords($text, $swords);
    $text = trimWhitespaces($text);
    return $text;
}
