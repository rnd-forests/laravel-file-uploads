<?php

namespace App\Components;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Fluent;
use IteratorAggregate;

class Grid extends Fluent implements IteratorAggregate
{
    /**
     * The base query of the model.
     *
     * @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * The paginator instance.
     *
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * Generates a URL to a controller action.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    public function action($name, $parameters = [], $absolute = true)
    {
        if ($controller = $this->get('controller')) {
            if ($controller instanceof Controller) {
                return $controller->action($name, $parameters, $absolute);
            }

            if (strpos($name, '@') == 0) {
                $name = $controller.'@'.ltrim($name, '@');
            }
        }

        return action($name, $parameters, $absolute);
    }

    /**
     * Sets the base query.
     *
     * @param  null|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return $this|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query = null)
    {
        if (is_null($query)) {
            return $this->query;
        }

        $this->query = $query;

        return $this;
    }

    /**
     * Paginates results returned from the query.
     *
     * @param integer $perPage
     * @param array $columns
     * @return $this
     */
    public function paginate($perPage = null, array $columns = ['*'])
    {
        $base = $this->query instanceof EloquentBuilder ? $this->query->toBase() : $this->query;
        $total = $base->getCountForPagination($columns);

        $this->query->forPage(
            $page = Paginator::resolveCurrentPage(),
            $perPage = $perPage ?: $this->get('perPage', config('pagination.per_page'))
        );

        $this->paginator = new LengthAwarePaginator([], $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return $this;
    }

    /**
     * @return \Generator
     */
    public function cursor()
    {
        return $this->query->cursor();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * @return \ArrayIterator|\Generator
     */
    public function getIterator()
    {
        if ($this->query->getEagerLoads()) {
            return $this->collection()->getIterator();
        }

        return $this->cursor();
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return $this|mixed
     */
    public function __call($method, $parameters)
    {
        if (!is_null($this->paginator) && method_exists($this->paginator, $method)) {
            return call_user_func_array([$this->paginator, $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }
}
