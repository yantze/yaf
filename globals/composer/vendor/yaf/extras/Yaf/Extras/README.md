# Yaf Extras


## Installation

Install via Composer

```sh
> composer require "yaf/extras:*"
```


----

### Class: RESTfulRouter

**RESTful** router, provide a quick way to register **RewriteRoute** with **HTTP method** adaptation(RESTful).


##### $router = new RESTfulRouter

Create a router.


##### $router->on($method, $url, $controller, $action)

- $method **String**: HTTP method name, support wildcard `*` to match all methods.
- $url **String**
- $controller **String**: controller class name
- $action **String**: method name of controller

**Tips**. you can register multi methods in single `on()`, use space to separate methods, eg. `$router->on('get post', 'user/:id', 'user', 'show')`


##### Example
```php
class Bootstrap extends \Yaf\Bootstrap_Abstract {
    // default YAF style route registration
    function _initRoute(\Yaf\Dispatcher $dispatcher) {
        $router = $dispatcher->getRouter();
        
        // default yaf rewrite route
        $router->addRoute('dog', new \Yaf\Route\Rewrite('dogs', array('controller' => 'dog', 'action' => 'index')));
        $router->addRoute('dog_show', new \Yaf\Route\Rewrite('dogs/:id', array('controller' => 'dog', 'action' => 'show')));
        $router->addRoute('dog_create', new \Yaf\Route\Rewrite('dogs/create', array('controller' => 'dog', 'action' => 'create')));
        $router->addRoute('dog_destroy', new \Yaf\Route\Rewrite('dogs/:id/delete', array('controller' => 'dog', 'action' => 'destroy')));
    }

    
    // RESTful style
    function _initRESTfulRoute() {
        $router = new \Yaf\Extras\RESTfulRouter;
        $router->on('post', 'cat', 'cat', 'create');
        $router->on('get', 'cat/:id', 'cat', 'show');
        $router->on('delete', 'cat/:id', 'cat', 'destroy');
        
        $router->on('put patch', 'cat/:id', 'cat', 'update');

        $router->on('*', 'pig/:id', 'pig', 'what');
    }
}
```

##### Strict Mode

`new RESTfulRouter(true)` to open strict mode.

In this mode, uri will matches **strictly** and new `<name>` style syntax was introduced.

eg.
`'/user/<name>'` will match `'/user/micheal'`, but not match `'/user/micheal/age'`, also not match `'/user/micheal/'`.


Type filter supported by `<name:filter>`.

Current filters

- int : pure number

eg.
`'/user/<id:int>'` will match `'/user/123`, but not match `'/user/micheal'`.


Because it base on `YAF\Route\Regex`, some regex can be used.
And currently you can't use `'()'`, it will cause error.

eg.
`'/blog/?'` will match `'/blog'` or `'/blog/'`.


----

### Class: AdaptiveView

Render with different way(renderer) according to view file's extension name.
Use default `Yaf Simple View` as fallback if no renderer matched.


##### $view = new AdaptiveView($path = '{yaf.application.directory}/views/')

- $path **String**: path of view files located, default is '{yaf.application.directory}/views/'

Create a view.


##### $view->on($extname, $renderder)

- $extname **String**: view file's extension name
- $renderer($file, $data) **Function**: called when extname matched, should `return` the rendered result.

Register a renderer with extname.


##### $view->*

Other common methods see [Yaf/View/Interface](http://www.php.net/manual/en/class.yaf-view-interface.php)


#### Example
`Bootstrap.php`
```php
class Bootstrap extends \Yaf\Bootstrap_Abstract {
    function _initView(\Yaf\Dispatcher $dispatcher) {
        $view = new \Yaf\Extras\AdaptiveView;
        $path = $view->getScriptPath();
    
        // plain text TEST
        $view->on('txt', function ($file, $data) use ($path) {
            return file_get_contents( $path .$file ) .' THIS IS JUST A TEST';
        });

        // twig
        $view->on('twig', function ($file, $data) use ($path) {
            $loader = new Twig_Loader_Filesystem($path);
            $twig = new Twig_Environment($loader);
            return $twig->loadTemplate($file)->render($data);
        });

        $dispatcher->disableView(); // disable auto-render
        $dispatcher->setView($view);
    }
}
```

Now you can use this view in `YourController.php`
```php
class TestController extends \Yaf\Controller_Abstract {
    public function testAction() {
        $view = $this->getView();
        $view->assign('content', 'Hello World'); 
        $view->display('text.txt');
        // $view->display('text.twig'); // render use twig
    }
}
```


----

### Class: ExtendedController

Common controller base on `\Yaf\Controller_Abstract` with many useful methods;


#### flash
Flash messaging

##### $this->flash($msg, $type = 'info')
Store message for next request

##### $this->flashNow($msg, $type = 'info')
For current request

##### $this->getFlash()

Retrieve message for current request



###### example
```php
class FooController extends ExtendedController {
    public function indexAction() {
        $this->flash('yep', 'success');
        $this->flashNow('nope', 'error');

        foreach ($this->getFlash() as $flash) {
            echo $flash['msg']; // 'nope' in current, 'yep' for next
            echo $flash['type'];
        }
    }
}
```

