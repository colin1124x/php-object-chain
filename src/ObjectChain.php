<?php namespace Colin;

use ArrayAccess;
use Iterator;
use stdClass;

class ObjectChain implements ArrayAccess, Iterator
{
    private $resource;
    private $value_get;
    private $value_exists;
    private $value_unset;

    public function __construct($o = null)
    {
        $o = $o instanceof self ? $o->value() : $o;

        if (is_array($o) || $o instanceof ArrayAccess) {
            $value_exists = function($o, $ind){return isset($o[$ind]);};
            $value_get = function($o, $ind){return isset($o[$ind]) ? $o[$ind] : null;};
            $value_unset = function($o, $ind){unset($o[$ind]);};

        } elseif ($o instanceof stdClass) {
            $value_exists = function($o, $ind){return isset($o->{$ind});};
            $value_get = function($o, $ind){return isset($o->{$ind}) ?  $o->{$ind} : null;};
            $value_unset = function($o, $ind){unset($o->{$ind});};

        } else {
            $value_exists = function($o, $ind){return false;};
            $value_get = function($o, $ind){return $o;};
            $value_unset = function($o, $ind){};
        }

        $this->resource = $o;
        $this->value_exists = $value_exists;
        $this->value_get = $value_get;
        $this->value_unset = $value_unset;
    }

    public function __get($name)
    {
        return $this[$name];
    }

    public function value()
    {
        return $this->resource;
    }

    public function offsetGet($ind)
    {
        $v = call_user_func($this->value_get, $this->resource, $ind);

        return new self($v);
    }

    public function offsetSet($ind, $val)
    {
        throw new \BadMethodCallException('暫時做成唯獨物件');
    }

    public function offsetExists($ind)
    {
        return call_user_func($this->value_exists, $this->resource, $ind);
    }

    public function offsetUnset($ind)
    {
        call_user_func($this->value_unset, $this->resource, $ind);
    }

    public function current()
    {
        return $this[$this->key()];
    }

    public function next()
    {
        return next($this->resource);
    }

    public function rewind()
    {
        return reset($this->resource);
    }

    public function key()
    {
        return key($this->resource);
    }

    public function valid()
    {
        if ( ! next($this->resource)) {
            prev($this->resource);
            return false;
        }

        return true;
    }



    public function __toString()
    {
        return (string) $this->value();
    }
}
