<?php
namespace DreamFactory\Library\Fabric\Common\Exceptions;

/**
 * Thrown when an instance is not found
 */
class InstanceNotFoundException extends InstanceException
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param string     $instanceId
     * @param null       $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct( $instanceId, $message = null, $code = 404, \Exception $previous = null )
    {
        parent::__construct(
            $instanceId,
            $message ?: 'Instance "' . $instanceId . '" not found.',
            $code,
            $previous
        );
    }

}