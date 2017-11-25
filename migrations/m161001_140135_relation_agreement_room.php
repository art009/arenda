<?php

use yii\db\Migration;
use yii\db\Query;

class m161001_140135_relation_agreement_room extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // соотношение договора и помещение
        $this->createTable('{{%agreement2quarter}}', [
            'agreement_id' => $this->integer()->notNull()->comment('Договор'),
            'quarter_id' => $this->integer()->notNull()->comment('Помещение'),
            'floor_id' => $this->integer()->notNull()->comment('Этаж'),
            'building_id' => $this->integer()->notNull()->comment('Здание'),
        ], $tableOptions);

        // ключ таблицы
        $this->addPrimaryKey('agreement2quarter_pk', '{{%agreement2quarter}}', ['agreement_id', 'quarter_id']);
        // перенесем все записи из таблицы договоров
        $models = \app\models\Agreement::find()->all();
        foreach ($models as $model) {
            $quart = \app\models\Room::findOne([$model->quarters]);
            $this->insert('{{%agreement2quarter}}',[
                'agreement_id' => $model->id,
                'quarter_id' => $model->quarters,
                'floor_id' => $quart->floor,
                'building_id' => $quart->building,
            ]);
        }

        // удаляем колонку помещения из таблицы
        $this->dropColumn('{{%agreement}}','quarters');

        // таблица обучения
        $this->createTable('{{%training}}', [
            'id' => $this->primaryKey()->comment('ИД'),
            'video' => $this->string()->notNull()->comment('Ссылка на YouTube'),
            'title' => $this->string()->notNull()->comment('Название'),
            'description' => $this->text()->comment('Описание'),
        ], $tableOptions);
    }

    public function down()
    {
        // вернем колонку помещения
        $this->addColumn('{{%agreement}}','quarters',$this->integer()->notNull()->comment('Помещение'));

        // перенесем таблицу
        $models = (new Query())
            ->select(['agreement_id', 'quarter_id'])
            ->from('{{%agreement2quarter}}')
            ->all();

        foreach ($models as $model) {
            $this->update('{{%agreement}}',['quarters' => $model['quarter_id']],['id' => $model['agreement_id']]);
        }

        // удалим таблицу связи
        $this->dropTable('{{%agreement2quarter}}');
        // удалим таблицу обучения
        $this->dropTable('{{%training}}');
    }
}
