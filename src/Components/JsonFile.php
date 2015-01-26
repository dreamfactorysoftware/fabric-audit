<?php
namespace DreamFactory\Library\Fabric\Common\Components;

use DreamFactory\Library\Fabric\Common\Utility\Json;
use Kisma\Core\Exceptions\FileSystemException;

/**
 * Reads/writes a json file
 */
class JsonFile extends Json
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @type string The absolute path of our JSON file.
     */
    protected $_filePath;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Construct
     *
     * @param string       $filePath        The absolute path of the file, including the name.
     * @param array|object $defaultContents The contents to write to the file if being created
     *
     * @throws FileSystemException
     */
    public function __construct( $filePath = null, $defaultContents = null )
    {
        $this->_filePath = static::ensureFileExists( dirname( $filePath ), basename( $filePath ), $defaultContents );
    }

    /**
     * Checks whether json file exists.
     *
     * @throws FileSystemException
     * @return bool
     */
    public function exists()
    {
        $_path = dirname( $this->_filePath );
        $_filePath = $this->_filePath;

        if ( !is_dir( $_path ) && false === @mkdir( $_path, 0777, true ) )
        {
            if ( file_exists( $_filePath ) )
            {
                throw new FileSystemException( $_path . ' exists but it is not a directory.' );
            }

            throw new FileSystemException( 'Cannot create directory "' . $_path . '" . ' );
        }

        return is_file( $this->_filePath );
    }

    /**
     * Reads the file
     *
     * @param bool $decoded If true (the default), the read data is decoded
     *
     * @throws FileSystemException
     * @return array|object
     */
    public function read( $decoded = true )
    {
        if ( !$this->exists() )
        {
            throw new FileSystemException( 'The file "' . $this->_filePath . '" does not exist.' );
        }

        if ( false === ( $_json = file_get_contents( $this->_filePath ) ) )
        {
            throw new FileSystemException( 'Unable to read JSON file "' . $this->_filePath . '".' );
        }

        return $decoded ? static::decode( $_json ) : $_json;
    }

    /**
     * Writes the file
     *
     * @param array|object $data        The unencoded data to store
     * @param int          $jsonOptions Options for json_encode. Default is static::DEFAULT_JSON_ENCODE_OPTIONS
     * @param int          $retries     The number of times to retry the write.
     * @param float|int    $retryDelay  The number of microseconds (100000 = 1s) to wait between retries
     *
     *
     * @throws FileSystemException
     * @throws \Exception
     */
    public function write( $data = array(), $jsonOptions = self::DEFAULT_JSON_ENCODE_OPTIONS, $retries = 3, $retryDelay = 500000 )
    {
        //  Try once at least!
        if ( empty( $retries ) )
        {
            $retries = 1;
        }

        $_encoded = static::encode( $data, $jsonOptions ) . ( $jsonOptions & static::JSON_PRETTY_PRINT ? PHP_EOL : null );

        while ( $retries-- )
        {
            try
            {
                if ( false === file_put_contents( $this->_filePath, $_encoded ) )
                {
                    throw new FileSystemException( 'Unable to write data to file "' . $this->_filePath . '".' );
                }

                break;
            }
            catch ( \Exception $_ex )
            {
                if ( $retries )
                {
                    usleep( $retryDelay );
                    continue;
                }

                throw $_ex;
            }
        }
    }

    /**
     * Decodes a JSON string
     *
     * @param string $file The absolute path to the file to decode
     * @param bool   $asArray
     * @param int    $depth
     * @param int    $options
     *
     * @return mixed
     */
    public static function decodeFile( $file, $asArray = true, $depth = 512, $options = 0 )
    {
        if ( !file_exists( $file ) || !is_readable( $file ) || false === ( $_json = file_get_contents( $file ) ) )
        {
            throw new \InvalidArgumentException( 'The file "' . $file . '" does not exist or cannot be read.' );
        }

        return static::decode( $_json, $asArray, $depth, $options );
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->_filePath;
    }

    /**
     * Makes sure the file passed it exists. Create default config and saves otherwise.
     *
     * @param string       $filePath        The absolute path of the file, including the name.
     * @param array|object $defaultContents The contents to write to the file if being created
     *
     * @throws FileSystemException
     * @return string The absolute path to the file
     */
    public function ensureFileExists( $filePath, $defaultContents = null )
    {
        $_path = dirname( $filePath );

        if ( !is_dir( $_path ) || false === @mkdir( $_path, 0777, true ) )
        {
            throw new FileSystemException( 'Unable to create directory: ' . $_path );
        }

        if ( !file_exists( $filePath ) )
        {
            if ( false === file_put_contents( $filePath, empty( $defaultContents ) ? '{}' : static::encode( $defaultContents ) )
            )
            {
                throw new FileSystemException( 'Unable to create file: ' . $filePath );
            }
        }

        //  Exists
        return $filePath;
    }

}