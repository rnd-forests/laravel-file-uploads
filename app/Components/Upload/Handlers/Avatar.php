<?php

namespace App\Components\Upload\Handlers;

use App\Components\Upload\UploadHandler;
use App\Components\Upload\Uploader;

class Avatar extends Uploader implements UploadHandler
{
    /**
     * {@inheritdoc}
     */
    protected function getCustomDiskName()
    {
        return 'avatars';
    }

    /**
     * Avatars of a user will be stored in a sub-directory
     * with the name be the user's ID.
     *
     * @return string
     */
    public function getFilePath()
    {
        return (string) $this->user->id;
    }

    /**
     * Get the URL to the default avatar.
     *
     * @return string
     */
    public function getDefaultAvatarUrl()
    {
        return $this->url('default.png');
    }
}
