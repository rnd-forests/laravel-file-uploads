<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string hashed_name
 * @property string extension
 * @property string basename
 * @property string path
 */
class Upload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'name',
        'hashed_name',
        'size',
        'type',
        'mime',
        'path',
        'extension',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function destination()
    {
        return $this->morphTo();
    }

    /**
     * Get the basename of the uploaded file.
     *
     * @return string
     */
    public function getBasenameAttribute()
    {
        return "{$this->hashed_name}.{$this->extension}";
    }

    /**
     * @return string
     */
    public function getFilePathAttribute()
    {
        return $this->path.DIRECTORY_SEPARATOR.$this->basename;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hashed_name';
    }
}
