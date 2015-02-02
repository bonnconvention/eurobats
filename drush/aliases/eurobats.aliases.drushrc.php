<?php

$aliases['eurobats.staging'] = array(
  'uri' => 'eurobats.edw.ro',
  'db-allows-remote' => TRUE,
  'remote-host' => 'eurobats.edw.ro',
  'remote-user' => 'php',
  'root' => '/var/www/html/cms/eurobats.edw.ro/docroot',
  'path-aliases' => array(
    '%files' => 'sites/default/files',
  ),
  'command-specific' => array(
    'sql-sync' => array(
      'simulate' => '1',
    ),
  ),
);

$aliases['eurobats.production'] = array(
  'uri' => 'eurobats.org',
  'db-allows-remote' => TRUE,
  'remote-host' => 'eurobats.org',
  'remote-user' => 'php',
  'root' => '/var/local/eurobats/www',
  'path-aliases' => array(
    '%files' => 'sites/default/files',
  ),
  'command-specific' => array(
    'sql-sync' => array(
      'simulate' => '1',
      'source-dump' => '/tmp/eurobats-source-dump-php.sql',
      'target-dump' => '/tmp/eurobats-target-dump-php.sql.gz',
    ),
  ),
);

// This alias is used in install and update scripts.
// Rewrite it in your aliases.local.php as you need.
$aliases['eurobats.staging.sync'] = $aliases['eurobats.production'];

// Add your local aliases.
if (file_exists(dirname(__FILE__) . '/aliases.local.php')) {
  include dirname(__FILE__) . '/aliases.local.php';
}
