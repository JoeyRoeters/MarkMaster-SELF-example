<?php

namespace App\Helpers\SweetAlert;

class SweetAlert
{
    public static $message = null;

    /** @var string list of alert types */
    public const TYPE_SUCCESS = 'success';
    public const TYPE_ERROR = 'error';
    public const TYPE_WARNING = 'warning';
    public const TYPE_INFO = 'info';

    /** @var string */
    private $icon;

    /** @var string|null */
    private $title;

    /** @var string */
    private $text;

    /** @var string|null */
    private $confirmButtonText;

    /** @var string|null */
    private $confirmButtonColor;

    /** @var string|null */
    private $cancelButtonText;

    /** @var string|null */
    private $cancelButtonColor;

    /** @var bool */
    private $showCancelButton = false;

    /** @var string|null */
    private $url;

    /** @var int|null */
    private $timer;

    /** @var int|null */
    private $defaultTimer = 1500;

    /**
     * @param string $type
     * @param string $icon
     */
    public function __construct(string $icon, string $text)
    {
        $this->icon = $icon;
        $this->text = $text;

        self::$message = $this;
    }

    public static function create(string $type, string $text): self
    {
        return new self($type, $text);
    }

    public static function createWarning(string $text): self
    {
        return self::create(self::TYPE_WARNING, $text);
    }

    public static function createInfo(string $text): self
    {
        return self::create(self::TYPE_INFO, $text);
    }

    public static function createSuccess(string $text): self
    {
        return self::create(self::TYPE_SUCCESS, $text);
    }

    public static function createError(string $text): self
    {
        return self::create(self::TYPE_ERROR, $text);
    }

    public static function createConfirm(string $text, string $url, ?string $title = null): self
    {
        $alert = self::create(self::TYPE_WARNING, $text);
        $alert->setTitle($title);
        $alert->setConfirmButtonText('Ga verder');
        $alert->setCancelButtonText('annuleer');
        $alert->setUrl($url);

        return $alert;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return SweetAlert
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return SweetAlert
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimer(): ?int
    {
        return $this->timer;
    }

    /**
     * @param int|null $timer
     *
     * @return $this
     */
    public function setTimer(?int $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDefaultTimer(): ?int
    {
        return $this->defaultTimer;
    }

    /**
     * @param int|null $defaultTimer
     *
     * @return $this
     */
    public function setDefaultTimer(?int $defaultTimer): self
    {
        $this->defaultTimer = $defaultTimer;

        return $this;
    }

    /**
     * @return $this
     */
    public function dontClose(): self
    {
        $this->setDefaultTimer(null);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmButtonText(): ?string
    {
        return $this->confirmButtonText;
    }

    /**
     * @param string|null $confirmButtonText
     *
     * @return SweetAlert
     */
    public function setConfirmButtonText(?string $confirmButtonText): self
    {
        $this->confirmButtonText = $confirmButtonText;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmButtonColor(): ?string
    {
        return $this->confirmButtonColor;
    }

    /**
     * @param string|null $confirmButtonColor
     *
     * @return SweetAlert
     */
    public function setConfirmButtonColor(?string $confirmButtonColor): self
    {
        $this->confirmButtonColor = $confirmButtonColor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCancelButtonText(): ?string
    {
        return $this->cancelButtonText;
    }

    /**
     * @param string|null $cancelButtonText
     *
     * @return SweetAlert
     */
    public function setCancelButtonText(?string $cancelButtonText): self
    {
        $this->cancelButtonText = $cancelButtonText;
        $this->setShowCancelButton(!empty($cancelButtonText));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCancelButtonColor(): ?string
    {
        return $this->cancelButtonColor;
    }

    /**
     * @param string|null $cancelButtonColor
     *
     * @return SweetAlert
     */
    public function setCancelButtonColor(?string $cancelButtonColor): self
    {
        $this->cancelButtonColor = $cancelButtonColor;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowCancelButton(): bool
    {
        return $this->showCancelButton;
    }

    /**
     * @param bool $showCancelButton
     *
     * @return SweetAlert
     */
    public function setShowCancelButton(bool $showCancelButton): self
    {
        $this->showCancelButton = $showCancelButton;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'icon' => $this->getIcon(),
            'text' => $this->getText(),
            'title' => $this->getTitle()
        ];

        if ($this->getConfirmButtonText()) {
            $data['confirmButtonText'] = $this->getConfirmButtonText();
        }

        if ($this->getConfirmButtonColor()) {
            $data['confirmButtonColor'] = $this->getConfirmButtonColor();
        }

        if ($this->getCancelButtonText()) {
            $data['cancelButtonText'] = $this->getCancelButtonText();
        }

        if ($this->getCancelButtonColor()) {
            $data['cancelButtonColor'] = $this->getCancelButtonColor();
        }

        if ($this->isShowCancelButton()) {
            $data['showCancelButton'] = $this->isShowCancelButton();
        }

        if ($this->getUrl()) {
            $data['url'] = $this->getUrl();
        }

        if ($this->getTimer()) {
            $data['timer'] = $this->getTimer();
        }

        // set default close timer for success message when time is not set and when default timer is not set to null
        if ($this->getIcon() === self::TYPE_SUCCESS && !isset($data['timer']) && is_int($this->getDefaultTimer())) {
            $data['timer'] = $this->getDefaultTimer();
        }

        return $data;
    }

    public static function appendToUrl($url): string
    {
        $sweetAlert = self::$message;

        if ($sweetAlert instanceof self) {
            $url = static::addParamsToUrl($url, ['swal' => base64_encode(json_encode($sweetAlert->toArray()))]);
        }

        return $url;
    }

    public static function hasMessage(): bool
    {
        return self::$message instanceof self;
    }

    public static function addParamsToUrl(string $url, array $params): string
    {
        /** @var array $parsedUrl */
        $parsedUrl = parse_url($url);

        $parts = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $parts);
        }
        foreach ($params as $key => $value) {
            $parts[$key] = $value;
        }

        $parsedUrl['query'] = http_build_query($parts);

        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = $parsedUrl['host'] ?? '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user = $parsedUrl['user'] ?? '';
        $pass = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass'] : '';
        $pass = ($user || $pass) ? "{$pass}@" : '';
        $path = $parsedUrl['path'] ?? '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "{$scheme}{$user}{$pass}{$host}{$port}{$path}{$query}{$fragment}";
    }
}
