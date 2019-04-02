<?php

namespace backend\modules\api\components;

/**
 * PrettyJsonResponse formatter for the `Api` module
 */
class PrettyJsonResponseFormatter extends \yii\web\JsonResponseFormatter
{
    /**
     * {@inheritdoc}
     */
    protected function formatJson($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');

        if ($response->data !== null) {
            $response->content = \yii\helpers\Json::encode($response->data, JSON_PRETTY_PRINT);
        }
    }
}
