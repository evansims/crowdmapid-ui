<?php

	class Views {

		private static $headerSent;

		public static function Erase() {
			ob_clean();
			Views::$headerSent = false;
		}

		public static function Render($template, $variables = array()) {
			global $site, $page;

			if($variables)
				extract($variables, EXTR_SKIP);

			if(!Views::$headerSent) {
				Views::$headerSent = true;
				require('app/views/header.php');
			}

			require("app/views/{$template}.php");
		}

		public static function Close() {
			if(Views::$headerSent) {
				require('app/views/footer.php');
			}

			$out = ob_get_clean();
			echo $out;
			exit;
		}

		public static function Redirect($url) {
			if(substr($url, 0, 4) != 'http') {
				global $site;
				$url = $site['url'] . '/' . $url;
			}

			Views::Erase();
			header("Location: $url");
			Cleanup();
		}

	}
