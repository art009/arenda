<?php

use yii\db\Migration;

class m161005_045329_agreement_modif extends Migration
{
    public function up()
    {
        $this->addColumn('{{%agreement}}','inner_number',$this->string(50)->comment('Внутренний номер'));
        $this->addColumn('{{%agreement}}','agreement_date',$this->integer()->comment('Дата заключения договора'));
    }

    public function down()
    {
        $this->dropColumn('{{%agreement}}','inner_number');
        $this->dropColumn('{{%agreement}}','agreement_date');
    }
}
