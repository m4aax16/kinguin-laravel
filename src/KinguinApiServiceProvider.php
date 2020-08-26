<?php

namespace m4aax16\kinguin;

use Illuminate\Support\ServiceProvider;

class KinguinApiServiceProvider extends ServiceProvider
{

    public function boot()
    {
       // dd("It works");
    }

    public function register()
    {
        $this->app->singleton(Kinguin::class, function(){
            return new Kinguin();    
        });
    }
}
