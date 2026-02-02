<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель переводчика
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property bool $is_active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TranslatorAvailability[] $availabilities
 */
class Translator extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%translator}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['is_active', 'boolean'],
            ['is_active', 'default', 'value' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'is_active' => 'Активен',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
        ];
    }

    public function getAvailabilities()
    {
        return $this->hasMany(TranslatorAvailability::class, ['translator_id' => 'id']);
    }

    /**
     * Список id, name, email доступных на дату переводчиков (для API).
     * @param string $date Дата в формате Y-m-d
     * @return array<int, array{id: int, name: string, email: string|null}>
     */
    public static function listAvailableForDate(string $date): array
    {
        return self::find()
            ->alias('t')
            ->select(['t.id', 't.name', 't.email'])
            ->innerJoin('translator_availability a', 'a.translator_id = t.id')
            ->where(['t.is_active' => 1, 'a.is_available' => 1])
            ->andWhere('a.day_of_week = DAYOFWEEK(:date)', [':date' => $date])
            ->orderBy('t.name')
            ->asArray()
            ->all();
    }

    /**
     * Количество активных переводчиков, доступных на указанную дату.
     * @param string $date Дата в формате Y-m-d
     */
    public static function countAvailableForDate(string $date): int
    {
        return (int) self::find()
            ->alias('t')
            ->innerJoin('translator_availability a', 'a.translator_id = t.id')
            ->where(['t.is_active' => 1, 'a.is_available' => 1])
            ->andWhere('a.day_of_week = DAYOFWEEK(:date)', [':date' => $date])
            ->count();
    }
}
