<?php

namespace T73Biz\CrudGenerator;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crud Generator including controller, model, view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $name = ucwords(strtolower($this->argument('name')));
        $fields = $this->argument('fields');
        $layout = $this->option('layout');

        $fillable_array = explode(',', $fields);
        foreach ($fillable_array as $value) {
            $data[] = preg_replace("/(.*?):(.*)/", "$1", trim($value));
        }

        $comma_separeted_str = implode("', '", $data);
        $fillable = "['";
        $fillable .= $comma_separeted_str;
        $fillable .= "']";

        $this->call('crud:controller', ['name' => $name . 'Controller', '--crud-name' => $name]);
        $this->call('crud:model', ['name' => str_plural($name), '--fillable' => $fillable]);
        $this->call('crud:migration', ['name' => str_plural(strtolower($name)), '--schema' => $fields]);
        $this->call('crud:view', ['name' => $name, 'fields' => $fields, '--layout' => $layout]);

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $isAdded = File::append($routeFile, "\nRoute::resource('" . strtolower($name) . "', '" . $name . "Controller');");
            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Crud.'],
            ['fields', InputArgument::REQUIRED, 'The fields of the form.'],
        ];
    }

    /*
     * Get the console command options.
     *
     * @return array
     */

    protected function getOptions()
    {
        return [
            ['route', '-r', InputOption::VALUE_OPTIONAL, 'Do you want to add the crud route to routes.php file? yes/no', 'yes'],
            ['layout', '-l', InputOption::VALUE_OPTIONAL, 'Which layout file do you want the views to extend?', 'master'],
        ];
    }

}
