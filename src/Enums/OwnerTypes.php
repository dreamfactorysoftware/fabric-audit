<?php
namespace DreamFactory\Library\Fabric\Common\Enums;

use DreamFactory\Library\Utility\Enums\FactoryEnum;

/**
 * The types of owners of hashes
 */
class OwnerTypes extends FactoryEnum
{
    //*************************************************************************
    //* Constants
    //*************************************************************************

    /**
     * @type int
     */
    const SYSTEM = 0;
    /**
     * @type int
     */
    const DEVELOPER = 1;
    /**
     * @type int
     */
    const END_USER = 2;
}