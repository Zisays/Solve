<?php

namespace Api\v1;

use Solve\Route;

class Users
{
    public function select($data, $filter): void
    {
        Route::success([], '查询成功！');
    }

    public function insert($data, $filter): void
    {
        Route::success([], '新增成功！');
    }

    public function update($data, $filter): void
    {
        Route::success([], '更新成功！');
    }

    public function delete($data, $filter): void
    {
        Route::success([], '删除成功！');
    }
}