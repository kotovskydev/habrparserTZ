<?php


class Db{

	public static function Connection()
	{

		$paramsPath = ROOT.'/config/db_params.php';
		$params = include($paramsPath);

		$dsn = "mysql:host={$params['host']};charset={$params['charset']};dbname={$params['dbname']}";
		$db = new PDO($dsn, $params['user'],$params['password']);

		return $db;
	}
}