<?php

use yii\db\Migration;

class m160918_143547_start_project extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица арендаторов
        $this->createTable('{{%tenant}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Организация или ИП'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица контактов
        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'type_contact' => $this->integer()->notNull()->comment('Тип контакта'),
            'value' => $this->string(100)->notNull()->comment('Контакт'),
            'tenant' => $this->integer()->notNull()->comment('Арендатор'),
            'description' => $this->string(255)->comment('Комментарий'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица типов контактов
        $this->createTable('{{%type_contact}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Название'),
            'parametr' => $this->string(255)->comment('Параметр'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица договоров
        $this->createTable('{{%agreement}}', [
            'id' => $this->primaryKey(),
            'tenant' => $this->integer()->notNull()->comment('Арендатор'),
            'date_from' => $this->integer()->notNull()->comment('Дата заключение договора'),
            'date_to' => $this->integer()->notNull()->comment('Дата окончания договора'),
            'quarters' => $this->integer()->notNull()->comment('Помещение'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица зданий
        $this->createTable('{{%building}}', [
            'id' => $this->primaryKey(),
            'city' => $this->string(255)->notNull()->comment('Город'),
            'street' => $this->string(255)->notNull()->comment('Улица'),
            'house' => $this->integer(5)->notNull()->comment('Номер дома'),
            'building' => $this->integer(5)->comment('Корпус'),
            'letter' => $this->integer(5)->comment('Литера'),
            'coordinates' => $this->string(255)->comment('Координаты'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица этажей
        $this->createTable('{{%floor}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string(100)->notNull()->comment('Название'),
            'building' => $this->integer()->notNull()->comment('Строение'),
            'map' => $this->string(255)->notNull()->comment('Схема размещения'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица помещений
        $this->createTable('{{%room}}', [
            'id' => $this->primaryKey(),
            'number_room' => $this->string(20)->notNull()->comment('Номер помещения'),
            'area_room' => $this->decimal(7,2)->comment('Площадь помещения'),
            'floor' => $this->integer()->notNull()->comment('Этаж'),
            'coordinates' => $this->integer()->notNull()->comment('Координаты'),
            'comment' => $this->string(255)->comment('Комментарий'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата измениния'),
            'created_by' => $this->integer()->notNull()->comment('Добавил'),
            'updated_by' => $this->integer()->notNull()->comment('Изменил'),
        ], $tableOptions);

        //таблица помещений
        $this->createTable('{{%room_coordinates}}', [
            'id' => $this->primaryKey(),
            'floor' => $this->integer()->notNull()->comment('Этаж'),
            'coordinates' => $this->string()->notNull()->comment('Координаты'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%tenant}}');
        $this->dropTable('{{%contact}}');
        $this->dropTable('{{%type_contact}}');
        $this->dropTable('{{%agreement}}');
        $this->dropTable('{{%building}}');
        $this->dropTable('{{%floor}}');
        $this->dropTable('{{%room}}');
        $this->dropTable('{{%room_coordinates}}');
    }
}
