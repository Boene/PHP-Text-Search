<?php

interface TokenProcessor
{
    public function process(array $tokens): array;
}
