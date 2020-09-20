<?php


namespace Tests\Fake\Model\Product;


use App\Models\Product\Product;
use App\Models\Product\ProviderInterface;
use App\Models\Product\SearchResult;
use Illuminate\Support\Collection;

class Provider implements ProviderInterface
{
    const EXIST_PRODUCT_NAME = 'someExistProduct';
    const COUNT = 30;

    public function search(string $name, int $pageSize, int $page): SearchResult
    {
        $items = [];
        if ($name == self::EXIST_PRODUCT_NAME && $page > 0) {
            for ($i = $this->calculateFirstIndex($pageSize, $page); $i < $pageSize && $i <= self::COUNT; $i++) {
                $items[] = $this->createProduct($i);
            }
        }
        return new SearchResult(new Collection($items), self::COUNT);
    }

    private function calculateFirstIndex(int $page, int $limit)
    {
        return $limit * ($page - 1);
    }

    private function createProduct(int $index): Product
    {
        $product = new Product();
        $product->name = 'Name#' . $index;
        $product->image_url = 'Image#' . $index;
        $product->external_id = 'ExternalID#' . $index;
        $product->categories = 'Categories#' . $index;
        return $product;
    }
}
