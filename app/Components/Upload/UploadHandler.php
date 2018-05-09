<?php

namespace App\Components\Upload;

interface UploadHandler
{
    /**
     * Store the file supplied by client.
     *
     * @return void
     */
    public function store();

    /**
     * Remove a stored file.
     *
     * @param  string|\App\Upload $file
     * @return string
     */
    public function delete($file);

    /**
     * Get URL of a stored file.
     *
     * @param  string|\App\Upload $file
     * @return string
     */
    public function url($file);

    /**
     * The file associated with the request.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return self
     */
    public function withFile($file);

    /**
     * The user initiating the upload request.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return self
     */
    public function withUser($user);

    /**
     * Get the path where the file will be stored relative
     * to the root directory of the disk.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getFilePath();
}
