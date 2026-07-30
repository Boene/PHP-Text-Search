<?php

class SearchEngine
{
    /// ### Public Properties ### ///

    public bool $test;

    /// ### Private Properties ### ///

    private array $index;
    private array $synonyms;

    /// ### Constructor ### ///

    public function __construct(array $index, array $synonyms, bool $test = false)
    {
        $this->index = $index;
        $this->test = $test;
        $this->synonyms = $synonyms;
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
        $word = $this->minFormatWord($word);
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

    public function searchForSynonyms(array $word): array
    {
        $begriff = $word[0];
        if (array_key_exists($begriff, $this->synonyms)) {
            $word = array_merge($word, $this->synonyms[$begriff]);
        }

        return $word;
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

    private function minFormatWord(string $word): string
    {
        $word = mb_strtolower($word);
        $word = trim($word);
        return $word;
    }

}
