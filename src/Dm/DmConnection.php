<?php

namespace LaravelDm\Dm;

use Illuminate\Database\Connection;
use PDO;
use LaravelDm\Dm\Query\Grammars\DmGrammar as QueryGrammar;
use LaravelDm\Dm\Query\DmBuilder as QueryBuilder;
use LaravelDm\Dm\Query\Processors\DmProcessor as Processor;
use LaravelDm\Dm\Schema\Grammars\DmGrammar as SchemaGrammar;
use LaravelDm\Dm\Schema\DmBuilder as SchemaBuilder;

class DmConnection extends Connection
{
    /**
     * @var string
     */
    protected $schema;

    /**
     * {@inheritdoc}
     */
    public function getDriverTitle()
    {
        return 'Dm';
    }

    /**
     * @param  PDO|\Closure  $pdo
     * @param  string  $database
     * @param  string  $tablePrefix
     * @param  array  $config
     */
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
        $this->schema = $config['schema'] ? $config['schema'] : $config['username'];
    }

    /**
     * Get current schema.
     *
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Set current schema.
     *
     * @param  string  $schema
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        $sessionVars = [
            'CURRENT_SCHEMA' => $schema,
        ];

        return $this->setSessionVars($sessionVars);
    }

    /**
     * Update session variables.
     *
     * @param  array  $sessionVars
     * @return $this
     */
    public function setSessionVars(array $sessionVars)
    {
        $vars = [];
        foreach ($sessionVars as $option => $value) {
            if (strtoupper($option) == 'CURRENT_SCHEMA' || strtoupper($option) == 'EDITION') {
                $vars[] = "$option  = $value";
            } else {
                $vars[] = "$option  = '$value'";
            }
        }

        foreach ($vars as $var) {
            $sql = 'ALTER SESSION SET '.$var;
            $this->statement($sql);
        }

        return $this;
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \LaravelDm\Dm\Schema\DmBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new SchemaBuilder($this);
    }

    /**
     * Get a new query builder instance.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return new QueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

    /**
     * Set session date format.
     *
     * @param  string  $format
     * @return $this
     */
    public function setDateFormat($format = 'YYYY-MM-DD HH24:MI:SS')
    {
        $sessionVars = [
            'NLS_DATE_FORMAT'      => $format,
            'NLS_TIMESTAMP_FORMAT' => $format,
        ];

        return $this->setSessionVars($sessionVars);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Grammar|\LaravelDm\Dm\Query\Grammars\DmGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new QueryGrammar($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar|\LaravelDm\Dm\Schema\Grammars\DmGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return new SchemaGrammar($this);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \LaravelDm\Dm\Query\Processors\DmProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new Processor();
    }

    /**
     * Bind values to their parameters in the given statement.
     *
     * @param  \PDOStatement  $statement
     * @param  array  $bindings
     * @return void
     */
    public function bindValues($statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            $statement->bindValue(is_string($key) ? $key : $key + 1, $value);
        }
    }
}
