<?php


namespace App\Http\Controllers;

use App\ViewModel\Product\Product;
use App\ViewModel\Product\ProviderInterface;
use App\ViewModel\Product\SearchResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProviderInterface
     */
    private $productsProvider;

    public function __construct(ProviderInterface $productsProvider)
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

    public function save(Request $request)
    {
        $form = new Product();
        $form->external_id = $request->post('external_id');
        $form->name = $request->post('name');
        $form->categories = $request->post('categories');
        $form->image_url = $request->post('image_url');

        return [
            'id' => $this->productsProvider->save($form),
        ];
    }

    private function createPagination(SearchResult $result, int $currentPage): LengthAwarePaginator
    {
        /**
         * @var $paginator LengthAwarePaginator
         */
        $paginator = resolve(LengthAwarePaginator::class, [
            'items' => $result->getItems(),
            'total' => $result->getTotalCount(),
            'perPage' => ProviderInterface::PAGE_SIZE,
            'currentPage' => $currentPage
        ]);
        $paginator->withQueryString();

        return $paginator;
    }
}
