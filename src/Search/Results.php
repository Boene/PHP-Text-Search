<?php

class Resulter
{
    /// ### Public Properties ### ///



    /// ### Private Properties ### ///

    private array $match_rate_list = [];

    /// ### Constructor ### ///



    /// ### Public Functions ### ///

    public function evaluate(array $query_data, array $search_result_ids)           /// Does not yet work for phrases made of multiple words.
    {
        $expected = array_column($query_data["results"], "content_id");             /// Extracts the expected content_id's from the results entry.
        $matches = array_intersect($expected, $search_result_ids);
        $misses = array_diff($expected, $search_result_ids);
        $unexpected = array_diff($search_result_ids, $expected);

        array_push($this->match_rate_list, count($matches) / count($expected));     /// Saves the match% of processed Query for totResult to calc the overall match% at the end.

        return ["query_data" => $query_data, "expected" => $expected, "matches" => $matches, "misses" => $misses, "unexpected" => $unexpected];
    }

    public function resultOverview(array $evaluated_data, int $id, bool $test, bool $vs_tags = false)
    {
        $query_data = $evaluated_data["query_data"];
        $expected = $evaluated_data["expected"];
        $matches = $evaluated_data["matches"];
        $misses = $evaluated_data["misses"];
        $unexpected = $evaluated_data["unexpected"];
        if ($test == true) {
            $this->showTestResults($id, $query_data["query"], $matches, $misses, $unexpected, count($expected));
            $this->calcRelevanceScore($matches, $query_data);
        }
    }

    public function totResult()
    {
        $tot_match_rate = array_sum($this->match_rate_list) / count($this->match_rate_list) * 100;
        echo("\n####################################\n");
        echo("# --- Total match rate: $tot_match_rate % --- #\n");
        echo("####################################");
    }

    public function showResults(array $results)
    {
        foreach ($results as $word => $ids) {
            $output = "Results for $word have been found in module(s) ";
            if ($results[$word] == [-1.56]) {
                echo("No result has been found for '$word'.\n");
                continue;
            }
            foreach ($ids as $id) {
                $output = $output . $id . ", ";
            }
            $output = rtrim($output, ', ');
            echo($output . "\n");
        }
    }

    /// ### Private Functions ### ///

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


}
