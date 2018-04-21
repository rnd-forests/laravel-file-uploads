<?php

namespace App\Components\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ClientFile
{
    /**
     * @var \Illuminate\Http\UploadedFile
     */
    protected $file;

    /**
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function originalName()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    /**
     * Generates a unique name for the uploaded file using UUID.
     *
     * @return string
     */
    public function hashedName()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return init
     */
    public function size()
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
    public function mime()
    {
        return $this->file->getClientMimeType();
    }

    /**
     * @return string
     */
    public function type()
    {
        $mime = $this->mime();

        return !empty($mime)
            ? Str::plural(head(explode('/', $mime)))
            : 'unknown';
    }

    /**
     * @return string
     */
    public function extension()
    {
        return $this->file->getClientOriginalExtension();
    }

    /**
     * Returns the attributes used to create new Upload model instance.
     *
     * @return array
     */
    public function toAttributes()
    {
        return [
            'name' => $this->originalName(),
            'hashed_name' => $this->hashedName(),
            'size' => $this->size(),
            'mime' => $this->mime(),
            'type' => $this->type(),
            'extension' => $this->extension(),
        ];
    }
}
