<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Shop\Models\Product;
use App\Http\Controllers\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Modules\Shop\Repositories\Front\Interfaces\ProductRepositoryInterface;
use Modules\Shop\Repositories\Front\Interfaces\CategoryRepositoryInterface;
use Modules\Shop\Repositories\Front\Interfaces\TagRepositoryInterface;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $productRepository;
    protected $categoryRepository;
    protected $tagRepository;
    protected $defaultPriceRange;
    protected $sortingQuery;

    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, TagRepositoryInterface $tagRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->defaultPriceRange = [
            'min' => 0,
            'max' => 500000
        ];

        $this->data['categories'] = $this->categoryRepository->findAll();
        $this->data['tag'] = $this->tagRepository->findAll();
        $this->data['price'] = $this->defaultPriceRange;


        $this->sortingQuery = null;
        $this->data['sortingQuery'] = $this->sortingQuery;
        $this->data['sortingOptions'] = [
            '' => 'Sort Products',
            '?sort=price&order=asc' => 'Price: Low to High',
            '?sort=price&order=desc' => 'Price: High to Low',
            '?sort=publish_date&order=desc' => 'Newest Item',
        ];
    }
    public function index(Request $request)
    {
        $priceFilter = $this->getPriceRangeFilter($request);
        $options = [
            'per_page' => $this->perPage,
            'filter' => [
                'price' => $priceFilter
            ]
        ];
        $this->data['filter']['price'] = $this->data['price'];
        if ($request->get('price')) {
            $this->data['filter']['price'] = $priceFilter;
        }
        if ($request->get('sort')) {
            $sort = $this->sortingRequest($request);
            $options['sorting'] = $sort;
            $this->sortingQuery = '?sort=' . $sort['sort'] . '&order=' . $sort['order'];
            $this->data['sortingQuery'] = $this->sortingQuery;
        }

        $this->data['products'] = $this->productRepository->findAll($options);
        // dd($this->data);
        return $this->loadTheme('products.index', $this->data);
    }

    public function dashboard()
    {
        return $this->loadTheme('seller.dashboard');
    }

    public function category(Request $request, $categorySlug)
    {
        $priceFilter = $this->getPriceRangeFilter($request);
        $category = $this->categoryRepository->findBySlug($categorySlug);
        $options = [
            'per_page' => $this->perPage,
            'filter' => [
                'category' => $categorySlug,
                'price' => $priceFilter
            ]
        ];
        $this->data['filter']['price'] = $this->data['price'];
        if ($request->get('price')) {
            $this->data['filter']['price'] = $priceFilter;
        }
        $this->data['products'] = $this->productRepository->findAll($options);
        $this->data['category'] = $category;
        return $this->loadTheme('products.category', $this->data);
    }

    public function tag(Request $request, $tagSlug)
    {
        $priceFilter = $this->getPriceRangeFilter($request);
        $tag = $this->tagRepository->findBySlug($tagSlug);
        $options = [
            'per_page' => $this->perPage,
            'filter' => [
                'tag' => $tagSlug,
                'price' => $priceFilter
            ]
        ];
        $this->data['filter']['price'] = $this->data['price'];
        if ($request->get('price')) {
            $this->data['filter']['price'] = $priceFilter;
        }
        $this->data['products'] = $this->productRepository->findAll($options);
        $this->data['tag'] = $tag;
        return $this->loadTheme('products.tag', $this->data);
    }

    public function getPriceRangeFilter($request)
    {

        if (!$request->get('price')) {
            return [];
        }
        $prices = explode(' - ', $request->get('price'));
        if (count($prices) < 0) {
            return $this->defaultPriceRange;
        }
        return [
            'min' => (int) $prices[0],
            'max' => (int) $prices[1],
        ];
    }
    public function sortingRequest(Request $request)
    {
        $sort = [];
        if ($request->get('sort') && $request->get('order')) {
            $sort = [
                'sort' => $request->get('sort'),
                'order' => $request->get('order'),
            ];
        } else if ($request->get('sort')) {
            $sort = [
                'sort' => $request->get('sort'),
                'order' => 'desc',
            ];
        }
        return $sort;
    }

    public function show($categorySlug, $productSlug)
    {
        $sku = Arr::last(explode('-', $productSlug));
        $product = $this->productRepository->findBySKU($sku);
        $this->data['product'] = $product;
        // dd($this->data);
        return $this->loadTheme('products.show', $this->data);
    }
}
