<?php

namespace Core\Traits;

use Core\Db;
use Core\Enums\SqlOrderByEnum;
use PDO;

trait Queryable
{
    static protected string|null $tableName = null;

    static protected string $query = "";

    protected array $commands = [];

    static protected function resetQuery() : void
    {
        static::$query = "";
    }

    // User::select() -> table name -> users
    // Note::select() -> table name -> notes
    static public function select(array $columns = ['*']): static
    {
        static::resetQuery();
        static::$query = "SELECT " . implode(', ', $columns) . " FROM " . static::$tableName . " ";

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }


    static public function find($id): static|false
    {
        $query = Db::connect()->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam('id', $id);
        $query->execute();

        return $query->fetchObject(static::class);
    }


    static public function findBy(string $column, $value): static|false
    {
        $query = Db::connect()->prepare("SELECT * FROM " . static::$tableName . " WHERE {$column} = :{$column}");
        $query->bindParam($column, $value);
        $query->execute();

        return $query->fetchObject(static::class);
    }


    public function where(string $column, string $operator, $value ): static
    {
        if ($this->prevent(['group', 'limit', 'order', 'having'])) {
            throw new \Exception("[Queryable]: WHERE can not be after ['group', 'limit', 'order', 'having']");
        }

        $obj = in_array('select', $this->commands) ? $this : static::select();

        if (!is_bool($value) && !is_numeric($value) && !(is_null($value)) && !in_array($operator, ['IN', 'NOT IN'])) {
            $value = "'{$value}'";
        }

        if( $value == null || mb_strtolower($value) === "null" ) {
            $operator = match (mb_strtolower($operator)) {
                "is" => " IS ",
                "is not" => "IS NOT",
                default => throw new \Exception("[Queryable]: wrong Operator, use IS / IS NOT with [null] value ")
            };

            $value = " NULL ";
        }

        if (!in_array("where", $this->commands)) {
            static::$query .= " WHERE";
        }

        static::$query .= " {$column} {$operator} {$value}";

        $obj->commands[] = 'where';

        return $obj;
    }

    public function andWhere(string $column, string $operator, $value): static
    {
        static::$query .= " AND";
        return $this->where($column, $operator, $value);
    }

    public function orWhere(string $column, string $operator, $value): static
    {
        static::$query .= " OR";
        return $this->where($column, $operator, $value);
    }

    public function whereNull(string $column)
    {
        return $this->where($column, "is", null);
    }

    public function whereNotNull(string $column)
    {
        return $this->where($column, "is not", null);
    }

    public function orWhereNull(string $column)
    {
        static::$query .= " OR";
        return $this->where($column, "is", null);
    }

    public function orWhereNotNull(string $column)
    {
        static::$query .= " OR";
        return $this->where($column, "is not", null);
    }

    public function andWhereNull(string $column)
    {
        static::$query .= " AND";
        return $this->where($column, "is", null);
    }

    public function andWhereNotNull(string $column)
    {
        static::$query .= " AND";
        return $this->where($column, "is not", null);
    }


    public function whereIn(string $column, array $value, $type = 'AND'): static
    {
        if (in_array('where', $this->commands)) {
            static::$query .= " {$type}";
        }

        $value = "(" . implode(',', $value) . ") ";

        return $this->where($column, 'IN', $value);
    }

    public function whereNotIn(string $column, array $value, $type = 'AND'): static
    {
        if (in_array('where', $this->commands)) {
            static::$query .= " {$type}";
        }

        $value = "(" . implode(',', $value) . ") ";

        return $this->where($column, 'NOT IN', $value);
    }

    public function join(string $table, string $t1Column, string $t2Column, string $operator = '=', string $type = 'LEFT'): static
    {
        if (!$this->prevent(['select'])) {
            throw new \Exception("[Queryable]: {$type} JOIN can not be before ['select']");
        }

        $this->commands[] = 'join';

        static::$query .= " {$type} JOIN {$table} ON {$t1Column} {$operator} {$t2Column}";

        return $this;
    }

//    public function orderBy(string $column, $orderBy = SqlOrderByEnum::ASC ): static
//    {
//        if (!$this->prevent(['select'])) {
//            throw new \Exception("[Queryable]: ORDER BY can not be before ['select']");
//        }
//
//        $this->commands[] = 'order';
//
//        static::$query .= " ORDER BY {$column} " . $orderBy->value ;
//
//        return $this;
//    }

    public function orderBy( array $columns ): static
    {
        if (!$this->prevent(['select'])) {
            throw new \Exception("[Queryable]: ORDER BY can not be before ['select']");
        }

        $this->commands[] = 'order';

        static::$query .= " ORDER BY ";

        $lastKey = array_key_last($columns);

        foreach ($columns as $column => $order) {
            static::$query .= " {$column} {$order->value}" . ($column === $lastKey ? '' : ',');
        }

        return $this;
    }


    public function groupBy(array $columns): static
    {
        if (!$this->prevent(['select'])) {
            throw new \Exception("[Queryable]: GROUP BY can not be before ['select']");
        }

        $this->commands[] = 'group';

        static::$query .= " GROUP BY " . implode(', ', $columns);

        return $this;
    }

    public function get(): bool|array
    {
        return Db::connect()
            ->query( static::$query )
            ->fetchAll(PDO::FETCH_CLASS, static::class );
    }


    static public function create(array $fields): int
    {
        $fields += ['created_at' => date(date('Y-m-d H:i:s')), 'updated_at' => date(date('Y-m-d H:i:s')) ];
        $params = static::prepareQueryParams($fields);

        $query = "INSERT INTO " . static::$tableName . " ( {$params['keys']} ) VALUES ( {$params['placeholders'] } )";
        $query = Db::connect()->prepare($query);

        $query->execute($fields);

        return (int) Db::connect()->lastInsertId();
    }


    static protected function prepareQueryParams(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys );

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders)
        ];
    }


    public function update(array $fields): bool
    {
        $fields += ['updated_at' => date(date('Y-m-d H:i:s')) ];
        $query = "UPDATE " . static::$tableName . " SET" . $this->updatePlaceholders(array_keys($fields)) . " WHERE id=:id";
        $query = Db::connect()->prepare($query);
        $fields['id'] = $this->id;

        return $query->execute($fields);
    }


    public static function destroy($id): bool
    {
        $query = "DELETE FROM " . static::$tableName . " WHERE id=:id";
        $query = Db::connect()->prepare($query);
        $query->bindParam('id', $id);

        return $query->execute();
    }

    public function remove()
    {
        if(!$this->id) {
            throw new \Exception('[destroy] The id field is missing');
        }
        return self::destroy( $this->note_id );
    }

    protected function updatePlaceholders(array $keys): string
    {
        $string = "";
        $lastKey = array_key_last($keys);


        foreach ( $keys as $index => $key) {
            $string .= " {$key}=:{$key}" . ( $lastKey === $index ? '' : ',' );
        }

        return $string;
    }

    public function pluck(string $column): array
    {
        $result = $this->get();
        $newArr = [];

        foreach ($result as $item) {
            $newArr[] = $item->$column;
        }

        return $newArr;
    }

    public function getSqlQuery(): string
    {
        return static::$query;
    }

    protected function prevent(array $allowedMethods): bool
    {
        foreach ($allowedMethods as $method) {
            if (in_array($method, $this->commands)) {
                return true;
            }
        }

        return false;
    }
}
