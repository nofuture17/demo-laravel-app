<?php


namespace App\ViewModel\Product;


interface Product
{
    public function getExternalID(): string;

    public function getName(): string;

    public function getImageUrl(): string;

    public function getCategories(): string;
}
