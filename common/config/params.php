<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,

    'nationals' => [
        \common\models\RussianGuest::NATIONAL_CODE => \common\models\RussianGuest::class,
        \common\models\GermanGuest::NATIONAL_CODE => \common\models\GermanGuest::class
    ],
    'testLogin' => 'test',
    'testPassword' => '12344321'
];
