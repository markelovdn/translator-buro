<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель доступности переводчика по дням недели
 * В БД day_of_week хранится в формате MySQL DAYOFWEEK: 1=Sunday, 2=Monday ... 7=Saturday
 *
 * @property int $id
 * @property int $translator_id
 * @property int $day_of_week 1=Sunday, 2=Monday ... 7=Saturday (MySQL DAYOFWEEK)
 * @property bool $is_available
 * @property int $created_at
 *
 * @property Translator $translator
 */
class TranslatorAvailability extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%translator_availability}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['translator_id', 'day_of_week'], 'required'],
            [['translator_id', 'day_of_week'], 'integer'],
            ['day_of_week', 'in', 'range' => [1, 2, 3, 4, 5, 6, 7]],
            ['is_available', 'boolean'],
            ['is_available', 'default', 'value' => true],
            [
                ['translator_id', 'day_of_week'],
                'unique',
                'targetAttribute' => ['translator_id', 'day_of_week'],
                'message' => 'Эта комбинация переводчика и дня недели уже существует.',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'translator_id' => 'Переводчик',
            'day_of_week' => 'День недели',
            'is_available' => 'Доступен',
            'created_at' => 'Создан',
        ];
    }

    public function getTranslator()
    {
        return $this->hasOne(Translator::class, ['id' => 'translator_id']);
    }

    public static function getDayNames(): array
    {
        return [
            1 => 'Воскресенье',
            2 => 'Понедельник',
            3 => 'Вторник',
            4 => 'Среда',
            5 => 'Четверг',
            6 => 'Пятница',
            7 => 'Суббота',
        ];
    }

    /**
     * Дни недели в порядке отображения (первый день недели из params) с признаком выходного.
     * @return array<int, array{day_of_week: int, name: string, is_weekend: bool}>
     */
    public static function getDaysForDisplay(): array
    {
        $names = self::getDayNames();
        $firstDay = (int) (\Yii::$app->params['firstDayOfWeek'] ?? 1);
        $weekendDays = (array) (\Yii::$app->params['weekendDays'] ?? [1, 7]);
        $order = $firstDay === 2 ? [2, 3, 4, 5, 6, 7, 1] : [1, 2, 3, 4, 5, 6, 7];
        $result = [];
        foreach ($order as $dayOfWeek) {
            $result[] = [
                'day_of_week' => $dayOfWeek,
                'name' => $names[$dayOfWeek] ?? '',
                'is_weekend' => in_array($dayOfWeek, $weekendDays, true),
            ];
        }
        return $result;
    }
}
