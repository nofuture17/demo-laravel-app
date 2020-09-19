<?php


namespace App\ViewModel\Product;


interface ProductsProvider
{
    const PAGE_SIZE = 20;

    public function search($name, $page): SearchResult;
}
