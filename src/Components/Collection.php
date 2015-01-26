<?php
namespace DreamFactory\Library\Fabric\Common\Components;

use DreamFactory\Library\Fabric\Common\Enums\DataFormats;
use DreamFactory\Library\Fabric\Common\Interfaces\CollectionLike;
use DreamFactory\Library\Fabric\Common\Utility\Json;
use Kisma\Core\Exceptions\OverwriteException;

/**
 * A generic KVP collection
 */
class Collection implements \Countable, \IteratorAggregate, \ArrayAccess, CollectionLike
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @type array
     */
    private $_items;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param array|Collection $items Initial items of the collection
     */
    public function __construct( $items = array() )
    {
        ( $items instanceof Collection )
            ? $this->merge( $items->all() )
            : $this->replace( $items );
    }

    /** @inheritdoc */
    public function clear()
    {
        $this->_items = array();

        return $this;
    }

    /** @inheritdoc */
    public function all( $format = null )
    {
        switch ( $format )
        {
            case DataFormats::JSON:
            case 'json':
                return Json::encode( $this->_items );
        }

        return $this->_items;
    }

    /** @inheritdoc */
    public function get( $key = null, $defaultValue = null, $burnAfterReading = false )
    {
        if ( null === $key )
        {
            return $this->all();
        }

        if ( array_key_exists( $key, $this->_items ) )
        {
            $_value = $this->_items[$key];

            if ( $burnAfterReading )
            {
                unset( $this->_items[$key] );
            }
        }
        else
        {
            $_value = $defaultValue;
        }

        return $_value;
    }

    /** @inheritdoc */
    public function set( $key, $value, $overwrite = true )
    {
        if ( !$overwrite && array_key_exists( $key, $this->_items ) )
        {
            throw new OverwriteException( 'Key "' . $key . '" is read-only.' );
        }

        $this->_items[$key] = $value;

        return $this;
    }

    /** @inheritdoc */
    public function add( $key, $value )
    {
        if ( !array_key_exists( $key, $this->_items ) )
        {
            $this->_items[$key] = $value;

            return $this;
        }

        if ( is_array( $this->_items[$key] ) )
        {
            $this->_items[$key] = array_merge( $this->_items[$key], $value );

            return $this;
        }

        $this->_items[$key] = array($this->_items[$key], $value);

        return $this;
    }

    /** @inheritdoc */
    public function remove( $key )
    {
        if ( isset( $this->_items[$key] ) || array_key_exists( $key, $this->_items ) )
        {
            unset( $this->_items[$key] );

            return true;
        }

        return false;
    }

    /** @inheritdoc */
    public function keys()
    {
        return array_keys( $this->_items );
    }

    /** @inheritdoc */
    public function has( $key )
    {
        return array_key_exists( $key, $this->_items );
    }

    /** @inheritdoc */
    public function hasValue( $value )
    {
        return array_search( $value, $this->_items, true );
    }

    /** @inheritdoc */
    public function replace( array $data )
    {
        $this->_items = $data;

        return $this;
    }

    /** @inheritdoc */
    public function merge( $data )
    {
        foreach ( $data as $key => $value )
        {
            $this->add( $key, $value );
        }

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator( $this->_items );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists( $offset )
    {
        return $this->has( $offset );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet( $offset )
    {
        return $this->get( $offset );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     */
    public function offsetSet( $offset, $value )
    {
        $this->set( $offset, $value );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     */
    public function offsetUnset( $offset )
    {
        $this->remove( $offset );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return count( $this->_items );
    }
}