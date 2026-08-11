<?php

class SearchEngine
{
    /// ### Public Properties ### ///

    public bool $test;
    public Tokenizer $tokenizer;
    public Normalizer $normalizer;
    public Indexer $indexer;
    public Resulter $resulter;

    /// ### Private Properties ### ///

    private array $index;
    private array $tag_index;
    private array $synonyms;

    /// ### Constructor ### ///

    public function __construct(Tokenizer $tokenizer, Normalizer $normalizer, Indexer $indexer, Resulter $resulter, array $index, array $tag_index, array $synonyms, bool $test = false)
    {
        $this->index = $index;
        $this->tag_index = $tag_index;
        $this->test = $test;
        $this->synonyms = $synonyms;
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->indexer = $indexer;
        $this->resulter = $resulter;
    }

    /// ### Public Functions ### ///

    public function searchForWord(string $search_phrase, bool $tags = false)                 // This functions splits any term (multiple words possible) into an array with all single words and looks for them in the index.
    {
        $used_index = $this->index;
        if ($tags == true) {                                                               // It's made for normal and tag searches, depending on the value of $tags.
            $used_index = $this->tag_index;
        }

        $search_phrase = $this->normalizer->preprocess($search_phrase);
        $search_words = $this->tokenizer->preprocess($search_phrase);
        if (count($search_words) == 2) {
            $combo1 = $search_words[0] . " " . $search_words[1];
            $search_words[] = $combo1;
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
                if (array_key_exists($word, $used_index)) {
                    $results[$word] = $used_index[$word];
                }
            }
            return $results;
        } else {
            foreach ($search_words as $word) {
                if (array_key_exists($word, $used_index)) {
                    $results[$word] = $used_index[$word];
                    continue;
                }
                $results[$word] = -1.56;
            }
            $this->resulter->showResults($results);
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

}
