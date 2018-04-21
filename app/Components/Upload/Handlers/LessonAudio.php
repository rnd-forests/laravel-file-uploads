<?php

namespace App\Components\Upload\Handlers;

use App\Components\Upload\UploadHandler;
use App\Components\Upload\Uploader;
use App\Lesson;
use RuntimeException;

class LessonAudio extends Uploader implements UploadHandler
{
    /**
     * @var \App\Lesson
     */
    protected $lesson;

    /**
     * {@inheritdoc}
     */
    public function ensureValidFields()
    {
        if (!($this->lesson && $this->lesson instanceof Lesson)) {
            throw new RuntimeException('Invalid lesson provided.');
        }

        parent::ensureValidFields();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCustomDiskName()
    {
        return 'audio';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilePath()
    {
        return 'lessons'.DIRECTORY_SEPARATOR.$this->lesson->id;
    }

    /**
     * {@inheritdoc}
     */
    protected function extraEventContext()
    {
        return ['lesson' => $this->lesson];
    }

    /**
     * Get the lesson associated with the audio.
     *
     * @param  \App\Lesson $lesson
     * @return self
     */
    public function withLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }
}
