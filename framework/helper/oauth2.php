<?php
/**
 * An OAuth2 client based on PHP
 * @author kaze
 */
class oauth2
{
	/* name: The service name.*/
	public $name;
	/* consumer_key: Client consumer key.*/
	private $consumer_key;
	/* consumer_secret: Client consumer secret.*/
	private $consumer_secret;
	/* access_token_url: Access token endpoint.*/
	private $access_token_url;
	/* authorize_url: Authorize endpoint.*/
	private $authorize_url;
	/* access_token: An access token, defaults to None.*/
	private $access_token;
	/* api params.*/
	public $params;

	public function __construct($params)
	{
		$this->name = $params['name'];
		$this->consumer_key = $params['consumer_key'];
		$this->consumer_secret = $params['consumer_secret'];
		$this->access_token_url = $params['access_token_url'];
		$this->authorize_url = $params['authorize_url'];
		$this->access_token = empty($params['access_token']) ? null : $params['access_token'];
		$this->params = array();
	}

	/**
	 *	
     *	@param reponse_type: The response type. Defaults to 'code'.
     *	@param params: Additional keyworded arguments to be added to the request querystring.
     *	@return a proper authorize URL.
     */
	public function get_authorize_url($response_type = 'code', $params = array())
	{
		$params = array_merge($params, $this->params, array(
			'client_id'		=> $this->consumer_key,
			'response_type'	=> $response_type
		));
		return $this->authorize_url . '?' . http_build_query($params);
	}

	/**
	 *	
     *	@param method: A string representation of the HTTP method to be used.
     *	@param params: Optional arguments. Same as Requests.
     *	@return Retrieves the access token.
     */
	public function get_access_token($method = 'get', $params = array())
	{
		$params['client_id']		= $this->consumer_key;
		$params['client_secret']	= $this->consumer_secret;
		$params['grant_type'] = isset($params['grant_type']) ? $params['grant_type'] : 'authorization_code';
		if ($params['grant_type'] == 'authorization_code')
		{
			return $this->_response($method, $this->access_token_url, $params, array());
		}
		elseif ($params['grant_type'] == 'refresh_token') 
		{
			// I don't know what will be happened lol
		}
		return false;
	}

	/**
	 *
	 * Sends a request to an OAuth 2.0 endpoint, properly wrapped around requests.
     *
     * method: A string representation of the HTTP method to be used.
     * api: The resource to be requested.
     * params: Optional arguments. Same as Requests.
     */
	public function request($method, $api, $params = array(), $muti = array())
	{
		$params['oauth_consumer_key']		= $this->consumer_key;
		$params['clientip']				= '127.0.0.1';
		return $this->_response($method, $api, $params, $muti);
	}

	/**
	 * A curl class.
	 *
	 * method: A string representation of the HTTP method to be used.
	 * url: Request address.
	 */
	private function _response($method, $url, $params, $muti)
	{
		$params = array_merge($this->params, $params);
		$method = strtolower($method);
		$user_agent = 'Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)';
		$ch = curl_init();
		curl_setopt ( $ch, CURLOPT_HEADER, false);
		//curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Except:'));
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_USERAGENT, $user_agent );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        if ($method == 'get')
        {
        	$url .= (strpos ( $url, '?' ) === false ? '?' : '&') . http_build_query($params);
        	curl_setopt ( $ch, CURLOPT_URL, $url);
        }
        if ($method == 'post') 
        {
        	curl_setopt ( $ch, CURLOPT_POST, true );
			curl_setopt ( $ch, CURLOPT_URL, $url );
			if ($muti)
			{
				foreach ($muti as $key => $item) {
					$params[$key] = '@'.$item;
				}
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params);
			}
			else
			{
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($params));
			}
        }
		$query = curl_exec($ch);
		return $query;
	}
}