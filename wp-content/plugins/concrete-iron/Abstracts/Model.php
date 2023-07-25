<?php
/**
 * Abstract class for our custom models used
 * throughout the theme.
 *
 * @author pebas
 * @package lisfinity/model
 * @version 1.0.0
 */

use function GuzzleHttp\_current_time;

/**
 * Class Model
 * -----------
 *
 * @package Lisfinity
 */
abstract class Model {

    /**
     * Prefix of our custom table
     * --------------------------
     *
     * @var string
     */
    protected $table_prefix = 'lisfinity_';

    /**
     * The table associated with the model
     * -----------------------------------
     *
     * @var string
     */
    protected $table;

    /**
     * The fields inserted into the table
     * ----------------------------------
     *
     * @var array
     */
    protected $fields;

    /**
     * The name of the database
     * ------------------------
     *
     * @var string
     */
    public $db;

    /**
     * Query of the database
     * ---------------------
     * @var string
     */
    private $query;

    /**
     * The version of the table
     * ------------------------
     *
     * @var $version
     */
    private $version;

    public function __construct() {
        $this->set_version();
        $this->set_table_fields();
        $this->table();
    }

    /**
     * Initialization of the necessary methods
     * ---------------------------------------
     */
    public function init() {
        $this->create_table();
    }

    /**
     * Get formatted table name for the database
     * -----------------------------------------
     *
     * @return string
     */
    public function get_formatted_table_name() {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_prefix . $this->table;

        return $table;
    }

    /**
     * Create a new table
     * ------------------
     */
    protected function create_table() {
        global $wpdb;
        $version = get_option( "lisfinity__{$this->table}_table_db_version" );

        if ( $version === $this->version ) {
            return;
        }

        $wpdb->hide_errors();
        $table = $this->get_formatted_table_name();

        $collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $fields = $this->table_fields();

        $sql = "
			CREATE TABLE $table (
  			  id bigint(20) NOT NULL auto_increment,
  			  $fields
			  created_at timestamp default current_timestamp,
			  updated_at timestamp default '0000-00-00 00:00:00',
			  PRIMARY KEY (id)
			) $collate;
		";
        dbDelta( $sql );

        update_option( "lisfinity__{$this->table}_table_db_version", $this->version );
    }

    protected function set_version() {
        return $this->version = '1.0.0';
    }

    protected function get_version() {
        return $this->version;
    }

    /**
     * Generate table fields
     * ---------------------
     *
     * @return string
     */
    protected function table_fields() {
        $fields       = $this->fields;
        $table_fields = '';

        if ( ! empty( $fields ) ) {
            foreach ( $fields as $name => $options ) {
                $table_fields .= "{$name} {$options['type']} default {$options['value']},";
            }
        }

        return $table_fields;
    }

    /**
     * Set table fields that will be created
     * -------------------------------------
     *
     * @return array
     */
    protected function set_table_fields() {
        return $this->fields = [];
    }

    /**
     * Get the fields from the table
     * -----------------------------
     *
     * @return array
     */
    public function get_table_fields() {
        return array_keys( $this->fields );
    }

    /**
     * Enter new query into the table
     * ------------------------------
     *
     * @param array $values - Values that will be
     *  combined with fields and stored in the database
     *
     * @param string $format - Format of the values that
     * are being stored in the database
     *
     * @return false|int|void
     */
    public function store( $values, $format = null ) {
        global $wpdb;

        $fields = $this->get_table_fields();

        if ( empty( $fields ) ) {
            return;
        }

        $data               = array_combine( $fields, $values );
        $table              = $this->get_formatted_table_name();
        $data['created_at'] = current_time( 'mysql' );

        try {
            $result = $wpdb->insert( $table, $data, $format );

            return $wpdb->insert_id;
        } catch ( Exception $e ) {
            throw $e->getMessage();
        }

    }

    /**
     * Update the values in the database
     * ---------------------------------
     *
     * @param $data
     * @param $where
     * @param string|array $format
     * @param string|array $where_format
     *
     * @return int
     */
    public function update_wp( $data, $where, $format = '', $where_format = '' ) {
        global $wpdb;

        $table = $this->get_formatted_table_name();

        try {
            $result = $wpdb->update( $table, $data, $where, $format, $where_format );

            return $wpdb->insert_id;
        } catch ( Exception $e ) {
            throw $e->getMessage();
        }
    }

    /**
     * Get formatted table name
     * ------------------------
     *
     * @return string
     */
    private function table() {
        global $wpdb;

        $this->db = $wpdb->prefix . $this->table_prefix . $this->table;

        return $this->db;
    }

    /**
     * Method to add where clauses to the query builder.
     * -------------------------------------------------
     *
     * @param $column
     * @param string $value
     * @param bool $prefix_db
     *
     * @return $this
     */
    public function where( $column, $value = '', $prefix_db = true ) {
        global $wpdb;

        $query      = '';
        $table_name = $prefix_db ? "$this->db." : '';
        if ( is_array( $column ) ) {
            $count = 0;
            $query = '';

            foreach ( $column as $clause ) {
                $clause[0] = esc_sql( $clause[0] );
                $clause[1] = esc_sql( $clause[1] );
                if ( $count === 0 ) {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( "WHERE {$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " WHERE {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                } else {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( " AND {$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " AND {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                }
                $count ++;
            }
        } else {
            $column = esc_sql( $column );
            $value  = esc_sql( $value );
            $query  .= $wpdb->prepare( " WHERE {$table_name}{$column} = %s", $value );
        }

        $this->query .= $query;

        return $this;
    }

    public function join( $table, $column = 'id', $column_pre = 'id', $side = 'LEFT' ) {
        $query = " $side JOIN $table ON {$this->db}.{$column_pre} = {$table}.{$column} ";

        $this->query .= $query;

        return $this;
    }

    /**
     * Method to add 'OR' to where clauses query builder
     * -------------------------------------------------
     *
     * @param $row
     * @param string $value
     *
     * @return $this
     */
    public function orWhere( $column, $value = '', $prefix_db = true ) {
        global $wpdb;

        $query      = '';
        $table_name = $prefix_db ? "$this->db." : '';
        if ( is_array( $column ) ) {
            $count = 0;
            $query = '';
            foreach ( $column as $clause ) {
                $clause[0] = esc_sql( $clause[0] );
                $clause[1] = esc_sql( $clause[1] );
                if ( $count === 0 ) {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( "{$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                } else {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( " AND {$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " AND {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                }
                $count ++;
            }
        } else {
            $column = esc_sql( $column );
            $value  = esc_sql( $value );
            $query  .= $wpdb->prepare( " WHERE {$table_name}{$column} = %s", $value );
        }

        $this->query .= " OR $query";

        return $this;
    }

    /**
     * Get the exact value that we are looking for
     * from the database
     * -------------------------------------------
     *
     * @param $value
     * @param string $arg - Additional argument if we wish to count
     * or select distinct from the database.
     * @param string|int $limit - Should we limit results returned
     * from the database.
     * @param string|int $order - how we should order the records.
     *
     * @return array|mixed|object|string|void|null
     */
    public function value( $value, $arg = '', $limit = '', $order = '' ) {
        global $wpdb;

        $value = ! empty( $arg ) ? " $arg($value)" : $value;
        $limit = ! empty( $limit ) ? " LIMIT $limit" : '';
        $order = ! empty( $order ) ? " $order" : '';
        $query = $wpdb->get_results( "SELECT $value FROM {$this->db} $this->query $limit $order;" );

        if ( $wpdb->last_error ) {
            return $wpdb->last_error;
        }

        $this->query = '';

        return $query;
    }

    /**
     * Get the all results from the database row
     * -----------------------------------------
     *
     * @param string|int $limit - Should we limit results returned
     * from the database.
     * @param string $order - Additional input to choose the
     * ordering of the results.
     * @param string $arg - Additional manually input arguments
     * for our query.
     * @param string $return - Choose what we want to return between
     * all results, just row or just a col.
     *
     * @return array|mixed|object|string|void|null
     */
    public function get( $limit = '', $order = '', $arg = '', $return = '' ) {
        global $wpdb;
        $db_fields = $this->get_table_fields();

        $fields[] = "{$this->db}.id";
        foreach ( $db_fields as $field ) {
            $fields[] = "{$this->db}.{$field}";
        }
        $fields = implode( ',', $fields );

        $arg   = ! empty( $arg ) ? " $arg" : '*';
        $limit = ! empty( $limit ) ? " LIMIT $limit" : '';
        if ( empty( $return ) ) {
            $query = $wpdb->get_results( "SELECT $arg FROM {$this->db} $this->query $order $limit;" );
        } else if ( 'col' === $return ) {
            $query = $wpdb->get_col( "SELECT $arg FROM {$this->db} $this->query $order $limit;" );
        }

        if ( $wpdb->last_error ) {
            return $wpdb->last_error;
        }

        $this->query = '';

        return $query;
    }

    /**
     * Prepare the fields for updating in the database
     * -----------------------------------------------
     *
     * @param $column
     * @param string $value
     *
     * @return $this
     */
    public function set( $column, $value = '' ) {
        global $wpdb;

        $query = '';
        if ( is_array( $column ) ) {
            $count = 0;
            $query = '';
            foreach ( $column as $clause ) {
                $clause[0] = esc_sql( $clause[0] );
                $clause[1] = esc_sql( $clause[1] );
                if ( $count === 0 ) {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( " $clause[0] = %s ", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " $clause[0] $clause[1] $clause[2] ";
                    }
                } else {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( ", {$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= ", {$clause[0]} $clause[1] $clause[2]";
                    }
                }
                $count ++;
            }
        } else {
            $column = esc_sql( $column );
            $value  = esc_sql( $value );
            $query  .= $wpdb->prepare( "{$column} = %s ", $value );
        }

        $this->query .= $query;

        return $this;
    }

    /**
     * Update the fields in the database
     * ---------------------------------
     *
     * @return bool|false|int|string
     */
    public function update() {
        global $wpdb;

        $query = $wpdb->query( "UPDATE {$this->db} SET $this->query;" );

        if ( $wpdb->last_error ) {
            return $wpdb->last_error;
        }

        $this->query = '';

        return $query;
    }

    /**
     * Get result from the table
     * -------------------------
     */
    public function show() {
    }

    /**
     * Remove item from the table
     * --------------------------
     */
    public function destroy() {
        global $wpdb;

        $query = $wpdb->query( "DELETE FROM {$this->db} $this->query;" );

        if ( $wpdb->last_error ) {
            return $wpdb->last_error;
        }

        $this->query = '';

        return $query;
    }

}
