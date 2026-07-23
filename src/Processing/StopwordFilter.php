<?php

class StopwordFilter
{
    /// ### Public Properties ### ///

    /// ### Private Properties ### ///

    private array $Stopwords;

    /// ### Constructor ### ///

    public function __construct(array $Stopwords)
    {
        $this->Stopwords = $Stopwords;
    }

    /// ### Public Functions ### ///

    public function process(array $tokens): array
    {
        $return_text = [];
        foreach ($tokens as $token) {
            if ((in_array($token, $this->Stopwords["stopwords"])) || (in_array($token, $this->Stopwords["context_words"]))) {
                continue;
            }
            $return_text[] = $token;

        }
        return $return_text;
    }

    /// ### Private Functions ### ///
}
