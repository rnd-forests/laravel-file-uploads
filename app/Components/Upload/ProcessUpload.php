<?php

namespace App\Components\Upload;

use App\Upload;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessUpload
{
    use Dispatchable, SerializesModels;

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * @var array
     */
    protected $context;

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param \Illuminate\Contracts\Filesystem\Filesystem $disk
     * @param array $context
     */
    public function __construct(
        UploadedFile $file,
        $path,
        Authenticatable $user,
        Filesystem $disk,
        $context
    ) {
        $this->file = $file;
        $this->path = $path;
        $this->user = $user;
        $this->disk = $disk;
        $this->context = $context;
    }

    /**
     * Handle the job.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            UploadProcessing::dispatch($this->user, $this->file, $this->context);

            $upload = $this->storeUploadedFile();

            UploadProcessed::dispatch($this->user, $upload, $this->context);

            return $upload;
        });
    }

    /**
     * Store the file from client.
     *
     * @return \App\Upload
     */
    protected function storeUploadedFile()
    {
        $upload = Upload::create(
            array_merge(
                ['owner_id' => $this->user->id, 'path' => $this->path],
                (new ClientFile($this->file))->toAttributes()
            )
        );

        $this->disk->putFileAs($upload->path, $this->file, $upload->basename);

        return $upload;
    }
}
