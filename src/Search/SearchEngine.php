<?php

class SearchEngine
{
    /// ### Public Properties ### ///

    public bool $test;
    public Tokenizer $tokenizer;
    public Normalizer $normalizer;
    public Indexer $indexer;

    /// ### Private Properties ### ///

    private array $index;
    private array $tag_index;
    private array $synonyms;

    /// ### Constructor ### ///

    public function __construct(Tokenizer $tokenizer, Normalizer $normalizer, Indexer $indexer, array $index, array $tag_index, array $synonyms, bool $test = false)
    {
        $this->index = $index;
        $this->tag_index = $tag_index;
        $this->test = $test;
        $this->synonyms = $synonyms;
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->indexer = $indexer;
    }

    /// ### Public Functions ### ///

    public function searchForWord(string $word)
    {
        $word = $this->normalizer->preprocess($word);
        if ($this->test == true) {
            if (array_key_exists($word, $this->index)) {
                return ($this->index[$word]);
            }
            return [];
        } else {
            if (array_key_exists($word, $this->index)) {
                $this->showResults($this->index[$word], $word);
                return ($this->index[$word]);
            }
            $this->showResults(null, $word);
            return [];
        }
    }

    public function searchPhraseInTags(string $search_phrase)          /// this function returns the search results only using the contents tags
    {
        $search_phrase = $this->normalizer->preprocess($search_phrase);
        $search_words = $this->tokenizer->preprocess($search_phrase);
        $results = [];

        foreach ($search_words as $word) {
            ######################################################################################################
            ######################################################################################################
            ######################################################################################################
            ######################################################################################################
        }
        return $results;
    }

    public function searchForSynonyms(array $word): array
    {
        $begriff = $word[0];
        if (array_key_exists($begriff, $this->synonyms)) {
            $word = array_merge($word, $this->synonyms[$begriff]);
        }

        return $word;
    }

    /// ### Private Functions ### ///

    private function showResults(array|null $results, string $word)
    {
        if (is_null($results)) {
            echo("No result has been found for '$word'.");
            return;
        }
        $output = "Results for '$word' have been found in module(s) ";
        foreach ($results as $module) {
            $output = $output . $module . ", ";
        }
        $output = rtrim($output, ', ');
        echo($output);
    }

}
