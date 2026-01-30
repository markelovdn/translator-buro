<?php

namespace common\services;

use Yii;
use common\models\Translator;
use yii\db\Exception as DbException;

class TranslatorAvailabilityService
{
    public function getTranslatorsResponse(string $date): array
    {
        if (!$this->validateDate($date)) {
            return [
                400,
                [
                    'error' => 'Неверный формат даты',
                    'message' => 'Дата должна быть в формате YYYY-MM-DD',
                ],
            ];
        }

        try {
            $items = Translator::listAvailableForDate($date);
            return [
                200,
                [
                    'date' => $date,
                    'count' => count($items),
                    'items' => $items,
                ],
            ];
        } catch (DbException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                500,
                [
                    'error' => 'Ошибка базы данных',
                    'message' => YII_DEBUG ? $e->getMessage() : 'Не удалось получить данные',
                ],
            ];
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                500,
                [
                    'error' => 'Внутренняя ошибка сервера',
                    'message' => YII_DEBUG ? $e->getMessage() : 'Произошла ошибка',
                ],
            ];
        }
    }

    public function getStatusResponse(string $date): array
    {
        if (!$this->validateDate($date)) {
            return [
                400,
                [
                    'error' => 'Неверный формат даты',
                    'message' => 'Дата должна быть в формате YYYY-MM-DD',
                ],
            ];
        }

        try {
            $count = Translator::countAvailableForDate($date);
            return [
                200,
                [
                    'date' => $date,
                    'message' => $count > 0 ? 'Список переводчиков готов' : 'Нет свободных переводчиков',
                ],
            ];
        } catch (DbException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                500,
                [
                    'error' => 'Ошибка базы данных',
                    'message' => YII_DEBUG ? $e->getMessage() : 'Не удалось получить данные',
                ],
            ];
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                500,
                [
                    'error' => 'Внутренняя ошибка сервера',
                    'message' => YII_DEBUG ? $e->getMessage() : 'Произошла ошибка',
                ],
            ];
        }
    }

    private function validateDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
