<?php

namespace zfhassaan\easypaisa\Facade;

use Illuminate\Support\Facades\Facade;

class EasypaisaFacade extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'easypaisa';
    }
}