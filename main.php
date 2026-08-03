<?php

require_once dirname(__FILE__) . '/src/Interfaces/TokenProcessor.php';
require_once dirname(__FILE__) . '/src/Pipelines/TokenPipeline.php';
require_once dirname(__FILE__) . '/src/Processing/Normalizer.php';
require_once dirname(__FILE__) . '/src/Processing/Tokenizer.php';
require_once dirname(__FILE__) . '/src/Processing/StopwordFilter.php';
require_once dirname(__FILE__) . '/src/Search/SearchEngine.php';
require_once dirname(__FILE__) . '/src/Search/Indexing.php';
require_once dirname(__FILE__) . '/src/Test/TestEngine.php';

$filePath_content = __DIR__."/content/content_v3.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_index = __DIR__."/content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_query = __DIR__."/tests/gold_standard_v3.json";
$json_file_query = file_get_contents($filePath_query);
$queries = json_decode($json_file_query, true);

$filePath_tag_index = __DIR__."/content/index_tags.json";
$json_file_tag_index = file_get_contents($filePath_tag_index);
$tag_index = json_decode($json_file_tag_index, true);

$filePath_swords = __DIR__."/content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$stopwords = json_decode($json_file_swords, true);

$filePath_synonyms = __DIR__."/src/OpenThes/synonyms.json";
$json_file_synonyms = file_get_contents($filePath_synonyms);
$synonyms = json_decode($json_file_synonyms, true);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$tokenProcessor_config =        /// Configuration of Indexing methods.
[
    "stopwords" => true,
    "stemming" => false
];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$Tokenizer = new Tokenizer();
$Normalizer = new Normalizer($replace_numbers = true, $remove_hyphened_terms = false);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$token_processors = [];         /// Only creates Objects when they are needed, according to $tokenProcessor_config.
if ($tokenProcessor_config["stopwords"]) {
    $token_processors[] = new StopwordFilter($stopwords);
}
if ($tokenProcessor_config["stemming"]) {

}

$token_Pipeline = new TokenPipeline($token_processors);         /// This Pipeline is given to the Indexer with all the Objects representing the wanted methods.

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$Indexer = new Indexer($Tokenizer, $Normalizer, $token_Pipeline, $index, $tag_index, $data, $filePath_index, $filePath_tag_index);
$SearchEngine = new SearchEngine($Tokenizer, $Normalizer, $Indexer, $index, $tag_index, $synonyms, $test = false);
$TestEngine = new TestEngine($SearchEngine, $queries);

/// $TestEngine->runQuery(1);
/// $Indexer->createIndex();
$Indexer->createTagIndex();
