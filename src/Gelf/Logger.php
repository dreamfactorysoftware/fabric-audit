<?php
namespace DreamFactory\Library\Fabric\Common\Gelf;

use DreamFactory\Library\Fabric\Common\Components\FabricObject;
use DreamFactory\Library\Fabric\Common\Gelf\Enums\Levels;
use DreamFactory\Library\Fabric\Common\Utility\Json;
use Psr\Log\LoggerInterface;

/**
 * A GELF logger
 */
class Logger extends FabricObject implements LoggerInterface
{
    //**************************************************************************
    //* Constants
    //**************************************************************************

    /**
     * @var string Hostname of graylog2 server
     */
    const DEFAULT_HOST = 'graylog.fabric.dreamfactory.com';
    /**
     * @const integer Port that graylog2 server listens on
     */
    const DEFAULT_PORT = 12201;
    /**
     * @const integer Maximum message size before splitting into chunks
     */
    const MAX_CHUNK_SIZE = 8154;
    /**
     * @const integer Maximum number of chunks allowed by GELF
     */
    const MAX_CHUNKS_ALLOWED = 128;

    //**********************************************************************
    //* Public Methods
    //**********************************************************************

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function emergency( $message, array $context = array() )
    {
        $this->log( Levels::EMERGENCY, $message, $context );
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function alert( $message, array $context = array() )
    {
        $this->log( Levels::ALERT, $message, $context );
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function critical( $message, array $context = array() )
    {
        $this->log( Levels::CRITICAL, $message, $context );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function error( $message, array $context = array() )
    {
        $this->log( Levels::ERROR, $message, $context );
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function warning( $message, array $context = array() )
    {
        $this->log( Levels::WARNING, $message, $context );
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function notice( $message, array $context = array() )
    {
        $this->log( Levels::NOTICE, $message, $context );
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function info( $message, array $context = array() )
    {
        $this->log( Levels::INFO, $message, $context );
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function debug( $message, array $context = array() )
    {
        $this->log( Levels::DEBUG, $message, $context );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function log( $level, $message, array $context = array() )
    {
        $_message = new Message( array('context' => $context) );
        $_message->setFullMessage( $_message );

        return $this->sendMessage( $_message );
    }

    //**********************************************************************
    //* Protected Methods
    //**********************************************************************

    /**
     * @param Message $message The message to send
     *
     * @return bool
     */
    public function sendMessage( Message $message )
    {
        if ( false === ( $_chunks = $this->_prepareMessage( $message ) ) )
        {
            return false;
        }

        $_url = 'udp://' . static::DEFAULT_HOST . ':' . static::DEFAULT_PORT;
        $_sock = stream_socket_client( $_url );

        foreach ( $_chunks as $_chunk )
        {
            if ( !fwrite( $_sock, $_chunk ) )
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Static method for preparing a GELF message to be sent
     *
     * @param Message $message
     *
     * @return array
     */
    protected function _prepareMessage( Message $message )
    {
        $_json = Json::encode( $message->toArray() );

        if ( false === ( $_gzJson = gzcompress( $_json ) ) )
        {
            return false;
        }

        //  If we are less than the max chunk size, we're done
        if ( strlen( $_gzJson ) <= static::MAX_CHUNK_SIZE )
        {
            return array($_gzJson);
        }

        return $this->_prepareChunks( str_split( $_gzJson, static::MAX_CHUNK_SIZE ) );
    }

    /**
     * Static method for packing a chunk of GELF data
     *
     * @param array  $chunks The array of chunks of gzipped JSON GELF data to prepare
     * @param string $msgId  The 8-byte message id, same for entire chunk set
     *
     * @return string[] An array of packed chunks ready to send
     */
    protected function _prepareChunks( $chunks, $msgId = null )
    {
        $msgId = $msgId ?: hash( 'sha256', microtime( true ) . rand( 10000, 99999 ), true );

        $_sequence = 0;
        $_count = count( $chunks );

        if ( $_count > static::MAX_CHUNKS_ALLOWED )
        {
            return false;
        }

        $_prepared = array();

        foreach ( $chunks as $_chunk )
        {
            $_prepared[] = pack( 'CC', 30, 15 ) . $msgId . pack( 'nn', $_sequence++, $_count ) . $_chunk;
        }

        return $_prepared;
    }
}
