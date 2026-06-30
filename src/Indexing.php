<?php

require_once dirname(__FILE__) . '/Normalizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';
require_once dirname(__FILE__) . '/Tokenizer.php';


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
