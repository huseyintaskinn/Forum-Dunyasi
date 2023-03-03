<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'sql109.byetcluster.com';
$CFG->dbname    = 'epiz_32750720_49';
$CFG->dbuser    = '32750720_4';
$CFG->dbpass    = '(g22SpNw[5';
$CFG->prefix    = 'mdle6_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_general_ci',
);

$CFG->wwwroot   = 'http://denememoodle.epizy.com';
$CFG->dataroot  = '/home/vol18_2/epizy.com/epiz_32750720/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
