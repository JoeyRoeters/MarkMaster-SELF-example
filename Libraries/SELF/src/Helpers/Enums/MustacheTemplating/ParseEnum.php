<?php

namespace SELF\src\Helpers\Enums\MustacheTemplating;

use SELF\src\MustacheTemplating\Mustache;

enum ParseEnum: string
{
    case IF = 'if';
    case FOREACH = 'foreach';
    case FOR = 'for';
    case INCLUDE = 'include';
    case EXTENDS = 'extends';

    public function parse(Mustache $mustache, array $matches, array &$data): string
    {
        $method = 'parse' . ucfirst($this->value);

        return $this->$method($mustache, $matches, $data);
    }

    private function parseIf(Mustache $mustache, array $matches, array &$data): string
    {
        $variable = $matches[1];
        $ifTrue = $matches[2];
        $ifFalse = '';
        if (isset($matches[4])) {
            $ifFalse = $matches[4];
        }

        preg_match('/^(.+?)\s*(==|!=|<|>|<=|>=)\s*(.+?)$/', $variable, $comparison);
        $value = isset($data[$comparison[1]]) ? $data[$comparison[1]] : false;
        $operator = $comparison[2];
        $comparisonValue = $comparison[3];
        $result = $this->compare($value, $operator, $comparisonValue);

        return $result ? $ifTrue : $ifFalse;
    }

    private function parseForEach(Mustache $mustache, array $matches, array &$data): string
    {
        $array = $matches[1];
        $variable = $matches[2];
        $content = $matches[3];
        $output = '';
        if (isset($data[$array]) && is_array($data[$array])) {
            foreach ($data[$array] as $item) {
                $itemContext = [$variable => $item];
                $itemOutput = $mustache->renderTemplate($content, array_merge($data, $itemContext));
                $output .= $itemOutput;
            }
        }

        return $output;
    }

    private function parseFor(Mustache $mustache, array $matches, array &$data): string
    {
        $start = $matches[2];
        $end = $matches[3];
        $variable = $matches[1];
        $content = $matches[4];
        $output = '';

        if (is_numeric($start) && is_numeric($end) && $start <= $end) {
            $count = $end - $start + 1;
            for ($i = $start; $i <= $end; $i++) {
                $itemContext = [
                    $variable => $i,
                    'loop' => [
                        'index' => $i - $start,
                        'iteration' => $i - $start + 1,
                        'count' => $count
                    ]
                ];

                $itemOutput = $mustache->renderTemplate($content, array_merge($data, $itemContext));
                $output .= $itemOutput;
            }
        }

        return $output;

    }

    private function parseInclude(Mustache $mustache, array $matches, array &$data): string
    {
        $templateName = $matches[1];
        $include = new Mustache($templateName, $data);

        return $mustache->renderTemplate($include->render(), $data);
    }

    private function parseExtends(Mustache $mustache, array $matches, array &$data): string
    {
        $baseTemplateName = trim($matches[1], "'\"");

        $base = new Mustache($baseTemplateName, $data);
        $baseTemplateContent = $base->getFileContents();

        preg_match_all('/\{%\s*block\s+(\w+)\s*%\}(.*?)\{%\s*endblock\s*%\}/s', $baseTemplateContent, $baseBlockMatches, PREG_SET_ORDER);
        foreach ($baseBlockMatches as $baseBlockMatch) {
            $blockName = $baseBlockMatch[1];
            $blockContent = $baseBlockMatch[2];

            preg_match('/\{%\s*block\s+' . preg_quote($blockName, '/') . '\s*%\}(.*?)\{%\s*endblock\s*%\}/s', $mustache->getFileContents(), $childBlockMatch);
            $childBlockContent = $childBlockMatch[1] ?? '';

            $baseTemplateContent = str_replace($baseBlockMatch[0], $childBlockContent ?: $blockContent,  $baseTemplateContent);

        }
        $output = $baseTemplateContent;

        return preg_replace('/\{%\s*extends\s+.+?\s*%\}/s', '', $output);
    }

    private function compare($value, $operator, $comparisonValue)
    {
        $result = false;
        switch ($operator) {
            case '==':
                $result = $value == $comparisonValue;
                break;
            case '!=':
                $result = $value != $comparisonValue;
                break;
            case '<':
                $result = $value < $comparisonValue;
                break;
            case '>':
                $result = $value > $comparisonValue;
                break;
            case '<=':
                $result = $value <= $comparisonValue;
                break;
            case '>=':
                $result = $value >= $comparisonValue;
                break;
        }

        return $result;
    }
}
