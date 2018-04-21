<?php

namespace App\Components\Upload;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;

class UploadProcessing
{
    use Dispatchable, SerializesModels;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    public $file;

    /**
     * @var array
     */
    public $context = [];

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $context
     */
    public function __construct(Authenticatable $user, UploadedFile $file, $context = [])
    {
        $this->user = $user;
        $this->file = $file;
        $this->context = $context;
    }
}
