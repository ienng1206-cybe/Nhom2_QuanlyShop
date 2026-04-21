<?php

class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require PATH_VIEW_MAIN;
    }

    protected function requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}

