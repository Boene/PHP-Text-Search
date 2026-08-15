<?php

require_once dirname(__FILE__) . '/../Processing/Normalizer.php';
require_once dirname(__FILE__) . '/../Processing/Tokenizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';

class Indexer
{
    /// ### Public Properties ### ///

    /// ### Private Properties ### ///

    private Tokenizer $tokenizer;
    private Normalizer $normalizer;
    private TokenPipeline $pipeline;
    private array $index;
    private array $tag_index;
    private array $data;
    private string $filePath_index;
    private string $filePath_tag_index;

    /// ### Constructor ### ///

    public function __construct(
        Tokenizer $tokenizer,
        Normalizer $normalizer,
        TokenPipeline $pipeline,
        array $index,
        array $tag_index,
        array $data,
        string $filePath_index,
        string $filePath_tag_index
    ) {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->pipeline = $pipeline;
        $this->index = $index;
        $this->tag_index = $tag_index;
        $this->data = $data;
        $this->filePath_index = $filePath_index;
        $this->filePath_tag_index = $filePath_tag_index;
    }

    /// ### Public Functions ### ///

    public function createIndex()
    {
        $a = true;
        $i = 1;

        while ($a == true) {
            $doc = $this->getDocumentByID($i, $this->data);
            $doc_string = $this->documentToString($doc);
            $preprocessed_data = $this->normalizer->preprocess($doc_string);
            $tokens = $this->tokenizer->preprocess($preprocessed_data);
            $tokens = $this->pipeline->run($tokens);
            foreach ($tokens as $word) {
                if ($this->checkForWord($word) == true) {
                    $this->addID($word, $i);
                } else {
                    $this->addWord($word, $i);
                }
            }
            if ($this->getDocumentById($i + 1, $this->data) == null) {           // stop while loop when id is not found aka end of index
                $a = false;
            }
            $i += 1;
        }
    }

    public function createTagIndex()
    {
        $a = true;
        $i = 1;

        while ($a == true) {
            $doc = $this->getDocumentByID($i, $this->data);
            $doc_string = $this->tagsToString($doc);
            $preprocessed_data = $this->normalizer->preprocess($doc_string);
            $tokens = $this->tokenizer->preprocess($preprocessed_data);
            $tokens = $this->pipeline->run($tokens);
            foreach ($tokens as $word) {
                if ($this->checkForTag($word) == true) {
                    $this->addID_tags($word, $i);
                } else {
                    $this->addWord_tags($word, $i);
                }
            }
            if ($this->getDocumentById($i + 1, $this->data) == null) {           // stop while loop when id is not found aka end of index
                $a = false;
            }
            $i += 1;
        }
    }

    /// ### Private Functions ### ///

    private function documentToString(array $document): string
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

    private function tagsToString(array $document)
    {
        $tag_words = "";
        foreach ($document["tags"] as $tag) {
            $tag_words = $tag_words . " " . $tag;
        }
        return $tag_words;
    }

    private function getDocumentByID(int $id, array $documents): ?array
    {
        foreach ($documents as $document) {
            if ($document["id"] == $id) {
                return $document;
            }
        }

        return null;
    }

    private function addWord(string $word, int $id)
    {
        $this->index[$word] = [$id];
        file_put_contents($this->filePath_index, json_encode($this->index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function addWord_tags(string $word, int $id)
    {
        $this->tag_index[$word] = [$id];
        file_put_contents($this->filePath_tag_index, json_encode($this->tag_index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

    private function addID_tags(string $word, int $id)
    {
        if (in_array($id, $this->tag_index[$word])) {
            return;
        } else {
            array_push($this->tag_index[$word], $id);
            file_put_contents($this->filePath_tag_index, json_encode($this->tag_index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

    private function checkForTag(string $word): bool
    {
        if (array_key_exists($word, $this->tag_index)) {
            return true;
        } else {
            return false;
        }
    }
}
