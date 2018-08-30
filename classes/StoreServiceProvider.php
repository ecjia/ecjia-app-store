<?php

namespace Ecjia\App\Store;

use Royalcms\Component\App\AppParentServiceProvider;

class StoreServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-store', null, dirname(__DIR__));
    }
    
    public function register()
    {
        
    }
    
    
    
}