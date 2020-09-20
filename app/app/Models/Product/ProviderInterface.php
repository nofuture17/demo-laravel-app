<?php


namespace App\Models\Product;

interface ProviderInterface
{
    public function search(string $name, int $pageSize, int $page): SearchResult;
}
