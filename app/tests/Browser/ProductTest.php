<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\SearchPage;
use Tests\DuskTestCase;
use Tests\Fake\ViewModel\Product\Provider;

class ProductTest extends DuskTestCase
{
    private const NOT_EXIST_PRODUCT_NAME = 'someNotExistProduct';

    public function testSave()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->createSearchPage())
                ->enterProductName(Provider::EXIST_PRODUCT_NAME)
                ->saveProduct(1)
                ->assertProductIsSaved(1);
        });
    }

    public function testPreSavedProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->createSearchPage())
                ->enterProductName(Provider::EXIST_PRODUCT_NAME)
                ->assertProductIsSaved(Provider::SAVED_PRODUCT_POSITION);
        });
    }

    public function testEmptyForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->createSearchPage())
                ->assertDontHasResult();
        });
    }

    public function testSearchNotExistProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->createSearchPage())
                ->enterProductName(self::NOT_EXIST_PRODUCT_NAME)
                ->assertHasEmptyResult();
        });
    }

    public function testSearchExistProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->createSearchPage())
                ->enterProductName(Provider::EXIST_PRODUCT_NAME)
                ->assertHasPagination([2])
                ->assertHasNotEmptyResult(20)
                ->visit($this->createSearchPage(Provider::EXIST_PRODUCT_NAME, 2))
                ->assertHasPagination([1])
                ->assertHasNotEmptyResult(10)
                ->visit($this->createSearchPage(Provider::EXIST_PRODUCT_NAME, 3))
                ->assertHasEmptyResult()
                ->visit($this->createSearchPage(Provider::EXIST_PRODUCT_NAME, 0))
                ->assertHasEmptyResult();
        });
    }

    private function createSearchPage(?string $productName = null, ?int $page = null): SearchPage
    {
        return $this->app->make(SearchPage::class, ['productName' => $productName, 'page' => $page]);
    }
}
