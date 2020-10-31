<?php

namespace Toetet\FileInput;

use Illuminate\Support\ServiceProvider;

class FileInputServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fileinput.php' => config_path('fileinput.php'),
        ]);
    }
    
    public function register()
    {

    }
    
}