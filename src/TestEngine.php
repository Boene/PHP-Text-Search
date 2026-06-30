<?php

require_once dirname(__FILE__) . '/Normalizer.php';
require_once dirname(__FILE__) . '/SearchEngine.php';
require_once dirname(__FILE__) . '/Tokenizer.php';
require_once dirname(__FILE__) . '/Indexing.php';

$filePath_index = __DIR__."/../content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_content = __DIR__."/../content/content.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_swords = __DIR__."/../content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$swords = json_decode($json_file_swords, true);

$filePath_query = __DIR__."/../tests/queries_v2.json";
$json_file_query = file_get_contents($filePath_query);
$queries = json_decode($json_file_query, true);

function getQueryByID(int $id, array $queries)
{
    foreach ($queries as $query) {
        if ($query["id"] == $id) {
            return $query;
        }
    }

    return null;
}

function showTestResults(int $id, string $word, array $matches, array $misses, array $unexpected, int $count_expected, string $comment)
{
    $count_matches = count($matches);
    $match_percent = 100 * $count_matches / $count_expected;
    $match_string = "";
    $miss_string = "";
    $unexpected_string = "";
    foreach ($matches as $match) {
        $match_string = $match_string . $match . " ";
    }
    foreach ($misses as $miss) {
        $miss_string = $miss_string . $miss . " ";
    }
    foreach ($unexpected as $whoah) {
        $unexpected_string = $unexpected_string . $whoah . " ";
    }
    echo("Test results for Query-ID $id with test word '$word':\n");
    echo("Matches: $match_string\n");
    echo("Matched $count_matches / $count_expected ($match_percent %) correctly.\n");
    echo("Misses: $miss_string\n");
    echo("Unexpected matches: $unexpected_string\n\n");
}

function runQuery(int $count, array $queries, array $index, int $start = 1)
{
    for ($i = $start; $i <= $count; $i += 1) {
        $query_data = getQueryByID($i, $queries);
        $search_result = searchForWord($query_data["query"], $index, $test = true);
        $matches = array_intersect($query_data["expected"], $search_result);
        $misses = array_diff($query_data["expected"], $search_result);
        $unexpected = array_diff($search_result, $query_data["expected"]);
        showTestResults($i, $query_data["query"], $matches, $misses, $unexpected, count($query_data["expected"]), $query_data["comment"]);
    }
}
