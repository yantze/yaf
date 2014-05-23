<?php
class twig_Adapter implements Yaf_View_Interface {
   /**
   * assigned vars
   * <a href="http://my.oschina.net/var" class="referer" target="_blank">@var</a>  array
   */
   protected $_assigned = array();

   /**
   * twig environment
   * <a href="http://my.oschina.net/var" class="referer" target="_blank">@var</a>  Twig_Environment
   */
   protected $_twig;

   /**
   * <a href="http://my.oschina.net/var" class="referer" target="_blank">@var</a>  Twig_Loader_Filesystem
   */
   protected $_loader;

   /**
   * class constructor
   *
   * @param string $templatePath
   * @param array $envOptions options to set on the environment
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function __construct($templatePath=null, $envOptions=array()) {
      $this->_loader = new Twig_Loader_Filesystem($templatePath);
      $envOptions +=  array('debug' => true,);//for debug
      $this->_twig = new Twig_Environment($this->_loader, $envOptions);
      //for debug
      $this->_twig->addExtension(new Twig_Extension_Debug());
   }

   public function addFunction($name, Twig_FunctionInterface $function) {
      $this->_twig->addFunction($name, $function);
   }

   public function addGlobal($name, $value) {
      $this->_twig->addGlobal($name, $value);
   }

   /**
   * Set the template loader
   *
   * @param Twig_LoaderInterface $loader
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function setLoader(Twig_LoaderInterface $loader) {
      $this->_twig->setLoader($loader);
   }

   /**
   * Get the template loader
   *
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  Twig_LoaderInterface
   */
   public function getLoader() {
      return $this->_loader;
   }

   /**
   * Get the twig environment
   * 
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  Twig_Environment
   */
   public function getEngine() {
      return $this->_twig;
   }

   /**
   * Set the path to the templates
   *
   * @param string $path The directory to set as the path.
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function setScriptPath($paths) {
      $this->_loader->addPath($paths);
   }

   /**
   * add the path to the templates
   *
   * @param string $path The directory to set as the path.
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function addScriptPath($path) {
      $this->_loader->addPath($path);
   }

   /**
   * Retrieve the current template directory
   *
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  string
   */
   public function getScriptPath() {
      return $this->_loader->getPaths();
   }

   /**
   * No basepath support on twig, therefore alias for "setScriptPath()"
   *
   * <a href="http://my.oschina.net/u/244147" class="referer" target="_blank">@see</a>  setScriptPath()
   * @param string $path
   * @param string $prefix Unused
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function setBasePath($path, $prefix = 'Zend_View') {
      return $this->setScriptPath($path);
   }

   /**
   * No basepath support on twig, therefore alias for "setScriptPath()"
   *
   * <a href="http://my.oschina.net/u/244147" class="referer" target="_blank">@see</a>  setScriptPath()
   * @param string $path
   * @param string $prefix Unused
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function addBasePath($path, $prefix = 'Zend_View') {
      return $this->setScriptPath($path);
   }

   /**
   * Assign a variable to the template
   *
   * @param string $key The variable name.
   * @param mixed $val The variable value.
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function __set($key, $val) {
      $this->assign($key, $val);
   }

   /**
   * Allows testing with empty() and isset() to work
   *
   * @param string $key
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  boolean
   */
   public function __isset($key) {
      return isset($this->_assigned[$key]);
   }

   /**
   * Allows unset() on object properties to work
   *
   * @param string $key
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function __unset($key) {
      unset($this->_assigned[$key]);
   }

   /**
   * Assign variables to the template
   *
   * Allows setting a specific key to the specified value, OR passing
   * an array of key => value pairs to set en masse.
   *
   * <a href="http://my.oschina.net/u/244147" class="referer" target="_blank">@see</a>  __set()
   * @param string|array $spec The assignment strategy to use (key or
   * array of key => value pairs)
   * @param mixed $value (Optional) If assigning a named variable,
   * use this as the value.
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function assign($spec, $value = null) {
      if (is_array($spec)) {
         $this->_assigned = array_merge($this->_assigned, $spec);
      }

      $this->_assigned[$spec] = $value;
   }

   /**
   * Clear all assigned variables
   *
   * Clears all variables assigned to Zend_View either via
   * {<a href="http://my.oschina.net/link1212" class="referer" target="_blank">@link</a>  assign()} or property overloading
   * ({<a href="http://my.oschina.net/link1212" class="referer" target="_blank">@link</a>  __get()}/{<a href="http://my.oschina.net/link1212" class="referer" target="_blank">@link</a>  __set()}).
   *
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  void
   */
   public function clearVars() {
      $this->_assigned = array();
   }

   /**
   * Processes a template and returns the output.
   *
   * @param string $name The template to process.
   * <a href="http://my.oschina.net/u/556800" class="referer" target="_blank">@return</a>  string The output.
   */
   public function render($name, $valor = NULL) {
      $template = $this->_twig->loadTemplate($name);
      return $template->render($this->_assigned);
   }

   public function display($nombre, $valor = NULL) {
      $template = $this->_twig->loadTemplate($name);
      echo $template->render($this->_assigned);
   }

}
