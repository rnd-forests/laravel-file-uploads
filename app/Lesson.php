<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Relations\HasOne currentAudio
 */
class Lesson extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'audio', 'version'];

    /**
     * Current audio of the lesson.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentAudio()
    {
        return $this->hasOne(Upload::class, 'hashed_name', 'audio');
    }

    /**
     * All audio files associated with the lesson.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function audioFiles()
    {
        return $this->morphMany(Upload::class, 'destination');
    }

    /**
     * Get the path to the current audio file of the lesson.
     *
     * @return string
     */
    public function audioPath()
    {
        $file = $this->currentAudio;
        $handler = app('upload')->handler('lesson-audio');

        return $file ? $handler->url($file) : null;
    }
}
