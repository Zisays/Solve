# Solve PHP 框架

## 一、RESTful API 规范

Solve 框架中的 Api 接口采用 RESTful 架构设计

RESTful 架构可以充分的利用 HTTP 协议的各种功能，是 HTTP 协议的最佳实践

RESTful API 是一种软件架构风格、设计风格，可以让软件更加清晰，更简洁，更有层次，可维护性更好

### 1、域名

应该将 api 接口部署在二级域名下

```php
https://api.zisay.cn
```

### 2、版本号

应该将Api的版本号（ version 1 简写就是  v1）放入 URL中

```php
https://api.zisay.cn/v1/
```

### 3、资源

每次请求都是对资源的操作，而不是像以前那样要写多个方法

（ X ）错误的

```php
https://api.zisay.cn/getUser
https://api.zisay.cn/postUser
https://api.zisay.cn/putUser
https://api.zisay.cn/deleteUser
```

（ V ）正确的

```php
https://api.zisay.cn/v1/users
```

可以看出对资源正确的请求方式，只需要一种就可以了，然后我们我们可以通过以下 4 种请求方式，来对资源进行操作

#### （1）、查询请求（GET）

```php
https://api.zisay.cn/v1/users/1
```

#### （2）、新增请求（POST）

```php
https://api.zisay.cn/v1/users
```

```php
POST /index.php HTTP/1.1
Host: localhost
Content-Type:application/json;charset=utf-8

name=”zisay”&qq=”15593838”
```

#### （3）、更新请求（PUT）

```php
https://api.zisay.cn/v1/users
```

```php
PUT /index.php HTTP/1.1
Host: localhost
Content-Type:application/json;charset=utf-8

name=”zisay”&qq=”15593838”
```

#### （4）、删除请求（DELETE）

```php
https://api.zisay.cn/v1/users/1
```

### 4、过滤信息

```php
?limit=10：指定返回记录的数量
?offset=10：指定返回记录的开始位置。
?page=2&per_page=100：指定第几页，以及每页的记录数。
?sortby=name&order=asc：指定返回结果按照哪个属性排序，以及排序顺序。
?animal_type_id=1：指定筛选条件
```

## 二、如何使用？

### 1、配置路由

在 `config/api.php` 中配置

> 语法：Solve\Route::请求方式( URL地址 , 控制器@方法 , 版本号 )

```php
<?php
    
// GET 请求
Solve\Route::get('/users', 'Users@index', 'v1');    //查询所有用户数据
Solve\Route::get('/users/{id}', 'Users@index', 'v1');    //查询指定 ID 的用户数据
Solve\Route::get('/users/{id}/shop', 'Users@index', 'v1'); //查询指定 ID 的用户，所有购买的商品
Solve\Route::get('/users/{id}/shop/{id}', 'Users@index', 'v1');//查询指定 ID 的用户，购买的指定 ID 的商品


Solve\Route::get('/users?limit={limit}', 'Users@index', 'v1');    //指定返回记录的数量
Solve\Route::get('/users?offset={offset}', 'Users@index', 'v1');    //指定返回记录的开始位置
Solve\Route::get('/users?page={page}&per_page={per_page}', 'Users@index', 'v1');    //指定第几页，以及每页的记录数
Solve\Route::get('/users?sortby={sortby}&order={order}', 'Users@index', 'v1');    //指定返回结果按照哪个属性排序，以及排序顺序。

// POST 请求
Solve\Route::post('/users', 'Users@add', 'v1');    //新增用户数据

// PUT 请求
Solve\Route::put('/users', 'Users@edit', 'v1');    //更新用户数据

// DELETE 请求
Solve\Route::delete('/users/{id}', 'Users@delete', 'v1');    //删除指定 ID 的用户数据
```

### 2、编写 Api 接口文件

在  `Api/v1/`  目录下创建  `users.php`  文件，实现路由规则中定义的控制器与方法

```php
<?php

namespace Api\v1;

class User
{
    public function select($data, $filter): void
    {

    }

    public function insert($data): void
    {

    }

    public function update($data): void
    {

    }

    public function delete($data, $filter): void
    {

    }
}
```

