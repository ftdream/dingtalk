<?php
namespace DingTalk\Facades;

use Illuminate\Support\Facades\Facade;

class DingTalk extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ding-talk';
    }
}