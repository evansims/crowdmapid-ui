<?php

	class Service {

		public static $session;
		public static $api;
		public static $user;

		public static function About() {
			return Service::apiCall("GET", "/about");
		}

		public static function Register($email, $password) {

		}

		public static function Login($user_id, $password) {
			return Service::apiCall("GET", "/user/{$user_id}/password", array('password' => $password));
		}

		public static function Session($user_id, $session_id) {
			return Service::apiCall("GET", "/user/{$user_id}/sessions/{$session_id}");
		}

		public static function User($user_id) {
			$user = Service::apiCall("GET", "/user/{$user_id}");
			if($user && isset($user->user)) {
				Service::$user = $user->user;
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

		public static function updatePassword($password) {
			return Service::apiCall("POST", "/user/:user_id/password/", array('password' => $password));
		}

		public static function Admin() {
			return false;
		}

		static function apiCall($method, $url, $params = array()) {

			$api = &Service::$api;

			if(!$api) {
				$api = curl_init();
			}

			if ($api)
			{
				$params = array_merge(array(
					'api_secret' => CFG_API_KEY,
					'api_version' => '2'
				), $params);

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
				return $resp;
			}

		}

	}
