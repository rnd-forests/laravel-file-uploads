<?php

namespace App\Components;

use Illuminate\Support\Arr;
use Illuminate\Session\Store;
use Illuminate\Support\Fluent;
use Illuminate\Database\Eloquent\Model;

class Form extends Fluent
{
    /**
     * Session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session = null;

    /**
     * Creates new form instance.
     *
     * @param  array  $attributes
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        $this->session = app(Store::class);
    }

    /**
     * Gets form action URL.
     *
     * @return string
     */
    public function url()
    {
        return $this->get('url');
    }

    /**
     * Gets form HTTP method.
     *
     * @return string
     */
    public function method()
    {
        return $this->get('method', 'POST');
    }

    /**
     * Sets or gets data from form.
     *
     * @param  array|null  $data
     * @return $this|mixed
     */
    public function data(array $data = null)
    {
        if (is_null($data)) {
            return $this->get('data', []);
        }

        $this->attributes['data'] = $data;

        return $this;
    }

    /**
     * Sets or gets model from form.
     *
     * @param  Model|null  $model
     * @return $this|Model|null
     */
    public function model(Model $model = null)
    {
        if (is_null($model)) {
            return $this->get('model');
        }

        $this->attributes['model'] = $model;

        return $this;
    }

    /**
     * Gets form field value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function value($key, $default = null)
    {
        $key = $this->transformKey($key);

        if ($this->session->hasOldInput($key)) {
            return $this->session->getOldInput($key);
        }

        if ($model = $this->model()) {
            return data_get($model, $key);
        }

        return Arr::get($this->data(), $key, $default);
    }

    /**
     * Transforms key from array to dot syntax.
     *
     * @param  string  $key
     * @return string
     */
    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }
}
