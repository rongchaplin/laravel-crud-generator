<?php

namespace T73Biz\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'T73Biz\CrudGenerator\CrudCommand',
            'T73Biz\CrudGenerator\CrudControllerCommand',
            'T73Biz\CrudGenerator\CrudModelCommand',
            'T73Biz\CrudGenerator\CrudMigrationCommand',
            'T73Biz\CrudGenerator\CrudViewCommand'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
