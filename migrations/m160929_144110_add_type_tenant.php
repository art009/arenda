<?php

use yii\db\Migration;
use app\models\Tenant;

class m160929_144110_add_type_tenant extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tenant}}','type_tenant',$this->integer(6)->comment('Тип арендатора'));

        $this->update(Tenant::tableName(),['type_tenant' => Tenant::TYPE_TENANT_ACTUAL],['type_tenant' => null]);
    }

    public function down()
    {
        $this->dropColumn('{{%tenant}}','type_tenant');
    }
}
