<?php

require_once dirname(__FILE__) . '/Normalizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';
require_once dirname(__FILE__) . '/Tokenizer.php';

$filePath_index = __DIR__."/../content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);


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


// addWord("Salat", 12, $index, $filePath_index);
// addID("Salat", 5, $index, $filePath_index);
