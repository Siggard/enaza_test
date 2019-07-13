<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'APPdW5XB5W4HvAiE5_aB90oS2_EDDDdW',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'enableSession' => false,
            'identityClass' => 'common\models\data\User',
            'enableAutoLogin' => false
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'guest',
                    'only' => ['options', 'view', 'index'],
                    'except' => ['create', 'update', 'delete'],
                    'pluralize' => false
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'club',
                    'only' => ['options', 'view', 'index', 'optimize', 'play'],
                    'except' => ['create', 'update', 'delete'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'PUT optimize' => 'optimize',
                        'PUT play' => 'play'
                    ]
                ]
            ],
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON
        ]
    ],
    'params' => $params,
];
