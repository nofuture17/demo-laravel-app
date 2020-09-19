<?php


namespace Tests\Fake\ViewModel\Product;


use App\ViewModel\Product\Product;
use App\ViewModel\Product\ProviderInterface;
use App\ViewModel\Product\SearchResult;
use Illuminate\Support\Collection;

class Provider implements ProviderInterface
{
    const EXIST_PRODUCT_NAME = 'someExistProduct';
    const TOTAL_COUNT = 30;

    public function search($name, $page): SearchResult
    {
        $items = [];
        if ($name == self::EXIST_PRODUCT_NAME && $page > 0) {
            $index = $this->calculateFirstIndex($page);
            $count = 0;
            while ($index < self::TOTAL_COUNT && $count++ < self::PAGE_SIZE) {
                $items[] = $this->createProduct($index++);
            }
        }
        return new SearchResult(new Collection($items), self::TOTAL_COUNT);
    }

    private function createProduct(int $index): Product
    {
        $product = new Product();
        $product->name = 'Name#' . $index;
        $product->imageUrl = 'ImageUrl#' . $index;
        $product->externalID = 'ExternalID#' . $index;
        $product->categories = 'Category1#' . $index . ', Category2#' . $index;
        return $product;
    }

    private function calculateFirstIndex(int $page): int
    {
        return self::PAGE_SIZE * ($page - 1);
    }
}
