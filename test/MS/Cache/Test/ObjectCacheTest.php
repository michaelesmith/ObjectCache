<?php

/**
 * @version 0.2
 *
 * @author msmith
 */

namespace MS\Cache\Test;

use Doctrine\Common\Cache\ArrayCache;
use MS\Cache\ObjectCache;

class ObjectCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $object = $this->getMock('StdClass', array('method1', 'method2'));
        $object->expects($this->exactly(2))
            ->method('method1')
            ->with($this->callback(function($val){return in_array($val, array('val1', 'val2'));}))
            ->will($this->returnValueMap(array(
                array('val1', 'ret1'),
                array('val2', 'ret2'),
            )))
        ;
        $object->expects($this->once())
            ->method('method2')
            ->with($this->equalTo('val20'))
            ->will($this->returnValue('ret20'))
        ;

        $cache = new ArrayCache();

        $oc = new ObjectCache($object, $cache);

        $this->assertEquals('ret1', $oc->method1('val1'));
        $this->assertEquals('ret2', $oc->method1('val2'));
        $this->assertEquals('ret20', $oc->method2('val20'));
        //should not increase the number of calls to object
        $this->assertEquals('ret1', $oc->method1('val1'));
        $this->assertEquals('ret2', $oc->method1('val2'));
        $this->assertEquals('ret20', $oc->method2('val20'));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMethodNotFound()
    {
        $object = $this->getMock('StdClass', array('method1', 'method2'));

        $oc = new ObjectCache($object);

        $oc->method3();
    }

    public function testWithoutCache()
    {
        $object = $this->getMock('StdClass', array('method1', 'method2'));
        $object->expects($this->exactly(4))
            ->method('method1')
            ->with($this->callback(function($val){return in_array($val, array('val1', 'val2'));}))
            ->will($this->returnValueMap(array(
                array('val1', 'ret1'),
                array('val2', 'ret2'),
            )))
        ;
        $object->expects($this->exactly(2))
            ->method('method2')
            ->with($this->equalTo('val20'))
            ->will($this->returnValue('ret20'))
        ;

        $oc = new ObjectCache($object);

        $this->assertEquals('ret1', $oc->method1('val1'));
        $this->assertEquals('ret2', $oc->method1('val2'));
        $this->assertEquals('ret20', $oc->method2('val20'));
        //should increase the number of calls to object without use of cache object
        $this->assertEquals('ret1', $oc->method1('val1'));
        $this->assertEquals('ret2', $oc->method1('val2'));
        $this->assertEquals('ret20', $oc->method2('val20'));
    }

}
