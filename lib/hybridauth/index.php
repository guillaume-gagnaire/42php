<?php

include '../../scripts/init.php';

/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------

require_once( "Hybrid/Error.php" );
require_once( "Hybrid/Exception.php" );
require_once( "Hybrid/Logger.php" );
require_once( "Hybrid/Provider_Adapter.php" );
require_once( "Hybrid/Provider_Model.php" );
require_once( "Hybrid/Provider_Model_OpenID.php" );
require_once( "Hybrid/Provider_Model_OAuth1.php" );
require_once( "Hybrid/Provider_Model_OAuth2.php" );
require_once( "Hybrid/User.php" );
require_once( "Hybrid/User_Profile.php" );
require_once( "Hybrid/User_Contact.php" );
require_once( "Hybrid/User_Activity.php" );
if ( ! class_exists("Hybrid_Storage", false) ) {
    require_once("Hybrid/Storage.php");
}

require_once( "Hybrid/Auth.php" );
require_once( "Hybrid/Endpoint.php" );

Hybrid_Endpoint::process();
