<?php

namespace Configuration\Tests;

use PHPUnit\Framework\TestCase;
use Configuration\Config;
use DBAL\Database;

class ConfigTest extends TestCase{
    protected $db;
    protected $config;
    
    protected function setUp() {
        $this->db = new Database($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
        if(!$this->db->isConnected()){
            $this->markTestSkipped(
                'No local database connection is available'
            );
        }
        $this->db->query(file_get_contents(dirname(dirname(__FILE__)).'/database/database_mysql.sql'));
        $this->basket = new Basket($this->db, new Config($this->db));
    }
    
    protected function tearDown() {
        $this->db = null;
        $this->basket = null;
    }
    
    public function testExample(){
        $this->markTestIncomplete();
    }
}
