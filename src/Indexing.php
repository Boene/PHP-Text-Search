<?php

require_once dirname(__FILE__) . '/Normalizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';
require_once dirname(__FILE__) . '/Tokenizer.php';

class Indexer
{
    /// ### Public Properties ### ///

    /// ### Private Properties ### ///

    private Tokenizer $tokenizer;
    private Preprocessor $preprocessor;
    private SearchEngine $search_engine;
    private array $index;
    private array $data;
    private string $filePath_index;

    /// ### Constructor ### ///

    public function __construct(
        Tokenizer $tokenizer,
        Preprocessor $preprocessor,
        SearchEngine $search_engine,
        array $index,
        array $data,
        string $filePath_index
    ) {
        $this->tokenizer = $tokenizer;
        $this->preprocessor = $preprocessor;
        $this->search_engine = $search_engine;
        $this->index = $index;
        $this->data = $data;
        $this->filePath_index = $filePath_index;
    }

    /// ### Public Functions ### ///

    public function createIndex()
    {
        $a = true;
        $i = 1;

        while ($a == true) {
            $doc = $this->search_engine->getDocumentById($i, $this->data);
            $doc_string = $this->search_engine->documentToString($doc);
            $preprocessed_data = $this->preprocessor->preprocess($doc_string);
            $tokens = $this->tokenizer->tokenize($preprocessed_data);
            foreach ($tokens as $word) {
                if ($this->checkForWord($word) == true) {
                    $this->addID($word, $i);
                } else {
                    $this->addWord($word, $i);
                }
            }
            if ($this->search_engine->getDocumentById($i + 1, $this->data) == null) {
                $a = false;
            }
            $i += 1;
        }
    }

    /// ### Private Functions ### ///

    private function addWord(string $word, int $id)
    {
        $this->index[$word] = [$id];
        file_put_contents($this->filePath_index, json_encode($this->index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function addID(string $word, int $id)
    {
        if (in_array($id, $this->index[$word])) {
            return;
        } else {
            array_push($this->index[$word], $id);
            file_put_contents($this->filePath_index, json_encode($this->index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    private function checkForWord(string $word): bool
    {
        if (array_key_exists($word, $this->index)) {
            return true;
        } else {
            return false;
        }
    }
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function addWord(string $word, int $ID, array &$index, string $filePath_index)
{
    $index[$word] = [$ID];
    file_put_contents($filePath_index, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function addID(string $word, int $ID, array &$index, string $filePath_index)
{
    if (in_array($ID, $index[$word])) {
        return;
    } else {
        array_push($index[$word], $ID);
        file_put_contents($filePath_index, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

function checkForWord(string $word, array $index, int $ID = -1): bool
{
    if (array_key_exists($word, $index)) {
        return true;
    } else {
        return false;
    }
}

function createIndex(array $data, array $index, array $swords, string $filePath_index)
{
    $a = true;
    $i = 1;

    while ($a == true) {
        $doc = getDocumentById($i, $data);
        $doc_string = documentToString($doc);
        $preprocessed_data = Preprocess($doc_string, $swords);
        $tokens = Tokenize($preprocessed_data);
        foreach ($tokens as $word) {
            if (checkForWord($word, $index) == true) {
                addID($word, $i, $index, $filePath_index);
            } else {
                addWord($word, $i, $index, $filePath_index);
            }
        }
        if (getDocumentById($i + 1, $data) == null) {
            $a = false;
        }
        $i += 1;
    }
}
