<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HtmlSearchPage extends Page
{
    const TEXT_SEARCH_RESULT = 'Search result:';
    const TEXT_EMPTY_RESULT = 'Empty result';
    const TEXT_INSTRUCTION = 'Enter a product name';
    const TEXT_SEARCH_BUTTON_TEXT = 'Search';
    const BASE_PATH = '/';
    const TEXT_UPDATE = 'Update';

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $page;

    public function __construct(?string $name = null, ?int $page = null)
    {
        $this->name = $name;
        $this->page = $page;
    }

    public function url()
    {
        return $this->createUrl($this->name, $this->page);
    }

    public function assert(Browser $browser)
    {
        $browser->assertSee(self::TEXT_INSTRUCTION)
            ->assertSeeIn('@submit', self::TEXT_SEARCH_BUTTON_TEXT);
    }

    public function elements()
    {
        return [
            '@submit' => '.form__submit',
            '@nameInput' => '.form__name-input',
            '@resultItem' => '.results__item',
            '@resultBlock' => '.results',
            '@paginationBlock' => '.results__pagination',
        ];
    }

    public function saveProduct(Browser $browser, int $position)
    {
        $browser->click($this->createSaveButtonSelector($position))
            ->pause(2000);
    }

    public function assertProductIsSaved(Browser $browser, int $position)
    {
        $saveButtonSelector = $this->createSaveButtonSelector($position);
        $browser->assertSeeIn($saveButtonSelector, self::TEXT_UPDATE);
    }

    public function enterProductName(Browser $browser, string $name)
    {
        $browser->type('@nameInput', $name)
            ->press("@submit");
        $this->name = $name;
    }

    public function assertDontHasResult(Browser $browser)
    {
        $browser->assertMissing('@resultBlock');
    }

    public function assertHasEmptyResult(Browser $browser)
    {
        $browser->assertSee(self::TEXT_SEARCH_RESULT)
            ->assertSee(self::TEXT_EMPTY_RESULT)
            ->assertMissing('@resultItem');
    }

    public function assertHasNotEmptyResult(Browser $browser, $productsCount)
    {
        $browser->assertDontSee(self::TEXT_EMPTY_RESULT);
        for ($i = 1; $i <= $productsCount; $i++) {
            $this->assertShowProduct($browser, $i);
        }
        $this->assertDontShowProduct($browser, $productsCount + 1);
    }

    private function assertShowProduct(Browser $browser, int $index)
    {
        $baseSelector = $this->createProductPositionSelector($index) . ' > ';
        $browser->assertPresent($baseSelector . '.product__save');
        $browser->assertPresent($baseSelector . '.product__external_id');
        $browser->assertPresent($baseSelector . '.product__name');
        $browser->assertPresent($baseSelector . '.product__image');
        $browser->assertPresent($baseSelector . '.product__categories');
    }

    private function assertDontShowProduct(Browser $browser, int $position)
    {
        $browser->assertMissing($this->createProductPositionSelector($position));
    }

    public function assertHasPagination(Browser $browser, array $items)
    {
        $browser->assertPresent('@paginationBlock');
        foreach ($items as $page) {
            $browser->assertSourceHas($this->createUrl($this->name, $page, true));
        }
    }

    public function assertHasNotPagination(Browser $browser)
    {
        $browser->assertMissing('@paginationBlock');
    }

    private function createUrl(?string $name, ?int $page, $escapedAmp = false)
    {
        $url = self::BASE_PATH;
        if ($name) {
            $url .= "?name={$name}";
            if (isset($page)) {
                $url .= ($escapedAmp ? '&amp;' : '&') . "page={$page}";
            }
        }

        return $url;
    }

    private function createProductPositionSelector(int $position): string
    {
        return ".product:nth-child({$position})";
    }

    /**
     * @param int $position
     * @return string
     */
    private function createSaveButtonSelector(int $position): string
    {
        return $this->createProductPositionSelector($position) . ' > .product__save';
    }
}
