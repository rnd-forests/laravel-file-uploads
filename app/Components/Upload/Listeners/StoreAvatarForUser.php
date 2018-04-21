<?php

namespace App\Components\Upload\Listeners;

class StoreAvatarForUser extends Listener
{
    /**
     * {@inheritdoc}
     */
    public function preprocess()
    {
        $upload = $this->event->user->avatarImage;

        if ($upload) {
            $this->getHandler()->delete($upload);
            $upload->delete();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postprocess()
    {
        $this->event->user
            ->setAttribute('avatar', $this->event->upload->hashed_name)
            ->save();
    }
}
