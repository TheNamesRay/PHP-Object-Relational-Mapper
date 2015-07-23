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
 
class Database
{
    /**
     * Property: connection
     *
     * Where the PDO connection link
     * will be stored.
     */
    public static $connection;
    /**
     * Property: stmt
     *
     * Where the PDO statement
     * will be stored.
     */
    public static $stmt;

    /**
     * Constant: HOST
     * Constant: USER
     * Constant: PWD
     * Constant: DB
     *
     * The MySQL connection details.
     */    
    const HOST = '127.0.0.1';
    const USER = 'root';
    const PWD = '';
    const DB = 'store';
    /**
     * Function: load_database
     *
     * Create a link if not created
     * already.
     */    
    private static function load_database()
    {
        $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DB;
        self::$connection = new PDO($dsn, self::USER, self::PWD, array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }
    /**
     * Function: query
     *
     * Does a new query.
     */  
    public static function query($query)
    {
        if (!self::$connection) {
                    self::load_database();
        }
        self::$stmt = self::$connection->prepare($query);
		
        return new static;
    }
    /**
     * Function: bind
     *
     * Binds new keys into the 
     * PDOStatement.
     */  
    public static function bind($pos, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        self::$stmt->bindValue($pos, $value, $type);
        return new static;
    }
    /**
     * Function: execute
     *
     * Executes the statement.
     */  
    public static function execute()
    {
		return self::$stmt->execute();	
    }
    /**
     * Function: result_set
     *
     * PDOStatement::fetchAll()
     */  
    public static function result_set()
    {
        self::execute();
        return self::$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Function: single
     *
     * PDOStatement::fetch()
     */  
    public static function single()
    {
        self::execute();
        return self::$stmt->fetch(PDO::FETCH_ASSOC);
    }
}