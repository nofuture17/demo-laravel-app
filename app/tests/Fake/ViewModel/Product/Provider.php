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
    const SAVED_PRODUCT_POSITION = 2;
    const FAKE_ID = 1;

    public function save(Product $form): int
    {
        return self::FAKE_ID;
    }

    public function search($name, $page): SearchResult
    {
        $items = [];
        if ($name == self::EXIST_PRODUCT_NAME && $page > 0) {
            $index = $this->calculateFirstIndex($page);
            $count = 0;
            while ($index < self::TOTAL_COUNT && $count++ < self::PAGE_SIZE) {
                $product = $this->createProduct($index++);
                if ($count == self::SAVED_PRODUCT_POSITION) {
                    $this->makeProductSaved($product);
                }
                $items[] = $product;
            }
        }
        return new SearchResult(new Collection($items), self::TOTAL_COUNT);
    }

    private function createProduct(int $index): Product
    {
        $product = new Product();
        $product->name = 'Name#' . $index;
        $product->image_url = 'ImageUrl#' . $index;
        $product->external_id = 'ExternalID#' . $index;
        $product->categories = 'Category1#' . $index . ', Category2#' . $index;
        return $product;
    }

    private function calculateFirstIndex(int $page): int
    {
        return self::PAGE_SIZE * ($page - 1);
    }

    private function makeProductSaved(Product $product): int
    {
        return $product->id = self::FAKE_ID;
    }
}
