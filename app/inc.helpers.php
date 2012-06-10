<?php

	class Breadcrumbs {

		static private $crumbs;

		static public function Eat() {
			global $site;
			$url = parse_url($site['url']);
			$request = $_SERVER['REQUEST_URI'];

			if(strlen($request)) {
				if(isset($url['path'])) {
					if (substr($request, 0, strlen($url['path'])) == $url['path']) {
						$request = substr($request, strlen($url['path']));
					}
				}

				$request = trim($request, '/');
			}

			if(strlen($request)) {
				Breadcrumbs::$crumbs = explode('/', $request);
			} else {
				Breadcrumbs::$crumbs = array('home');
			}
		}

		static public function Crumb($id) {
			if(isset(Breadcrumbs::$crumbs[$id])) {
				return Breadcrumbs::$crumbs[$id];
			}

			return null;
		}

	}

	function timeSince($date1, $date2 = null) {

		if(!$date2) $date2 = "now";

		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);

		if(!$date1 || !$date2) return;

		$interval = $date1->diff($date2);
		$diff = '';

		if($interval->y) {
			if($interval->y) $diff .= $interval->y . ' years, ';
			if($interval->m) $diff .= $interval->m . ' months, ';
		} elseif($interval->m) {
			if($interval->m) $diff .= $interval->m . ' months, ';
			if($interval->d) $diff .= $interval->d . ' days, ';
		} elseif($interval->d >= 7) {
			$fraction = $interval->d / 7;
			$days = $interval->d - (round($fraction) * 7);

			$diff .= round($fraction) . ' weeks, ';
			if($days && $days >= 1) $diff .= $days . ' days, ';
		} else {
			if($interval->d) $diff .= $interval->d . ' days, ';
			if($interval->h) $diff .= $interval->h . ' hours, ';
			if($interval->i) $diff .= $interval->i . ' minutes, ';
			if($interval->s) $diff .= $interval->s . ' seconds, ';
		}

		if(strpos($diff, '1 weeks,') !== false) $diff = str_replace('1 weeks,', '1 week,', $diff);

		if($interval->y == 1) $diff = str_replace('years', 'year', $diff);
		if($interval->m == 1) $diff = str_replace('months', 'month', $diff);
		if($interval->d == 1) $diff = str_replace('days', 'day', $diff);

		if($interval->h == 1) $diff = str_replace('hours', 'hour', $diff);
		if($interval->i == 1) $diff = str_replace('minutes', 'minute', $diff);
		if($interval->s == 1) $diff = str_replace('seconds', 'second', $diff);

		$diff = rtrim(trim($diff), ',');
		return $diff;
	}

	function Cleanup() {
		// Clear out temporary cookie storage.
		Sessions::storageDelete(true);

		// Send footer, echo buffer, etc.
		Views::Close();
	}

	function Encrypt($string, $uniqueSalt = '') {
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(CFG_API_KEY . $uniqueSalt), $string, MCRYPT_MODE_CBC, md5(md5(CFG_API_KEY . $uniqueSalt))));
	}

	function Decrypt($string, $uniqueSalt = '') {
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(CFG_API_KEY . $uniqueSalt), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(CFG_API_KEY . $uniqueSalt))), "\0");
	}
