<?php

	class Service {

		public static $session;
		public static $api;
		public static $user;

		public static function About() {
			return Service::apiCall("GET", "/about");
		}

		public static function Register($email, $password) {
			return Service::apiCall("POST", "/user/{$user_id}", array('email' => $email, 'password' => $password));
		}

		public static function Login($user_id, $password) {
			return Service::apiCall("GET", "/user/{$user_id}/password", array('password' => $password));
		}

		public static function Session($user_id, $session_id) {
			return Service::apiCall("GET", "/user/{$user_id}/sessions/{$session_id}");
		}

		public static function User($user_id, $just_return = false) {
			$user = Service::apiCall("GET", "/user/{$user_id}");
			if($user && isset($user->user)) {
				if(!$just_return) {
					Service::$user = $user->user;
				}
			}
			return $user;
		}

		public static function getEmails() {
			return Service::apiCall("GET", "/user/:user_id/emails");
		}

		public static function getPasswordChangeDate() {
			return Service::apiCall("GET", "/user/:user_id/password");
		}

		public static function registerAddress($address) {
			return Service::apiCall("POST", "/user/:user_id/emails", array('email' => $address));
		}

		public static function promoteAddress($address) {
			return Service::apiCall("PUT", "/user/:user_id/emails/{$address}", array('primary' => 1));
		}

		public static function removeAddress($address) {
			return Service::apiCall("DELETE", "/user/:user_id/emails/{$address}");
		}

		public static function checkPassword($password) {
			return Service::apiCall("GET", "/user/:user_id/password/", array('password' => $password));
		}

		public static function changePassword($password) {
			return Service::apiCall("POST", "/user/:user_id/password/", array('password' => $password));
		}

		public static function getChallengeQuestion() {
			return Service::apiCall("GET", "/user/:user_id/challenge/question/");
		}

		public static function setChallengeQuestion($question) {
			return Service::apiCall("PUT", "/user/:user_id/challenge/question/", array('question' => $question));
		}

		public static function setChallengeAnswer($answer) {
			return Service::apiCall("PUT", "/user/:user_id/challenge/answer/", array('answer' => $answer));
		}

		public static function setPhone($number) {
			return Service::apiCall("POST", "/user/:user_id/phone/", array('phone' => $number));
		}

		public static function confirmPhone($number, $code) {
			return Service::apiCall("POST", "/user/:user_id/phone/confirm/", array('phone' => $number, 'code' => $code));
		}

		public static function getYubikeyPair() {
			return Service::apiCall("GET", "/user/:user_id/security/yubikey/");
		}

		public static function setYubikey($otp) {
			return Service::apiCall("POST", "/user/:user_id/security/yubikey/", array('otp' => $otp));
		}

		public static function deleteYubikey() {
			return Service::apiCall("DELETE", "/user/:user_id/security/yubikey/");
		}

		public static function loginFacebook($return_trip, $appid, $secret, $scope) {
			return Service::apiCall("GET", "/facebook/register/", array('return_trip' => $return_trip, 'fb_appid' => $appid, 'fb_secret' => $secret, 'fb_scope' => $scope));
		}

		public static function Admin() {
			return false;
		}

		static function apiCall($method, $url, $params = array()) {

			$api = &Service::$api;

			if(Sessions::$loggedin) {
				$params['user_id'] = Sessions::$data['user_id'];
				$params['session_id'] = Sessions::$data['session_id'];
			}

			if(!$api) {
				$api = curl_init();
			}

			if ($api)
			{
				$params = array_merge(array(
					'api_secret' => CFG_API_KEY,
					'api_version' => '2'
				), $params);

				$url = ltrim($url, '/');

				if(strpos($url, ':') !== false && Sessions::$data) {
					foreach(Sessions::$data as $skey => $sval) {
						$url = @str_replace(":{$skey}", $sval, $url);
					}
				}

				foreach($params as $key => $val) {
					if(strpos($val, '%') !== false) {
						foreach($session as $skey => $sval) {
							$val = @str_replace("%{$skey}%", $sval, $val);
						}
						$params[$key] = $val;
					}
				}

				if($method == 'GET') {
					curl_setopt($api, CURLOPT_POST, false);
					curl_setopt($api, CURLOPT_POSTFIELDS, NULL);
					curl_setopt($api, CURLOPT_HTTPGET, true);

					if(count($params)) {
						$url .= '?';
						foreach($params as $p => $v) $url .= $p . '=' . urlencode($v) . '&';
						$url = rtrim($url, '&');
					}
				} elseif($method == 'POST') {
					curl_setopt($api, CURLOPT_POST, true);
					curl_setopt($api, CURLOPT_POSTFIELDS, $params);
				} elseif($method == 'PUT') {
					curl_setopt($api, CURLOPT_POST, false);
					curl_setopt($api, CURLOPT_POSTFIELDS, NULL);
					curl_setopt($api, CURLOPT_CUSTOMREQUEST, "PUT");

					if(count($params)) {
						$url .= '?';
						foreach($params as $p => $v) $url .= $p . '=' . urlencode($v) . '&';
						$url = rtrim($url, '&');
					}
				} elseif($method == 'DELETE') {
					curl_setopt($api, CURLOPT_POST, false);
					curl_setopt($api, CURLOPT_POSTFIELDS, NULL);
					curl_setopt($api, CURLOPT_CUSTOMREQUEST, "DELETE");

					if(count($params)) {
						$url .= '?';
						foreach($params as $p => $v) $url .= $p . '=' . urlencode($v) . '&';
						$url = rtrim($url, '&');
					}
				}

				curl_setopt($api, CURLOPT_URL, CFG_API_ENDPOINT . $url);

				curl_setopt($api, CURLOPT_TIMEOUT, 3);

				curl_setopt($api, CURLOPT_HEADER, false);
				curl_setopt($api, CURLOPT_RETURNTRANSFER, true);

				$raw = curl_exec($api);
				$resp = json_decode($raw);

				if(defined('CFG_DEBUG_LOGGING') && CFG_DEBUG_LOGGING == TRUE) {
					file_put_contents('service.log', "-> " . CFG_API_ENDPOINT . $url . "\n\n<- " . $raw . "\n\n", FILE_APPEND);
				}

				return $resp;
			}

		}

	}
