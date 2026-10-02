<?php
/**
 * Base Controller
 */

class Controller
{
    /**
     * Render sebuah view dengan layout
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $viewPath = APP_ROOT . '/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            require APP_ROOT . '/views/errors/404.php';
            return;
        }

        // Layout landing (publik) vs dashboard
        if ($layout === 'none') {
            require $viewPath;
            return;
        }

        require APP_ROOT . "/views/layouts/{$layout}-header.php";
        require $viewPath;
        require APP_ROOT . "/views/layouts/{$layout}-footer.php";
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
