<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Commands;

use Symfony\Component\Console\Input\InputOption;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class VersionCommand extends \Illuminate\Console\Command
{
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
    protected $description = 'Display this application (not framework) version';

    /**
     * Execute the console command.
     *
     * @param AppVersionManagerContract $manager
     *
     * @return void
     */
    public function handle(AppVersionManagerContract $manager): void
    {
        if ($this->option('refresh')) {
            $manager->refresh();

            $this->info('Stored in files values updated, files recreated');
        } elseif ($build = $this->getBuildValue()) {
            $manager->setBuild($build);

            $this->info(sprintf('Application build version changed to "%s"', $build));
        } else {
            $this->output->writeln(
                $this->getBuild()
                    ? (string) $manager->build()
                    : $manager->formatted()
            );
        }
    }

    /**
     * @return string|null
     */
    protected function getBuildValue(): ?string
    {
        $set_build = $this->option('set-build');

        return \is_string($set_build) && $set_build !== ''
            ? $set_build
            : null;
    }

    /**
     * @return bool
     */
    protected function getBuild(): bool
    {
        return $this->option('build') === true;
    }

    /**
     * Get the console command options.
     *
     * @return array[]
     */
    protected function getOptions(): array
    {
        return [
            ['build', 'b', InputOption::VALUE_NONE, 'Display build value only.'],
            ['set-build', null, InputOption::VALUE_OPTIONAL, 'Set new build version value.'],
            ['refresh', 'r', InputOption::VALUE_NONE, 'Refresh stored in files version and build values.'],
        ];
    }
}
