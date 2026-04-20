<?php

class ProductController extends BaseController
{
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $sort = trim($_GET['sort'] ?? '');
        $priceRange = trim($_GET['price_range'] ?? '');
        $productModel = new ProductModel();
        $products = $productModel->allWithCategory($keyword, $sort, $priceRange);

        $this->render('product/index', [
            'title' => 'Danh sách sản phẩm',
            'view' => 'product/index',
            'products' => $products,
            'keyword' => $keyword,
            'sort' => $sort,
            'priceRange' => $priceRange,
        ]);
    }

    public function detail()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $productModel = new ProductModel();
        $reviewModel = new ReviewModel();

        $product = $productModel->findDetail($id);
        if (!$product) {
            redirect('product/index');
        }

        $reviews = $reviewModel->getByProduct($id);
        $this->render('product/detail', [
            'title' => 'Chi tiết sản phẩm',
            'view' => 'product/detail',
            'product' => $product,
            'reviews' => $reviews,
        ]);
    }
}
