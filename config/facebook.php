<?php
	/**
	 * Get an api_key and secret from facebook and fill in this content.
	 * save the file to app/config/facebook.php
	 *
	 * http://www.facebook.com/developers/
	 */
	$config = array(
		'Facebook' => array(
			'appId' => 'apid',
			'apiKey' => 'apikey',
			'secret' => 'secret',
			'cookie' => true,
			'userModel' => 'MyPlugin.MyUserModel'
		)
	);