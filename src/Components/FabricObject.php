<?php
/**
 * This file is part of DreamFactory Enterprise(tm) Queue Models Library
 *
 * Copyright 2014 DreamFactory Software, Inc. All Rights Reserved.
 *
 * Proprietary code, DO NOT DISTRIBUTE!
 *
 * @email   <support@dreamfactory.com>
 * @license proprietary
 */
namespace DreamFactory\Library\Fabric\Common\Components;

/**
 * Lightweight base class for Fabric components that have settings
 */
class FabricObject extends Collection
{
    //********************************************************************************
    //* Members
    //********************************************************************************

    /**
     * @type string A sorta-unique ID assigned to this object, the last part of which is the creation time
     */
    private $_id;

    //********************************************************************************
    //* Methods
    //********************************************************************************

    /**
     * Base constructor
     *
     * @param array|object $settings An array of key/value pairs that will be placed into storage
     */
    public function __construct( $settings = array() )
    {
        //	This is my hash. There are many like it, but this one is mine.
        $this->_id = hash( 'sha256', spl_object_hash( $this ) . getmypid() . microtime( true ) );

        parent::__construct( $settings );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }
}
