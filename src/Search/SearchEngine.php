<?php

class SearchEngine
{
    /// ### Public Properties ### ///

    public bool $test;

    /// ### Private Properties ### ///

    private array $index;

    /// ### Constructor ### ///

    public function __construct(array $index, bool $test = false)
    {
        $this->index = $index;
        $this->test = $test;
    }

    /// ### Public Functions ### ///

    public function getDocumentByID(int $id, array $documents): ?array
    {
        foreach ($documents as $document) {
            if ($document["id"] == $id) {
                return $document;
            }
        }

        return null;
    }

    public function searchForWord(string $word)
    {
        $word = mb_strtolower($word);
        $word = trim($word);
        if ($this->test == true) {
            if (array_key_exists($word, $this->index)) {
                return ($this->index[$word]);
            }
            return [];
        } else {
            if (array_key_exists($word, $this->index)) {
                $this->showResults($this->index[$word]);
                return;
            }
            $this->showResults(null);
            return;
        }
    }

    public function documentToString(array $document): string
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

    /// ### Private Functions ### ///

    private function showResults(array|null $results)
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
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

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
