<?php

/**
 *  V1 版本
 */

// GET 请求
Solve\Route::get('/users', 'Users@select', 'v1');    //查询所有用户数据
Solve\Route::get('/users/{id}', 'Users@select', 'v1');    //查询指定 ID 的用户数据
Solve\Route::get('/users/{id}/shop', 'Users@select', 'v1'); //查询指定 ID 的用户，所有购买的商品
Solve\Route::get('/users/{id}/shop/{id}', 'Users@select', 'v1');//查询指定 ID 的用户，购买的指定 ID 的商品


Solve\Route::get('/users?limit={limit}', 'Users@select', 'v1');    //指定返回记录的数量
Solve\Route::get('/users?offset={offset}', 'Users@select', 'v1');    //指定返回记录的开始位置
Solve\Route::get('/users?page={page}&per_page={per_page}', 'Users@select', 'v1');    //指定第几页，以及每页的记录数
Solve\Route::get('/users?sortby={sortby}&order={order}', 'Users@select', 'v1');    //指定返回结果按照哪个属性排序，以及排序顺序。

// POST 请求
Solve\Route::post('/users', 'Users@insert', 'v1');    //新增用户数据

// PUT 请求
Solve\Route::put('/users', 'Users@update', 'v1');    //更新用户数据

// DELETE 请求
Solve\Route::delete('/users/{id}', 'Users@delete', 'v1');    //删除指定 ID 的用户数据