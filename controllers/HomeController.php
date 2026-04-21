<?php

class HomeController extends BaseController
{
    public function index(): void
    {
        
        redirect('product/index');
    }
}

