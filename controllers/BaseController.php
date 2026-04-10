<?php

class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);
        require PATH_VIEW_MAIN;
    }

    protected function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}
