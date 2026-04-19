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
    'cart/update' => (new CartController())->update(),
    'cart/remove' => (new CartController())->remove(),
    'cart/clear' => (new CartController())->clear(),

    'order/checkout' => (new OrderController())->checkout(),
    'order/place' => (new OrderController())->place(),
    'order/cancel' => (new OrderController())->cancel(),
    'order/detail' => (new OrderController())->detail(),
    'order/my' => (new OrderController())->myOrders(),

    'review/store' => (new ReviewController())->store(),
    'review/index' => (new ReviewController())->index(),

    'admin/dashboard' => (new AdminController())->dashboard(),
    'admin/categories' => (new AdminController())->categories(),
    'admin/category-update' => (new AdminController())->updateCategory(),
    'admin/products' => (new AdminController())->products(),
    'admin/product-update' => (new AdminController())->updateProduct(),
    'admin/order-status' => (new AdminController())->updateOrderStatus(),
    'admin/users' => (new AdminController())->users(),
    'admin/update-user' => (new AdminController())->updateUser(),
    'admin/delete' => (new AdminController())->delete(),

    default => (new HomeController())->index(),
};