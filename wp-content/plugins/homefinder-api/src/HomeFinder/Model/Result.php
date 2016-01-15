<?php namespace HomeFinder\Model;

use Voodoo\Paginator;

class Result
{
    public $total;
    public $per_page;
    /* @var $paginator Paginator */
    public $paginator;
    public $items;

    /**
     * @param $total
     * @param $per_page
     * @param $items
     * @return Result
     */
    public static function withTotalAndPerPageAndCurrentItems($total, $per_page, $items)
    {
        $instance = new Result();

        $instance->items = $items;
        $instance->total = $total;
        $instance->per_page = $per_page;

        return $instance;
    }


}