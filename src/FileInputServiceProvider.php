<?php

namespace Toetet\FileInput;

use Illuminate\Support\ServiceProvider;

class FileInputServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fileinput.php' => config_path('fileinput.php'),
        ]);

        $this->publishes([
                __DIR__.'/../database/migrations/create_files_tables.php.stub' => $this->getMigrationFileName($filesystem),
            ], 'migrations');
    }
    
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fileinput.php',
            'fileinput'
        );
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_files_tables.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_files_tables.php")
            ->first();
    }
}