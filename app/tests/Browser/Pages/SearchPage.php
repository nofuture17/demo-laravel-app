<?php


namespace Tests\Browser\Pages;


use Laravel\Dusk\Browser;

interface SearchPage
{
    public function saveProduct(Browser $browser, int $position);

    public function assertProductIsSaved(Browser $browser, int $position);

    public function enterProductName(Browser $browser, string $name);

    public function assertDontHasResult(Browser $browser);

    public function assertHasEmptyResult(Browser $browser);

    public function assertHasNotEmptyResult(Browser $browser, $productsCount);

    public function assertHasPagination(Browser $browser, array $items);

    public function assertHasNotPagination(Browser $browser);
}
