<?php

require_once dirname(__FILE__) . '/src/Processing/Normalizer.php';
require_once dirname(__FILE__) . '/src/Processing/Tokenizer.php';
require_once dirname(__FILE__) . '/src/Processing/StopwordFilter.php';
require_once dirname(__FILE__) . '/src/Search/SearchEngine.php';
require_once dirname(__FILE__) . '/src/Search/Indexing.php';
require_once dirname(__FILE__) . '/src/Test/TestEngine.php';

$filePath_content = __DIR__."/content/content.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_index = __DIR__."/content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_swords = __DIR__."/content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$stopwords = json_decode($json_file_swords, true);

$filePath_query = __DIR__."/tests/queries_v2.json";
$json_file_query = file_get_contents($filePath_query);
$queries = json_decode($json_file_query, true);




$Tokenizer = new Tokenizer();
$Normalizer = new Normalizer($replace_numbers = true);
$StopwordFilter = new StopwordFilter($stopwords);
$SearchEngine = new SearchEngine($index, $test = true);
$TestEngine = new TestEngine($SearchEngine, $queries, $index);
$Indexer = new Indexer($Tokenizer, $Normalizer, $SearchEngine, $index, $data, $filePath_index);

$TestEngine->runQuery(10);
