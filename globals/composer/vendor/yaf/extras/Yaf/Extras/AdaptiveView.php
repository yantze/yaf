<?php

namespace Yaf\Extras;
# TODO
# enable autorender
# - auto replace extension in autorender, yaf will input `template.phtml`
# - default extension setting, like use `html.twig`
# * returned data from controller will not pass to render(), yaf sucks

// Register custom renderer according to file's extension name
// use default Yaf Simple View as fallback if no renderer matched
class AdaptiveView implements \Yaf\View_Interface {
    private $path;
    private $renderers = array();
    private $data = array();

    public function __construct($path = null) {
        if ( isset($path) ) {
            $this->path = $path;
        } else {
            $config = \Yaf\Application::app()->getConfig();
            $this->path = $config['application.directory'] .'/views/';
        }
    }

    public function render($file, $data = null) {
        if ( is_array($data) ) {
            $this->data = array_merge($this->data, $data);
        }

        // render according to extname
        $extname = strtolower( pathinfo($file, PATHINFO_EXTENSION) );

        if ( isset($this->renderers[$extname]) ) {
            return call_user_func($this->renderers[$extname], $file, $this->data);
        } else {
            return $this->renderDefault($file, $this->data);
        }
    }

    public function display($file, $data = null) {
        echo $this->render($file, $data);
    }

    public function assign($name, $value = null) {
        $this->data[$name] = $value;
    }

    public function getScriptPath() {
        return $this->path;
    }

    public function setScriptPath($path) {
        $this->path = $path;
    }


    // renderer registration
    // $renderder is a callable object
    // so it can be a function or a array(object, methodName)
    // see http://www.php.net/manual/en/function.is-callable.php
    public function on($extname, $renderer) {
        $extname = strtolower($extname);

        if ( is_callable($renderer) ) {
            $this->renderers[$extname] = $renderer;
        } else {
            throw new Exception('renderer is not callable');
        }
    }

    private function renderDefault($file, $data) {
        $view = new \Yaf\View\Simple($this->path);
        return $view->render($file, $data);
    }
}