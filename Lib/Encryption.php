<?php
class Encryption {
	public static function generateEncryptedPassword($password){
		return password_hash($password,1);
	}
}
