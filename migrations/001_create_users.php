<?php
class CreateUsers extends PhakeMigration
{
    public function up ()
    {

        create_table('users',array(
            'username' => array('type'=>'string','unique'=>true),
            'nickname' => 'string',
            'password' => array('type'=>'string','size'=>100),
            'email' => array('type'=>'string','size'=>100),
            'timestamps')
        );

    }

    public function down ()
    {

        drop_table('users');

    }
}
