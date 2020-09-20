<?php

namespace Tests\Unit;

use App\Models\Product\Product;
use App\Models\Product\ProviderInterface;
use App\Models\Product\SearchResult;
use App\ViewModel\Product\Product as ViewModelProduct;
use App\ViewModel\Product\Provider as ViewModelProvider;
use App\ViewModel\Product\ProviderInterface as ViewModelProviderInterface;
use App\ViewModel\Product\SearchResult as ViewModelSearchResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\Fake\Model\Product\Provider;
use Tests\TestCase;

class ViewModelProductProviderTest extends TestCase
{
    use RefreshDatabase;

    public function testSearch()
    {
        $pagesCount = ViewModelProvider::calculatePagesCount(Provider::COUNT);
        $modelProvider = $this->createModelProvider();
        $viewModelProvider = $this->createViewModelProvider($modelProvider);
        for ($page = 1; $page <= $pagesCount; $page++) {
            $this->assertPageResult($modelProvider, $viewModelProvider, $page);
        }
    }

    public function testNoItemsResultOnWrongPage()
    {
        $viewModelProvider = $this->createViewModelProvider($this->createModelProvider());
        $this->assertNoItemsResult($viewModelProvider->search(Provider::EXIST_PRODUCT_NAME, 0));
        $lastPage = ViewModelProvider::calculatePagesCount(Provider::COUNT);
        $this->assertNoItemsResult($viewModelProvider->search(Provider::EXIST_PRODUCT_NAME, $lastPage + 1));
    }

    public function testNoItemsResultOnWrongName()
    {
        $viewModelProvider = $this->createViewModelProvider($this->createModelProvider());
        $this->assertNoItemsResult($viewModelProvider->search('someOtherName', 1));
    }

    public function testSave()
    {
        $form = $this->createProductForm();
        $viewModelProvider = $this->createViewModelProvider($this->createModelProvider());

        $this->assertDontHasProductWithExternalID($form->external_id);
        $firstID = $viewModelProvider->save($form);
        $this->assertNotEmpty($firstID);
        $this->assertHasProductWithExternalID($form->external_id);
        $secondID = $viewModelProvider->save($form);
        $this->assertEquals($firstID, $secondID);
    }

    public function testSetIdForSavedProductsInSearchResult()
    {
        $viewModelProvider = $this->createViewModelProvider($this->createModelProvider());
        $searchResult = $viewModelProvider->search(Provider::EXIST_PRODUCT_NAME, 1);
        $savingProduct = $searchResult->getItems()->first();
        $id = $viewModelProvider->save($savingProduct);
        $searchResult = $viewModelProvider->search(Provider::EXIST_PRODUCT_NAME, 1);
        $savedProduct = $savingProduct = $searchResult->getItems()->first();
        $this->assertNotEmpty($savedProduct->id);
        $this->assertEquals($id, $savedProduct->id);
    }

    private function assertDontHasProductWithExternalID(string $id)
    {
        $this->assertDatabaseMissing('products', [
            'external_id' => $id,
        ]);
    }

    private function assertHasProductWithExternalID(string $id)
    {
        $this->assertDatabaseHas('products', [
            'external_id' => $id,
        ]);
    }

    private function assertNoItemsResult(ViewModelSearchResult $result)
    {
        $this->assertEquals(0, $result->getItems()->count());
    }

    private function assertPageResult(ProviderInterface $modelProvider, ViewModelProviderInterface $viewModelProvider, int $page)
    {
        $modelResult = $modelProvider->search(Provider::EXIST_PRODUCT_NAME, ViewModelProviderInterface::PAGE_SIZE, $page);
        $viewModelResult = $viewModelProvider->search(Provider::EXIST_PRODUCT_NAME, $page);
        $this->assertResults($modelResult, $viewModelResult);
    }

    private function assertResults(SearchResult $modelResult, ViewModelSearchResult $viewModelSearchResult)
    {
        $this->assertEquals($modelResult->getTotalCount(), $viewModelSearchResult->getTotalCount());
        $this->assertItems($modelResult->getItems(), $viewModelSearchResult->getItems());
    }

    /**
     * @param Collection|Product[] $modelItems
     * @param Collection|ViewModelProduct[] $viewModelItems
     */
    private function assertItems(Collection $modelItems, Collection $viewModelItems)
    {
        $this->assertEquals($modelItems->count(), $viewModelItems->count());
        foreach ($modelItems as $index => $modelItem) {
            $this->assertItem($modelItem, $viewModelItems[$index]);
        }
    }

    private function assertItem(Product $modelItem, ViewModelProduct $viewModelItem)
    {
        $this->assertEquals($modelItem->name, $viewModelItem->name);
        $this->assertEquals($modelItem->image_url, $viewModelItem->image_url);
        $this->assertEquals($modelItem->external_id, $viewModelItem->external_id);
        $this->assertEquals($modelItem->categories, $viewModelItem->categories);
    }

    private function createModelProvider(): ProviderInterface
    {
        return new Provider();
    }

    private function createViewModelProvider(ProviderInterface $modelProvider): ViewModelProviderInterface
    {
        return new ViewModelProvider($modelProvider);
    }

    private function createProductForm(): ViewModelProduct
    {
        $form = new ViewModelProduct();
        $form->name = 'TestName';
        $form->external_id = 'TestExternalID';
        $form->image_url = 'TestImageUrl';
        $form->categories = 'TestCategories';
        return $form;
    }
}
