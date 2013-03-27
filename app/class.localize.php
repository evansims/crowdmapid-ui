<?php

	class Localize {

		public static function contextPlural($string, $count, $singular, $plural, $none = null) {

			if ( ! $none) $none = $string;

			if ($count === 0) return str_replace(array(':count', ':context'), array($count, $plural), $none);
			elseif ($count === 1) return str_replace(array(':count', ':context'), array($count, $singular), $string);
			else return str_replace(array(':count', ':context'), array($count, $plural), $string);

		}

	}