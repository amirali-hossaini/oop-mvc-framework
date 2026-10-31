<?php

if (! function_exists('requireFromRoot')) {
    function requireFromRoot(string $path): void
    {
        require_once dirname(__DIR__, 2) . '/' . ltrim($path, '/');
    }
}

if (! function_exists('includeFromRoot')) {
    function includeFromRoot(string $path): void
    {
        include dirname(__DIR__, 2) . '/' . ltrim($path, '/');
    }
}

if (! function_exists('fileExistsFromRoot')) {
    function fileExistsFromRoot(string $path): bool
    {
        return file_exists(dirname(__DIR__, 2) . '/' . ltrim($path, '/'));
    }
}

if (! function_exists('view')) {
    function view(string $view): void
    {
        if (fileExistsFromRoot("app/Views/{$view}.php")) {

            includeFromRoot("app/Views/{$view}.php");

            return;
        }

        die("View $view does not exists.");
    }
}

if (! function_exists('getUrl')) {
    function getUrl(): array
    {
        if (! isset($_GET['url']) || ! is_string($_GET['url'])) {
            return [];
        }

        $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);

        return explode('/', $url);
    }
}