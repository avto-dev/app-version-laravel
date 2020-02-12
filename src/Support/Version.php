<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Support;

/**
 * Do NOT use this class in your applications. This class ONLY for internal usage and can be changed or removed in any
 * time.
 *
 * @internal
 */
final class Version
{
    /**
     * @var int|null
     */
    protected $major;

    /**
     * @var int|null
     */
    protected $minor;

    /**
     * @var int|null
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $build;

    /**
     * Create a new version instance.
     *
     * @param int|null    $major
     * @param int|null    $minor
     * @param int|null    $path
     * @param string|null $build
     */
    public function __construct(?int $major = null, ?int $minor = null, ?int $path = null, ?string $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->path  = $path;
        $this->build = $build;
    }

    /**
     * Create a new version instance using rav version string representation.
     *
     * @param string $raw_value
     *
     * @return static
     */
    public static function parse(string $raw_value): self
    {
        /**
         * @link <https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
         */
        static $regexp = '/^' .
                         '(?P<major>0|[1-9]\d*)\.' .
                         '(?P<minor>0|[1-9]\d*)\.' .
                         '(?P<patch>0|[1-9]\d*)(?:-' .
                         '(?P<pre>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)' .
                         '(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))' .
                         '*))?(?:\+' .
                         '(?P<meta>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?' .
                         '$/m';

        \preg_match($regexp, \trim($raw_value), $matches);

        return new self(
            isset($matches['major']) && \filter_var($matches['major'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['major']
                : null,
            isset($matches['minor']) && \filter_var($matches['minor'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['minor']
                : null,
            isset($matches['patch']) && \filter_var($matches['patch'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['patch']
                : null,
            isset($matches['pre']) || isset($matches['meta'])
                ? \implode('+', \array_filter([$matches['pre'] ?? null, $matches['meta'] ?? null]))
                : null
        );
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return \is_int($this->getMajor()) && \is_int($this->getMinor()) && \is_int($this->getPath());
    }

    /**
     * @return int|null
     */
    public function getMajor(): ?int
    {
        return $this->major;
    }

    /**
     * @param int|null $major
     *
     * @return $this
     */
    public function setMajor(?int $major): self
    {
        $this->major = $major;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinor(): ?int
    {
        return $this->minor;
    }

    /**
     * @param int|null $minor
     *
     * @return $this
     */
    public function setMinor(?int $minor): self
    {
        $this->minor = $minor;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPath(): ?int
    {
        return $this->path;
    }

    /**
     * @param int|null $path
     *
     * @return $this
     */
    public function setPath(?int $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * @param string|null $build
     *
     * @return $this
     */
    public function setBuild(?string $build): self
    {
        $this->build = $build;

        return $this;
    }
}
