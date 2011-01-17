<?php
/**
 * Insert Test for Phake_Translator_MySQL
 */

error_reporting(E_ALL ^ E_NOTICE);

require_once 'Phake_Translator_MySQL.php';
require_once 'PHPUnit/Framework.php';

class MySQL_Insert_Test extends PHPUnit_Framework_TestCase
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

    public function testBasicInsert()
    {
        $into = 'users';
        $data = array('name'=>'Tom');
        $expectedResult = "INSERT INTO `users` (`name`) VALUES ('Tom')";
        $this->assertEquals($expectedResult,self::ws($this->translator->insert($into,$data)));
    }

    public function testMultipleColumnInsert()
    {
        $into = 'users';
        $data = array('name'=>'Tom','password'=>'Pony','created'=>'now');
        $expectedResult = "INSERT INTO `users` (`name`,`password`,`created`) VALUES ('Tom','Pony','now')";
        $this->assertEquals($expectedResult,self::ws($this->translator->insert($into,$data)));
    }

    public function testMultipleRowInsert()
    {
        $into = 'users';
        $data = array(array('name'=>'Tom'), array('name'=>'John'));
        $expectedResult = "INSERT INTO `users` (`name`) VALUES ('Tom'),('John')";
        $this->assertEquals($expectedResult, self::ws($this->translator->insert($into, $data)));
    }
}
