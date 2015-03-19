<?php

$aliases['staging'] = array(
  'uri' => 'http://eurobats.edw.ro',
  'db-allows-remote' => TRUE,
  'remote-host' => 'php-devel1.edw.lan',
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

$aliases['prod'] = array(
  'uri' => 'http://eurobats.org',
  'db-allows-remote' => TRUE,
  'remote-host' => 'cms.int',
  'remote-user' => 'php',
  'root' => '/var/local/eurobats/docroot',
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

// Add your local aliases.
if (file_exists(dirname(__FILE__) . '/aliases.local.php')) {
  include dirname(__FILE__) . '/aliases.local.php';
}
