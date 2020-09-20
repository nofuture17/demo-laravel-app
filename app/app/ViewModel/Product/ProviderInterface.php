<?php


namespace App\ViewModel\Product;


interface ProviderInterface
{
    const PAGE_SIZE = 20;

    public function search($name, $page): SearchResult;

    public function save(Product $form): ?int;
}
