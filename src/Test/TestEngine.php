<?php

require_once dirname(__FILE__) . '/../Processing/Normalizer.php';
require_once dirname(__FILE__) . '/../Processing/Tokenizer.php';
require_once dirname(__FILE__) . '/../Search/SearchEngine.php';
require_once dirname(__FILE__) . '/../Search/Indexing.php';

$filePath_index = __DIR__."/../../content/index.json";
$json_file_index = file_get_contents($filePath_index);
$index = json_decode($json_file_index, true);

$filePath_content = __DIR__."/../../content/content_v3.json";
$json_file = file_get_contents($filePath_content);
$data = json_decode($json_file, true);

$filePath_swords = __DIR__."/../../content/stopwords.json";
$json_file_swords = file_get_contents($filePath_swords);
$swords = json_decode($json_file_swords, true);

$filePath_query = __DIR__."/../../tests/gold_standard_v3.json";
$json_file_query = file_get_contents($filePath_query);
$queries = json_decode($json_file_query);

class TestEngine
{
    /// ### Public Properties ### ///

    private SearchEngine $search_engine;
    private array $queries;
    private array $index;

    /// ### Constructor ### ///

    public function __construct(SearchEngine $search_engine, array $queries, array $index)
    {
        $this->search_engine = $search_engine;
        $this->queries = $queries;
        $this->index = $index;
    }

    /// ### Public Functions ### ///

    public function runQuery(int $count, int $start = 1)
    {
        $match_rate_list = [];
        for ($i = $start; $i <= $count; $i += 1) {
            $query_data = $this->getQueryByID($i);
            $search_result = $this->search_engine->searchForWord($query_data["query"]);
            if ($this->search_engine->test != true) {
                continue;
            } else {
                $expected = array_column($query_data["results"], "content_id");         /// extracts the expected content_id's from the results entry
                $matches = array_intersect($expected, $search_result);
                $misses = array_diff($expected, $search_result);
                $unexpected = array_diff($search_result, $expected);
                array_push($match_rate_list, count($matches) / count($expected));
                $this->showTestResults($i, $query_data["query"], $matches, $misses, $unexpected, count($expected));
                $this->calcRelevanceScore($matches, $query_data);
            }
        }
        $tot_match_rate = array_sum($match_rate_list) / count($match_rate_list) * 100;
        echo("\n####################################\n");
        echo("# --- Total match rate: $tot_match_rate % --- #\n");
        echo("####################################");
    }

    /// ### Private Functions ### ///

    private function calcRelevanceScore(array $matches, array $query_data)
    {
        $score = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            "total" => 0
        ];
        $max_score = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            "total" => 0
        ];
        foreach ($query_data["results"] as $result) {
            /// echo($result . " -- " . $result["content_id"]);
            /// print_r($matches);
            if (in_array($result["content_id"], $matches)) {
                $score[$result["relevance"]] += $result["relevance"];
                $score["total"] += $result["relevance"];
            }
            $max_score[$result["relevance"]] += $result["relevance"];
            $max_score["total"] += $result["relevance"];
        }
        echo("\n####################################\n");
        echo("Score for Relevance 4: $score[4] of $max_score[4]\n");
        echo("Score for Relevance 3: $score[3] of $max_score[3]\n");
        echo("Score for Relevance 2: $score[2] of $max_score[2]\n");
        echo("Score for Relevance 1: $score[1] of $max_score[1]\n");
        if ($max_score["total"] != 0) {
            echo("Total score reached: " . $score["total"] . " of " . $max_score["total"] . " (" . number_format(100 * $score["total"] / $max_score["total"], 2) . "%)\n");
        }
        echo("#################################### \n");
    }

    private function getQueryByID(int $id)
    {
        foreach ($this->queries as $query) {
            if ($query["test_id"] == $id) {
                return $query;
            }
        }

        return null;
    }

    private function showTestResults(int $id, string $word, array $matches, array $misses, array $unexpected, int $count_expected, string $comment = "No comment")
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
        echo("\nTest results for Query-ID $id with test word '$word':\n");
        echo("Matches: $match_string\n");
        echo("Matched $count_matches / $count_expected ($match_percent %) correctly.\n");
        echo("Misses: $miss_string\n");
        echo("Unexpected matches: $unexpected_string\n");
    }
}
