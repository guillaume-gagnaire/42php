<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

require_once realpath( dirname( __FILE__ ) )  . "/StorageInterface.php";

/**
 * HybridAuth storage manager
 */
class Hybrid_Storage implements Hybrid_Storage_Interface
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->config( "php_session_id", Session::$id );
		$this->config( "version", Hybrid_Auth::$version );
	}

	/**
	 * Config
	 * @param String $key
	 * @param String $value
	 */
	public function config($key, $value = null)
	{
		$key = strtolower( $key );

		if( $value ){
            Session::set('ha.config.'.$key, serialize($value));
		}
		elseif( isset( $_SESSION["HA::CONFIG"][$key] ) ){
			return unserialize( Session::get('ha.config.'.$key) );
		}

		return NULL;
	}

	/**
	 * Get a key
	 * @param String $key
	 */
	public function get($key)
	{
		$key = strtolower( $key );

        $val = Session::get('ha.config.'.$key, null);
		if( !is_null($val) ){
			return unserialize( $val );
		}

		return NULL;
	}

	/**
	 * GEt a set of key and value
	 * @param String $key
	 * @param String $value
	 */
	public function set( $key, $value )
	{
		$key = strtolower( $key );

        Session::set('ha.config.'.$key, serialize($value));
	}

	/**
	 * Clear session storage
	 */
	function clear()
	{
        Session::set('ha.config', []);
	}

	/**
	 * Delete a specific key
	 * @param String $key
	 */
	function delete($key)
	{
		$key = strtolower( $key );

        Session::remove('ha.store.'.$key);
	}

	/**
	 * Delete a set
	 * @param String $key
	 */
	function deleteMatch($key)
	{
		$key = strtolower( $key );

        $f = Session::get('ha.store', []);
        foreach( $f as $k => $v ){
            if( strstr( $k, $key ) ){
                unset( $f[ $k ] );
            }
        }
        Session::set('ha.store', $f);
	}

	/**
	 * Get the storage session data into an array
	 * @return Array
	 */
	function getSessionData()
	{
		return Session::get('ha.store', null);
	}

	/**
	 * Restore the storage back into session from an array
	 * @param Array $sessiondata
	 */
	function restoreSessionData( $sessiondata = NULL )
	{
		Session::set('ha.store', unserialize( $sessiondata ));
	}
}
