<?php

namespace SELF\src\Http\Responses;

class AssetResponse extends Response
{
    public function __construct(
        protected string $path,
    )
    {
        parent::__construct('1.1', [
            'Content-Type' => implode(
                ';',
                [
                    'text/css',
                ])
            ]
        );
    }

    public function output(): void
    {
        $this->body = file_get_contents($this->path);

        parent::output();
    }
}