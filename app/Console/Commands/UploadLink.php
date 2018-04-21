<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploader:create-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symbolic link for some important storage directories';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filesystem = $this->laravel->make('files');

        $dirs = [
            'app/avatars' => 'avatars',
            'app/audio' => 'audio',
        ];

        foreach ($dirs as $origin => $destination) {
            if (file_exists(public_path($destination))) {
                $this->error("The [{$destination}] directory already exists.");
                continue;
            }

            $filesystem->link(storage_path($origin), public_path($destination));

            $this->info("The [${origin}] directory has been linked.");
        }
    }
}
