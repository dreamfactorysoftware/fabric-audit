<?php
namespace DreamFactory\Library\Fabric\Common\Utility;

/**
 * A standard JSON encoder/decoder for PHP v5.3+
 */
class Json
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @type int
     */
    const JSON_UNESCAPED_SLASHES = 64;
    /**
     * @type int
     */
    const JSON_PRETTY_PRINT = 128;
    /**
     * @type int
     */
    const JSON_UNESCAPED_UNICODE = 256;
    /**
     * @type int The default options for json_encode. This value is (JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT +
     *       JSON_UNESCAPED_UNICODE)
     */
    const DEFAULT_JSON_ENCODE_OPTIONS = 448;

    //******************************************************************************
    //* Methods
    //******************************************************************************
    /**
     * JSON encodes data
     *
     * @param  mixed $data        Data to encode
     * @param  int   $jsonOptions Options for json_encode. Default is static::DEFAULT_JSON_ENCODE_OPTIONS
     *
     * @return string Encoded json
     */
    public static function encode( $data, $jsonOptions = self::DEFAULT_JSON_ENCODE_OPTIONS )
    {
        if ( false === ( $_json = json_encode( $data, $jsonOptions ) ) || JSON_ERROR_NONE != json_last_error() )
        {
            throw new \InvalidArgumentException( 'The data could not be encoded: ' . json_last_error_msg() );
        }

        return $_json;
    }

    /**
     * Decodes a JSON string
     *
     * @param string $json The data to decode
     * @param bool   $asArray
     * @param int    $depth
     * @param int    $options
     *
     * @return mixed
     */
    public static function decode( $json, $asArray = true, $depth = 512, $options = 0 )
    {
        if ( false === ( $_data = json_decode( $json, $asArray, $depth, $options ) ) ||
            JSON_ERROR_NONE != json_last_error()
        )
        {
            throw new \InvalidArgumentException( 'The data could not be decoded: ' . json_last_error_msg() );
        }

        return $_data;
    }
}