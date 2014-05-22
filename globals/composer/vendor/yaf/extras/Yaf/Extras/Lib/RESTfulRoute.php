<?php

namespace Yaf\Extras\Lib;

// RESTful Route
// implementation by Compositing on RewriteRouter
class RESTfulRoute implements \Yaf\Route_Interface {
    private $route;
    private $method = '*'; // default any

    public function __construct($path, $options, $strict) {
        if ( is_string($options['method']) ) {
            $this->method = strtolower( $options['method'] );
        }

        // default base on rewrite route
        if ( !$strict ) { 
            $this->route = new \Yaf\Route\Rewrite($path, $options);
        }
        // strict style route syntax base on regex route
        // eg. '/user/<name>', match '/user/haha' ,not match '/user/haha/', not match 'user/haha'
        // type filter extended
        // eg. '/user/<id:int>', match '/user/123', not match '/user/haha'
        // some regex is also supported, don't use "()"
        // eg. '/user/<name>/?', match '/user/haha' and match '/user/haha/'
        else {
            $keys = array(''); // must start with 1, YAF sucks
            $path = preg_replace_callback('/<(\w+)(:int)?>/', function ($matches) use (&$keys) {
                array_push($keys, $matches[1]);

                if ( isset($matches[2]) ) { // integer type, TODO other types
                    return '(\d+)';
                } else {
                    return '([\w-%]+)'; // normal type
                }
            }, $path);

            unset($keys[0]);
            // regex must wrapped by #
            $this->route = new \Yaf\Route\Regex("#^$path$#", $options, $keys);
        }
    }

    public function route(/*\Yaf\Request_Abstract*/ $request) {
        // HTTP method adapt
        if ( $this->method !== '*' ) {
            $method = strtolower( $request->getMethod() );

            // fallback method
            if ( $method === 'post' && isset($_POST['_method'])) {
                $method = strtolower( $_POST['_method'] );
            }

            if ( $method !== $this->method ) {
                return false;
            }
        }

        // url adapt
        return $this->route->route($request);
    }

    
    public function assemble(array $mvc, array $query = NULL) {
        // interface method
    }
}