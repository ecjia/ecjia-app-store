<?php

namespace Ecjia\App\Store;

use Royalcms\Component\App\AppServiceProvider;

class StoreServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-store');
    }
    
    public function register()
    {
        
    }
    
    
    
}