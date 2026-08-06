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

    /// ### Constructor ### ///

    public function __construct(SearchEngine $search_engine, array $queries)
    {
        $this->search_engine = $search_engine;
        $this->queries = $queries;
    }

    /// ### Public Functions ### ///

    public function runQuery(int $count, int $start = 1)            /// This function is designed for testing ($test == true) under lab conditions,
    {                                                               /// meaning that the search looks at descriptive information of the content as well as its tags
        $match_rate_list = [];                                      /// and has the perfect answers set in advance in the form of a list of ids.
        for ($i = $start; $i <= $count; $i += 1) {                  /// With $test == false, it simply returns all the ids of matched content.
            $query_data = $this->getQueryByID($i);
            $search_result = $this->search_engine->searchForWord($query_data["query"]);
            $search_result_ids = [];
            foreach ($search_result as $word => $ids) {
                $search_result_ids = array_unique(array_merge($search_result_ids, $ids));
            }
            if ($this->search_engine->test != true) {
                continue;
            } else {
                $expected = array_column($query_data["results"], "content_id");         /// extracts the expected content_id's from the results entry
                $matches = array_intersect($expected, $search_result_ids);
                $misses = array_diff($expected, $search_result_ids);
                $unexpected = array_diff($search_result_ids, $expected);
                array_push($match_rate_list, count($matches) / count($expected));
                $this->search_engine->resultShower->resultOverview($query_data, $matches, $misses, $unexpected, count($expected), $i, $this->search_engine->test);
            }
        }
        if ($this->search_engine->test == true) {
            $this->search_engine->resultShower->totResult($match_rate_list);
        }
    }

    public function testAgainstTags()           /// This function compares the search results without using tags against only using tags, which are considered as truth
    {
        ######################################################################################################
        ######################################################################################################
        // searchForWord auch für multi-word input? ---> searchForWord($tag) ? || eigene Klasse showResults?
        ######################################################################################################
        ######################################################################################################
    }

    /// ### Private Functions ### ///

    private function calcRelevanceScore(array $matches, array $query_data) // copied
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
        echo("Matched $count_matches / $count_expected (". number_format($match_percent, 2) ."%) correctly.\n");
        echo("Misses: $miss_string\n");
        echo("Unexpected matches: $unexpected_string\n");
    }
}
