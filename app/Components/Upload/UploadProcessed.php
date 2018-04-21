<?php

namespace App\Components\Upload;

use App\Upload;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadProcessed
{
    use Dispatchable, SerializesModels;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * @var \App\Upload
     */
    public $upload;

    /**
     * @var array
     */
    public $context = [];

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param \App\Upload $upload
     * @param array $context
     */
    public function __construct(Authenticatable $user, Upload $upload, $context = [])
    {
        $this->user = $user;
        $this->upload = $upload;
        $this->context = $context;
    }
}
