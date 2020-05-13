<?php
//支付宝支付
class Alipay {
    public function __construct()
    {
    }

    public function pay() {
        echo "pay bill by alipay";
    }
}

//微信支付
class Wechatpay {
    public function __construct()
    {
    }

    public function pay() {
        echo "pay bill by wechatpay";
    }
}

//银联支付
class Unionpay {
    public function __construct()
    {
    }

    public function pay() {
        echo 'pay bill by unionpay';
    }
}

//支付账单
class PayBill {
    private $payMethod;

    public function __construct()
    {
        $this->payMethod = new Alipay();
    }

    public function payMyBill() {
        $this->payMethod->pay();
    }
}

$pb = new PayBill();
$pb->payMyBill();

/*
 * 通过上面的代码我们知道,当我们创建一个class PayBill 的实例的时候, PayBill的构造函数里面有{ $this->payMethod= new Alipay (); }, 也就是实例化了一个class Alipay . 这个时候依赖就产生了, 这里可以理解为当我想用支付宝支付的时候, 那我首先要获取到一个支付宝的实例,或者理解为获取支付宝的功能支持. 当用我们完 new 关键字的时候, 依赖其实已经解决了，因为我们获取了Alipay 的实例.

其实在我知道ioc概念之前，我的代码中大部分都是这种模式 ~ _ ~ . 这种有什么问题呢， 简单来说， 比如当我想用的不是支付宝而是微信的时候怎么办， 你能做的就是修改Payment 的构造函数的代码,实例化一个微信支付Wechatpay.

如果我们的程序不是很大的时候可能还感觉不出什么，但是当你的代码非常复杂，庞大的时候，如果我们的需求经常改变，那么修改代码就变的非常麻烦了。所以ioc 的思想就是不要在 class Payment 里面用new 的方式去实例化解决依赖， 而且转为由外部来负责，简单一点就是内部没有new 的这个步骤，通过依赖注入的方式同样的能获取到支付的实例.
 */