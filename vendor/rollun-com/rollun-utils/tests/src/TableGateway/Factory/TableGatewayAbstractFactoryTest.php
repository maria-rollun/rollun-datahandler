<?php

namespace rollun\test\tableGateway\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use rollun\tableGateway\Factory\TableGatewayAbstractFactory;
use Zend\Db\Adapter\Adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-29 at 18:23:51.
 */
class TableGatewayAbstractFactoryTest extends TestCase
{

    /**
     * @var Returner
     */
    protected $object;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->container = include './config/container.php';
        $this->object = new TableGatewayAbstractFactory();
        $this->adapter = $this->container->get('db');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function testTableGatewayAbstractFactory__canCreateIfTableAbsent()
    {
        $requestedName = 'the_name_of_table_which_absent';
        $result = $this->object->canCreate($this->container, $requestedName);
        $this->assertSame(
                false, $result
        );
    }

    public function testTableGatewayAbstractFactory__canCreateIfTableExist()
    {
        $createStatementStr = 'CREATE TABLE IF NOT EXISTS tbl_name_which_exist (id INT)';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();

        $requestedName = 'tbl_name_which_exist';
        $result = $this->object->canCreate($this->container, $requestedName);
        $this->assertSame(
                true, $result
        );
        $createStatementStr = 'DROP TABLE IF EXISTS tbl_name_which_exist';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();
    }

    public function testTableGatewayAbstractFactory__invokeIfTableAbsent()
    {
        $createStatementStr = 'CREATE TABLE IF NOT EXISTS tbl_name_which_exist (id INT)';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();

        $requestedName = 'tbl_name_which_exist';
        if ($this->object->canCreate($this->container, $requestedName)) {
            $result = $this->object->__invoke($this->container, $requestedName);
        }
        $this->assertSame(
                'Zend\Db\TableGateway\TableGateway', get_class($result)
        );
        $createStatementStr = 'DROP TABLE IF EXISTS tbl_name_which_exist';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();
    }

}
