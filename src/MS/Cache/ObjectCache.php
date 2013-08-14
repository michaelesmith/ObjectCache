<?php

namespace MS\Cache;

use Doctrine\Common\Cache\Cache;

/**
 * An object cache that will seamlessly cache all method calls to an object
 *
 * @version 0.1
 *
 * @author msmith
 */
class ObjectCache
{
    protected $object;

    protected $cache;

    public function __construct($object, Cache $cache = null)
    {
        $this->object = $object;
        $this->cache = $cache;
    }

    public function __call($method, $arguments)
    {
        if(!method_exists($this->object, $method)){
            throw new \BadMethodCallException(sprintf('object of class %s has no method "%s"', get_class($this->object), $method));
        }

        if(!$this->cache){

            return call_user_func_array(array($this->object, $method), $arguments);
        }

        $key = md5($method . serialize($arguments));
        if($this->cache->contains($key)){

            return $this->cache->fetch($key);
        }else{
            $result = call_user_func_array(array($this->object, $method), $arguments);
            $this->cache->save($key, $result);

            return $result;
        }
    }
}
