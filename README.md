[![Build Status](https://travis-ci.org/michaelesmith/ObjectCache.png?branch=master)](https://travis-ci.org/michaelesmith/ObjectCache)

README
======

What is it?
-------------------

An object cache that will seamlessly cache all method calls to an object. Basically it will mimic an object caching method calls made to it. This is probably most useful in dependency injection. One caveat, a method called with the same parameters is expected to always return the same value.

Installation
------------

### Use Composer (*recommended*)

The recommended way to install msDateTime is through composer.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Just create a `composer.json` file for your project:

``` json
{
    "require": {
        "michaelesmith/object-cache": "*"
    }
}
```

For more info on composer see https://github.com/composer/composer

Examples
--------

###Basic

    $object = ...;
    $cache = new \Doctrine\Common\Cache\ArrayCache();
    $oc = new ObjectCache($object, $cache);
    // use $oc as usual and methods will only be called once
