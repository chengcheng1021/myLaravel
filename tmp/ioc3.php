<?php

class B {

}

class A {
    public function __construct(B $args)
    {
    }

    public function dosomething() {
        echo 'Hello World';
    }
}

//建立 class A 的反射
$reflection = new ReflectionClass('A');

$b = new B();

//获取 class A 的实例
$instance = $reflection->newInstance([$b]);

$instance->dosomething();//输出'Hello World'

$constructor = $reflection->getConstructor();//获取class A的构造函数
$dependencies = $constructor->getParameters();//获取class A的依赖类

dump($constructor);
dump($dependencies);

/**
 * dump 得到的 $constructor 和 $dependencies 结果如下
 * //$constructor
 *
 * ReflectionMethod {#351
        +name: "__construct"
        +class: "A"
        parameters: array:1 []
        extra: array:3 []
        modifiers: "public"
 * }
 */

/**
 * $dependencies
 * array:1 [
         0 => ReflectionParameter {#352
          +name: "args"
           position: 0
           typeHint: "B"
       }
 ]
 */


/**
 * 通过上面的代码我们可以获取到 class A 的构造函数，还有构造函数依赖的类，这个地方我们依赖一个名字为 'args' 的量，而且通过TypeHint可以知道他是类型为 Class B; 反射机制可以让我去解析一个类，能过获取一个类里面的属性，方法 ，构造函数， 构造函数需要的参数。 有个了这个才能实现Laravel 的IOC 容器.
 */
