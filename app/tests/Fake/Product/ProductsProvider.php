<?php


namespace Tests\Fake\Product;


use Illuminate\Support\Collection;

class ProductsProvider implements \App\ViewModel\Product\ProductsProvider
{
    const EXIST_PRODUCT_NAME = 'someExistProduct';
    const TOTAL_COUNT = 30;

    public function search($name, $page): \App\ViewModel\Product\SearchResult
    {
        $items = [];
        if ($name == self::EXIST_PRODUCT_NAME && $page > 0) {
            $index = $this->calculateFirstIndex($page);
            $count = 0;
            while ($index < self::TOTAL_COUNT && $count++ < self::PAGE_SIZE) {
                $items[] = new Product($index++);
            }
        }
        return new SearchResult(new Collection($items), $items ? self::TOTAL_COUNT : 0);
    }

    private function calculateFirstIndex(int $page): int
    {
        return self::PAGE_SIZE * ($page - 1);
    }
}
