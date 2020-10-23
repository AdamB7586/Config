<?php

namespace Configuration\Tests;

use PHPUnit\Framework\TestCase;
use Configuration\Config;
use DBAL\Database;

class ConfigTest extends TestCase
{
    protected $db;
    protected $config;
    
    protected function setUp(): void
    {
        $this->db = new Database($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
        if (!$this->db->isConnected()) {
            $this->markTestSkipped(
                'No local database connection is available'
            );
        }
        $this->db->query(file_get_contents(dirname(dirname(__FILE__)).'/database/database_mysql.sql'));
        $this->config = new Config($this->db);
    }
    
    protected function tearDown(): void
    {
        $this->db = null;
        $this->config = null;
    }
    
    /**
     * @covers Configuration\Config::__construct
     * @covers Configuration\Config::getAll
     */
    public function testGetConfig()
    {
        $settings = $this->config->getAll();
        $this->assertArrayHasKey('my_table', $settings);
        $this->assertArrayHasKey('settings', $settings);
        $this->assertEquals('hello', $settings['my_table']);
    }
    
    /**
     * @covers Configuration\Config::__construct
     * @covers Configuration\Config::__get
     * @covers Configuration\Config::__set
     * @covers Configuration\Config::set
     * 
     */
    public function testSetConfig()
    {
        $this->assertEquals('second', $this->config->second_table);
        $this->config->second_table = 'users';
        $this->assertEquals('users', $this->config->second_table);
    }
    
    /**
     * @covers Configuration\Config::__construct
     * @covers Configuration\Config::__get
     * @covers Configuration\Config::getAll
     * @covers Configuration\Config::set
     * @covers Configuration\Config::update
     */
    public function testUpdateConfig()
    {
        $origSettingConfig = $this->config->getAll()['settings'];
        $this->assertEquals('Hello', $origSettingConfig);
        $this->assertEquals($this->config, $this->config->update('settings', 'This is my new setting'));
        $this->assertNotEquals('Hello', $this->config->getAll()['settings']);
        $this->assertEquals('This is my new setting', $this->config->settings);
    }
    
    /**
     * @covers Configuration\Config::__construct
     * @covers Configuration\Config::getAll
     */
    public function testAlternateTable()
    {
        $this->db->query("RENAME TABLE `{$GLOBALS['database']}`.`config` TO `{$GLOBALS['database']}`.`config_new`;");
        $alternate = new Config($this->db, 'config_new');
        $this->assertObjectHasAttribute('table_config', $alternate);
        $this->assertNotEmpty($alternate->getAll());
    }
    
    /**
     * @covers Configuration\Config::__construct
     */
    public function testEmptyConfig()
    {
        $this->db->delete('config_new', []);
        $this->expectOutputString('Please make sure the config setting have been added to the database');
        new Config($this->db, 'config_new');
        $this->db->query("DROP TABLE `{$GLOBALS['database']}`.`config_new`;");
    }
}
