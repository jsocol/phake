<?php
/**
 * Update Test for Phake_Translator_MySQL
 */

error_reporting(E_ALL ^ E_NOTICE);

require_once 'Phake_Translator_MySQL.php';
require_once 'PHPUnit/Framework.php';

class MySQL_Update_Test extends PHPUnit_Framework_TestCase
{

    public static function ws ( $string )
    {
        $string = preg_replace('/\s{2,}/',' ',$string);
        return trim($string);
    }

    public function setUp()
    {
        $this->translator = new Phake_Translator_MySQL;
    }

    public function testBasicUpdate()
    {
        $into = 'users';
        $data = array('name'=>'Tom');
        $expectedResult = "UPDATE `users` SET `name` = 'Tom'";
        $this->assertEquals($expectedResult,self::ws($this->translator->update($into,$data)));
    }

    public function testUpdateMultipleColumns()
    {
        $table = 'users';
        $data = array('name'=>'Tom','job'=>'boxer');
        $expectedResult = "UPDATE `users` SET `name` = 'Tom',`job` = 'boxer'";
        $this->assertEquals($expectedResult,self::ws($this->translator->update($table,$data)));
    }

    public function testUpdateWhere()
    {
        $table = 'users';
        $data = array('password'=>'Pony');
        $where = array('name'=>'Tom');
        $expectedResult = "UPDATE `users` SET `password` = 'Pony' WHERE `name` = 'Tom'";
        $this->assertEquals($expectedResult,self::ws($this->translator->update($table,$data,$where)));
    }

    public function testUpdateWhereSimpleLimit()
    {
        $table = 'users';
        $data = array('password'=>'Pony');
        $where = array('name'=>'Tom');
        $limit = 1;
        $expectedResult = "UPDATE `users` SET `password` = 'Pony' WHERE `name` = 'Tom' LIMIT 1";
        $this->assertEquals($expectedResult,self::ws($this->translator->update($table,$data,$where,$limit)));
    }

    public function testUpdateWhereOffsetLimit()
    {
        $table = 'users';
        $data = array('password'=>'Pony');
        $where = array('name'=>'Tom');
        $limit = array(10,30);
        $expectedResult = "UPDATE `users` SET `password` = 'Pony' WHERE `name` = 'Tom' LIMIT 10,30";
        $this->assertEquals($expectedResult,self::ws($this->translator->update($table,$data,$where,$limit)));
    }

}
