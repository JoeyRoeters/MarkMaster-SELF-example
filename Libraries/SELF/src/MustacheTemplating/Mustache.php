<?php

namespace SELF\src\MustacheTemplating;

use SELF\src\Exceptions\MustacheTemplating\TemplateNotFoundException;
use SELF\src\Helpers\Enums\MustacheTemplating\ParseEnum;
use SELF\src\Helpers\Interfaces\Templating\TemplateEngineInterface;

class Mustache implements TemplateEngineInterface
{
    public function __construct(
        protected string $templatePath,
        protected array $data,
    )
    {
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

    public function getFileContents(): string
    {
        $path = ASSETS . '/templates/' . $this->templatePath . '.mustache';
        if (!file_exists($path)) {
            throw new TemplateNotFoundException('Template not found: ' . $path);
        }

        return file_get_contents($path);
    }

    public function renderTemplate(string $content, array $data): string
    {
        $output = $content;

        // Check if template extends a base template
        if (preg_match('/\{%\s*extends\s+(.+?)\s*%\}/s', $output, $matches)) {
            $output = ParseEnum::EXTENDS->parse($this, $matches, $data);
        }

        // Replace variables in the template with their values
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $output = str_replace("{{ " . $key . "." . $subKey . " }}", $subValue, $output);
                }

                continue;
            }

            $output = str_replace("{{ " . $key . " }}", $value, $output);
        }

        // Parse if/else conditions in the template
        $output = preg_replace_callback('/\{%\s*if\s+(.+?)\s*%\}(.*?)\{%\s*(else\s*%\}(.*?)\{%\s*)?endif\s*%\}/s', function($matches) use(&$data) {
            return ParseEnum::IF->parse($this, $matches, $data);
        }, $output);

        // Parse foreach loops in the template
        $output = preg_replace_callback('/\{%\s*foreach\s+(.+?)\s+as\s+(.+?)\s*%\}(.*?)\{%\s*endforeach\s*%\}/s', function($matches) use(&$data) {
            return ParseEnum::FOREACH->parse($this, $matches, $data);
        }, $output);

        // Parse for loops in the template
        $output = preg_replace_callback('/\{%\s*for\s+(\w+)\s+in\s+(\d+)\.\.(\d+)\s*%\}(.*?)\{%\s*endfor\s*%\}/s', function($matches) use($data) {
            return ParseEnum::FOR->parse($this, $matches, $data);
        }, $output);

        // Parse include statements in the template
        $output = preg_replace_callback('/\{%\s*include\s+\'(.+?)\'\s*%\}/s', function($matches) use($data) {
            return ParseEnum::INCLUDE->parse($this, $matches, $data);
        }, $output);

        return $output;
    }

    public function render(): string
    {
        $content = $this->getFileContents();

        return $this->renderTemplate($content, $this->data);
    }
}