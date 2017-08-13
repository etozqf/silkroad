<?php

class session_storage_redis extends session_storage
{
	function __construct($options = array())
	{
		if(!$this->test())
		{
            throw new Exception("The redis extension isn't available");
        }
		ini_set('session.save_handler', 'redis');
		ini_set('session.save_path', $options['redis_servers']);
	}
	
	function test()
	{
		return extension_loaded('redis');
	}
}
