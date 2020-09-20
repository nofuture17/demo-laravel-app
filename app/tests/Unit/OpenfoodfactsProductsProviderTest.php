<?php


namespace Tests\Unit;

use App\Models\Product\OpenfoodfactsProvider;
use App\Models\Product\ProviderInterface;
use PHPUnit\Framework\TestCase;

class OpenfoodfactsProductsProviderTest extends TestCase
{
    private const EXIST_PRODUCT_NAME = 'Cristaline';
    private const NOT_EXIST_PRODUCT_NAME = 'someNotExistProduct';
    private const PAGE_SIZE = 20;
    private const WRONG_PAGE = 999;
    private const FIRST_PAGE = 1;

    public function testSearchNotExistProduct()
    {
        $provider = $this->createProvider();
        $result = $provider->search(self::NOT_EXIST_PRODUCT_NAME, self::PAGE_SIZE, self::FIRST_PAGE);
        $this->assertEquals(0, $result->getTotalCount());
        $this->assertTrue($result->getItems()->isEmpty());
    }

    public function testNoItemsOnWrongPage()
    {
        $provider = $this->createProvider();
        $result = $provider->search(self::EXIST_PRODUCT_NAME, self::PAGE_SIZE, self::WRONG_PAGE);
        $this->assertNotEquals(0, $result->getTotalCount());
        $this->assertTrue($result->getItems()->isEmpty());
    }

    public function testSearchExistProduct()
    {
        $provider = $this->createProvider();
        $lastPage = 0;
        $totalCount = 0;
        $itemsCounter = 0;
        $page = self::FIRST_PAGE;
        do {
            $result = $provider->search(self::EXIST_PRODUCT_NAME, self::PAGE_SIZE, $page++);
            if (!$totalCount) {
                $totalCount = $result->getTotalCount();
                $this->assertNotEquals(0, $totalCount);
            } else {
                $this->assertEquals($totalCount, $result->getTotalCount());
            }
            if (!$lastPage) {
                $lastPage = $this->calculateLastPage($totalCount);
            }
            $currentPageItemsCount = $result->getItems()->count();
            $itemsCounter += $currentPageItemsCount;
            $this->assertLessThanOrEqual(self::PAGE_SIZE, $currentPageItemsCount);
        } while ($page <= $lastPage);
        $this->assertEquals($totalCount, $itemsCounter);
    }

    private function calculateLastPage(int $totalCount): int
    {
        return (int)ceil($totalCount / self::PAGE_SIZE);
    }

    private function createProvider(): ProviderInterface
    {
        return new OpenfoodfactsProvider();
    }
}
