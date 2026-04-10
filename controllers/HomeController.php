<?php

class HomeController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products = $productModel->allWithCategory();

        $this->render('home/index', [
            'title' => 'Trang chủ',
            'view' => 'home/index',
            'products' => $products,
        ]);
    }
}