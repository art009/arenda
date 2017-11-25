<?php

use yii\db\Migration;
use yii\rbac\Item;
use app\models\Agreement;
use app\models\Building;
use app\models\Contact;
use app\models\Floor;
use app\models\Room;
use app\models\Tenant;
use app\models\TypeContact;
use app\models\Training;

class m161003_061858_role_list extends Migration
{
    public function up()
    {
        // управление договорами
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Agreement::ROLE_AGREEMENT_INDEX, Item::TYPE_PERMISSION, 'Доступ к списоку договоров', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_CREATE, Item::TYPE_PERMISSION, 'Добавление договора', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_UPDATE, Item::TYPE_PERMISSION, 'Изменить договора', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_DELETE, Item::TYPE_PERMISSION, 'Удалить договора', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_MAP, Item::TYPE_PERMISSION, 'Карта с арендованными площадями', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_INFO, Item::TYPE_PERMISSION, 'Информация с договорами по помещению', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_ADD_QUARTERS, Item::TYPE_PERMISSION, 'Добавление помещения к договору', NULL, time(), time()],
            [Agreement::ROLE_AGREEMENT_DELETE_QUARTERS, Item::TYPE_PERMISSION, 'Удаление помещения из договора', NULL, time(), time()],
        ]);

        // управление зданиями
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Building::ROLE_BUILDING_INDEX, Item::TYPE_PERMISSION, 'Доступ к список зданий', NULL, time(), time()],
            [Building::ROLE_BUILDING_CREATE, Item::TYPE_PERMISSION, 'Добавление здания', NULL, time(), time()],
            [Building::ROLE_BUILDING_UPDATE, Item::TYPE_PERMISSION, 'Изменить здания', NULL, time(), time()],
            [Building::ROLE_BUILDING_DELETE, Item::TYPE_PERMISSION, 'Удалить здания', NULL, time(), time()],
        ]);

        // управление котактами
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Contact::ROLE_CONTACT_CREATE, Item::TYPE_PERMISSION, 'Права на добавление контактной информации', NULL, time(), time()],
            [Contact::ROLE_CONTACT_UPDATE, Item::TYPE_PERMISSION, 'Права на изменение контактной информации', NULL, time(), time()],
            [Contact::ROLE_CONTACT_DELETE, Item::TYPE_PERMISSION, 'Права на удаление контактной информации', NULL, time(), time()],
        ]);

        // управление записями по этажам
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Floor::ROLE_FLOOR_INDEX, Item::TYPE_PERMISSION, 'Права на отображения списка этажей в здание', NULL, time(), time()],
            [Floor::ROLE_FLOOR_CREATE, Item::TYPE_PERMISSION, 'Права на добавление этаджа', NULL, time(), time()],
            [Floor::ROLE_FLOOR_UPDATE, Item::TYPE_PERMISSION, 'Права на изменение информации об этаже', NULL, time(), time()],
            [Floor::ROLE_FLOOR_DELETE, Item::TYPE_PERMISSION, 'Права на удаление информации об этаже', NULL, time(), time()],
            [Floor::ROLE_FLOOR_MAP, Item::TYPE_PERMISSION, 'Права на редактирование карты этажа', NULL, time(), time()],
            [Floor::ROLE_FLOOR_TABLE_FLOOR, Item::TYPE_PERMISSION, 'Права на виджет этажей', NULL, time(), time()],
        ]);

        // управление помещениями
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Room::ROLE_ROOM_CREATE, Item::TYPE_PERMISSION, 'Права на добавление информации об офиса', NULL, time(), time()],
            [Room::ROLE_ROOM_UPDATE, Item::TYPE_PERMISSION, 'Права на изменение информации об офисе', NULL, time(), time()],
            [Room::ROLE_ROOM_DELETE, Item::TYPE_PERMISSION, 'Права на удаление информации об офисе', NULL, time(), time()],
        ]);

        // управление арендаторами
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Tenant::ROLE_TENANT_INDEX, Item::TYPE_PERMISSION, 'Права на отображение списков арендаторов', NULL, time(), time()],
            [Tenant::ROLE_TENANT_CREATE, Item::TYPE_PERMISSION, 'Права на добавление информации арендаторе', NULL, time(), time()],
            [Tenant::ROLE_TENANT_UPDATE, Item::TYPE_PERMISSION, 'Права на изменение информации об арендаторе', NULL, time(), time()],
            [Tenant::ROLE_TENANT_DELETE, Item::TYPE_PERMISSION, 'Права на удаление информации об арендаторе', NULL, time(), time()],
            [Tenant::ROLE_TENANT_CONTACT_TABLE, Item::TYPE_PERMISSION, 'Права на отображение контактной информации', NULL, time(), time()],
        ]);

        // управление типами контакта
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [TypeContact::ROLE_TYPE_CONTACT_INDEX, Item::TYPE_PERMISSION, 'Права на отображение типов контактов', NULL, time(), time()],
            [TypeContact::ROLE_TYPE_CONTACT_CREATE, Item::TYPE_PERMISSION, 'Права на добавление типа контакта', NULL, time(), time()],
            [TypeContact::ROLE_TYPE_CONTACT_UPDATE, Item::TYPE_PERMISSION, 'Права на изменение типа контакта', NULL, time(), time()],
            [TypeContact::ROLE_TYPE_CONTACT_DELETE, Item::TYPE_PERMISSION, 'Права на удаление типа контакта', NULL, time(), time()],
        ]);

        // управление обучающими материалами
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            [Training::ROLE_TRAINING_CONTROLL, Item::TYPE_PERMISSION, 'Права на управление материалами обучения', NULL, time(), time()],
            [Training::ROLE_TRAINING_INDEX, Item::TYPE_PERMISSION, 'Права на просмотр материалов по обучению', NULL, time(), time()],
        ]);
    }

    public function down()
    {
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_DELETE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_MAP]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_INFO]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_ADD_QUARTERS]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Agreement::ROLE_AGREEMENT_DELETE_QUARTERS]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Building::ROLE_BUILDING_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Building::ROLE_BUILDING_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Building::ROLE_BUILDING_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Building::ROLE_BUILDING_DELETE]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Contact::ROLE_CONTACT_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Contact::ROLE_CONTACT_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Contact::ROLE_CONTACT_DELETE]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_DELETE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_MAP]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Floor::ROLE_FLOOR_TABLE_FLOOR]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Room::ROLE_ROOM_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Room::ROLE_ROOM_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Room::ROLE_ROOM_DELETE]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Tenant::ROLE_TENANT_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Tenant::ROLE_TENANT_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Tenant::ROLE_TENANT_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Tenant::ROLE_TENANT_DELETE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Tenant::ROLE_TENANT_CONTACT_TABLE]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>TypeContact::ROLE_TYPE_CONTACT_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>TypeContact::ROLE_TYPE_CONTACT_CREATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>TypeContact::ROLE_TYPE_CONTACT_UPDATE]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>TypeContact::ROLE_TYPE_CONTACT_DELETE]);

        $this->delete('{{%auth_item}}','name = :name',[':name'=>Training::ROLE_TRAINING_INDEX]);
        $this->delete('{{%auth_item}}','name = :name',[':name'=>Training::ROLE_TRAINING_CONTROLL]);
    }
}
