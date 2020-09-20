<?php


namespace App\Models\Product;


use Illuminate\Support\Collection;

class OpenfoodfactsProvider implements ProviderInterface
{
    private const BASE_URL = 'https://world.openfoodfacts.org/cgi/search.pl';

    public function search(string $name, int $pageSize, int $page): SearchResult
    {
        $products = [];
        $data = $this->getData($this->createUrl($name, $pageSize, $page));
        foreach ($data['items'] as $item) {
            $products[] = $this->createProduct($item);
        }
        return new SearchResult(new Collection($products), $data['totalCount']);
    }

    private function createProduct(array $productData): Product
    {
        $product = new Product();
        $product->external_id = $productData['id'];
        $product->name = $productData['product_name'] ?? '';
        $product->image_url = $productData['image_url'] ?? '';
        $product->categories = $productData['categories'] ?? '';
        return $product;
    }

    private function getData(string $url): array
    {
        $arrayData = [];
        if ($stringData = file_get_contents($url)) {
            $arrayData = json_decode($stringData, true);
        }

        return [
            'items' => $arrayData['products'] ?? [],
            'totalCount' => $arrayData['count'] ?? 0
        ];
    }

    private function createUrl(string $name, int $pageSize, int $page): string
    {
        $name = urlencode($name);
        return self::BASE_URL . "?search_terms={$name}&action=process&page_size={$pageSize}&page={$page}&json=1";
    }
}
