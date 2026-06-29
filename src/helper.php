<?php

require_once dirname(__FILE__) . '/Normalizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';
require_once dirname(__FILE__) . '/Tokenizer.php';

$filePath_index = __DIR__."/../content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_content = __DIR__."/../content/content.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_swords = __DIR__."/../content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$swords = json_decode($json_file_swords, true);

////////////////////////////////////////////////////////////
// temporary way to fill index.json based on content.json

$a = true;
$i = 1;

while ($a == true) {
    $doc = getDocumentById($i, $data);
    $doc_string = DocumentToString($doc);
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
