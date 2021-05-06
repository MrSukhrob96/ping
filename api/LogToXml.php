	<?php
	
	$logPath = realpath('Y:\Событие-2015.10.17-14.10.log');
	$txtPath = dirname( __FILE__ ).'\\test.txt';

    function readLog($fileName)
    {
        if (file_exists($fileName))
		{
            try {
                $file = @file($fileName);
                if ($file)
				{
                    return $file;
                } else 
				{
                    return 'is empty';
                }
            } 
			catch (Exception $ex)
			{
                echo $ex->getMessage();
            }
        } else 
		{
				return 'error';
		}
        return 'Not found';
    }
	

    function updateData($fileDir, $data)
    {
        if (file_exists(dirname(__FILE__ . '\\' . $fileDir)))
		{
            if (file_put_contents($fileDir, $data))
                return true;
        }

        return false;
    }


    function array_key_last($array)
    {
        if (!is_array($array) || empty($array))
		{
            return NULL;
        }
        $arrayKey = array_keys($array)[count($array) - 1];
        return $arrayKey;
    }


    function smsData($logNum, $txtNum, $logPath)
    {
        $array = array();
        if ($logNum >  $txtNum) 
		{
            for ($i = $txtNum + 1; $i <= $logNum; $i++) 
			{
                $array[] = readLog($logPath)[$i];
            }
            updateData('test.txt', array_key_last(readLog($logPath)));
        } else 
		{
            updateData('test.txt', array_key_last(readLog($logPath)));
        }

        return $array;
    }



    function createXmlMessage($message, $phoneNumber)
    {
        $dom = new DomDocument('1.0', 'UTF-8');
        $report = $dom->appendChild($dom->createElement('Report'));
        $customers = $report->appendChild($dom->createElement('Customers'));
		for($i = 0; $i < count($phoneNumber); $i++)
		{
			$customer = $customers->appendChild($dom->createElement('Customer'));
			$customer->setAttribute('CustomerText', $message);
			$customer->setAttribute('CustomerTel', $phoneNumber[$i]);
		}
        $dom->formatOutput = true;
        $dom->saveXML();
        $dom->save('X:/in/sms.xml');
    }

	function checkHasIp($ip, $data)
	{
		$num = 0;
		
		foreach($ip as $key => $i)
		{
			$preg = '/'.$i.'/i';
			if(preg_match($preg, $data))
			{
				$num++;
			}
		}
		
		return $num;
	}

    $logNum = array_key_last(readLog($logPath));
    $txtNum =  readLog($txtPath)[0];	

	if(!empty($txtNum)){
		updateData('test.txt', array_key_last(readLog($logPath)));
	}

	$sms = smsData($logNum, $txtNum, $logPath);

	
	$ipList = [
		"192.168.10.96",
		"192.168.10.217",
		"192.168.10.104",
		"192.168.11.103",
		"192.168.10.204",
		"192.168.10.212",
		"192.168.10.217",
		"192.168.10.140",
		"46.20.206.246"
	];
	
	$phoneNumber = [
		'992928444727',
		'992928390028',
		'992929449304',
		'992926352444',
		'992927568388',
		'992927560111',
		'992929074057'
	];
	
    if (!empty($sms)) 
	{
        $message = '';

        foreach ($sms as $key => $data)
		{
			$off = preg_match('/таймаут/i', $data);
			
			if($off)
			{
				$n = checkHasIp($ipList, $data);
				if($n)
				{
					$message .= $data . ' ';
				}
			}
							
        }
		
		if(trim($message) != ''){
			createXmlMessage($message, $phoneNumber);
		}
	}
