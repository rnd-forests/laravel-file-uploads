<?php

namespace App\Providers;

use App\Components\Upload\Handlers;
use App\Components\Upload\Listeners;
use App\Components\Upload\UploadManager;
use App\Components\Upload\UploadProcessed;
use App\Components\Upload\UploadProcessing;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class UploadServiceProvider extends ServiceProvider
{
    /**
     * Map between upload handlers and their associated listeners.
     *
     * @var array
     */
    protected $handlers = [
        Handlers\Avatar::class => Listeners\StoreAvatarForUser::class,
        Handlers\LessonAudio::class => Listeners\StoreLessonAudio::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('upload')->cycle(function ($event) {
            $this->callListeners($event);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerManager();
    }

    /**
     * Call custom event listeners for uploading events.
     *
     * @param  mixed $event
     * @return void
     */
    protected function callListeners($event)
    {
        $listeners = Arr::get($this->handlers, $this->getHandlerClass($event));

        if (is_null($listeners)) {
            return;
        }

        if (is_string($listeners)) {
            $listeners = (array) $listeners;
        }

        foreach ($listeners as $listener) {
            if (!class_exists($listener)) {
                continue;
            }

            $listener = $this->makeListener($listener, $event);
            if (!$listener instanceof Listeners\Listener) {
                continue;
            }

            $this->callListener($listener, $event);
        }
    }

    /**
     * Get handler class for current event.
     *
     * @param  mixed $event
     * @return string
     */
    protected function getHandlerClass($event)
    {
        return get_class(Arr::get($event->context, 'handler'));
    }

    /**
     * Create new listener instance.
     *
     * @param  string $listener
     * @param  mixed $event
     * @return \App\Components\Upload\Listeners\Listener
     */
    protected function makeListener($listener, $event)
    {
        return $this->app->makeWith($listener, ['event' => $event]);
    }

    /**
     * Call appropriate methods on listener.
     *
     * @param  \App\Components\Upload\Listeners\Listener $listener
     * @param  mixed $event
     * @return void
     */
    protected function callListener($listener, $event)
    {
        foreach ([
            UploadProcessing::class => 'preprocess',
            UploadProcessed::class => 'postprocess',
        ] as $class => $method) {
            if ($event instanceof $class && method_exists($listener, $method)) {
                $listener->$method();
            }
        }
    }

    /**
     * Register the upload manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('upload', function ($app) {
            return tap(new UploadManager($app), function ($manager) {
                $this->registerHandlers($manager);
            });
        });
    }

    /**
     * Register the upload handlers.
     *
     * @param  \App\Components\Upload\UploadManager $manager
     * @return void
     */
    protected function registerHandlers($manager)
    {
        foreach (array_keys($this->handlers) as $handler) {
            $this->app->singleton($handler, function ($app) use ($handler) {
                return new $handler($app);
            });
        }

        foreach ([
            'avatar',
            'lesson-audio',
        ] as $key) {
            $this->{'add'.Str::studly($key).'Handler'}($manager, $key);
        }
    }

    /**
     * @param \App\Components\Upload\UploadManager $manager
     * @param string $key
     */
    protected function addAvatarHandler($manager, $key)
    {
        $manager->extend($key, function () {
            return $this->app->make(Handlers\Avatar::class);
        });
    }

    /**
     * @param \App\Components\Upload\UploadManager $manager
     * @param string $key
     */
    public function addLessonAudioHandler($manager, $key)
    {
        $manager->extend($key, function () {
            return $this->app->make(Handlers\LessonAudio::class);
        });
    }
}
