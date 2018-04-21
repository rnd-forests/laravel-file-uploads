<?php

namespace App\Components\Upload\Listeners;

use Illuminate\Support\Arr;

class StoreLessonAudio extends Listener
{
    /**
     * {@inheritdoc}
     */
    public function postprocess()
    {
        $lesson = Arr::get($this->event->context, 'lesson');
        $lesson->setAttribute('audio', $this->event->upload->hashed_name)->save();
        $lesson->audioFiles()->save($this->event->upload);
    }
}
