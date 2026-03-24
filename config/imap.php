<?php

return [

    /*
    |--------------------------------------------------------------------------
    | IMAP default account
    |--------------------------------------------------------------------------
    |
    | The default account identifier. It will be used as default for any
    | communication if no account name was provided.
    |
    */
    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Available IMAP accounts
    |--------------------------------------------------------------------------
    |
    | Multi-account support. You can provide as many accounts as you like.
    |
    */
    'accounts' => [

        'default' => [
            'host' => env('IMAP_HOST', 'outlook.office365.com'),
            'port' => env('IMAP_PORT', 993),
            'protocol' => env('IMAP_PROTOCOL', 'imap'),
            'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
            'validate_cert' => true,
            'username' => env('IMAP_USERNAME', 'support@elasto.lk'),
            'password' => env('IMAP_PASSWORD', ''),
            'authentication' => null,
            'proxy' => [
                'socket' => null,
                'request_fulluri' => false,
                'username' => null,
                'password' => null,
            ]
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Available IMAP options
    |--------------------------------------------------------------------------
    |
    | Check out http://php.net/manual/en/function.imap-open.php for all options.
    |
    */
    'options' => [
        'delimiter' => '/',
        'fetch' => 1, // \Webklex\PHPIMAP\IMAP::FT_UID
        'sequence' => 0, // \Webklex\PHPIMAP\IMAP::ST_MSGN,
        'fetch_order' => 'desc',
        'open' => [
            // 'DISABLE_AUTHENTICATOR' => 'GSSAPI'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Flags
    |--------------------------------------------------------------------------
    |
    | List all flags you want to use.
    |
    */
    'flags' => ['recent', 'flagged', 'answered', 'deleted', 'seen', 'draft'],

    /*
    |--------------------------------------------------------------------------
    | File mask
    |--------------------------------------------------------------------------
    |
    | The file mask for the attachment directory.
    |
    */
    'file_mask' => 0775,

];
