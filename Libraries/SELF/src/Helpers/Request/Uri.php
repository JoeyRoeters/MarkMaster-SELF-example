<?php

namespace SELF\src\Helpers\Request;

use SELF\src\Helpers\Interfaces\Message\UriInterface;

class Uri implements UriInterface
{
    public function __construct(
        private string $scheme = '',
        private string $userInfo = '',
        private string $host = '',
        private string $port = '',
        private string $path = '',
        private string $query = '',
        private string $fragment = '',
    ) {
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->getHost();

        if (!empty($this->getUserInfo())) {
            $authority = $this->getUserInfo() . '@' . $authority;
        }

        if (!empty($this->getPort())) {
            $authority .= ':' . $this->getPort();
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): UriInterface
    {
        $new = clone $this;
        $new->scheme = $scheme;
        return $new;
    }

    public function withUserInfo(string $user, string $password = null): UriInterface
    {
        $new = clone $this;
        $new->userInfo = $user;
        if (!empty($password)) {
            $new->userInfo .= ':' . $password;
        }
        return $new;
    }

    public function withHost(string $host): UriInterface
    {
        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    public function withPort(int $port): UriInterface
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function withPath(string $path): UriInterface
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function withQuery(string $query): UriInterface
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function withFragment(string $fragment): UriInterface
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        $uri = $this->getScheme() . '://';
        $uri .= $this->getAuthority();
        $uri .= $this->getPath();
        $uri .= $this->getQuery();
        $uri .= $this->getFragment();
        return $uri;
    }

    public static function createFromString(string $uri): UriInterface
    {
        $parts = parse_url($uri);

        return new Uri(
            $parts['scheme'] ?? '',
            $parts['user'] ?? '',
            $parts['host'] ?? '',
            $parts['port'] ?? '',
            $parts['path'] ?? '',
            $parts['query'] ?? '',
            $parts['fragment'] ?? '',
        );
    }
}