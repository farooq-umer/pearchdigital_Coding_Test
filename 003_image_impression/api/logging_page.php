<?php
/**
 * 
 */
class LogApi
{
	public function log()
	{
		try {
			$logFilePath = '../log.log';

			if( !isset($_POST['img_impression']) ) {
				$request = 'POST:' . print_r($_POST, 1);
				throw new Exception('img_impression is missing ' . $request);
			}

			$log = 'POST:' . print_r($_POST, 1);
			$islogged = error_log( $log, 3, $logFilePath);

			if(!$islogged) {
				$res = ['error' => 'Check file permissions'];
				echo json_encode($res);
				return;
			}

			$res = ['success' => 'Image Impression is logged'];
			echo json_encode($res);   
	   }
	   catch (Exception $e) {
	   		$dateTime = date('Y-m-d H:i:s');
	   		$log = 'Date: ' . $dateTime . ' Caught Exception: ' . $e->getMessage() . "\n";
	   		error_log( $log, 3, $logFilePath );

	   		$res = ['error' => 'Caught Exception: Check logs'];
			echo json_encode($res);
	   }
	}
}

$api = new LogApi();
$api->log();
