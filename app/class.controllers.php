<?php

	class Controllers {

		public static function Home() {
			$user = Service::$user;

			$passwordChanged = (isset($user->password_last_changed) ? timeSince($user->password_last_changed) . ' ago' : 'never');
			if(strpos($passwordChanged, 'year') || strpos($passwordChanged, 'month')) $passwordChanged .= '. You should change your password';

			Views::Render("home", array(
				'user' => $user,
				'passwordChanged' => $passwordChanged,
				'accountRegistered' => timeSince($user->registered) . ' ago'
			));
			Cleanup();
		}

		public static function Accounts() {
			$user = &Service::$user;

			$add_action_message = null;
			$add_action_error = null;
			$add_action_value = '';

			$edit_action_message = null;
			$edit_action_error = null;

			if(isset($_POST['activity'])) {

				if($_POST['activity'] == 'add') {
					$ret = Service::registerAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$add_action_message = 'Address registered successfully.';
						$user->emails = $ret->emails;
					} else {
						$add_action_value = $_POST['email'];
						if(isset($ret->error)) {
							$add_action_error = $ret->error;
						} else {
							$add_action_error = 'There was a problem registering this address.';
						}
					}
				} elseif($_POST['activity'] == 'promote') {
					$ret = Service::promoteAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$edit_action_message = "Address promoted successfully. {$_POST['email']} is now your primary account.";
						$user->emails = $ret->emails;
					} else {
						if(isset($ret->error)) {
							$edit_action_error = $ret->error;
						} else {
							$edit_action_error = 'There was a problem promoting this address.';
						}
					}
				} elseif($_POST['activity'] == 'remove') {
					$ret = Service::removeAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$edit_action_message = "Address removed successfully.";
						$user->emails = $ret->emails;
					} else {
						if(isset($ret->error)) {
							$edit_action_error = $ret->error;
						} else {
							$edit_action_error = 'There was a problem removing this address.';
						}
					}
				}

			}

			Views::Render("accounts", array(
				'user' => $user,
				'add_action_message' => $add_action_message,
				'add_action_error' => $add_action_error,
				'add_action_value' => $add_action_value,
				'edit_action_message' => $edit_action_message,
				'edit_action_error' => $edit_action_error
			));
			Cleanup();
		}

		public static function Security() {
			global $secretQuestions;

			$questions = array();
			for($i = 0; $i <= 10; $i++) {
				$r = null;
				while($r == null || isset($questions[$r])) {
					$r = mt_rand(0, count($secretQuestions) - 1);
				}
				$questions[$r] = $secretQuestions[$r];
			}
			$user = &Service::$user;

			$passwordChanged = (isset($user->password_last_changed) ? timeSince($user->password_last_changed) . ' ago' : 'never');
			if(strpos($passwordChanged, 'year') || strpos($passwordChanged, 'month')) $passwordChanged .= '. You should change your password';

			if(isset($_POST['activity'])) {

				if($_POST['activity'] == 'password') {
					$password = (isset($_POST['password']) ? $_POST['password'] : null);
					$new_password = (isset($_POST['new_password']) ? $_POST['new_password'] : null);
					$confirm_password = (isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null);

					$ret = Service::registerAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$add_action_message = 'Address registered successfully.';
						$user->emails = $ret->emails;
					} else {
						$add_action_value = $_POST['email'];
						if(isset($ret->error)) {
							$add_action_error = $ret->error;
						} else {
							$add_action_error = 'There was a problem registering this address.';
						}
					}
				} elseif($_POST['activity'] == 'yubikey_pair') {
					$ret = Service::promoteAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$edit_action_message = "Address promoted successfully. {$_POST['email']} is now your primary account.";
						$user->emails = $ret->emails;
					} else {
						if(isset($ret->error)) {
							$edit_action_error = $ret->error;
						} else {
							$edit_action_error = 'There was a problem promoting this address.';
						}
					}
				} elseif($_POST['activity'] == 'question') {
					$ret = Service::removeAddress($_POST['email']);
					if(isset($ret->success) && $ret->success) {
						$edit_action_message = "Address removed successfully.";
						$user->emails = $ret->emails;
					} else {
						if(isset($ret->error)) {
							$edit_action_error = $ret->error;
						} else {
							$edit_action_error = 'There was a problem removing this address.';
						}
					}
				}

			}

			Views::Render("security", array(
				'user' => $user,
				'questions' => $questions,
				'passwordChanged' => $passwordChanged
			));
			Cleanup();
		}

		public static function Login() {
			Views::Render("login");
			Cleanup();
		}

		public static function Logout() {
			Sessions::ResetCookie();
			Sessions::storagePut('login_message', 'You have been successfully logged out.', true);
			Views::Redirect('login');
		}

	}

