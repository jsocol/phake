<?php
class CreateWidgets extends PhakeMigration
{

    public function up ()
    {
        create_table('widgets',array(
            'user_id'=>'int',
            'name'=>array('type'=>'string','size'=>255),
            'timestamps'));
    }

    public function down ()
    {
        drop_table('widgets');
    }
}
