<?php

namespace AvtoDev\AppVersion\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class VersionCommand extends Command
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
    public function handle(AppVersionManagerContract $manager)
    {
        if ($this->option('refresh')) {
            $manager->refresh();

            $this->info('Stored in files values updated, files recreated');
        } elseif ($build = $this->option('set-build')) {
            $manager->setBuild($build);

            $this->info(sprintf('Application build version changed to "%s"', $build));
        } else {
            $this->output->writeln(
                $this->option('build')
                    ? $manager->build()
                    : $manager->formatted()
            );
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['build', 'b', InputOption::VALUE_NONE, 'Display build value only.'],
            ['set-build', null, InputOption::VALUE_OPTIONAL, 'Set new build version value.'],
            ['refresh', 'r', InputOption::VALUE_NONE, 'Refresh stored in files version and build values.'],
        ];
    }
}
