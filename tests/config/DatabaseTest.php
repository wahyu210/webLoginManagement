<?php
namespace MochamadWahyu\Phpmvc\Config;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase{
    public function testGetConnection()
    {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }
    public function testGetConnectionsingleton()
    {
        $connection = Database::getConnection();
        $connection1 = Database::getConnection();
        // self::assertNotNull($connection);
        self::assertSame($connection, $connection1);
    }
}