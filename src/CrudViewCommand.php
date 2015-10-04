<?php

namespace T73Biz\CrudGenerator;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudViewCommand extends Command
{

    /**
     * The model crud name to be used for generating views
     *
     * @var string
     */
    protected $crudName;

    /**
     * The model crud name, capatalized, to be used for generating views
     *
     * @var string
     */
    protected $crudNameCap;

    /**
     * The model crud name, singularized, to be used for generating views
     *
     * @var string
     */
    protected $crudNameSingular;

    /**
     * The model crud name, singularized and capitalized, to be used for generating views
     *
     * @var string
     */
    protected $crudNameSingularCap;

    /**
     * The model crud name, pluralized, to be used for generating views
     *
     * @var string
     */
    protected $crudNamePlural;

    /**
     * The model crud name, pluralized an capitalized, to be used for generating views
     *
     * @var string
     */
    protected $crudNamePluralCap;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create view files for crud operation';

    /**
     * Fields argument
     * @var string
     */
    protected $fields;

    /**
     * Form fields extracted from field arguments
     * @var array
     */
    protected $formFields = array();

    /**
     * Form fields HTML strings for replacing into the stubs
     * @var string
     */
    protected $formFieldsHtml;

    /**
     * The view to extend from for the blade views
     * @var string
     */
    protected $layout;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:view';

    /**
     * The directory path for the views
     * @var string
     */
    protected $path;

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
        $this->setCrudnames();
        $this->fields = $this->argument('fields');
        $this->layout = $this->option('layout');
        $this->setFields();
        $this->setPath();
        $this->setFormFieldsHtml();
        $this->buildIndex();
        $this->buildCreate();
        $this->buildEdit();
        $this->buildShow();
        $this->info('View created successfully.');

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
            ['layout', '-l', InputOption::VALUE_OPTIONAL, 'Which layout file do you want the views to extend?', 'master'],
        ];
    }

    protected function buildCreate()
    {
        $replacements = array(
            '%%formFieldsHtml%%' => $this->formFieldsHtml
        );

        $this->populateStub($this->path . 'create.blade.php', __DIR__ . '/stubs/create.blade.stub', $replacements);
    }

    protected function buildEdit()
    {
        $replacements = array(
            '%%formFieldsHtml%%' => $this->formFieldsHtml
        );

        $this->populateStub($this->path . 'edit.blade.php', __DIR__ . '/stubs/edit.blade.stub', $replacements);
    }

    protected function buildIndex()
    {
        // Form fields and label
        $formHeadingHtml = '';
        $formBodyHtml = '';
        $i = 0;
        foreach ($this->formFields as $key => $value) {
            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            $formHeadingHtml .= '<th>' . $label . '</th>' . "\n\t\t\t\t\t";

            if ($i == 0) {
                $formBodyHtml .= '<td><a href="{{ url(\'/%%crudName%%\', $item->id) }}">{{ $item->' . $field . ' }}</a></td>';
            } else {
                $formBodyHtml .= '<td>{{ $item->' . $field . ' }}</td>';
            }
            $formBodyHtml .= "\n\t\t\t\t\t";

            $i++;
        }

        $replacements = array(
            '%%formHeadingHtml%%' => $formHeadingHtml,
            '%%formBodyHtml%%' => $formBodyHtml
        );

        $this->populateStub($this->path . 'index.blade.php', __DIR__ . '/stubs/index.blade.stub', $replacements);

    }

    protected function buildShow()
    {
        $formHeadingHtml = $formBodyHtmlForShowView = '';
        $i = 0;
        foreach ($this->formFields as $key => $value) {
            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            $formHeadingHtml .= '<th>' . $label . '</th>' . "\n\t\t\t\t\t";

            $formBodyHtmlForShowView .= '<td> {{ $%%crudNameSingular%%->' . $field . ' }} </td>';

            $i++;
        }
        
        $replacements = array(
            '%%formHeadingHtml%%' => $formHeadingHtml,
            '%%formBodyHtml%%' => $formBodyHtmlForShowView
        );

        $this->populateStub($this->path . 'show.blade.php', __DIR__ . '/stubs/show.blade.stub', $replacements);
    }

    protected function setCrudnames()
    {
        $this->crudName = strtolower($this->argument('name'));
        $this->crudNameCap = ucwords($this->crudName);
        $this->crudNameSingular = str_singular($this->crudName);
        $this->crudNameSingularCap = ucwords($this->crudNameSingular);
        $this->crudNamePlural = str_plural($this->crudName);
        $this->crudNamePluralCap = ucwords($this->crudNamePlural);

    }

    protected function populateStub($file, $stub, $replacements)
    {
        if(!file_exists($stub)) {
            throw new Exception("Stub file not found.", 1);
        }

        if (!copy($stub, $file)) {
            throw new Exception('failed to copy' . $file, 1);
        }

        if (!is_array($replacements)) {
            throw new Exception('Replacements must be an array.', 1);
        }

        $names = array(
            '%%crudName%%' => $this->crudName,
            '%%crudNameCap%%' => $this->crudNameCap,
            '%%crudNameSingular%%' => $this->crudNameSingular,
            '%%crudNameSingularCap%%' => $this->crudNameSingularCap,
            '%%crudNamePlural%%' => $this->crudNamePlural,
            '%%crudNamePluralCap%%' => $this->crudNamePluralCap
        );
        foreach ($names as $key => $value) {
            $replacements[$key] = $value;
        }

        $replacements['%%layout%%'] = $this->layout;

        foreach ($replacements as $key => $value) {
            file_put_contents($file, str_replace($key, $value, file_get_contents($file)));
        }

    }

    protected function setFields()
    {
        $x = 0;
        foreach (explode(',', $this->fields) as $item) {
            $array = explode(':', $item);
            $this->formFields[$x]['name'] = trim($array[0]);
            $this->formFields[$x]['type'] = trim($array[1]);
            $x++;
        }

    }

    protected function setFormFieldsHtml()
    {
        $fieldTypes = array(
            'string' => 'text',
            'text'  => 'textarea',
            'password' => 'password',
            'email' => 'email'
        );
        foreach ($this->formFields as $item) {
            $label = ucwords(strtolower(str_replace('_', ' ', $item['name'])));
            if(!array_key_exists($item['type'], $fieldTypes)) {
                $item['type'] = 'string';
            }
            $this->formFieldsHtml .=
                "
                <div>
                    {!! Form::label('" . $item['name'] . "', '" . $label . ": ') !!}
                    <div>
                        {!! Form::" . $fieldTypes[$item['type']] . "('" . $item['name'] . "', null ) !!}
                    </div>
                </div>\n";
        }
    }

    protected function setPath()
    {
        $this->path = base_path('resources/views/') . $this->crudName . '/';
        if (!is_dir($this->path)) {
            mkdir($this->path);
        }
    }

}
