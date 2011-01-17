<?php
/**
 * Insert Test for Phake_Translator_MySQL
 */

error_reporting(E_ALL ^ E_NOTICE);

require_once 'Phake_Translator_MySQL.php';
require_once 'PHPUnit/Framework.php';

class MySQL_Where_Test extends PHPUnit_Framework_TestCase
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

    public function testWhereBasicSelect()
    {
        $from = 'users';
        $what = '*';
        $where = array('name'=>'Tom');
        $expectedResult = "SELECT * FROM `users` WHERE `name` = 'Tom'";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testWhereOrGroupSelect()
    {
        $from = 'users';
        $what = '*';
        $where = array('or'=>array('name'=>'Tom','email'=>'tom@tom.com'));
        $expectedResult = "SELECT * FROM `users` WHERE ( `name` = 'Tom' OR `email` = 'tom@tom.com' )";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testWhereNestedGroupSelect()
    {
        $from = 'users';
        $what = '*';
        $where = array('name'=>'Tom','or'=>array('dog'=>'small','cat'=>'large'));
        $expectedResult = "SELECT * FROM `users` WHERE `name` = 'Tom' AND ( `dog` = 'small' OR `cat` = 'large' )";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }

    public function testWhereTwoNestedGroupsSelect()
    {
        $from = 'users';
        $what = '*';
        $where = array(
            'or'=>array(
                'name'=>'Tom',
                'or'=>array(
                    'cat'=>'small',
                    'dog'=>'large'),
                'and'=>array(
                    'house'=>'boat',
                    'shoe'=>'foot'),
            ),
        );
        $expectedResult = "SELECT * FROM `users` WHERE ( `name` = 'Tom' OR ( `cat` = 'small' OR `dog` = 'large' ) OR ( `house` = 'boat' AND `shoe` = 'foot' ) )";
        $this->assertEquals($expectedResult,self::ws($this->translator->select($from,$what,$where)));
    }
}
