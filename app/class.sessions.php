<?php

	// Cookies expire after 30 minutes. Set this to 0 to expire on session.
	define('SESSION_COOKIE_EXPIRE_SECONDS', 1800);
	define('SESSION_REAUTHORIZE_SECONDS', 120);

	class Sessions {

		static public $loggedin;
		static public $data;

		static public function Check() {

			global $page;
			$login_error = null;

			if (isset($_COOKIE['cmid_user_id']) && isset($_COOKIE['cmid_session_id'])) { // && isset($_COOKIE['cmid_activity'])) {
				Sessions::$data['user_id'] = Decrypt($_COOKIE['cmid_user_id'], $_SERVER['HTTP_USER_AGENT']);
				Sessions::$data['session_id'] = Decrypt($_COOKIE['cmid_session_id'], $_SERVER['HTTP_USER_AGENT']);

				if(strlen(Sessions::$data['user_id']) == 128 && strlen(Sessions::$data['session_id']) == 64) {
					$session = Service::Session(Sessions::$data['user_id'], Sessions::$data['session_id']);

					if($session) {
						if($session->success) {
							Service::User(Sessions::$data['user_id']);

							Sessions::SetCookie();
							Sessions::$loggedin = true;
							return true;
						} else {
							$page['errors']['login_error'] = $session->error;
						}
					} else {
						$page['errors']['login_error'] = 'The service is currently unavailable. Please try again later.';
					}
				}
			}

			if (isset($_POST['email']) && isset($_POST['password'])) {
				$auth = Service::Login($_POST['email'], $_POST['password']);

				if($auth) {
					if($auth->success) {
						Sessions::$data['user_id'] = $auth->user_id;
						Sessions::$data['session_id'] = $auth->session_id;

						Service::User(Sessions::$data['user_id']);

						Sessions::SetCookie();
						Sessions::$loggedin = true;
						return true;
					} else {
						$page['errors']['login_error'] = $auth->error;
					}
				} else {
					$page['errors']['login_error'] = 'The service is currently unavailable. Please try again later.';
				}
			}

			Sessions::ResetCookie();
			Sessions::$loggedin = false;

			$p = Breadcrumbs::Crumb(0);
			if($p != 'login' && $p != 'logout' && $p != 'register' && $p != 'recovery') {
				Views::Redirect('login');
			}

			return false;

		}

		static public function SetCookie() {
			$expire = SESSION_COOKIE_EXPIRE_SECONDS;
			if($expire) $expire = time() + SESSION_COOKIE_EXPIRE_SECONDS;

			$secure = false;
			if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS'])) $secure = true;

			$server = $_SERVER['HTTP_HOST'];

			setcookie('cmid_user_id', Encrypt(Sessions::$data['user_id'], $_SERVER['HTTP_USER_AGENT']), $expire, '/',  $server, $secure, true);
			setcookie('cmid_session_id', Encrypt(Sessions::$data['session_id'], $_SERVER['HTTP_USER_AGENT']), $expire, '/', $server, $secure, true);
			//setcookie('cmid_activity', Encrypt(time() + SESSION_REAUTHORIZE_SECONDS, $_SERVER['HTTP_USER_AGENT']), $expire, '/', $server, $secure, true);
		}

		static public function ResetCookie() {
			$secure = false;
			if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS'])) $secure = true;

			$server = $_SERVER['HTTP_HOST'];

			$expire = time() - 31556926;

			setcookie('cmid_user_id', null, $expire, '/', $server, $secure, true);
			setcookie('cmid_session_id', null, $expire, '/', $server, $secure, true);
			//setcookie('cmid_activity', '', time() - 31556926, '/', $erver, $secure, true);

			Sessions::storageDelete(false);
		}

		static public function storageDelete($temporary, $specific = false) {
			$secure = false;
			if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS'])) $secure = true;

			$server = $_SERVER['HTTP_HOST'];

			$expire = time() - 31556926;

			if ($temporary) {
				if (!isset($_COOKIE['cmid_storage_temporary'])) return true;
				$cookies = $_COOKIE['cmid_storage_temporary'];

				foreach($cookies as $cookie => $value) {
					if(!$specific || $cookie == $specific) {
						setcookie("cmid_storage_temporary[{$cookie}]", '', $expire, '/', $server, $secure, true);
					}
				}
			} else {
				if (!isset($_COOKIE['cmid_storage'])) return true;
				$cookies = $_COOKIE['cmid_storage'];

				foreach($cookies as $cookie => $value) {
					if(!$specific || $cookie == $specific) {
						setcookie("cmid_storage[{$cookie}]", '', $expire, '/', $server, $secure, true);
					}
				}
			}

			return true;
		}

		static public function storagePut($key, $value, $temporary = true) {
			$expire = SESSION_COOKIE_EXPIRE_SECONDS;
			if($expire) $expire = time() + SESSION_COOKIE_EXPIRE_SECONDS;

			$secure = false;
			if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS'])) $secure = true;

			$server = $_SERVER['HTTP_HOST'];

			if($temporary) {
				setcookie("cmid_storage_temporary[{$key}]", $value, 0, '/', $server, $secure, true);
			} else {
				setcookie("cmid_storage[{$key}]", $value, $expire, '/', $server, $secure, true);
			}
		}

		static public function storageGet($key, $temporary) {
			if ($temporary) {
				if(isset($_COOKIE['cmid_storage_temporary'][$key])) {
					return $_COOKIE['cmid_storage_temporary'][$key];
				}
			} else {
				if(isset($_COOKIE['cmid_storage'][$key])) {
					return $_COOKIE['cmid_storage'][$key];
				}
			}

			return false;
		}

	}
