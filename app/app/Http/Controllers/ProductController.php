<?php


namespace App\Http\Controllers;

use App\ViewModel\Product\ProductsProvider;
use App\ViewModel\Product\SearchResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductsProvider
     */
    private $productsProvider;

    public function __construct(ProductsProvider $productsProvider)
    {
        $this->productsProvider = $productsProvider;
    }

    public function search(Request $request)
    {
        $name = $request->get('name');
        $page = $request->get('page', 1);
        $items = null;
        if ($name) {
            $searchResult = $this->productsProvider->search($name, $page);
            $items = $this->createPagination($searchResult, $page);
        }

        return view('product.search', [
            'name' => $name,
            'page' => $page,
            'items' => $items
        ]);
    }

    private function createPagination(SearchResult $result, int $currentPage): LengthAwarePaginator
    {
        /**
         * @var $paginator LengthAwarePaginator
         */
        $paginator = resolve(LengthAwarePaginator::class, [
            'items' => $result->getItems(),
            'total' => $result->getTotalCount(),
            'perPage' => ProductsProvider::PAGE_SIZE,
            'currentPage' => $currentPage
        ]);
        $paginator->withQueryString();

        return $paginator;
    }
}
