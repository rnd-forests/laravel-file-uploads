<?php

namespace App\Components\Upload\Listeners;

use Illuminate\Support\Arr;

abstract class Listener
{
    /**
     * The event fired during upload process.
     *
     * @var mixed
     */
    protected $event;

    /**
     * @param mixed $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Actions should be taken before uploading process.
     *
     * @return mixed
     */
    public function preprocess()
    {
        //
    }

    /**
     * Actions should be taken after uploading process.
     *
     * @return mixed
     */
    public function postprocess()
    {
        //
    }

    /**
     * @return \App\Components\Upload\UploadHandler
     */
    public function getHandler()
    {
        return Arr::get($this->event->context, 'handler');
    }
}
