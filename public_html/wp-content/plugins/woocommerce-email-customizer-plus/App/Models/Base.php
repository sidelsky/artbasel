<?php

namespace Wecp\App\Models;
abstract class Base
{
    protected $table = NULL, $primary_key = NULL, $fields = array();
    static protected $db;

    function __construct()
    {
        global $wpdb;
        self::$db = $wpdb;
    }

    /**
     * create the table
     * @return mixed
     */
    abstract function create();

    /**
     * Save the table data
     * @param $data
     * @return mixed
     */
    function saveData($data)
    {
        $primary_key = sanitize_key((isset($data[$this->primary_key]) && !empty($data[$this->primary_key])) ? $data[$this->primary_key] : 0);
        if (!empty($primary_key)) {
            $row = $this->getByKey($primary_key);
            if (!empty($row)) {
            } else {
                return $this->insertRow($data);
            }
            return $primary_key;
        } else {
            return $this->insertRow($data);
        }
    }

    /**
     * get the table name
     * @return null
     */
    function getTable()
    {
        return $this->table;
    }

    /**
     * inserting the row to table
     * @param $data
     * @return int
     */
    function insertRow($data)
    {
        if (!empty($this->fields)) {
            $columns = implode('`,`', array_keys($this->fields));
            $values = implode(',', $this->fields);
            $actual_values = $this->formatData($data);
            $query = self::$db->prepare("INSERT INTO {$this->table} (`{$columns}`) VALUES ({$values});", $actual_values);
            self::$db->query($query);
            return self::$db->insert_id;
        }
        return 0;
    }

    /**
     * Format the data according to value
     * @param $data
     * @return array
     */
    function formatData($data)
    {
        $result = array();
        if (!empty($this->fields)) {
            foreach ($this->fields as $key => $value) {
                $key = trim($key);
                if ('%d' == trim($value)) {
                    $value = intval(isset($data[$key]) ? $data[$key] : 0);
                } elseif ('%f' == trim($value)) {
                    $value = floatval(isset($data[$key]) ? $data[$key] : 0);
                } else {
                    $value = isset($data[$key]) ? $data[$key] : NULL;
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                    }
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * create the table
     * @param $query
     * @param bool $add_charset
     */
    protected function createTable($query, $add_charset = true)
    {
        if ($add_charset) {
            $query = $query . ' ' . self::$db->get_charset_collate() . ';';
        }
        if (!function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }
        dbDelta($query);
    }

    /**
     * @param $where
     * @param $single
     * @param $select
     * @return array|object|void|null
     */
    function getWhere($where, $select = '*', $single = true)
    {
        if (is_array($select) || is_object($select)) {
            $select = implode(',', $select);
        }
        if (empty($select)) {
            $select = '*';
        }
        $query = "SELECT {$select} FROM {$this->table} WHERE {$where};";
        if ($single) {
            return self::$db->get_row($query, OBJECT);
        } else {
            return self::$db->get_results($query, OBJECT);
        }
    }

    /**
     * @param $select
     * @return array|object|void|null
     */
    function getAll($select = '*')
    {
        if (is_array($select) || is_object($select)) {
            $select = implode(',', $select);
        }
        if (empty($select)) {
            $select = '*';
        }
        $query = "SELECT {$select} FROM {$this->table};";
        return self::$db->get_results($query, OBJECT);
    }

    /**
     * Update the table
     * @param $data
     * @param array $where
     * @return bool|false|int
     */
    function updateRow($data, $where = array())
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return self::$db->update($this->table, $data, $where);
    }

    /**
     * Update the table
     * @param array $where
     * @return bool|false|int
     */
    function deleteRow($where)
    {
        return self::$db->delete($this->table, $where);
    }

    /**
     * get the data from table
     * @param $key
     * @return array|object|void|null
     */
    function getByKey($key)
    {
        $key = sanitize_key($key);
        $query = self::$db->prepare("SELECT * FROM {$this->table} WHERE `{$this->primary_key}` = %d;", array($key));
        return self::$db->get_row($query, OBJECT);
    }

    /**
     * get the value of the primary key
     * @return null
     */
    function getPrimaryKey()
    {
        return $this->primary_key;
    }
}