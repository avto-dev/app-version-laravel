<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Commands;

use InvalidArgumentException;
use AvtoDev\AppVersion\Support\Version;
use Symfony\Component\Console\Input\InputOption;
use AvtoDev\AppVersion\AppVersionManagerInterface;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;

class VersionCommand extends \Illuminate\Console\Command
{
    protected const
        OPTION_GET_SEGMENT = 'get-segment',
        OPTION_SET_BUILD   = 'set-build',
        OPTION_SET_VERSION = 'set-version';

    protected const
        SEGMENT_MAJOR = 'major',
        SEGMENT_MINOR = 'minor',
        SEGMENT_PATCH = 'patch',
        SEGMENT_BUILD = 'build';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display this application <options=bold>(not framework)</> version';

    /**
     * Execute the console command.
     *
     * @param AppVersionManagerInterface $manager
     *
     * @return int
     */
    public function handle(AppVersionManagerInterface $manager): int
    {
        $repository = $manager->repository();

        // Get single version segment
        if (\is_string($segment = $this->option(static::OPTION_GET_SEGMENT))) {
            /** @var string $segment */
            $this->output->writeln((string) $this->getVersionSegment($segment, $repository));

            return 0;
        }

        // Complex version values set
        if (\is_string($version = $this->option(static::OPTION_SET_VERSION))) {
            /** @var string $version */
            $this->setNewVersionRaw($version, $repository);
            $this->info('New version value is set!');

            return 0;
        }

        if (\is_string($build = $this->option(static::OPTION_SET_BUILD))) {
            /** @var string $build */
            $repository->setBuild(\trim($build));
            $this->comment("Build version value successfully set to '{$build}'");
        }

        $this->output->writeln($manager->version());

        return 0;
    }

    /**
     * @param string              $segment
     * @param RepositoryInterface $repository
     *
     * @throws InvalidArgumentException
     *
     * @return int|string|null
     */
    protected function getVersionSegment(string $segment, RepositoryInterface $repository)
    {
        switch ($segment) {
            case self::SEGMENT_MAJOR:
                return $repository->getMajor();

            case self::SEGMENT_MINOR:
                return $repository->getMinor();

            case self::SEGMENT_PATCH:
                return $repository->getPatch();

            case self::SEGMENT_BUILD:
                return $repository->getBuild();
        }

        throw new InvalidArgumentException('Unknown version segment passed');
    }

    /**
     * @param string              $raw_version
     * @param RepositoryInterface $repository
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setNewVersionRaw(string $raw_version, RepositoryInterface $repository): void
    {
        $version = Version::parse(\ltrim(\trim($raw_version), 'vV'));

        if (! $version->isValid()) {
            throw new InvalidArgumentException("Wrong version value ({$raw_version}) passed");
        }

        $repository->setMajor((int) $version->getMajor());
        $repository->setMinor((int) $version->getMinor());
        $repository->setPatch((int) $version->getPatch());

        $build = $version->getBuild();

        if (\is_string($build)) {
            $repository->setBuild($build);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function specifyParameters(): void
    {
        parent::specifyParameters();

        $this->addOption(
            static::OPTION_GET_SEGMENT,
            null,
            InputOption::VALUE_OPTIONAL,
            'Display one version segment and exit. Allowed segments is: <comment>' . \implode(
                ', ', [self::SEGMENT_MAJOR, self::SEGMENT_MINOR, self::SEGMENT_PATCH, self::SEGMENT_BUILD]
            ) . '</comment>'
        );
        $this->addOption(
            static::OPTION_SET_BUILD,
            null,
            InputOption::VALUE_OPTIONAL,
            'Set new <comment>build</comment> version value'
        );
        $this->addOption(
            static::OPTION_SET_VERSION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Complex version setter'
        );
    }
}
