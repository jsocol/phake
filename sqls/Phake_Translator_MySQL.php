<?php

class Phake_Translator_MySQL
{

    const TABLE_CREATE = "CREATE TABLE IF NOT EXISTS `%s` ( %s ) %s";
    const TABLE_DROP = "DROP TABLE IF EXISTS `%s`";
    const TABLE_ALTER = "ALTER TABLE %s";

    const COLUMN_PRIMARY_KEY = "id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY";
    const COLUMN_NOT_NULL = "%s NOT NULL";
    const COLUMN_NULL = "%s NULL";
    const COLUMN_UNIQUE = "%s UNIQUE";
    const COLUMN_DEFAULT = "%s DEFAULT '%s'";
    const COLUMN_TIMESTAMPS = "created INT(20) NOT NULL DEFAULT 0, updated INT(20) NOT NULL DEFAULT 0";

    const COLUMN_INT = "`%s` INT(%d)";
    const COLUMN_VARCHAR = "`%s` VARCHAR(%d)";
    const COLUMN_TEXT = "`%s` TEXT";
    const COLUMN_BLOB = "`%s` BLOB";
    const COLUMN_TIME = "`%s` TIME";
    const COLUMN_DATE = "`%s` DATE";
    const COLUMN_DATETIME = "`%s` DATETIME";
    const COLUMN_FLOAT = "`%s` FLOAT";
    const COLUMN_BINARY = "`%s` BINARY";
    const COLUMN_CHAR = "`%s` CHAR";
    const COLUMN_ENUM = "`%s` ENUM(%s)";
    const COLUMN_SET = "`%s` SET(%s)";

    const INDEX_INDEX = "INDEX `%s` ( %s )";
    const INDEX_UNIQUE = "UNIQUE `%s` ( %s )";
    const INDEX_FULLTEXT = "FULLTEXT INDEX `%s` ( %s )";

    const CONSTRAINT_FOREIGN_KEY = "FOREIGN KEY `%s` ( %s ) REFERENCES `%s` ( %s )";
    const CONSTRAINT_UNIQUE = "UNIQUE `%s` ( %s )";

    const FOREIGN_DELETE = "%s ON DELETE %s";
    const FOREIGN_UPDATE = "%s ON UPDATE %s";
    const FOREIGN_RESTRICT = "RESTRICT";
    const FOREIGN_CASCADE = "CASCADE";
    const FOREIGN_SET_NULL = "SET NULL";
    const FOREIGN_NO_ACTION = "NO ACTION";

    const SELECT_STATEMENT = "SELECT %s FROM `%s` %s %s %s";
    const SELECT_WHAT_QUOTE = '`%s`';
    const SELECT_AS = '`%s` AS `%s`';

    const WHERE = "WHERE %s ";
    const WHERE_EQUALS = "`%s` = '%s'";
    const WHERE_NULL = '`%s` IS NULL';
    const WHERE_NOT_NULL = '`%s` IS NOT NULL';
    const WHERE_AND = " AND ";
    const WHERE_OR = " OR ";
    const WHERE_GROUP = " ( %s ) ";

    const SELECT_ORDERBY = "ORDER BY %s ";
    const SELECT_ORDERBY_ASC = "`%s` ASC";
    const SELECT_ORDERBY_DESC = "`%s` DESC";
    const SELECT_LIMIT_OFFSET = "LIMIT %d,%d ";
    const SELECT_LIMIT_NO_OFFSET = "LIMIT %d ";

    const INSERT = "INSERT INTO `%s` (%s) VALUES %s";
    const INSERT_VALUES = "(%s)";
    const INSERT_COLUMN_QUOTE = '`%s`';
    const INSERT_VALUE_QUOTE = "'%s'";

    const UPDATE = "UPDATE `%s` SET %s %s %s";

    public function create_table($name, Array $columns, Array $options = NULL)
    {
        $column_defs = array();

        if(!in_array('no_id',$columns)){
            $column_defs[] = self::COLUMN_PRIMARY_KEY;
        }else{
            unset($columns[array_search('no_id',$columns)]);
        }

        foreach($columns as $c => $t)
        {
            // timestamps and special columns
            if(is_int($c)) {
                if('timestamps' == $t) {
                    $column_defs[] = self::COLUMN_TIMESTAMPS;
                } // if timestamps == $t
            // regular columns
            } else {
                // extended options
                if(is_array($t)){
                    $type = $t['type'];
                    $null = $t['null'] ? true : false;
                    if ( 0 != intval($t['size']) ) {
                        $size = intval($t['size']);
                    } else {
                        $size = in_array($type,array('string','varchar')) ? 50 : 16;
                    }
                    $values = $t['values'];
                    $unique = $t['unique'] ? true : false;
                    $default = $t['default'];
                // simple options
                } else {
                    $type = $t;
                    $null = false;
                    $size = in_array($type,array('string','varchar')) ? 50 : 16;
                    $values = '';
                    $unique = false;
                    $default = null;
                } // if is_array($t)

                $c_def = '';

                switch($type) {
                    case 'int':
                    case 'integer':
                    case 'number':
                        $c_def = sprintf(self::COLUMN_INT,$c,$size);
                        break;
                    case 'float':
                    case 'double':
                    case 'real':
                        $c_def = sprintf(self::COLUMN_FLOAT,$c,$size);
                        break;
                    case 'date':
                        $c_def = sprintf(self::COLUMN_DATE,$c);
                        break;
                    case 'time':
                        $c_def = sprintf(self::COLUMN_TIME,$c);
                        break;
                    case 'datetime':
                        $c_def = sprintf(self::COLUMN_DATETIME,$c);
                        break;
                    case 'text':
                        $c_def = sprintf(self::COLUMN_TEXT,$c);
                        break;
                    case 'string':
                    case 'varchar':
                    default:
                        $c_def = sprintf(self::COLUMN_VARCHAR,$c,$size);
                } // switch $type

                if($null){
                    $c_def=sprintf(self::COLUMN_NULL,$c_def);
                }else{
                    $c_def=sprintf(self::COLUMN_NOT_NULL,$c_def);
                }

                if($unique) {
                    $c_def=sprintf(self::COLUMN_UNIQUE,$c_def);
                }

                if($default){
                    $c_def=sprintf(self::COLUMN_DEFAULT,$c_def,$default);
                }

                $column_defs[] = $c_def;

            } // if/else is_int($c)

        } // foreach $columns

        // table options
        $table_opts = array();

        if($options){

        }

        return sprintf(self::TABLE_CREATE,
                       $name,
                       implode(',',$column_defs),
                       implode(' ',$table_opts));
    }

    /**
     * Generates a DROP TABLE statement.
     * @param string name of the table
     * @return string DROP TABLE SQL
     */
    public function drop_table ( $name )
    {
        return sprintf(self::TABLE_DROP,$name);
    }

    /**
     * Generates an ALTER TABLE statement
     */
    public function alter_table ()
    {
    }

    /**
     * Generates a WHERE clause, only used internally
     */
    protected function _where ( Array $where, $join = self::WHERE_AND )
    {

        $clause = array();

        foreach($where as $col=>$val){
            if(is_array($val)){
                switch($col){
                    case 'and':
                        $clause[] = sprintf(self::WHERE_GROUP,$this->_where($val,self::WHERE_AND));
                        break;
                    case 'or':
                        $clause[] = sprintf(self::WHERE_GROUP,$this->_where($val,self::WHERE_OR));
                        break;
                } // end switch
            }
            else { // !is_array($val)
                if('null'==strtolower($val)){
                    $clause[] = sprintf(self::WHERE_NULL,$col);
                }else if('notnull'==strtolower($val)){
                    $clause[] = sprintf(self::WHERE_NOT_NULL,$col);
                }else{
                    $clause[] = sprintf(self::WHERE_EQUALS,$col,$val);
                }
            }
        }

        return implode($join,$clause);
    }

    /**
     * Generates a SELECT statement
     */
    public function select ( $from, $what = '*', Array $where = NULL, Array $orderby = NULL, $limit = false )
    {
        if(is_array($what)){
            $what_clause=array();

            foreach($what as $k=>$v){
                if(is_numeric($k)){
                    if('*'==$v){
                        $what_clause[] = $v;
                    }else{
                        $what_clause[] = sprintf(self::SELECT_WHAT_QUOTE,$v);
                    }
                } else {
                    $what_clause[] = sprintf(self::SELECT_AS,$k,$v);
                }
            }
            $what = implode(',',$what_clause);
        }else if(!$what){
            $what = '*';
        }

        if(is_array($where)){
            $where = sprintf(self::WHERE,$this->_where($where));
        }

        if(is_array($orderby)){
            $orderby_clause = array();

            foreach($orderby as $k=>$v){
                if(is_numeric($k)){
                    $orderby_clause[] = sprintf(self::SELECT_ORDERBY_ASC,$v);
                }else if('desc'==strtolower($v)){
                    $orderby_clause[] = sprintf(self::SELECT_ORDERBY_DESC,$k);
                } else {
                    $orderby_clause[] = sprintf(self::SELECT_ORDERBY_ASC,$k);
                }
            }
            $orderby = implode(',',$orderby_clause);
        }
        if($orderby){
            $orderby = sprintf(self::SELECT_ORDERBY, $orderby);
        }

        if(is_array($limit)){
            $limit = sprintf(self::SELECT_LIMIT_OFFSET,$limit[0],$limit[1]);
        }else if($limit){
            $limit = sprintf(self::SELECT_LIMIT_NO_OFFSET,$limit);
        }

        return sprintf(self::SELECT_STATEMENT,$what,$from,$where,$orderby,$limit);
    }

    public function insert ( $into, $data )
    {
        if (is_array($data[0])) {
            $columns = array_keys($data[0]);
        } else {
            $columns = array_keys($data);
        }

        foreach($columns as &$col){
            $col = sprintf(self::INSERT_COLUMN_QUOTE,$col);
        }
        $columns = implode(',',$columns);

        if (is_array($data[0])) {
            foreach($data as &$row){
                $values = array_values($row);
                foreach($values as &$val){
                    $val = sprintf(self::INSERT_VALUE_QUOTE, $val);
                }
                $row = sprintf(self::INSERT_VALUES, implode(',', $values));
            }
            $rows = implode(',', $data);
        } else {
            $values = array_values($data);
            foreach($values as &$val){
                $val = sprintf(self::INSERT_VALUE_QUOTE,$val);
            }
            $rows = sprintf(self::INSERT_VALUES, implode(',',$values));
        }

        return sprintf(self::INSERT,$into,$columns,$rows);
    }

    public function update ( $table, Array $new_data, $where = NULL, $limit = NULL )
    {

        $columns = array();
        foreach($new_data as $col=>$val){
            $columns[] = sprintf(self::WHERE_EQUALS,$col,$val);
        }
        $columns = implode(',',$columns);

        if(is_array($where)){
            $where = sprintf(self::WHERE,$this->_where($where));
        }

        if(is_array($limit)){
            $limit = sprintf(self::SELECT_LIMIT_OFFSET,$limit[0],$limit[1]);
        }else if($limit){
            $limit = sprintf(self::SELECT_LIMIT_NO_OFFSET,$limit);
        }

        return sprintf(self::UPDATE,$table,$columns,$where,$limit);
    }
}
