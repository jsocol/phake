<?php
/**
 * CreateTableTest for Phake_Translator_MySQL
 */

error_reporting(E_ALL ^ E_NOTICE);

require_once 'Phake_Translator_MySQL.php';
require_once 'PHPUnit/Framework.php';

class MySQL_Create_Table_Test extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->translator = new Phake_Translator_MySQL;
    }

    public function testBasicCreateTable()
    {
        $tableStructure = array('name'=>'string');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` VARCHAR(50) NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testBasicDropTable()
    {
        $expectedResult = "DROP TABLE IF EXISTS `basic`";
        $this->assertEquals($expectedResult,$this->translator->drop_table('basic'));
    }

    public function testCreateTableWithNullColumn()
    {
        $tableStructure = array('name'=>'string',
                                'email'=>array('type'=>'string','null'=>true));
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` VARCHAR(50) NOT NULL,`email` VARCHAR(50) NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithColumnSize()
    {
        $tableStructure = array('name'=>'string',
                                'email'=>array('type'=>'string','size'=>100));
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` VARCHAR(50) NOT NULL,`email` VARCHAR(100) NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithTextColumn()
    {
        $tableStructure = array('content'=>'text');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`content` TEXT NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithDateColumn()
    {
        $tableStructure = array('created'=>'date');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`created` DATE NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithDateTimeColumn()
    {
        $tableStructure = array('created'=>'datetime');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`created` DATETIME NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithTimeColumn()
    {
        $tableStructure = array('created'=>'time');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`created` TIME NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithUniqueColumn()
    {
        $tableStructure = array('username'=>array('type'=>'string','unique'=>true));
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`username` VARCHAR(50) NOT NULL UNIQUE ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithTimestamps()
    {
        $tableStructure = array('timestamps');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `basic` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,created INT(20) NOT NULL DEFAULT 0, updated INT(20) NOT NULL DEFAULT 0 ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('basic',$tableStructure));
    }

    public function testCreateTableWithoutId()
    {
        $tableStructure = array('group_id'=>'int','user_id'=>'int','no_id');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `groups_users` ( `group_id` INT(16) NOT NULL,`user_id` INT(16) NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('groups_users',$tableStructure));
    }

    public function testCreateTableWithFloat()
    {
        $tableStructure = array('scale' => 'float');
        $expectedResult = "CREATE TABLE IF NOT EXISTS `things` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`scale` FLOAT NOT NULL ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('things',$tableStructure));
    }

    public function testCreateTableWithDefault()
    {
        $tableStructure = array('cats'=>array('type'=>'int','default'=>12));
        $expectedResult = "CREATE TABLE IF NOT EXISTS `cat_ladies` ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`cats` INT(16) NOT NULL DEFAULT '12' ) ";
        $this->assertEquals($expectedResult,$this->translator->create_table('cat_ladies',$tableStructure));
    }

    public function tearDown()
    {
        $this->translator = null;
    }
}
