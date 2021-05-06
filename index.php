<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60" ;>
    <link rel="icon" href="demo_icon.gif" type="image/gif" sizes="16x16">
	<link rel="stylesheet" href="./public/style.css" />
    <script src="./public/app.js"></script>
    <title>Matin</title>
</head>

<body>

    <div id="clock">
        <div id="date"></div>
        <ul>
            <li id="hours"></li>
            <li id="point">:</li>
            <li id="mins"></li>
            <li id="point">:</li>
            <li id="secs"></li>
        </ul>
    </div>

<?php
	require_once __DIR__ . "/vendor/autoload.php";

	use Api\{DB, Response, FileReader, PingIpAddress};


	$db = (new FileReader('config/db.json'))->json();
	$data = (new FileReader('config/messages.json'))->json();

	function pingIp($ip) 
	{
		$result = 0;
		exec("ping -n 2 $ip", $output, $status);

		for($i = 2; $i < 4; $i++)
		{
			$ping = iconv('CP866','UTF-8',$output[$i]) . "<br/>";
			if(preg_match('/'.'число байт'.'/i', $ping))
			{
				$result++;
			} 
		}
		return $result;
	}


	foreach($data['ip'] as $key => $ip)
	{
		$state = pingIp($key);
		if($state == 0)
		{
			date_default_timezone_set("Asia/Dushanbe");
			$message = "Компьютер {$ip} не работает " . date("Y-m-d H:m:s"); 
			foreach($data['phone'] as $phone) 
			{
				$result = (new DB($db['MSSQL']))->create($phone, $message);
			}
		}
	}

?>

</body>

</html>