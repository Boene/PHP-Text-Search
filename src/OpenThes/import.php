<?php

$mysqli = new mysqli(
    "localhost",
    "root",
    "Petersql_123",
    "openthesaurus"
);

if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$sql = "
SELECT
    t.synset_id,
    t.word
FROM term AS t
WHERE
    (t.level_id NOT IN (2, 3, 4) OR t.level_id IS NULL)
    AND NOT EXISTS (
        SELECT 1
        FROM term_tag AS tt
        WHERE
            tt.term_tags_id = t.id
            AND tt.tag_id IN (
                6, 7, 9, 10, 12, 13, 18, 19,
                21, 24, 25, 32, 39, 44,
                53, 58, 101, 120, 122,
                129, 231, 357
            )
    )
    AND t.word NOT LIKE '\"%' 
    AND t.word NOT LIKE '''%' 
    AND t.word NOT LIKE '! %'
    AND t.word NOT LIKE '(%'
    AND t.word NOT LIKE '.%'
    AND t.word NOT LIKE '%...%'
ORDER BY t.synset_id;
";

$result = $mysqli->query($sql);

if (!$result) {
    die("SQL-Fehler: " . $mysqli->error);
}

$groups = [];

while ($row = $result->fetch_assoc()) {

    $synsetId = $row["synset_id"];
    $word = $row["word"];
    $groups[$synsetId][] = $word;
}

$lookup = [];

foreach ($groups as $syn) {

    if (count($syn) < 2) {
        continue;
    }

    foreach ($syn as $wort) {
        $lookup[$wort] = array_values(
            array_diff($syn, [$wort])
        );
    }
}

ksort($lookup);
$json = json_encode($lookup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents("synonyms.json", $json);
