# Laravel 5 CRUD Generator
Laravel CRUD Generator v. 1.1

### Requirements
    Laravel >=5.1
    PHP >= 5.5.9 

## Installation

1. Run 
    ```
    composer require t73biz/crud-generator
    ```
    
2. Add service provider into **/config/app.php** file.
    ```php
    'providers' => [
        ...
    
        T73Biz\CrudGenerator\CrudGeneratorServiceProvider::class,
    ],
    ```
    
    Add bellow lines for "laravelcollective/html" package if you've not done yet.

    ```php
    'providers' => [
        ...
    
        Collective\Html\HtmlServiceProvider::class,
    ],
    
    'aliases' => [
    
        ...
    
        'Form'		=> Collective\Html\FormFacade::class, 
    	'HTML'		=> Collective\Html\HtmlFacade::class,
    ],
    ```
3. Run **composer update**

Note: You should have configured database as well for this operation.

## Commands

#### Crud command:

```
php artisan crud:generate Person "name:string, email:string, phone:integer, message:text" --layout "main"
```

-----------
-----------


#### Others command (optional):

For controller generator: 

```
php artisan crud:controller PersonController --crud-name="Person"
```

For model generator: 

```
php artisan crud:model Person --fillable="['name', 'email', 'message']"
```

For migration generator: 

```
php artisan crud:migration Person --schema="name:string, email:string, phone:integer, message:text"
```

For view generator: 

```
php artisan crud:view Person "name:string, email:string, phone:integer, message:text" --layout "main"
```

By default, the generator will attempt to append the crud route to your *routes.php* file. If you don't want the route added, you can use the option ```--route=no```.

#### After creating all resources run migrate command *(and, if necessary, include the route for your crud as well)*.

```
php artisan migrate
```

If you chose not to add the crud route in automatically (see above), you will need to include the route manually.
```php
Route::resource('person', 'PersonController');
```

##Author

[Ronald Chaplin](http://t73.biz)

[Sohel Amin](http://www.sohelamin.com)

