<?php

$drupal_hash_salt = 'secret 80 characters string';

$databases = [
  'default' =>
    [
      'default' =>
        [
          'database' => 'eurobats',
          'username' => 'root',
          'password' => 'root',
          'host' => 'localhost',
          'port' => '',
          'driver' => 'mysql',
          'prefix' => '',
        ],
    ],
];

$base_url = 'http://eurobats.test';  // NO trailing slash!
