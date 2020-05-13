<?php

interface Pay {
    public function pay();
}

//支付宝支付
class Alipay implements Pay {
    public function __construct()
    {
    }

    public function pay() {
        echo "pay bill by alipay";
    }
}

//微信支付
class Wechatpay implements Pay {
    public function __construct()
    {
    }

    public function pay() {
        echo "pay bill by wechatpay";
    }
}

//银联支付
class Unionpay implements Pay {
    public function __construct()
    {
    }

    public function pay() {
        echo 'pay bill by unionpay';
    }
}

//付款
class PayBill {
    private $payMethod;

    public function __construct(Pay $payMethod)
    {
        $this->payMethod = $payMethod;
    }

    public function payMyBill() {
        $this->payMethod->pay();
    }
}

//生成依赖
$payMethod = new Alipay();
//注入依赖
$pb = new PayBill($payMethod);
$pb->payMyBill();

/**
 * 上面的代码中，跟之前的比较的话，我们加入一个Pay 接口， 然后所有的支付方式都继承了这个接口并且实现了pay 这个功能. 可能大家会问为什么要用接口，这个我们稍后会讲到.

当我们实例化PayBill的之前， 我们首先是实例化了一个Alipay，这个步骤就是生成了依赖了，然后我们需要把这个依赖注入到PayBill 的实例当中，通过代码我们可以看到 { $pb = new PayBill( payMethod ); }, 我们是通过了构造函数把这个依赖注入了PayBill 里面. 这样一来 $pb 这个PayBill 的实例就有了支付宝支付的能力了.

把class Alipay 的实例通过constructor注入的方式去实例化一个 class PayBill. 在这里我们的注入是手动注入, 不是自动的. 而Laravel 框架实现则是自动注入.
 */