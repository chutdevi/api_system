<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'port' => '1433',
	'hostname' => 'Driver={SQL Server};Server=192.168.161.101\PCSDBSV;Database=report_service;',
	'username' => 'pcs_admin',
	'password' => 'P@ss!fa',
	'database' => 'report_service',
	'dbdriver' => 'odbc',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['fal'] = array(
	'dsn'	=> '',
	'port' => '1433',
	'hostname' => 'Driver={SQL Server};Server=192.168.161.101\PCSDBSV;Database=FASYSTEM;',
	'username' => 'pcs_admin',
	'password' => 'P@ss!fa',
	'database' => 'FASYSTEM',
	'dbdriver' => 'odbc',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['pick'] = array(
	'dsn'	=> '',
	'port' => '1433',
	'hostname' => 'Driver={SQL Server};Server=192.168.161.101\PCSDBSV;Database=tbkkfa01_dev;',
	'username' => 'pcs_admin',
	'password' => 'P@ss!fa',
	'database' => 'tbkkfa01_dev',
	'dbdriver' => 'odbc',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.17.131.18)(PORT = 1524))
        (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = EXPK)))';

$name = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.17.131.18)(PORT = 1524)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = EXPK)))';

$db['expk'] = array(
	'dsn'	=> '',
	'hostname' => $name,
	'username' => 'EXPK',
	'password' => 'EXPK',
	'database' => 'EXPK',
	'dbdriver' => 'oci8',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


$db['jeaw_db'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.82.31',
	'username' => 'monty',
	'password' => 'some_pass',
	'database' => 'report_service',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['fin_db'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.82.58',
	'username' => 'pcsadmin',
	'password' => 'P@ssw0rd',
	'database' => 'system_tool',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['user'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.82.31',
	'username' => 'monty',
	'password' => 'some_pass',
	'database' => 'subport_system',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['fa'] = array(
	'dsn'	=> '',
	'hostname' => 'Driver={IBM DB2 ODBC DRIVER - DB2COPY3};Database=tbkfa03;hostname=192.168.161.1;port=50000;protocol=TCPIP;',
	'username' => 'tbk',
	'password' => 'kbt',
	'database' => 'TBKFA03',
	'dbdriver' => 'odbc',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['fa8'] = array(
	'dsn'	=> '',
	'hostname' => 'Driver={IBM DB2 ODBC DRIVER - DB2COPY3};Database=tbkfa04;hostname=192.168.176.1;port=50000;protocol=TCPIP;',
	'username' => 'tbk',
	'password' => 'kkbt',
	'database' => 'TBKFA04',
	'dbdriver' => 'odbc',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['emp'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.82.23',
	'username' => 'root',
	'password' => 'root',
	'database' => 'member',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['ship'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.161.6',
	'username' => 'develop',
	'password' => 'tbk',
	'database' => 'ship',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);