<?php

function getDocumentById(int $id, array $documents): ?array
{
    foreach ($documents as $document) {
        if ($document["id"] == $id) {
            return $document;
        }
    }

    return null;
}

function DocumentToString(array $document): string
{
    $title = $document["title"];
    $description = $document["description"];
    $tag_words = "";
    foreach ($document["tags"] as $tag) {
        $tag_words = $tag_words . " " . $tag;
    }
    $words = $title . " " . $description . " " . $tag_words;
    return $words;
}
