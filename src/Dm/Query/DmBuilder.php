<?php

namespace LaravelDm\Dm\Query;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;

class DmBuilder extends Builder
{
    /**
     * Run the query as a "select" statement against the connection.
     *
     * @return array
     */
    protected function runSelect()
    {
        $expression = $this->toSql();
        if (strpos($expression, ' group_concat(') !== false) {
            $expression = str_replace(' group_concat(', ' wm_concat(', $expression);
        }
        if (strpos($expression, ' GROUP_CONCAT(') !== false) {
            $expression = str_replace(' GROUP_CONCAT(', ' WM_CONCAT(', $expression);
        }

        if ($this->lock) {
            $this->connection->beginTransaction();
            $result = $this->connection->select($expression, $this->getBindings(), ! $this->useWritePdo);
            $this->connection->commit();

            return $result;
        }

        return $this->connection->select($expression, $this->getBindings(), ! $this->useWritePdo);
    }

    /**
     * Clone the query.
     *
     * @return static
     */
    public function clone()
    {
        return clone $this;
    }


    /**
     * Add a new "raw" select expression to the query.
     * 
     * @param  string  $expression
     * @param  array  $bindings
     * @return $this
     */
    public function selectRaw($expression, array $bindings = [])
    {
        if (strpos($expression, ' group_concat(') !== false) {
            $expression = str_replace(' group_concat(', ' wm_concat(', $expression);
        }
        if (strpos($expression, ' GROUP_CONCAT(') !== false) {
            $expression = str_replace(' GROUP_CONCAT(', ' WM_CONCAT(', $expression);
        }
        $this->addSelect(new Expression($expression));

        if ($bindings) {
            $this->addBinding($bindings, 'select');
        }

        return $this;
    }
    
    /**
     * Add a "where JSON overlaps" clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereJsonOverlaps($column, $value, $boolean = 'and', $not = false)
    {
        $parts = explode('->', $column, 2);
        $type = 'JsonOverlaps';

        $this->wheres[] = compact('type', 'column', 'value', 'boolean', 'not');

        if (! $value instanceof ExpressionContract) {
            $json_column = json_encode([$parts[1] => $value]);
            $this->addBinding($json_column);
        }

        return $this;
    }
    
    /**
     * Get the raw SQL representation of the query with embedded bindings.
     *
     * @return string
     */
    public function toRawSql()
    {
        return $this->grammar->substituteBindingsIntoRawSql(
            $this->toSql(), $this->connection->prepareBindings($this->getBindings())
        );
    }
}
