<?php

namespace Core;

/**
 * Creatsify Studio - Lightweight Template View Engine
 * Supports sections, layouts, partials, and automatic XSS escaping.
 */
class View
{
    private string $viewPath;
    private array $data = [];
    private ?string $layout = null;
    private array $layoutData = [];
    private array $sections = [];
    private ?string $currentSection = null;

    public function __construct(string $viewPath, array $data = [])
    {
        $base = App::getInstance()->getBasePath() . '/views/';
        $this->viewPath = $base . ltrim(str_replace('.', '/', $viewPath), '/') . '.php';
        $this->data = $data;
    }

    /**
     * Render view to string
     */
    public function render(): string
    {
        if (!file_exists($this->viewPath)) {
            throw new \RuntimeException("View file not found: {$this->viewPath}");
        }

        // Extract variables for view scope
        extract($this->data, EXTR_SKIP);

        // Capture view content
        ob_start();
        require $this->viewPath;
        $content = ob_get_clean();

        // If a layout was declared during view execution, wrap content in layout
        if ($this->layout !== null) {
            $layoutPath = App::getInstance()->getBasePath() . '/views/' . ltrim(str_replace('.', '/', $this->layout), '/') . '.php';
            if (!file_exists($layoutPath)) {
                throw new \RuntimeException("Layout file not found: {$layoutPath}");
            }

            // Extract layout variables, merging view data with layout-specific data
            extract(array_merge($this->data, $this->layoutData));

            // Expose $content and sections to layout
            $this->sections['content'] = $content;

            ob_start();
            require $layoutPath;
            $content = ob_get_clean();
        }

        return $content;
    }

    /**
     * Static helper for direct rendering
     */
    public static function make(string $viewPath, array $data = []): string
    {
        $view = new self($viewPath, $data);
        return $view->render();
    }

    /**
     * Declare layout with optional data
     */
    public function layout(string $layout, array $data = []): void
    {
        $this->layout = $layout;
        $this->layoutData = $data;
    }

    /**
     * Start a section block
     */
    public function section(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    /**
     * End current section block
     */
    public function endSection(): void
    {
        if ($this->currentSection === null) {
            throw new \LogicException("No open section to end.");
        }

        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    /**
     * Yield section content in layout
     */
    public function yield(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Include a partial view
     */
    public function partial(string $partialPath, array $extraData = []): void
    {
        $base = App::getInstance()->getBasePath() . '/views/';
        $file = $base . ltrim(str_replace('.', '/', $partialPath), '/') . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("Partial not found: {$file}");
        }

        $mergedData = array_merge($this->data, $extraData);
        extract($mergedData, EXTR_SKIP);
        require $file;
    }

    /**
     * XSS sanitization helper
     */
    public function e(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
