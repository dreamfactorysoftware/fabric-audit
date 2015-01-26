<?php
namespace DreamFactory\Library\Fabric\Common\Enums;

use DreamFactory\Library\Utility\Enums\FactoryEnum;

/**
 * The states in which a job may be
 */
class JobStates extends FactoryEnum
{
    //********************************************************************************
    //* Constants
    //********************************************************************************

    /**
     * @var int
     */
    const WAITING = 1;
    /**
     * @var int
     */
    const RUNNING = 2;
    /**
     * @var int
     */
    const COMPLETED_WITH_ERROR = 3;
    /**
     * @var int
     */
    const COMPLETED_SUCCESSFULLY = 4;
    /**
     * @var int
     */
    const CANCELED = 100;
    /**
     * @var int
     */
    const PENDING_CANCELLATION = 101;
}
