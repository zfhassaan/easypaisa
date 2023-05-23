<?php

return [
    'mode'=>env('EASYPAISA_MODE'),
    'type'=>env('EASYPAISA_TYPE'),
    'callback'=> env('EASYPAISA_CALLBACK_URL'),

    'sandbox_url'=>env('EASYPAISA_SANDBOX_URL'),
    'sandbox_username'=> env('EASYPAISA_SANDBOX_USERNAME'),
    'sandbox_password'=>env('EASYPAISA_SANDBOX_PASSWORD'),
    'sandbox_storeid'=>env('EASYPAISA_SANDBOX_STOREID'),
    'sandbox_hashkey'=> env('EASYPAISA_SANDBOX_HASHKEY'),

    'prod_username'=> env('EASYPAISA_PRODUCTION_USERNAME'),
    'prod_password'=>env('EASYPAISA_PRODUCTION_PASSWORD'),
    'prod_storeid'=> env('EASYPAISA_PRODUCTION_STOREID'),
    'prod_hashkey'=> env('EASYPAISA_PRODUCTION_HASHKEY'),
    'prod_url'=> env('EASYPAISA_PRODUCTION_URL'),

    'hosted'=> env('EASYPAISA_HOSTED_CHECKOUT'),
];