<?php

namespace Solve\Package\Chord;

use PDO;
use PDOStatement;

class DataBase
{
    protected PDO $pdo;
    protected PDOStatement $pdoStmt;
    protected string $sql = '';
    protected array $whereArray = [];
    protected array $updateArray = [];

    /**
     * 功能：启动一个事务
     * 说明：关闭自动提交模式。自动提交模式被关闭的同时，通过 PDO 对象实例对数据库做出的更改直到调用 commit() 结束事务才被提交。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     */
    public function begin(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * 功能：检查是否在一个事务内
     * 说明：检查驱动内的一个事务当前是否处于激活。此方法仅对支持事务的数据库驱动起作用。
     * 返回值：如果当前事务处于激活，则返回 true ，否则返回 false 。
     * @return bool
     */
    public function inTrans(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * 功能：提交一个事务
     * 说明：提交一个事务，数据库连接返回到自动提交模式直到下次调用 begin() 开始一个新的事务为止。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * 功能：回滚一个事务
     * 说明：回滚由 begin() 发起的当前事务。如果没有事务激活，将抛出一个 PDOException 异常。
     * 如果数据库被设置成自动提交模式，此函数（方法）在回滚事务之后将恢复自动提交模式。
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * 功能：执行 SQL 语句，以 PDOStatement 对象形式返回结果集
     * PDO::query() 在单次函数调用内执行 SQL 语句，以 PDOStatement 对象形式返回结果集（如果有数据的话）。
     * 如果反复调用同一个查询，用 PDO::prepare() 准备 PDOStatement 对象，并用 PDOStatement::execute() 执行语句，将具有更好的性能。
     * 如果没有完整获取结果集内的数据，就调用下一个 PDO::query()，将可能调用失败。
     * 应当在执行下一个 PDO::query() 前，先用 PDOStatement::closeCursor() 释放数据库PDOStatement 关联的资源。
     * @param string $statement
     * @return bool|array
     */
    public function query(string $statement): bool|array
    {
        try {
            $queryArray = [];
            $stm = $this->pdo->query($statement);
            foreach ($stm->fetchAll() as $k => $v) {
                foreach ($v as $key => $value) {
                    if (!is_numeric($key)) {
                        $queryArray[$k][$key] = $value;
                    }
                }
            }
            return $queryArray;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：执行一条SQL语句，并返回受影响的行数
     * 说明：
     * exec() 在一个单独的函数调用中执行一条 SQL 语句，返回受此语句影响的行数。
     * exec() 不会从一条 SELECT 语句中返回结果。
     * 对于在程序中只需要发出一次的 SELECT 语句，可以考虑使用 query()。
     * 对于需要发出多次的语句，可用 prepare() 来准备一个 PDOStatement 对象并用 PDOStatement::execute() 发出语句。
     * 返回值：返回受修改或删除SQL语句影响的行数。如果没有受影响的行，则返回 0。
     * @param string $sql
     * @return bool|int
     */
    public function exec(string $sql): bool|int
    {
        try {
            $this->pdo->exec($sql);
            return $this->rowCount();
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：获取 PDO 当前连接属性的值
     * 说明：此函数（方法）返回一个数据库连接的属性值。 取回 PDOStatement 属性
     * 注意有些数据库/驱动可能不支持所有的数据库连接属性。
     * 返回值：成功调用则返回请求的 PDO 属性值。不成功则返回 null。
     * @param int $attribute
     * @return mixed
     */
    public function getAttr(int $attribute): mixed
    {
        try {
            return $this->pdo->getAttribute($attribute);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：设置 PDO 当前连接属性的值
     * 说明：设置数据库句柄属性。下面列出了一些可用的通用属性；有些驱动可能使用另外的特定属性。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttr(int $attribute, mixed $value): bool
    {
        try {
            return $this->pdo->setAttribute($attribute, $value);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：返回当前可用的 PDO 驱动
     * 说明：此函数（方法）返回所有当前可用在 __construct() 的参数 DSN 中的 PDO 驱动。
     * 返回值：返回一个 包含可用 PDO 驱动名字的数组。如果没有可用的驱动，则返回一个空数组。
     * @return bool|array
     */
    public function getDrivers(): bool|array
    {
        try {
            return $this->pdo->getAvailableDrivers();
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：返回受上一个 SQL 语句影响的行数
     * @return int
     */
    public function rowCount(): int
    {
        return $this->pdoStmt->rowCount();
    }

    /**
     * 功能：返回结果集中的列数
     * @return int
     */
    public function columnCount(): int
    {
        return $this->pdoStmt->columnCount();
    }

    /**
     * 功能：返回最后插入行的ID或序列值
     * 说明：返回最后插入行的ID，或者是一个序列对象最后的值，取决于底层的驱动。
     * 返回值：
     * 如果没有为参数 name 指定序列名称，PDO::lastInsertId() 则返回一个表示最后插入数据库那一行的行ID的字符串。
     * 如果为参数 name 指定了序列名称，PDO::lastInsertId() 则返回一个表示从指定序列对象取回最后的值的字符串。
     * 如果当前 PDO 驱动不支持此功能，则 PDO::lastInsertId() 触发一个 IM001 SQLSTATE 。
     * @param string|null $name
     * @return string
     */
    public function lastInsertId(string $name = null): string
    {
        if (!empty($name)) {
            return $this->pdo->lastInsertId($name);
        } else {
            return $this->pdo->lastInsertId();
        }
    }

    /**
     * 功能：打印一条 SQL 预处理命令
     */
    public function sql(): void
    {
        echo '<pre>';
        $this->pdoStmt->debugDumpParams();
        echo '</pre>';
    }

    /**
     * 功能：准备要执行的语句，并返回语句对象
     * 说明：为 PDOStatement::execute() 方法准备待执行的 SQL 语句。
     * @param string $statement 必须是对目标数据库服务器有效的 SQL 语句模板
     * @param array $driver_options 数组包含一个或多个 key=>value 键值对，为返回的 PDOStatement 对象设置属性。
     * 常见用法是：设置 PDO::ATTR_CURSOR 为 PDO::CURSOR_SCROLL，将得到可滚动的光标。某些驱动有驱动级的选项，在 prepare 时就设置。
     */
    public function prepare(string $statement, array $driver_options = []): bool|PDOStatement
    {
        try {
            return $this->pdo->prepare($statement, $driver_options);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：为 SQL 查询里的字符串添加引号
     * @param string $string
     * @param int $parameter_type
     * @return bool|string
     */
    public function quote(string $string, int $parameter_type = PDO::PARAM_STR): bool|string
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * 功能：输出错误信息页面
     * @param $e
     * @return void
     */
    protected function error($e): void
    {
        include 'solve/page/try.php';
    }
}