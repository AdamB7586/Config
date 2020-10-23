<?php

namespace Configuration;

use DBAL\Database;

class Config
{
    protected $db;
    protected $config = [];
    protected $table_config = 'config';
    
    /**
     * Constructor
     * @param Database $db This should be an instance of the database class
     * @param string $config_table If the config table is different from the default set this here
     */
    public function __construct(Database $db, $config_table = 'config')
    {
        $this->db = $db;
        if (func_num_args() > 1) {
            $this->table_config = $config_table;
        }
        $settings = $this->db->selectAll($this->table_config);
        if (is_array($settings)) {
            foreach ($settings as $item) {
                $this->config[$item['setting']] = $item['value'];
            }
        } else {
            echo('Please make sure the config setting have been added to the database');
        }
    }
    
    /**
     * Get the config value for a given setting
     * @param string $setting This should be the name of the setting that you wish to retrieve
     * @return mixed The setting value if it exists will be returned
     */
    public function __get($setting)
    {
        return $this->config[$setting];
    }
    
    /**
     * Return all of the settings
     * @return array All of the settings will be returned
     */
    public function getAll()
    {
        return $this->config;
    }
    
    /**
     * Updates a config setting temporarily
     * @param string $setting This should be the setting name
     * @param mixed $value The new value for the setting should be added here
     * @return $this
     */
    public function __set($setting, $value)
    {
        return $this->set($setting, $value);
    }
    /**
     * Updates a config setting temporarily
     * @param string $setting This should be the setting name
     * @param mixed $value The new value for the setting should be added here
     * @return $this
     */
    public function set($setting, $value)
    {
        $this->config[$setting] = $value;
        return $this;
    }
    
    /**
     * Set a setting in the database
     * @param string $setting This should be the setting name
     * @param mixed $value The new value for the setting should be added here
     * @return $this
     */
    public function update($setting, $value)
    {
        if ($this->db->update($this->table_config, ['value' => $value], ['setting' => $setting], 1)) {
            $this->set($setting, $value);
        }
        return $this;
    }
}
