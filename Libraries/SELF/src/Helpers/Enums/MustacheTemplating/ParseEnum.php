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
    case VARIABLE = 'variable';

    public function parse(Mustache $mustache, array $matches, array &$data): string
    {
        $method = 'parse' . ucfirst($this->value);

        return $this->$method($mustache, $matches, $data);
    }

    private function parseVariable(Mustache $mustache, array $matches, array &$data): string
    {
        $content = $mustache->getFileContents();

        // Replace variables in the template with their values
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        continue;
                    }

                    $content = str_replace("{{ " . $key . "." . $subKey . " }}", $subValue, $content);
                }

                continue;
            }

            $content = str_replace("{{ " . $key . " }}", $value, $content);
        }

        $content = preg_replace_callback('/{{\s*asset\s*\(\s*[\'"](.+?)[\'"]\s*\)\s*}}/', function($matches) {
            return PUBLIC_DIR . '/' . $matches[1];
        }, $content);

        $content = preg_replace_callback('/{{\s*route\s*\(\s*[\'"](.+?)[\'"]\s*\)\s*}}/', function($matches) {
            return environment('APP_URL') . '/' . $matches[1];
        }, $content);


        return $content;
    }

    private function parseIf(Mustache $mustache, array $matches, array &$data): string
    {
        $variable = $matches[1];
        $ifTrue = $matches[2];
        $ifFalse = '';
        if (isset($matches[4])) {
            $ifFalse = $matches[4];
        }

        // Check for isset condition
        if (preg_match('/isset\(([^)]+)\)/', $variable, $issetMatches)) {
            $issetVariable = $issetMatches[1];
            $result = isset($data[$issetVariable]) ? $data[$issetVariable] : false;
        } else if (preg_match('/^(.+?)\s*(==|!=|<|>|<=|>=)\s*(.+?)$/', $variable, $comparison)) {
            $parts = preg_split('/\./', $comparison[1]);
            if (count($parts) > 1) {
                $value = isset($data[$parts[0]][$parts[1]]) ? $data[$parts[0]][$parts[1]] : false;
            } else {
                $value = isset($data[$comparison[1]]) ? $data[$comparison[1]] : false;
            }
            $operator = $comparison[2];
            $comparisonValue = $comparison[3];
            $result = $this->compare($value, $operator, $comparisonValue);
        } else {
            $result = isset($data[$variable]) ? $data[$variable] : false;
        }

//        s_dump($ifTrue);

        return $result === true ? $ifTrue : $ifFalse;
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

        preg_match_all('/\{%\s*block\s+(\w+)\s*%\}(.*?)\{%\s*endblock\s+\1\s*%\}/s', $mustache->getFileContents(), $childBlockMatches, PREG_SET_ORDER);
        foreach ($childBlockMatches as $childBlockMatch) {
            $blockName = $childBlockMatch[1];
            $blockContent = $childBlockMatch[2];

            preg_match('/\{%\s*block\s+' . preg_quote($blockName, '/') . '\s*%\}(.*?)\{%\s*endblock\s*' . preg_quote($blockName, '/') . '\s*%\}/s', $baseTemplateContent, $baseBlockMatch);
            $baseBlockContent = $baseBlockMatch[1] ?? '';

            $baseTemplateContent = str_replace($baseBlockMatch[0], $blockContent ?: $baseBlockContent,  $baseTemplateContent);
        }

        $output = $baseTemplateContent;

        // replace extend $baseTemplateName
        return preg_replace('/\{%\s*extends\s+\'?' . preg_quote($baseTemplateName, '/') . '\'?\s*%\}/', '', $output);
    }

    private function compare($value, $operator, $comparisonValue)
    {
        $result = false;
        if (is_numeric($value) && is_numeric($comparisonValue)) {
            $value = (int) $value;
            $comparisonValue = (int) $comparisonValue;
        }

        if (is_bool($value)) {
            $value = (bool) $value;
            $comparisonValue = (bool) $comparisonValue;
        }

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
