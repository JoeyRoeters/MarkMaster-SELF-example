<?php

namespace SELF\src\MustacheTemplating;

use SELF\src\Exceptions\MustacheTemplating\TemplateNotFoundException;
use SELF\src\Helpers\Enums\MustacheTemplating\ParseEnum;
use SELF\src\Helpers\Interfaces\Templating\TemplateEngineInterface;

class Mustache implements TemplateEngineInterface
{
    private ?string $content = null;

    /**
     * @param string $templatePath
     * @param array $data
     */
    public function __construct(
        protected string $templatePath,
        protected array $data,
    )
    {
        $this->appendData([
            'base_url' => BASE_URL,
            'assets' => PUBLIC_DIR . '/assets'
        ]);
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function appendData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * get the contents of the template file
     *
     * @return string
     * @throws TemplateNotFoundException
     */
    public function getFileContents(): string
    {
        if ($this->content) {
            return $this->content;
        }

        $path = ASSETS_DIR . '/templates/' . $this->templatePath . '.mustache';
        if (!file_exists($path)) {
            throw new TemplateNotFoundException('Template not found: ' . $path);
        }

        return $this->content = file_get_contents($path);
    }

    /**
     * Render the template
     *
     * @param string $content
     * @param array $data
     * @return string
     */
    public function renderTemplate(string $content, array $data): string
    {
        $this->content = $content;

        // Check if template extends a base template
        while (preg_match('/\{%\s*extends\s+(.+?)\s*%\}/s', $this->content, $matches)) {
            $this->content = ParseEnum::EXTENDS->parse($this, $matches, $data);
        }

        $this->content = ParseEnum::VARIABLE->parse($this, [], $data);

        // Parse foreach loops in the template
        $this->content = preg_replace_callback('/\{%\s*foreach\s+(\w+(?:\.\w+)*)\s+as\s+(\w+)\s*%\}(.*?)\{%\s*endforeach\s*\1\s*%\}/s', function($matches) use(&$data) {
            return ParseEnum::FOREACH->parse($this, $matches, $data);
        }, $this->content);

        // Parse for loops in the template
        $this->content = preg_replace_callback('/\{%\s*for\s+(\w+)\s+in\s+(\d+)\.\.(\d+)\s*%\}(.*?)\{%\s*endfor\s*%\}/s', function($matches) use($data) {
            return ParseEnum::FOR->parse($this, $matches, $data);
        }, $this->content);

        // Parse if/else conditions in the template
        $this->content = preg_replace_callback('/\{%\s*if\s+(.+?)\s*%\}(.*?)\{%\s*(else\s*%\}(.*?)\{%\s*)?endif\s*%\}/s', function($matches) use(&$data) {
            return ParseEnum::IF->parse($this, $matches, $data);
        }, $this->content);

        // Parse include statements in the template
        $this->content = preg_replace_callback('/\{%\s*include\s+\'(.+?)\'\s*%\}/s', function($matches) use($data) {
            return ParseEnum::INCLUDE->parse($this, $matches, $data);
        }, $this->content);

        return $this->content;
    }

    /**
     * Render the template class
     *
     * @return string
     * @throws TemplateNotFoundException
     */
    public function render(): string
    {
        $content = $this->getFileContents();

        return $this->renderTemplate($content, $this->data);
    }
}