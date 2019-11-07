<?php
namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $builder;
    protected $filters;

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * @param $builder
     * @return mixed
     */
    function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    /**
     * @return array|ø
     */
    public function getFilters()
    {
        $filtersArray = $this->filters;

        $result =  array_filter($this->request->only(is_array($filtersArray) ? $filtersArray : func_get_args()));

        return $result ;
    }

}