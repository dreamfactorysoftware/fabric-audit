<?php
namespace DreamFactory\Library\Fabric\Common\Enums;

use DreamFactory\Library\Utility\Enums\FactoryEnum;

/**
 * DataFormats
 * The data formats of which we are aware
 */
class DataFormats extends FactoryEnum
{
    //*************************************************************************
    //	Constants
    //*************************************************************************

    /**
     * @var int native/original/unadulterated
     */
    const NATIVE = 0;
    /**
     * @var int
     */
    const JSON = 1;
    /**
     * @var int
     */
    const XML = 2;
    /**
     * @var int
     */
    const HTTP = 3;
    /**
     * @var int Comma-separated values
     */
    const CSV = 4;
    /**
     * @var int Pipe-separated values
     */
    const PSV = 5;
    /**
     * @var int Tab-separated values
     */
    const TSV = 6;

    //*************************************************************************
    //* Members
    //*************************************************************************

    /**
     * @var array A hash of level names against Monolog levels
     */
    protected static $_strings = array(
        'tsv'    => self::TSV,
        'psv'    => self::PSV,
        'csv'    => self::CSV,
        'http'   => self::HTTP,
        'xml'    => self::XML,
        'json'   => self::JSON,
        'native' => self::NATIVE,
    );

    //*************************************************************************
    //* Methods
    //*************************************************************************

    /**
     * @param string $stringLevel
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function toNumeric( $stringLevel )
    {
        if ( !is_string( $stringLevel ) )
        {
            throw new \InvalidArgumentException( 'The data format "' . $stringLevel . '" is a string.' );
        }

        if ( !in_array( strtolower( $stringLevel ), array_keys( static::$_strings ) ) )
        {
            throw new \InvalidArgumentException( 'The data format "' . $stringLevel . '" is invalid.' );
        }

        return static::defines( strtoupper( $stringLevel ), true );
    }

    /**
     * @param int $numericLevel
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function toString( $numericLevel )
    {
        if ( !is_numeric( $numericLevel ) )
        {
            throw new \InvalidArgumentException( 'The data format "' . $numericLevel . '" is not numeric.' );
        }

        if ( !in_array( $numericLevel, static::$_strings ) )
        {
            throw new \InvalidArgumentException( 'The data format "' . $numericLevel . '" is invalid.' );
        }

        return static::nameOf( $numericLevel, true, false );
    }
}
