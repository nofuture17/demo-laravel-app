<?php


namespace App\ViewModel\Product;


use Illuminate\Support\Collection;

class Provider implements ProviderInterface
{
    /**
     * @var \App\Models\Product\ProviderInterface
     */
    private $productsProviderModel;

    public function __construct(\App\Models\Product\ProviderInterface $productsProvider)
    {
        $this->productsProviderModel = $productsProvider;
    }

    public function search($name, $page): SearchResult
    {
        $modelResult = $this->productsProviderModel->search($name, self::PAGE_SIZE, $page);
        $products = [];
        foreach ($modelResult->getItems() as $item) {
            $products[] = $this->createProductVM($item);
        }
        return new SearchResult(new Collection($products), $modelResult->getTotalCount());
    }

    public static function calculatePagesCount(int $itemsCount): int
    {
        return (int)ceil($itemsCount / self::PAGE_SIZE);
    }

    private function createProductVM(\App\Models\Product\Product $item): Product
    {
        $product = new Product();
        $product->name = $item->name;
        $product->externalID = $item->external_id;
        $product->imageUrl = $item->image_url;
        $product->categories = $item->categories;
        return $product;
    }
}
