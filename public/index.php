<?php

//class ClosureTest
//{
//    public function getClosure($name)
//    {
//        // use将外部变量引入进来
//        return function($score) use ($name) {
//            return $name."------>".$score."\n";
//        };
//    }
//}
//
//$my_closure = new ClosureTest;
//
//
//$closure = $my_closure->getClosure("Jackson");  // 变量$name在实例化一个Closure的时候传进来
//echo $closure(80);  // 变量$score在Closure被调用的时候传进来
//die;


//支付类接口
interface Pay
{
    public function pay();
}


//支付宝支付
class Alipay implements Pay {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by alipay';
    }
}
//微信支付
class Wechatpay implements Pay  {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by wechatpay';
    }
}
//银联支付
class Unionpay implements Pay  {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by unionpay';
    }
}

//付款
class PayBill {

    private $payMethod;

    public function __construct( Pay $payMethod)
    {
        $this->payMethod= $payMethod;
    }

    public function  payMyBill()
    {
        $this->payMethod->pay();
    }
}


//容器类装实例或提供实例的回调函数
class Container
{

    //用于装提供实例的回调函数，真正的容易还会装实例等其他内容
    //从而实现单例等高级功能
    protected $bindings = [];

    //绑定接口和生成相应实例的回调函数
    public function bind($abstract, $concrete = null, $shared = false)
    {
        //如果提供的参数不是回调函数，则产生默认的回调函数
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
            //print_r($concrete);die;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
        //print_r($this->bindings);die;
    }

    //默认生成实例的回调函数
    protected function getClosure($abstract, $concrete)
    {
        return function ($c) use ($abstract, $concrete) {
            //print_r($c);die;
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $c->$method($concrete);
        };
    }

    public function make($abstract)
    {
        echo '进入make----------------------------------------'.PHP_EOL;
        $concrete = $this->getConcrete($abstract);
        //print_r($concrete);die;
        //print_r($this->isBuildable($concrete, $abstract));die;
        if ($this->isBuildable($concrete, $abstract)) {
            print_r('执行build前 concrete ------'.print_r($concrete,true).PHP_EOL);
            $object = $this->build($concrete);
            print_r('执行build后 object ------'.print_r($object,true).PHP_EOL);
            //print_r($object);die;
        } else {
            echo 44444;
            $object = $this->make($concrete);
        }

        return $object;
    }

    protected function isBuildable($concrete, $abstract)
    {
        //print_r($concrete === $abstract);die;
        //print_r($concrete instanceof Closure);die;
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    //获取绑定的回调函数
    protected function getConcrete($abstract)
    {
        //print_r($abstract);die;
        print_r("进入闭包getConcreate---------".$abstract.PHP_EOL);
        if (!isset($this->bindings[$abstract])) {
            return $abstract;
        }
        //print_r($this->bindings[$abstract]);die;
        return $this->bindings[$abstract]['concrete'];
    }

    //实例化对象
    public function build($concrete)
    {
        print_r('进入build------'.PHP_EOL);
        //print_r($concrete instanceof Closure);
        if ($concrete instanceof Closure) {
            echo '进行build中闭包方法了----------------------------------------'.PHP_EOL;

            print_r('闭包前参数 this ---------'.print_r($this, true).PHP_EOL);
            //print_r('闭包后结果 ---------'.print_r($concrete($this),true).PHP_EOL);
            //print_r($concrete($this));die;

            return $concrete($this);
        }

        print_r('跳过闭包------'.PHP_EOL);

        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            echo $message = "Target [$concrete] is not instantiable";
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    //解决通过反射机制实例化对象时的依赖
    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = NULL;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }

        return (array)$dependencies;
    }

    protected function resolveClass(ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }

}

$app = new Container;
$app->bind("Pay", "Alipay");//Pay 为接口， Alipay 是 class Alipay
$app->bind("tryToPayMyBill", "PayBill"); //tryToPayMyBill可以当做是Class PayBill 的服务别名

//通过字符解析，或得到了Class PayBill 的实例
$paybill = $app->make("tryToPayMyBill");

//因为之前已经把Pay 接口绑定为了 Alipay，所以调用pay 方法的话会显示 'pay bill by alipay '
$paybill->payMyBill();



die;

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
