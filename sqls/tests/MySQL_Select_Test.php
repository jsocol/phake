<?php
/**
 * Select Test for Phake_Translator_MySQL
 */

error_reporting(E_ALL ^ E_NOTICE);

require_once 'Phake_Translator_MySQL.php';
require_once 'PHPUnit/Framework.php';

class MySQL_Select_Test extends PHPUnit_Framework_TestCase
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

    public function testBasicSelect()
    {
        $from  = 'users';
        $what  = array('*');
        $expectedResult = "SELECT * FROM `users`";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what)));
    }

    public function testSelectWithColumns()
    {
        $from = 'users';
        $what = array('username','password');
        $expectedResult = "SELECT `username`,`password` FROM `users`";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what)));
    }

    public function testSelectWithColumnsAs()
    {
        $from = 'users';
        $what = array('username' => 'login');
        $expectedResult = "SELECT `username` AS `login` FROM `users`";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what)));
    }

    public function testSelectWithMixedColumns()
    {
        $from = 'users';
        $what = array('username','password'=>'hash');
        $expectedResult = "SELECT `username`,`password` AS `hash` FROM `users`";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what)));
    }


    public function testSelectWithWhere()
    {
        $from = 'users';
        $what = '*';
        $where = array('username' => 'Tom');
        $expectedResult = "SELECT * FROM `users` WHERE `username` = 'Tom'";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testSelectWithMultipleWhere()
    {
        $from = 'users';
        $what = '*';
        $where = array('username'=>'Tom','year'=>2010);
        $expectedResult = "SELECT * FROM `users` WHERE `username` = 'Tom' AND `year` = '2010'";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testSelectWithOrderBy()
    {
        $from = 'users';
        $what = '*';
        $orderby = array('created');
        $expectedResult = "SELECT * FROM `users` ORDER BY `created` ASC";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,$orderby)));
    }

    public function testSelectWithOrderByDesc()
    {
        $from = 'users';
        $what = '*';
        $orderby = array('created'=>'desc');
        $expectedResult = "SELECT * FROM `users` ORDER BY `created` DESC";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,$orderby)));
    }

    public function testSelectWithMultipleOrderBy()
    {
        $from = 'users';
        $what = '*';
        $orderby = array('created','updated');
        $expectedResult = "SELECT * FROM `users` ORDER BY `created` ASC,`updated` ASC";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,$orderby)));
    }

    public function testSelectWithMixedOrderBy()
    {
        $from = 'users';
        $what = '*';
        $orderby = array('created','updated'=>'desc');
        $expectedResult = "SELECT * FROM `users` ORDER BY `created` ASC,`updated` DESC";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,$orderby)));
    }

    public function testSelectWithSimpleLimit()
    {
        $from = 'users';
        $what = '*';
        $limit = 10;
        $expectedResult = "SELECT * FROM `users` LIMIT 10";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,null,$limit)));
    }

    public function testSelectWithOffsetLimit()
    {
        $from = 'users';
        $what = '*';
        $limit = array(10,20);
        $expectedResult = "SELECT * FROM `users` LIMIT 10,20";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,null,null,$limit)));
    }

    public function testSelectWithNoWhat()
    {
        $from = 'users';
        $expectedResult = "SELECT * FROM `users`";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from)));
    }

    public function testSelectNull()
    {
        $from = 'users';
        $what = false;
        $where = array('email'=>'null');
        $expectedResult = "SELECT * FROM `users` WHERE `email` IS NULL";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testSelectNotNull()
    {
        $from = 'users';
        $what = false;
        $where = array('email'=>'notnull');
        $expectedResult = "SELECT * FROM `users` WHERE `email` IS NOT NULL";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testSelectOrderByAsc()
    {
        $from = 'users';
        $what = '*';
        $orderby = array('created'=>'asc');
        $expectedResult = "SELECT * FROM `users` ORDER BY `created` ASC";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,NULL,$orderby)));
    }

    public function testSelectAdvanced1()
    {
        $from = 'users';
        $what = array('name','username'=>'login','password');
        $where = array('id'=>1,'username'=>'Tom');
        $orderby = array('created','updated'=>'desc');
        $limit = array(4,10);
        $expectedResult = "SELECT `name`,`username` AS `login`,`password` FROM `users` WHERE `id` = '1' AND `username` = 'Tom' ORDER BY `created` ASC,`updated` DESC LIMIT 4,10";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where,$orderby,$limit)));
    }
}
