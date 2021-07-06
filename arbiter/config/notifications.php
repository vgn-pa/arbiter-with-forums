<?php

return [

    /**
     * Currently only supports gmail
     */
    'email_notifications' => [

        'enabled' => env('PA_NOTIFICATION_EMAILS', 0),

        /**
         * PA email address
         */
        'pa_email' => 'support@planetarion.com',

        /**
         * The email address that collects email notification from PA
         */
        'email_address' => env('PA_NOTIFICATION_EMAIL_ADDRESS', ''),

        /**
         * The password of the email inbox that collects email notifications from PA
         */
        'email_password' => env('PA_NOTIFICATION_EMAIL_PASSWORD', ''),

    ],

];