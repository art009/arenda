<?php

use yii\db\Migration;

class m160922_114125_modif_room extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room}}','number_bti',$this->string(20)->comment('Номер БТИ'));
        $this->addColumn('{{%floor}}','number_velel',$this->integer(3)->notNull()->comment('Порядковый номер этажа'));
    }

    public function down()
    {
        $this->dropColumn('{{%room}}','number_bti');
        $this->dropColumn('{{%floor}}','number_velel');
    }
}
