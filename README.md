# YjcPHP
A simple framework for study.  
一个简单的框架，支持传统php-fpm+nginx模式，也支持swoole http server模式。

## 示例

进入`web`目录，执行：
```  bash
php -S 0.0.0.0:9050
```

访问 :
```
http://127.0.0.1:9050/Index/index
http://127.0.0.1:9050/Index/index?output=xml
```

返回数据示例 :
```
{
    "code": 0,
    "msg": "成功",
    "data": {
        "name": "yjcphp",
        "ver": "0.1"
    }
}
```

## 输出数据格式
通过在url里增加类似`?output=json`选项，以返回不同的数据。支持的格式：

- template  支持模板
- json
- xml
- pure 用于直接输出图片
- msgpack 需要安装该扩展

## swoole server

需要先安装`swoole`扩展。仅作为应用服务器的时候，无需安装`nginx`和启动`php-fpm`。

进入`server`目录，执行：
``` bash
 php http_server.php
```

访问 :
```
http://127.0.0.1:9051/Index/index
http://127.0.0.1:9051/Index/index?output=xml
```

注意代码里不能用`exit`。使用`return`代替。
