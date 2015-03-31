<?php
/**
 * Reprensents the class that handles the google shorten url
 * 
 * @author Julius Caamic <julius@greenbrainer.com>
 * @copyright (c) Copyright (c) 2015, Julius Caamic
 */
class GoogleUrlApi extends Controller {
	/**
	 * Sets the url of the api
	 * 
	 * @var string
	 */
	protected static $apiURL = 'https://www.googleapis.com/urlshortener/v1/url';

	/**
	 * google dev API key
	 * 
	 * @var string
	 */
	protected static $apiKey = '';

	/**
	 * Initialise the controller
	 */
	public function init() {
		parent::init();
	}

	/**
	 * Set the API key
	 * 
	 * @param string $key
	 */
	public static function setAPIKey($key) {
		self::$apiKey = $key;
	}

	/**
	 * Shorten the url
	 * 
	 * @param  string $url
	 * @return string
	 */
	public static function shorten($url) {		
		$response = self::send($url);

		return isset($response['id']) ? $response['id'] : $url;
	}
	
	/**
	 * Expand the url
	 * 
	 * @param  string $url
	 * @return string
	 */
	public static function expand($url) {
		$response = self::send($url, false);

		return isset($response['longUrl']) ? $response['longUrl'] : $url;
	}
	
	/**
	 * Send information request to google
	 * 
	 * @param  string  $url
	 * @param  boolean $shorten
	 * @return json
	 */
	public static function send($url, $shorten = true) {
		$ch = curl_init();

		if($shorten) {
			curl_setopt($ch,CURLOPT_URL, self::$apiURL . '?key=' . self::$apiKey);
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array("longUrl"=>$url)));
			curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		}
		else {
			curl_setopt($ch, CURLOPT_URL,  self::$apiURL . '?key=' . self::$apiKey.'&shortUrl='.$url);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		$result = curl_exec($ch);

		curl_close($ch);

		return json_decode($result,true);
	}		
}