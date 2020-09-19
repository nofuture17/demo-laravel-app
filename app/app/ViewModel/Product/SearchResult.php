<?php


namespace App\ViewModel\Product;


use Illuminate\Support\Collection;

class SearchResult
{
    private $items;
    private $totalCount;

    public function __construct(Collection $items, $totalCount)
    {
        $this->items = $items;
        $this->totalCount = $totalCount;
    }

    /**
     * @return Collection|Product[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
