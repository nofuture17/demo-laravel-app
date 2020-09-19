<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HtmlSearchPage;
use Tests\DuskTestCase;
use Tests\Fake\ViewModel\Product\Provider;

class ProductTest extends DuskTestCase
{
    public function testSave()
    {

    }

    public function testEmptyForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HtmlSearchPage($this->baseUrl()))
                ->assertDontHasResult();
        });
    }

    public function testSearchNotExistProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HtmlSearchPage($this->baseUrl()))
                ->enterProductName('someNotExistProductName')
                ->assertHasEmptyResult();
        });
    }

    public function testSearchExistProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HtmlSearchPage($this->baseUrl()))
                ->enterProductName(Provider::EXIST_PRODUCT_NAME)
                ->assertHasPagination([2])
                ->assertHasNotEmptyResult(20)
                ->visit(new HtmlSearchPage($this->baseUrl(), Provider::EXIST_PRODUCT_NAME, 2))
                ->assertHasPagination([1])
                ->assertHasNotEmptyResult(10)
                ->visit(new HtmlSearchPage($this->baseUrl(), Provider::EXIST_PRODUCT_NAME, 3))
                ->assertHasEmptyResult()
                ->visit(new HtmlSearchPage($this->baseUrl(), Provider::EXIST_PRODUCT_NAME, 0))
                ->assertHasEmptyResult();
        });
    }
}
