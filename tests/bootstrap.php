<?php
/**
 * Bootstrap script for PHPUnit tests
 */
$_basePath = dirname( __DIR__ );
$_configPath = $_basePath . '/config';
$_vendorPath = $_basePath . '/vendor';

if ( !is_dir( $_vendorPath ) )
{
    echo 'Please run composer install/update before running tests.';
    exit( 1 );
}

//	Composer
$_autoloader = require( $_basePath . '/vendor/autoload.php' );

\Kisma::set( 'app.log_file', $_basePath . '/build/console.test.log' );

/** @noinspection PhpIncludeInspection */
file_exists( $_configPath . '/test.config.php' ) && $_config = require( $_configPath . '/test.config.php' );

//	Testing keys
/** @noinspection PhpIncludeInspection */
file_exists( __DIR__ . '/config/keys.php' ) && require_once __DIR__ . '/config/keys.php';


