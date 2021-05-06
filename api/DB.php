<?php

namespace Api;

use PDO;
use Exception;

class DB
{

	private  $dbHost;
	private  $dbName;
	private  $dbUser;
	private  $dbPass;

	public 	$conn;

	public function __construct($config)
	{
		$this->dbHost = $config['dbHost'];
		$this->dbName = $config['dbName'];
		$this->dbUser = $config['dbUser'];
		$this->dbPass = $config['dbPass'];

		$this->connectDB();
	}

	private function connectDB()
	{
		try {
			$this->conn = new PDO("sqlsrv:server =" . $this->dbHost . ";Database = " . $this->dbName . ";", $this->dbUser, $this->dbPass);
			if (!$this->conn) {
				throw new Exception('Can\'t connect to database');
			}
			return $this->conn;
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	public function create($phone, $message)
	{
		$sql = "insert into Notification_Trans (Type,From_Address,To_Address,To_Host, Text, ID_Gateway, Date_in, User_n, Status) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute(['03', 'MDO MATIN', $phone, '217.8.32.135', $message, 'Matin', date("Y-m-d H:i:s"), '500', '01']);

		if (!$result) {
			return false;
		}
		return true;
	}
}
