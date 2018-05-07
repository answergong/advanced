<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        //富文本编辑器
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => 'backend/web/uploads',  // 上传目录,比如这里可以填写 ./uploads
            'uploadUrl' => 'backend/web/uploads',
            'imageAllowExtensions' => ['jpg', 'png', 'gif']
        ],
        //......
    ],
    'aliases' => [
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'language' => 'zh-CN',
//    'charset' => 'utf-8',
//    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\UserBackend',
            'enableAutoLogin' => true,
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-green-light',
                ],
            ],
        ],
        //语言包配置
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
//                        'rbac-admin' => 'rbac-admin.php' //可以加多个，是yii::t里面的第一个参数名
                    ],
                    'sourceLanguage' => 'en',
                    'basePath' => '@common/message', //配置语言文件路径，现在采用默认的，就可以不配置这个
                ],
            ],
        ],
        //静态配置主题
//        'view' => [
//            'theme' => [
//                // 'basePath' => '@app/themes/spring',
//                // 'baseUrl' => '@web/themes/spring',
//                'pathMap' => [
//                    '@app/views' => [
//                        '@app/themes/christmas',
//                        '@app/themes/spring',
//                    ]
//                ],
//            ],
//        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    //动态配置主题(通过开关来配置  backend/components/themecontrol.php)
    'as theme' => [
        'class' => 'backend\components\ThemeControl',
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //这里是允许访问的action，不受权限控制
            //controller/action
//            'site/*',
            '*',//允许所有人访问admin节点及其子节点
        ]
    ],
    'params' => $params,
];
