<?php

namespace RoLocations\Controller\Console;

use T4webActionInjections\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\SqlInterface;
use PDOException;

class InitController extends AbstractActionController
{

    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function runAction(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $message = "Success completed" . PHP_EOL;

        try {
            $this->createTableCountries()
                ->createTableRegions()
                ->createTableCities();
        } catch (PDOException $e) {
            $message = $e->getMessage() . PHP_EOL;
        }

        return $message;
    }

    private function createTableCountries()
    {
        $table = new Ddl\CreateTable('countries');
        $table->addColumn(new Column\Integer('id', false, null, ['autoincrement' => true]));
        $table->addColumn(new Column\Varchar('name', 255));
        $table->addConstraint(new Constraint\PrimaryKey('id'));

        $this->create($table);

        return $this;
    }

    private function createTableRegions()
    {
        $table = new Ddl\CreateTable('regions');
        $table->addColumn(new Column\Integer('id', false, null, ['autoincrement' => true]));
        $table->addColumn(new Column\Integer('country_id', false, null));
        $table->addColumn(new Column\Varchar('name', 255));
        $table->addConstraint(new Constraint\PrimaryKey('id'));

        $this->create($table);

        return $this;
    }

    private function createTableCities()
    {
        $table = new Ddl\CreateTable('cities');
        $table->addColumn(new Column\Integer('id', false, null, ['autoincrement' => true]));
        $table->addColumn(new Column\Integer('region_id', false, null));
        $table->addColumn(new Column\Integer('country_id', false, null));
        $table->addColumn(new Column\Varchar('name', 255));
        $table->addConstraint(new Constraint\PrimaryKey('id'));

        $this->create($table);

        return $this;
    }

    private function create(SqlInterface $table)
    {
        $sql = new Sql($this->dbAdapter);
        $this->dbAdapter->query($sql->buildSqlString($table), Adapter::QUERY_MODE_EXECUTE);
    }
}