<?php

/**
 * A laravel package for bkash payment gateway
 * @author Pranay Chakraborty <pranaycb.ctg@gmail.com>
 * @link https://github.com/pranaycb
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Bkash gateway environment
    |--------------------------------------------------------------------------
    |
    | This value is the environment for your bkash gateway environment.
    | Supported two environment: sandbox (for demo) and production (for live)
    |
    */
    'environment' => env('BKASH_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Bkash app key
    |--------------------------------------------------------------------------
    */
    'app_key' => env('BKASH_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Bkash app secret
    |--------------------------------------------------------------------------
    */
    'app_secret' => env('BKASH_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Bkash username
    |--------------------------------------------------------------------------
    */
    'username' => env('BKASH_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | Bkash password
    |--------------------------------------------------------------------------
    */
    'password' => env('BKASH_PASSWORD'),
];
