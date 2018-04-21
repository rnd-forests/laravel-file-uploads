<?php

namespace App\Components\Upload;

use App\Upload;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use RuntimeException;

abstract class Uploader
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    protected $file;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function store()
    {
        $this->ensureValidFields();

        $this->dispatchUploadJob();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($file)
    {
        return $this->getDiskInstance()
            ->delete($this->getStoredFilePath($file));
    }

    /**
     * {@inheritdoc}
     */
    public function url($file)
    {
        return $this->getDiskInstance()
            ->url($this->getStoredFilePath($file));
    }

    /**
     * {@inheritdoc}
     */
    public function withFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilePath()
    {
        throw new RuntimeException('Path to the file must be specified.');
    }

    /**
     * Check properties before perform uploading.
     *
     * @return void
     * @throws RuntimeException
     */
    protected function ensureValidFields()
    {
        if (!($this->file && $this->file instanceof UploadedFile && $this->file->isValid())) {
            throw new RuntimeException('Invalid upload file provided.');
        }

        if (!($this->user && $this->user instanceof Authenticatable)) {
            throw new RuntimeException('Cannot process the provided user.');
        }
    }

    /**
     * Dispatch the upload job.
     *
     * @return void
     */
    protected function dispatchUploadJob()
    {
        ProcessUpload::dispatch(
            $this->file,
            $this->getFilePath(),
            $this->user,
            $this->getDiskInstance(),
            $this->eventContext()
        );
    }

    /**
     * Get the path of a uploaded file.
     *
     * @param  string|\App\Upload $file
     * @return string
     */
    protected function getStoredFilePath($file)
    {
        if ($file instanceof Upload) {
            $file = $file->filePath;
        }

        if (!is_string($file)) {
            throw new RuntimeException('Invalid file path provided.');
        }

        return $file;
    }

    /**
     * Get the storage disk instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getDiskInstance()
    {
        return $this->app->make('filesystem')->disk($this->getDiskName());
    }

    /**
     * Get the the name of the disk or use the default.
     *
     * @return string
     */
    final protected function getDiskName()
    {
        return $this->getCustomDiskName() ?? config('filesystems.default');
    }

    /**
     * Get the custom disk name where files will be stored.
     *
     * You can change these disks' configuration in `config/filesystems.php` file.
     *
     * @return string|null
     */
    protected function getCustomDiskName()
    {
        return null;
    }

    /**
     * Gets data for uploading events.
     *
     * @return array
     */
    final protected function eventContext()
    {
        return array_merge($this->extraEventContext(), [
            'handler' => $this,
        ]);
    }

    /**
     * Gets extra data for events fired during upload process.
     *
     * @return array
     */
    protected function extraEventContext()
    {
        return [];
    }
}
