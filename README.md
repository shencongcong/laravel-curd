<h1 align="center"> laravelCurd </h1>

<p align="center"> laravelCurd </p>


## Installing

```shell
$ composer require shencongcong/laravelCurd ~1.0
```

## 配置

### Laravel 应用

1. 在 `config/app.php` 注册 ServiceProvider

```php
'providers' => [
    // ...
     Shencongcong\LaravelCurd\LaravelCurdServiceProvider::class,
],
'aliases' => [
    // ...
     'Curd'=> Shencongcong\LaravelCurd\Facade::class,
],
```

## Usage

```php
 增加一条数据 
 use Event
use App\Model\Test;

# 增加(add)
 //增加逻辑前面如需要处理业务加上这段代码(没有业务处理则省略)
 Event::listen("curd:beforeAdd", [$m,$data]);
 
 // Test 是Model
 \LaravelCurd::make(Test::class)->add();
 
 // 增加逻辑后面如需要处理业务加上这段代码(没有业务处理则省略)
 Event::listen("curd:afterAdd", [$m,$data]);
   
  // update、list、detail、delete、restore 等event事情看各自的方法
 #  修改(update)
 \LaravelCurd::make(Test::class)->update();
 
  #  列表(list)
  \LaravelCurd::make(Test::class)->list($pageSize,$withTrashed);
  $pageSize 默认是0 不分页 2 表示每页展示2条
  $withTrashed 默认是true, 表示软删除的不展示, false 表示软删除的也展示出来
  
  # 详情(detail)
    \LaravelCurd::make(Test::class)->detail();
  
  # 删除(delete)
    \LaravelCurd::make(Test::class)->delete($hasForce);
    $hasForce 默认是false 表示软删除 true 表示硬删除
    
  # 软删除恢复(restore)
  \LaravelCurd::make(Test::class)->restore();
  
```



## License

MIT