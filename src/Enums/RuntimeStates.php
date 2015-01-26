<?php
namespace DreamFactory\Library\Fabric\Common\Enums;

use DreamFactory\Library\Utility\Enums\FactoryEnum;

/**
 * Defines the various states in which a platform may exist
 */
class RuntimeStates extends FactoryEnum
{
    //*************************************************************************
    //* Constants
    //*************************************************************************

    /**
     * @var int
     */
    const ADMIN_REQUIRED = 0;
    /**
     * @var int
     */
    const DATA_REQUIRED = 1;
    /**
     * @var int
     */
    const INIT_REQUIRED = 2;
    /**
     * @var int
     */
    const READY = 3;
    /**
     * @var int
     */
    const SCHEMA_REQUIRED = 4;
    /**
     * @var int
     */
    const UPGRADE_REQUIRED = 5;
    /**
     * @var int
     */
    const WELCOME_REQUIRED = 6;
    /** @var int Indicates that the database is in place and the schema has been created */
    const DATABASE_READY = 7;
}