<?php

class InstagramAPIClient
{
	protected $clientID;
	protected $clientSecret;
	protected $redirectURI;

	/**
	 * Cf. https://developers.facebook.com/docs/instagram-basic-display-api/getting-started 
	 * for more information about the variables used here.
	 *
	 * @param String $clientID
	 * @param String $clientSecret
	 * @param String $redirectURI
	 */
	public function __construct(String $clientID, String $clientSecret, String $redirectURI)
	{
		$this->clientID = $clientID;
		$this->clientSecret = $clientSecret;
		$this->redirectURI = $redirectURI;
	}

	/**
	 * Visit this url in the browser to get the API code we need
	 * to generate tokens
	 *
	 * @return string
	 */
	public function get_auth_url()
	{
		return `https://api.instagram.com/oauth/authorize?client_id={$this->clientID}&redirect_uri={$this->redirectURI}&scope=user_profile,user_media&response_type=code`;
	}

	/**
	 * Generates first a short-lived token, then the long-lived token,
	 * and stores them in the database
	 *
	 * @param String $code The code returned in the url by the get_auth_url() function
	 * @return void
	 */
	public function generate_tokens(String $code)
	{
		$response = wp_remote_post('https://api.instagram.com/oauth/access_token', [
			'body' => [
				'client_id' => $this->clientID,
				'client_secret' => $this->clientSecret,
				'grant_type' => 'authorization_code',
				'redirect_uri' => $this->redirectURI,
				'code' => $code
			]
		]);

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			return wp_mail('krimo@pixelsmith.co', 'Error on the instagram API', $error_message);
		}

		$decoded = json_decode($response['body'], TRUE);

		if (!isset($decoded['access_token'])) {
			return wp_mail('krimo@pixelsmith.co', 'Error on the instagram API (short lived token)', $response['body']);
		}

		$shortLivedToken = $decoded['access_token'];
		$instagramUserId = $decoded['user_id'];

		update_option('instagramUserID', $instagramUserId);
		update_option('instagramShortTokenResponse', $response['body']);

		$this->set_long_lived_token($shortLivedToken);

		if (!wp_next_scheduled('instagram_cron_hook')) {
			wp_schedule_event(time(), 'thirty_days', 'instagram_cron_hook');
		}
	}

	/**
	 * Turn a short-lived token (1h) to a long-lived token (30 days),
	 * and store the expiration date in the DB so we can periodically check the validity
	 * of the latter.
	 *
	 * @param String $shortLivedToken
	 * @return void
	 */
	public function set_long_lived_token(String $shortLivedToken)
	{
		$response = wp_remote_get("https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=a52ec22529cf95d219a54d4247f2ed0d&access_token=$shortLivedToken");

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			return wp_mail('krimo@pixelsmith.co', 'WP Error on the instagram API', $error_message);
		}

		$decoded = json_decode($response['body'], TRUE);

		if (!isset($decoded['access_token'])) {
			return wp_mail('krimo@pixelsmith.co', 'Error on the instagram API (long lived token)', $response['body']);
		}

		$longLivedToken = $decoded['access_token'];
		$expirationDate = $decoded['expires_in'];

		update_option('instagramLongTokenResponse', $response['body']);
		update_option('instagramLongLivedToken', $longLivedToken);
		update_option('instagramExpirationDate', $expirationDate);
	}

	/**
	 * Long-lived tokens are valid for 30 days only, use this function
	 * to check whether or not the current long-lived token stored in
	 * the database is still valid. To be used in a cron job.
	 *
	 * @return void
	 */
	public function check_token_validity()
	{
		if (get_option('instagramLongLivedToken') === '') return;

		$longLivedToken = get_option('instagramLongLivedToken');
		$expirationTimestamp = get_option('instagramExpirationDate');
		$dayInSeconds = 60 * 60 * 24;
		$remainingDays = ((int) $expirationTimestamp - time()) / $dayInSeconds;

		if ($remainingDays <= 30) {
			$this->refresh_token($longLivedToken);
		}
	}

	/**
	 * Updates the long-lived token in the DB with a fresh one,
	 * and reset its expiration date.
	 *
	 * @param String $longLivedToken
	 * @return void
	 */
	public function refresh_token(String $longLivedToken)
	{
		$response = wp_remote_get("https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=$longLivedToken");

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			return wp_mail('krimo@pixelsmith.co', 'WP Error on the instagram API', $error_message);
		}

		$decoded = json_decode($response['body'], TRUE);
		$token = $decoded['access_token'];
		$expirationDate = $decoded['expires_in'];

		update_option('instagramLongLivedToken', $token);
		update_option('instagramExpirationDate', $expirationDate);
	}

	/**
	 * Gets the user instagram feed
	 *
	 * @return array
	 */
	public function get_user_media()
	{
		$token = get_option('instagramLongLivedToken');
		$baseURI = 'https://graph.instagram.com/' . get_option('instagramUserID')  . '/';

		$response = wp_remote_get($baseURI . 'media', [
			'body' => [
				'fields' => 'id,caption,media_url,permalink,media_type,thumbnail_url',
				'access_token' => $token
			]
		]);

		if (is_array($response) && !is_wp_error($response)) {
			return json_decode($response['body'])->data;
		}

		return [];
	}
}


// add_action('instagram_cron_hook', 'InstagramPixelsmithAPI::check_token_validity');


// if (isset($_GET['code'])) {
// 	InstagramPixelsmithAPI::generate_token(str_replace('#_', '', $_GET['code']));
// }
