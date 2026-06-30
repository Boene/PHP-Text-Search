<?php

///////////////////////////////////////////////////////////////
//////////////// Access and format the content ////////////////

function getDocumentById(int $id, array $documents): ?array
{
    foreach ($documents as $document) {
        if ($document["id"] == $id) {
            return $document;
        }
    }

    return null;
}

function documentToString(array $document): string
{
    $title = $document["title"];
    $description = $document["description"];
    $tag_words = "";
    foreach ($document["tags"] as $tag) {
        $tag_words = $tag_words . " " . $tag;
    }
    $words = $title . " " . $description . " " . $tag_words;
    return $words;
}

////////////////////////////////////////////////////////////////////
//////////////// Search for and present the results ////////////////

function showResults(array|null $results)
{
    if (is_null($results)) {
        echo("No result has been found.");
        return;
    }
    $output = "Results have been found in module(s) ";
    foreach ($results as $module) {
        $output = $output . $module . ", ";
    }
    $output = rtrim($output, ', ');
    echo($output);
}

function searchForWord(string $word, array $index, bool $test = false)
{
    $word = mb_strtolower($word);
    if ($test == true) {
        if (array_key_exists($word, $index)) {
            return ($index[$word]);
        }
        return [];
    } else {
        if (array_key_exists($word, $index)) {
            showResults($index[$word]);
            return;
        }
        showResults(null);
        return;
    }
}
