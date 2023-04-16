<?php

namespace SELF\src\Http\Responses;

class AssetResponse extends Response
{
    public function __construct(
        protected string $path,
    )
    {
        $mimeType = mime_content_type($this->path);
        $explode = explode('.', $this->path);
        $extension = end($explode);
        switch ($extension) {
            case 'svg':
                $mimeType = 'image/svg+xml';
                break;
            case 'js':
                $mimeType = 'application/javascript';
                break;
            case 'css':
                $mimeType = 'text/css';
                break;
            case 'png':
                $mimeType = 'image/png';
                break;
            case 'jpg':
                $mimeType = 'image/jpg';
                break;
            case 'jpeg':
                $mimeType = 'image/jpeg';
                break;
            case 'gif':
                $mimeType = 'image/gif';
                break;
            case 'ico':
                $mimeType = 'image/x-icon';
                break;
        }

        parent::__construct('1.1', [
            'Content-Type' => implode(
                ';',
                [$mimeType]
            )]
        );
    }

    public function output(): void
    {
        $this->body = file_get_contents($this->path);

        parent::output();
    }
}