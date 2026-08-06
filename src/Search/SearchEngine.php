<?php

class SearchEngine
{
    /// ### Public Properties ### ///

    public bool $test;
    public Tokenizer $tokenizer;
    public Normalizer $normalizer;
    public Indexer $indexer;
    public ResultShower $resultShower;

    /// ### Private Properties ### ///

    private array $index;
    private array $tag_index;
    private array $synonyms;

    /// ### Constructor ### ///

    public function __construct(Tokenizer $tokenizer, Normalizer $normalizer, Indexer $indexer, ResultShower $resultShower, array $index, array $tag_index, array $synonyms, bool $test = false)
    {
        $this->index = $index;
        $this->tag_index = $tag_index;
        $this->test = $test;
        $this->synonyms = $synonyms;
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->indexer = $indexer;
        $this->resultShower = $resultShower;
    }

    /// ### Public Functions ### ///

    public function searchForWord(string $search_phrase)                    // This functions splits any term (multiple words possible) into
    {                                                                       // an array with all single words and looks for them in the index.
        $search_phrase = $this->normalizer->preprocess($search_phrase);     // Its thought for normal and tag searches, depending on the given index.
        $search_words = $this->tokenizer->preprocess($search_phrase);
        if (count($search_words) == 2) {
            $combination = $search_words[0] . " " . $search_words[1];
            $search_words[] = $combination;
        } elseif (count($search_words) == 3) {
            $combo1 = $search_words[0] . " " . $search_words[1];
            $search_words[] = $combo1;
            $combo2 = $search_words[1] . " " . $search_words[2];
            $search_words[] = $combo2;
            $combo3 = $search_words[0] . " " . $search_words[1] . " " . $search_words[2];
            $search_words[] = $combo3;
        }
        $results = [];
        if ($this->test == true) {
            foreach ($search_words as $word) {
                if (array_key_exists($word, $this->index)) {
                    $results[$word] = $this->index[$word];
                }
            }
            return $results;
        } else {
            foreach ($search_words as $word) {
                if (array_key_exists($word, $this->index)) {
                    $results[$word] = $this->index[$word];
                    continue;
                }
                $results[$word] = -1.56;
            }
            $this->showResults($results);
            return $results;
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

    /// ### Private Functions ### ///

    private function showResults(array $results)
    {
        // print_r($results);
        foreach ($results as $word => $ids) {
            $output = "Results for $word have been found in module(s) ";
            if ($results[$word] == -1.56) {
                echo("No result has been found for '$word'.\n");
                continue;
            }
            foreach ($ids as $id) {
                $output = $output . $id . ", ";
            }
            $output = rtrim($output, ', ');
            echo($output);
        }
    }
}
