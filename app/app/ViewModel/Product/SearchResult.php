<?php


namespace App\ViewModel\Product;


use Illuminate\Support\Collection;

interface SearchResult
{
    /**
     * @return Product[]|Collection
     */
    public function getItems(): Collection;

    public function getTotalCount(): int;
}
