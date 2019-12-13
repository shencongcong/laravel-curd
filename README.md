<h1 align="center"> laravelCurd </h1>

<p align="center"> laravelCurd </p>


## Installing

```shell
$ composer require shencongcong/laravel-curd ~1.0
```

## 配置

### Laravel 应用

在 `config/app.php` 注册 ServiceProvider

```php
'providers' => [
    // ...
     Shencongcong\LaravelCurd\LaravelCurdServiceProvider::class,
],
'aliases' => [
    // ...
     'LaravelCurd'=> Shencongcong\LaravelCurd\Facade::class,
],
```

## 使用


```php

use Event
use App\Model\Test;

1. 增加(add)
// 增加数据处理如需要处理业务加上这段代码,默认将laravel的request请求中的数据传入(没有业务处理则省略)
 $arr = ['id'=>1,'name'=>'hlcc']; //处理好的数据
 Event::listen("curd:filterData", function($m, $data) use($arr){
        return $arr;
 });
 
 //增加逻辑前面如需要处理业务加上这段代码(没有业务处理则省略)
 Event::listen("curd:beforeAdd", function($m, $data){
        //todo 
 });
 
  // 增加逻辑后面如需要处理业务加上这段代码(没有业务处理则省略)
  Event::listen("curd:afterAdd", function($m,$data){
     //todo
  });
    
 // Test 是Model
 \LaravelCurd::make(Test::class)->add();
 

  // update、list、detail、delete、restore 等event事件从源码中查看
 2.  修改(update)
 \LaravelCurd::make(Test::class)->update();
 
 3.  列表(list)
  \LaravelCurd::make(Test::class)->list($pageSize,$withTrashed);
  $pageSize 默认是0 不分页 2 表示每页展示2条
  $withTrashed 默认是true, 表示软删除的不展示, false 表示软删除的也展示出来
  
 4.  详情(detail)
    \LaravelCurd::make(Test::class)->detail();
  
 5. 删除(delete)
    \LaravelCurd::make(Test::class)->delete($hasForce);
    $hasForce 默认是false 表示软删除 true 表示硬删除
    
 6. 软删除恢复(restore)
  \LaravelCurd::make(Test::class)->restore();
  
```


## License

MIT