<?php

namespace App\Components;

use App\Upload;

/**
 * @property-read null|\App\Upload avatarImage
 */
trait HasAvatar
{
    /**
     * Get the user's avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        $file = $this->getUploadedAvatar();
        $handler = app('upload')->handler('avatar');

        return $file ? $handler->url($file) : $handler->getDefaultAvatarUrl();
    }

    /**
     * Get user's custom avatar.
     *
     * @return null|\App\Upload
     */
    public function getUploadedAvatar()
    {
        if (!$this->avatarExisted()) {
            return null;
        }

        return $this->avatarImage;
    }

    /**
     * Upload instance associated with the user's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function avatarImage()
    {
        return $this->hasOne(Upload::class, 'hashed_name', 'avatar');
    }

    /**
     * Determine if user's custom avatar existed or not.
     *
     * @return bool
     */
    protected function avatarExisted()
    {
        return array_key_exists('avatar', $this->getAttributes()) && !is_null($this->getAttribute('avatar'));
    }
}
