<?php

$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController())->index(),
    'auth/login' => (new AuthController())->login(),
    'auth/register' => (new AuthController())->register(),
    'auth/logout' => (new AuthController())->logout(),

    'product/index' => (new ProductController())->index(),
    'product/detail' => (new ProductController())->detail(),

    'cart/index' => (new CartController())->index(),
    'cart/add' => (new CartController())->add(),
    'cart/remove' => (new CartController())->remove(),

    'order/checkout' => (new OrderController())->checkout(),
    'order/my' => (new OrderController())->myOrders(),

    'review/store' => (new ReviewController())->store(),

    'admin/dashboard' => (new AdminController())->dashboard(),
    'admin/categories' => (new AdminController())->categories(),
    'admin/products' => (new AdminController())->products(),
    'admin/order-status' => (new AdminController())->updateOrderStatus(),
    'admin/delete' => (new AdminController())->delete(),

    default => (new HomeController())->index(),
};