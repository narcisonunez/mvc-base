# Routes

Register all yours routes in the resources/routes/web.php

    $router->get("/posts", "PostsController@store");

or

    $router->get("/posts", ["controller" => "PostsController", "action" => "store"]);

## Available router methods

    $router->get("route", "Controller@action")
    $router->post("route", "Controller@action")
    $router->patch("route", "Controller@action")
    $router->delete("route", "Controller@action")

To be able to use patch and delete you will need to have a hidden input named \_method with value of PATCH or DELETE.

    <input type="hidden" name="_method" value="PATCH">

or use a helper function

    form_method("PATCH");

## Action Filters

    $route->get("/posts", "PostsController@store")->actionFilters([
        "before" => ["methodName", "methodName"], // To be executed before the action.
        "after" => ["methodName"] // To be execute after the action
    ])

Action filters receives the url parameters in the same order as specified in the route.

    Route: posts/{id}/edit

The methodName action filter will receive the id as the first argument. methodName(\$id)

## Middlewares

### Global Middlewares

They will be executed for every request and need to be registered in the \$globals array inside App/Middlewares/Middleware class.

### Routes middleware

They can be assigned using the router to an specific route using the middlewares method and need to be registered in the \$routes array inside App/Middlewares/Middleware class.

    $routes = [
        "alias" => MyOwnMiddleware::class
    ]

Using the router to assign a middleware looks like this

    $router->get("path", "Controller@action")->middlewares(["alias"])

### Helper functions

    . config("key", "default") : Get values from the app/config.php file

    . view("name", [DATA]) : Load a view passing all the data array as variables

    . view_404() : Load the 404 view

    . env_value("key", "default") : Get values from .env file

    . redirect("path") : Redirect to an specific path

    . dd($var, $var2, ...) : Dump and die.

    . form_method("PATCH") : echo out a hidden input with the method

    . form_csrf() : echo out a hidden input with a secure csrf token

## Server Configuration

### Apache .htaccess configS

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]

### Trouble Shooting

If it's not working, make sure that mod_rewrite is installed on Apache.

### nginx config

    location / {
        if (!-f $request_filename){
            set $rule_0 1$rule_0;
        }
        if (!-d $request_filename){
            set $rule_0 2$rule_0;
        }
        if ($rule_0 = "21"){
            rewrite ^/(.*)$ /index.php?\$1 last;
        }
    }
