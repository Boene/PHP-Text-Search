<?php

$filePath = __DIR__."/../content/content.json";
$json_file = file_get_contents($filePath);
$data = json_decode($json_file, true);

$text = "   a Warum liegtn hierß              überall . , ! rum, du #!?:,!# LALALALALA a   ";

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

function Preprocess(string $text)
{
    $text = normalize($text);
    $text = removePunctuationWithSpaces($text);
    $text = trimWhitespaces($text);
    return $text;
}

echo Preprocess($text);
