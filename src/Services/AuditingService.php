<?php
/**
 * This file is part of the DreamFactory Services Platform(tm) SDK For PHP
 *
 * DreamFactory Services Platform(tm) <http://github.com/dreamfactorysoftware/dsp-core>
 * Copyright 2012-2014 DreamFactory Software, Inc. <support@dreamfactory.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace DreamFactory\Library\Fabric\Auditing\Services;

use DreamFactory\Library\Fabric\Auditing\Components\GelfMessage;
use DreamFactory\Library\Fabric\Auditing\Enums\AuditLevels;
use DreamFactory\Library\Fabric\Auditing\Utility\GelfLogger;
use DreamFactory\Library\Utility\IfSet;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contains auditing methods for DFE
 */
class AuditingService implements LoggerAwareInterface
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @type string
     */
    const DEFAULT_FACILITY = 'fabric-instance';

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @type GelfLogger
     */
    protected static $_logger = null;

    //********************************************************************************
    //* Public Methods
    //********************************************************************************

    /**
     * @param string $host
     */
    public static function setHost( $host = GelfLogger::DEFAULT_HOST )
    {
        static::getLogger()->setHost( $host );
    }

    /**
     * Logs API requests to logging system
     *
     * @param string  $instanceId The id of the sending instance
     * @param Request $request    The request
     * @param int     $level      The level, defaults to INFO
     * @param string  $facility   The facility, used for sorting
     *
     * @return bool
     */
    public static function logRequest( $instanceId, Request $request, $level = AuditLevels::INFO, $facility = self::DEFAULT_FACILITY )
    {
        try
        {
            $_host = $request->getHost();

            $_data = array(
                'request_timestamp' => (double)$request->server->get( 'REQUEST_TIME_FLOAT' ),
                'user_agent'        => $request->headers->get( 'user-agent' ),
                'source_ip'         => $request->getClientIps(),
                'content_type'      => $request->getContentType(),
                'content_length'    => (int)$request->headers->get( 'Content-Length' ) ?: 0,
                'instance_id'       => $instanceId,
                'token'             => $request->headers->get( 'x-dreamfactory-session-token' ),
                'facility'          => $facility,
                'app_name'          => IfSet::get(
                    $_GET,
                    'app_name',
                    $request->headers->get(
                        'x-dreamfactory-application-name',
                        $request->headers->get( 'x-application-name' )
                    )
                ),
                'host'              => $_host,
                'method'            => $request->getMethod(),
                'path_info'         => $request->getPathInfo(),
                'path_translated'   => $request->server->get( 'PATH_TRANSLATED' ),
                'query'             => $request->query->all(),
            );

            $_message = new GelfMessage( $_data );
            $_message->setLevel( $level );
            $_message->setShortMessage( $request->getMethod() . ' ' . $request->getRequestUri() );
            $_message->setFullMessage( 'DFE Audit | ' . implode( ', ', $_data['source_ip'] ) . ' | ' . $_data['request_timestamp'] );

            static::getLogger()->send( $_message );
        }
        catch ( \Exception $_ex )
        {
            //  Completely ignore any issues
        }
    }

    /**
     * @return GelfLogger
     */
    public static function getLogger()
    {
        return static::$_logger ?: static::$_logger = new GelfLogger();
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger( LoggerInterface $logger )
    {
        static::$_logger = $logger;

        return $this;
    }

}
