<?php

interface TokenProcessor
{
    public function token_process(array $tokens): array;
}
