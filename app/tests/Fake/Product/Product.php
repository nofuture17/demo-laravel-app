<?php


namespace Tests\Fake\Product;


class Product implements \App\ViewModel\Product\Product
{
    /**
     * @var int
     */
    private $index;

    public function __construct(int $index)
    {
        $this->index = $index;
    }

    public function getExternalID(): string
    {
        return "ExternalID#{$this->index}";
    }

    public function getName(): string
    {
        return "Name#{$this->index}";
    }

    public function getImageUrl(): string
    {
        return "ImageUrl#{$this->index}";
    }

    public function getCategories(): string
    {
        return "Category1#{$this->index}, Category2#{$this->index}";
    }
}
