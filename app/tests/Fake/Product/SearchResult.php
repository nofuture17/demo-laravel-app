<?php


namespace Tests\Fake\Product;


use Illuminate\Support\Collection;

class SearchResult implements \App\ViewModel\Product\SearchResult
{
    private $items;
    private $pagesCount;

    public function __construct(Collection $items, $totalCount)
    {
        $this->items = $items;
        $this->pagesCount = $totalCount;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getTotalCount(): int
    {
        return $this->pagesCount;
    }
}
