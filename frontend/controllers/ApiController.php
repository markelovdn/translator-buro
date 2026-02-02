<?php

namespace frontend\controllers;

use Yii;
use common\services\TranslatorAvailabilityService;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->charset = 'UTF-8';
        return parent::beforeAction($action);
    }

    public function actionTranslators(string $date = null): array
    {
        $date = $date ?: date('Y-m-d');
        $service = Yii::createObject(TranslatorAvailabilityService::class);
        [$code, $body] = $service->getTranslatorsResponse($date);
        Yii::$app->response->statusCode = $code;
        return $body;
    }

    public function actionStatus(string $date = null): array
    {
        $date = $date ?: date('Y-m-d');
        $service = Yii::createObject(TranslatorAvailabilityService::class);
        [$code, $body] = $service->getStatusResponse($date);
        Yii::$app->response->statusCode = $code;
        return $body;
    }
}
