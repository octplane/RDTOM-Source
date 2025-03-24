<?php 
class CookieTokenHandler
{
	public function set($token_string) {
		setcookie("token", $token_string, time() + 2678400);
	}

	public function get() {
		return $_COOKIE["token"];
	}
}
