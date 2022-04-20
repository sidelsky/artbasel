<?php

namespace Wecp\App\Models;
class EmailCustomizerPlusTemplatesModel extends Base
{
    protected $name = "email_customizer_plus_templates";

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . $this->name;
        $this->primary_key = 'id';
        $this->fields = array(
            'type' => '%s',
            'is_active' => '%d',
            'language' => '%s',
            'styles' => '%s',
            'elements' => '%s',
            'html' => '%s',
            'mjml' => '%s',
            'extra' => '%s'
        );
    }

    /**
     * get the table name
     * @return string
     */
    function getTableName()
    {
        global $wpdb;
        return $wpdb->prefix . $this->name;
    }

    function create()
    {
        if (is_multisite()) {
            // get ids of all sites
            $blog_table = self::$db->blogs;
            $blog_ids = self::$db->get_col("SELECT blog_id FROM {$blog_table}");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                // create tables for each site
                $this->runTableCreation();
                restore_current_blog();
            }
        } else {
            // activated on a single site
            $this->runTableCreation();
        }
    }

    /**
     * run table creation query
     */
    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->getTableName()} (
				 `{$this->getPrimaryKey()}` int(11) NOT NULL AUTO_INCREMENT,
                 `type` varchar(255) DEFAULT NULL,
                 `is_active` int DEFAULT 1,
                 `css` longtext DEFAULT NULL,
                 `html` longtext DEFAULT NULL,
                 `mjml` longtext DEFAULT NULL,
                 `language` varchar(255) DEFAULT NULL,
                 `styles` longtext DEFAULT NULL,
                 `elements` longtext DEFAULT NULL,
                 `extra` longtext DEFAULT NULL,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }
}