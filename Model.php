<?php

/**
 * Lightweight PHP ORM
 * @version 1.0
 *
 * @author  Kedi Agbogre <kedi97@gmail.com>
 * @skype   ThePoliticallyCorrect
 * @php     5.3 or greater
 * @license GNU GPL
 */
 
class Model
{
    /**
     * Property: dbh
     * Type: object
     *
     * The database connection.
     */
    private $_dbh;
    /**
     * Property: data
     * Type: object
     *
     * All the table's contents should be 
     * stored here.
     */
    public $data;
    /**
     * Property: validation
     * Type: closure
     *
     * Should contain a closure which should
     * contain different conditionals to validate
     * the data, return false if failed.
     */
    public $validation;
    /**
     * Property: _table
     * Type: string
     *
     * The table the ORM will work with
     */
    private $_table;
    /**
     * Function: __construct
     *
     * Set all the default values and 
     * the requested table name.
     *
     * @param $p Table Name
     */    
    public function __construct($p,$dbh)
    {
        $this->data   = new StdClass();
        $this->validation = function(){};
        $this->_table = $p;
        $this->_dbh = $dbh;
    }
    /**
     * Function: load
     *
     * Should be used for SELECT & UPDATE 
     * queries only. It loads all the data.
     * 
     * @param $q Identifier
     * @param $r Identifier Value
     * @param $s What to Select (separated by commas, optional)
     *
     * @example load('id',$_SESSION['user_id'],'username, like_count, password')
     */   
    public function load($q, $r, $s = '*')
    {
        $t = "SELECT $s FROM {$this->_table} WHERE $q = ?";
        $u = $this->_dbh->query($t)->bind(1, $r)->single();
        $this->data = (object) $u;
        return $u ? true : false;
    }
    /**
     * Function: delete
     *
     * Deletes a record from the database.
     * You have to set the identifier to 
     * delete that particular record.
     *
     * @param $v Identifier
     *
     * @example $m=new Model('users');
     * @example $m->data->username='John';
     * @example $m->delete('username');
     */   
    public function delete($v)
    {
        $t = "DELETE FROM {$this->_table}";
        $t .= " WHERE $v = :where_$v";
        $u = $this->_dbh->query($t);
        $u->bind("where_$v",$this->data->$v);
        $u->execute();
    }
    /**
     * Function: save
     *
     * Executes an UPDATE or SELECT query.
     *
     * @param $v Identifier (optional)
     *
     * @example $m=new Model('users');
     * @example $m->data->username='John';
     * @example $m->delete('username');
     */   
    public function save($v = null)
    {
        // run the validation
        $q = $this->validation;
        if ($q() === false)
            return false;
 
        // get data as array
        $w = get_object_vars($this->data);
        $y = '';
        $j = '';
        
        // if identifier, we assume it's an UPDATE
        if (null !== $v) {
            foreach ($w as $z => $aa) {
                if (end($w) !== $aa) {
                    $y .= "$z = :update_$z, ";
                } else {
                    $y .= "$z = :update_$z";
                }
            }
            $t = "UPDATE {$this->_table} SET $y";
            $t .= " WHERE $v = :where_$v";
            $u = $this->_dbh->query($t);
            $u->bind("where_$v",$this->data->$v);
            foreach ($w as $z => $aa) {
                $u->bind('update_' . $z, $aa);
            }
        // if NO identifier, we assume it's an INSERT
        } else {
            foreach ($w as $z => $aa) {
                if (end($w) !== $aa) {
                    $y .= ":insert_$z, ";
                    $j .= $z . ', ';
                } else {
                    $j .= $z;
                    $y .= ":insert_$z";
                }
            }
            $t = "INSERT INTO {$this->_table}($j) VALUES ($y)";
            $u = $this->_dbh->query($t);
            foreach ($w as $z => $aa) {
                $u->bind('insert_' . $z, $aa);
            }
        }
        // execute the final query
        $u->execute();
        return true;
    }
}
