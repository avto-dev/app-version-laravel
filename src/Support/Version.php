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
    protected $patch;

    /**
     * @var string|null
     */
    protected $build;

    /**
     * Create a new version instance.
     *
     * @param int|null    $major
     * @param int|null    $minor
     * @param int|null    $patch
     * @param string|null $build
     */
    public function __construct(?int $major = null, ?int $minor = null, ?int $patch = null, ?string $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
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

        $checkCallback = static function ($value): bool {
            return \is_string($value) && $value !== '';
        };

        \preg_match($regexp, \trim($raw_value), $matches);

        return new static(
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
                ? \implode('+', \array_filter([$matches['pre'] ?? null, $matches['meta'] ?? null], $checkCallback))
                : null
        );
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        // Validate build value
        if (\is_string($this->build) && \preg_match('~^[a-zA-Z0-9-.+]+$~', $this->build) !== 1) {
            return false;
        }

        // Validate major value
        if (! \is_int($this->major) || $this->major < 0) {
            return false;
        }

        // Validate minor value
        if (! \is_int($this->minor) || $this->minor < 0) {
            return false;
        }

        // Validate pacth value
        if (! \is_int($this->patch) || $this->patch < 0) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted version value (e.g.: `1.0.0-beta+build.1`, `0.0.1`).
     *
     * Format: `{major}.{minor}.{patch}[-{build_with_meta}]`
     */
    public function format(): string
    {
        $version = \implode('.', [$this->major ?? 0, $this->minor ?? 0, $this->patch ?? 0]);

        if (\is_string($this->build) && $this->build !== '') {
            $version .= "-{$this->build}";
        }

        return $version;
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
    public function getPatch(): ?int
    {
        return $this->patch;
    }

    /**
     * @param int|null $patch
     *
     * @return $this
     */
    public function setPatch(?int $patch): self
    {
        $this->patch = $patch;

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
