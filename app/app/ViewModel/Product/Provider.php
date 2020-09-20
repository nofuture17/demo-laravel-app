<?php


namespace App\ViewModel\Product;


use App\Models\Product\Product as ModelProduct;
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

    public function save(Product $form): ?int
    {
        $product = ModelProduct::byExternalID($form->external_id)->first();
        if (!$product) {
            $product = new ModelProduct();
        }
        $this->fillProduct($product, $form);
        $product->save();
        return $product->id;
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

    private function createProductVM(ModelProduct $product): Product
    {
        $productVM = new Product();
        if ($product->id) {
            $productVM->id = $product->id;
        } elseif ($id = ModelProduct::byExternalID($product->external_id)->first()->id ?? null) {
            $productVM->id = $id;
        }
        $productVM->name = $product->name;
        $productVM->external_id = $product->external_id;
        $productVM->image_url = $product->image_url;
        $productVM->categories = $product->categories;
        return $productVM;
    }

    private function fillProduct(ModelProduct $product, Product $form)
    {
        $product->name = $form->name;
        $product->external_id = $form->external_id;
        $product->image_url = $form->image_url;
        $product->categories = $form->categories;
    }
}
