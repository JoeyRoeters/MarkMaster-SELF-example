<?php

namespace SELF\src\Helpers\Interfaces\Templating;

interface TemplateEngineInterface
{
    public function render(): string;
}