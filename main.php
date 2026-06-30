<?php

require_once dirname(__FILE__) . '/src/Normalizer.php';
require_once dirname(__FILE__) . '/src/SearchEngine.php';
require_once dirname(__FILE__) . '/src/Tokenizer.php';
require_once dirname(__FILE__) . '/src/Indexing.php';
require_once dirname(__FILE__) . '/src/TestEngine.php';

$filePath_content = __DIR__."/content/content.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_index = __DIR__."/content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_swords = __DIR__."/content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$swords = json_decode($json_file_swords, true);

$filePath_query = __DIR__."/tests/queries_v2.json";
$json_file_query = file_get_contents($filePath_query);
$queries = json_decode($json_file_query, true);


// print_r(searchForWord("wurst", $index, $test = true));

runQuery(count($queries), $queries, $index, );
