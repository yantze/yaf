<?php

namespace Yaf\Extras\Lib;

// Flash message
class Flash implements \ArrayAccess, \IteratorAggregate, \Countable {
    const SESSION_KEY = 'yaf.flash';
    private static $instance; // singleton

    private $flashes;
    private $session;
    public function __construct() {
        self::$instance = $this;

        // use yaf session
        $session = $this->session = \Yaf\Session::getInstance();

        // retrieve flashes from prev request
        if ( $session->has($this::SESSION_KEY) ) {
            // var_dump($session);
            $this->flashes = $session->get($this::SESSION_KEY);
            $session->del($this::SESSION_KEY);
        } else {
            $this->flashes = array();
        }

        $session->set($this::SESSION_KEY, array());
    }
    public static function getInstance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // flash in current request
    public function now($data) {
        array_push($this->flashes, $data);
    }

    // flash in next request
    public function next($data) {
        $nextFlashes = $this->session->get($this::SESSION_KEY);
        array_push($nextFlashes, $data);
        $this->session->set($this::SESSION_KEY, $nextFlashes);
    }

    // retain current flash to next
    // public static function keep() {
    //     // TODO bug keep() before now()
    //     for (self::$currentFlashes as $data) {
    //         array_push(self::$nextFlashes, $data);
    //     }
    // }


    // =======================
    // implements

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset)
    {
        return isset($this->flashes[$offset]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset)
    {
        return isset($this->flashes[$offset]) ? $this->flashes[$offset] : null;
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value)
    {
        $this->flashes[$offset] = $value;
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset)
    {
        unset($this->flashes[$offset]);
    }

    /**
     * Iterator Aggregate: Get Iterator
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->flashes);
    }

    /**
     * Countable: Count
     */
    public function count()
    {
        return count($this->flashes());
    }
}
