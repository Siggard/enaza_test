<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,

    'nationals' => [
        \common\models\guests\RussianGuest::NATIONAL_CODE => \common\models\guests\RussianGuest::class,
        \common\models\guests\GermanGuest::NATIONAL_CODE => \common\models\guests\GermanGuest::class
    ],
    'testLogin' => 'test',
    'testPassword' => '12344321'
];
