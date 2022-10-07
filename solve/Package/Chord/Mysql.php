<?php

namespace Solve\Package\Chord;

class Mysql extends DataBase
{
    private string $action = '';
    private array $insertArray = [];
    private array $insertParameter = ['1' => '', '2' => '', '3' => ''];
    private string $updateParameter = '';

    public function __construct(string $dsn, string $username, string $password, array $driver_options = [])
    {
        try {
            $this->pdo = new \PDO($dsn, $username, $password, $driver_options);
            return $this->pdo;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    public function select(string $column = '*'): static
    {
        $this->action = 'select';
        $this->sql = ' select ' . $column;
        return $this;
    }

    public function from(string $table): static
    {
        $this->sql .= ' from ' . $table;
        return $this;
    }

    public function where(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        if ($arithmetic == 'in') {
            $this->sql .= ' where ' . $column . ' in ( ';
            $splicing = '';
            foreach ($value as $v) {
                $splicing .= '?,';
                $this->whereArray[count($this->whereArray) + 1] = $v;
            }
            $this->sql .= substr($splicing, 0, -1) . ')';
        } else {
            $this->whereAdd('where', $column, $arithmetic, $value);
        }
        return $this;
    }

    public function and(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        $this->whereAdd('and', $column, $arithmetic, $value);
        return $this;
    }

    public function or(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        $this->whereAdd('or', $column, $arithmetic, $value);
        return $this;
    }

    private function whereAdd(string $action, string $column, mixed $arithmetic, mixed $value): void
    {
        if (!empty($value)) {
            $this->value($action, $column, $arithmetic, $value);
        } else {
            $this->arithmetic($action, $column, $arithmetic);
        }
    }

    private function arithmetic(string $action, string $column, mixed $arithmetic): void
    {
        $this->sql .= ' ' . $action . ' ' . $column . '=? ';
        $this->whereArray[count($this->whereArray) + 1] = $arithmetic;
    }

    private function value(string $action, string $column, mixed $arithmetic, mixed $value): void
    {
        $this->sql .= ' ' . $action . ' ' . $column . $arithmetic . '? ';;
        $this->whereArray[count($this->whereArray) + 1] = $value;
    }

    public function groupBy(string $condition): static
    {
        $this->sql .= ' group by ' . $condition;
        return $this;
    }

    public function having(string $column, string $arithmetic = '=', string $value = ''): static
    {
        $this->sql .= ' having ' . $column . $arithmetic . '?';
        $this->whereArray[count($this->whereArray) + 1] = array($arithmetic, $value);
        return $this;
    }

    public function orderBy(string $column, string $sort = 'asc'): static
    {
        $this->sql .= ' order by ' . $column . ' ' . $sort;
        return $this;
    }

    public function limit(string $start, string $end = ''): static
    {
        $this->sql .= ' limit ' . $start;
        if (!empty($end)) {
            $this->sql .= ',' . $end;
        }
        return $this;
    }

    public function insertInto(string $table): static
    {
        $this->action = 'insert';
        $this->sql = 'insert into ' . $table;
        return $this;
    }

    public function values(array $insertDataArray): static
    {
        $i = 1;
        foreach ($insertDataArray as $k => $v) {
            $this->insertParameter['1'] .= $k . ',';
            $this->insertParameter['2'] .= '?,';
            $this->insertParameter['3'] .= $v . ',';
            $this->insertArray[$i] = $v;
            $i++;
        }
        $i = 0;
        return $this;
    }

    private function insertSplicing(string $sql): string
    {
        $column = substr($this->insertParameter['1'], 0, -1);
        $preValue = substr($this->insertParameter['2'], 0, -1);
        $value = substr($this->insertParameter['3'], 0, -1);
        $this->sql = $sql . '(' . $column . ') values(' . $value . ')';
        return $sql . '(' . $column . ') values(' . $preValue . ')';
    }

    public function update(string $table): static
    {
        $this->action = 'update';
        $this->sql = 'update ' . $table . ' set ';
        return $this;
    }

    public function set(array $DataArray): static
    {
        $s = 1;
        foreach ($DataArray as $k => $v) {
            $this->updateParameter .= $k . '=?,';
            $this->updateArray[$s] = $v;
            $s++;
        }
        $this->sql .= substr($this->updateParameter, 0, -1);
        return $this;
    }

    public function deleteFrom(string $table): static
    {
        $this->action = 'delete';
        $this->sql = 'delete from' . $table;
        return $this;
    }

    public function run(): bool|array
    {
        return match ($this->action) {
            'select' => $this->runSelect(),
            'insert' => $this->runInsert(),
            'update' => $this->runUpdate(),
            'delete' => $this->runDelete()
        };
    }

    private function runSelect(): bool|array
    {
        try {
            $this->extracted();
            $this->pdoStmt->execute();
            $rSelect = $this->pdoStmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->whereArray = array();
            return $rSelect;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runInsert(): bool
    {
        try {
            $sql = $this->insertSplicing($this->sql);
            $this->pdoStmt = $this->prepare($sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            if (!empty($this->insertArray)) {
                $count = count($this->insertArray);
                for ($i = 1; $i <= $count; $i++) {
                    $this->pdoStmt->bindValue($i, $this->insertArray[$i]);
                }
            }
            $this->pdoStmt->execute();
            $this->insertArray = array();
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runUpdate(): bool
    {
        try {
            $this->pdoStmt = $this->prepare($this->sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            $updateWhereArray = array_merge($this->updateArray, $this->whereArray);
            $this->updateArray = array_combine(range(1, count($updateWhereArray)), array_values($updateWhereArray));
            if (!empty($this->updateArray)) {
                $count = count($this->updateArray);
                for ($i = 1; $i <= $count; $i++) {
                    $this->pdoStmt->bindValue($i, $this->updateArray[$i]);
                }
            }
            $this->pdoStmt->execute();
            $this->updateArray = array();
            $this->whereArray = array();
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runDelete(): bool
    {
        try {
            $this->extracted();
            $this->pdoStmt->execute();
            $this->whereArray = array();
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function extracted(): void
    {
        $this->pdoStmt = $this->prepare($this->sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        if (!empty($this->whereArray)) {
            $count = count($this->whereArray);
            for ($i = 1; $i <= $count; $i++) {
                $this->pdoStmt->bindValue($i, $this->whereArray[$i]);
            }
        }
    }


}