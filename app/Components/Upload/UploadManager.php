<?php

namespace App\Components\Upload;

use Illuminate\Support\Manager;

class UploadManager extends Manager
{
    /**
     * Register an event listener before uploading process.
     *
     * @param  mixed $callback
     * @return void
     */
    public function before($callback)
    {
        $this->app->make('events')->listen(UploadProcessing::class, $callback);
    }

    /**
     * Register an event listener after uploading process.
     *
     * @param  mixed $callback
     * @return void
     */
    public function after($callback)
    {
        $this->app->make('events')->listen(UploadProcessed::class, $callback);
    }

    /**
     * Register an event listener for the entire uploading cycle.
     *
     * @param  mixed $callback
     * @return void
     */
    public function cycle($callback)
    {
        $this->app->make('events')->listen([UploadProcessing::class, UploadProcessed::class], $callback);
    }

    /**
     * Call a custom upload handler.
     *
     * @param  string|null $driver
     * @return mixed
     */
    public function handler($driver = null)
    {
        return $this->driver($driver);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return 'avatar';
    }
}
