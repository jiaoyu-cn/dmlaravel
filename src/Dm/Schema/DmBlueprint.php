<?php

namespace LaravelDm\Dm\Schema;

use Illuminate\Database\Schema\Blueprint;

class DmBlueprint extends Blueprint
{
    /**
     * Table comment.
     *
     * @var string
     */
    public $comment = null;

    /**
     * Column comments.
     *
     * @var array
     */
    public $commentColumns = [];

    /**
     * Create a default index name for the table.
     *
     * @param  string  $type
     * @param  array  $columns
     * @return string
     */
    protected function createIndexName($type, array $columns)
    {
        $short_type = [
            'primary' => 'pk',
            'foreign' => 'fk',
            'unique'  => 'uk',
        ];

        $type = isset($short_type[$type]) ? $short_type[$type] : $type;

        $index = strtolower($this->connection->getTablePrefix().$this->table.'_'.implode('_', $columns).'_'.$type);

        $index = str_replace(['-', '.'], '_', $index);

        //shorten the name if it is longer than 30 chars
        while (strlen($index) > 30) {
            $parts = explode('_', $index);

            for ($i = 0; $i < count($parts); $i++) {
                //if any part is longer than 2 chars, take one off
                $len = strlen($parts[$i]);
                if ($len > 2) {
                    $parts[$i] = substr($parts[$i], 0, $len - 1);
                }
            }

            $index = implode('_', $parts);
        }
        return $index;
    }

    /**
     * Create a new nvarchar2 column on the table.
     *
     * @param  string  $column
     * @param  int  $length
     * @return \Illuminate\Support\Fluent
     */
    public function nvarchar2($column, $length = 8188)
    {
        return $this->addColumn('nvarchar2', $column, compact('length'));
    }
	
    /**
     * Create a new decimal column on the table.
     *
     * @param  string  $column
     * @param  int  $total
     * @param  int  $places
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function decimal($column, $total = 22, $places = 6)
    {
        return $this->addColumn('decimal', $column, compact('total', 'places'));
    }

    /**
     * Create a new date-time column on the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function dateTime($column, $precision = null)
    {
        return $this->addColumn('dateTime', $column, compact('precision'));
    }

    /**
     * Create a new date-time column (with time zone) on the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function dateTimeTz($column, $precision = null)
    {
        return $this->addColumn('dateTimeTz', $column, compact('precision'));
    }

    /**
     * Create a new timestamp column on the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timestamp($column, $precision = null)
    {
        return $this->addColumn('timestamp', $column, compact('precision'));
    }

    /**
     * Create a new timestamp (with time zone) column on the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timestampTz($column, $precision = null)
    {
        return $this->addColumn('timestampTz', $column, compact('precision'));
    }

    /**
     * Add nullable creation and update timestamps to the table.
     *
     * @param  int|null  $precision
     * @return void
     */
    public function timestamps($precision = null)
    {
        $this->timestamp('created_at', $precision)->nullable();

        $this->timestamp('updated_at', $precision)->nullable();
    }

    /**
     * Add nullable creation and update timestamps to the table.
     *
     * Alias for self::timestamps().
     *
     * @param  int|null  $precision
     * @return void
     */
    public function nullableTimestamps($precision = null)
    {
        $this->timestamps($precision);
    }

    /**
     * Add creation and update timestampTz columns to the table.
     *
     * @param  int|null  $precision
     * @return void
     */
    public function timestampsTz($precision = null)
    {
        $this->timestampTz('created_at', $precision)->nullable();

        $this->timestampTz('updated_at', $precision)->nullable();
    }

    /**
     * Add creation and update datetime columns to the table.
     *
     * @param  int|null  $precision
     * @return void
     */
    public function datetimes($precision = null)
    {
        $this->datetime('created_at', $precision)->nullable();

        $this->datetime('updated_at', $precision)->nullable();
    }

    /**
     * Add a "deleted at" timestamp for the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function softDeletes($column = 'deleted_at', $precision = null)
    {
        return $this->timestamp($column, $precision)->nullable();
    }

    /**
     * Add a "deleted at" timestampTz for the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function softDeletesTz($column = 'deleted_at', $precision = null)
    {
        return $this->timestampTz($column, $precision)->nullable();
    }

    /**
     * Add a "deleted at" datetime column to the table.
     *
     * @param  string  $column
     * @param  int|null  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function softDeletesDatetime($column = 'deleted_at', $precision = null)
    {
        return $this->datetime($column, $precision)->nullable();
    }

    /**
     * Create a new char column on the table.
     *
     * @param  string  $column
     * @param  int|null  $length
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function char($column, $length = 1)
    {
        return $this->addColumn('char', $column, compact('length'));
    }

    /**
     * Create a new string column on the table.
     *
     * @param  string  $column
     * @param  int|null  $length
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function string($column, $length = 8188)
    {
        return $this->addColumn('string', $column, compact('length'));
    }
}
