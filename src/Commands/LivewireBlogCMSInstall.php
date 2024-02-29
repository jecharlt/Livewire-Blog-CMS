<?php

namespace Jecharlt\LivewireBlogCMS\Commands;

use Illuminate\Console\Command;

class LivewireBlogCMSInstall extends Command
{
    public $signature = 'livewire-blog-cms:install';

    public $description = 'Install the livewire-blog-cms package';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => 'Jecharlt\LivewireBlogCMS\LivewireBlogCMSServiceProvider',
            '--tag' => 'livewire-blog-cms-assets'
        ]);
        if ($this->confirm('Do you want to create a symbolic link for the storage directory now? This may affect existing links.',
            true)) {
            $this->call('storage:link');
        }
        if ($this->confirm('Do you want to run the Livewire Blog CMS migrations now? This will modify your database.',
            true)) {
            $this->call('migrate');
        }
        $this->info("Setup complete!");
        return self::SUCCESS;
    }
}
