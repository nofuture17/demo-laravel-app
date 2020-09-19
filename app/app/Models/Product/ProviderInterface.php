<?php


namespace App\Models\Product;

interface ProviderInterface
{
    public function search(string $name, int $limit, int $offset): SearchResult;
}
