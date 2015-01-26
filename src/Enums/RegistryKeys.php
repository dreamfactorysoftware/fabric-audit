<?php
namespace DreamFactory\Library\Fabric\Common\Enums;

use DreamFactory\Library\Utility\Enums\FactoryEnum;

/**
 * RegistryKeys
 * Enumerations of pre-defined application config keys
 */
class RegistryKeys extends FactoryEnum
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @type string The top-level server key
     */
    const SERVERS = 'servers';
    /**
     * @type string The top-level key for storing db servers
     */
    const DB_SERVER = 'db';
    /**
     * @type string The top-level key for storing web servers
     */
    const WEB_SERVER = 'web';
    /**
     * @type string The top-level key for storing app servers
     */
    const APP_SERVER = 'app';
    /**
     * @type string The level key for storing servers
     */
    const SERVER_ID = 'server-id';
}
