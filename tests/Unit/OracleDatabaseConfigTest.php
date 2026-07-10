<?php

namespace Tests\Unit;

use Tests\TestCase;

class OracleDatabaseConfigTest extends TestCase
{
    public function test_oracle_database_configuration_is_present(): void
    {
        $config = config('database.connections.oracle');

        $this->assertNotNull($config);
        $this->assertEquals('oracle', $config['driver']);
        $this->assertEquals('127.0.0.1', $config['host']);
        $this->assertEquals('1521', $config['port']);
        $this->assertEquals('xe', $config['database']);
        $this->assertEquals('system', $config['username']);
        $this->assertEquals('', $config['password']);
        $this->assertEquals('AL32UTF8', $config['charset']);
        $this->assertEquals('', $config['prefix']);
        $this->assertTrue($config['prefix_indexes']);
    }
}
