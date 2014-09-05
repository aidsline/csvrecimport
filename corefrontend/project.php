<?php
/**
 * User: John
 * Date: 20.01.14
 * Time: 21:57
 */


class dbGoDb {
    var $dbs;

    public function __construct() {
        $this->goDBsetup();
    }

    public static function goDBsetup() {
        \go\DB\autoloadRegister();
        $params = array(
            'host' => 'localhost',
            'username' => 'csvimport',
            'password' => 'CvbQ9UH1',
            'dbname' => 'csvimport',
            'charset' => 'utf8',
            '_debug' => false,
            '_prefix' => '',
        );
        $dbs = go\DB\DB::create($params, 'mysql');
        return $dbs;
    }

}
