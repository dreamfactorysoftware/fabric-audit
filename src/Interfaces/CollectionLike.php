<?php
namespace DreamFactory\Library\Fabric\Common\Interfaces;

use DreamFactory\Library\Fabric\Common\Components\Collection;
use DreamFactory\Library\Fabric\Common\Enums\DataFormats;
use Kisma\Core\Exceptions\OverwriteException;

/**
 * Something that acts like a Collection
 */
interface CollectionLike
{
    /**
     * Completely empty the collection
     *
     * @return $this
     */
    public function clear();

    /**
     * Returns the items in the collection as an array by default. Use $format to get other data types
     *
     * @param int|string $format
     *
     * @return array|string
     */
    public function all( $format = DataFormats::NATIVE );

    /**
     * @param string $key Item to retrieve.
     * @param mixed  $defaultValue
     * @param bool   $burnAfterReading
     *
     * @return mixed Value of the key or default value
     */
    public function get( $key = null, $defaultValue = null, $burnAfterReading = false );

    /**
     * Set a key value pair
     *
     * @param string $key   Key to set
     * @param mixed  $value Value to set
     * @param bool   $overwrite
     *
     * @throws OverwriteException
     * @return $this
     */
    public function set( $key, $value, $overwrite = true );

    /**
     * Add a value to the collection.
     * If the key exists, the value is converted to an array
     * and the new value is pushed on the end of the array.
     *
     * @param string $key   Key to add
     * @param mixed  $value Value to add
     *
     * @return $this
     */
    public function add( $key, $value );

    /**
     * Removes an item from the collection
     *
     * @param string $key key to remove
     *
     * @return bool True if item was removed
     */
    public function remove( $key );

    /**
     * Returns an array of the collection's keys
     *
     * @return array
     */
    public function keys();

    /**
     * Checks if the collection contains a specific key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has( $key );

    /**
     * Checks if the collection contains a specific value
     *
     * @param string $value Value to find
     *
     * @return bool|string Returns the key of found value or FALSE
     */
    public function hasValue( $value );

    /**
     * Replace the contents of the collection with the given array of data
     *
     * @param array $data Replacement array of data
     *
     * @return $this
     */
    public function replace( array $data );

    /**
     * Merges the data given into collection
     *
     * @param Collection|array $data array of key value pair data or a collection
     *
     * @return $this
     */
    public function merge( $data );
}