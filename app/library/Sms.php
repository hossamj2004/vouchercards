<?php 

namespace app\library;

/**
 * General Functions
 * 
 * This class contains function used to send sms
 * 
 * @package		EnticeKit
 * @author		ِAhmed Awad <a.awad@starwallet.com>
 *
 */
class Sms
{
	/**
	 * Send sms to mobile
	 * 
     * @param string $number the mobile number you want to send sms to it
     * @param string $message the message you want to send it
     * @param string $lang the language of the message, default "en" as english
     * @param string $sender the sender name of the message, default "Starwallet"
     *
     * @return mixed|null|string result of the curl result.
     */
	
    public static function send_sms($number, $message, $lang="en", $sender="Starwallet", $provider="resalty")
	{

		$message = str_replace(" ", "%20", $message);

		$sms_api_url = "";
		switch ($provider)
		{
			case 'silverstreet':
				$username = "star1";
				$password = "IpvpEgkJ";
				
				$sms_api_url = "http://api.silverstreet.com/send.php?username=".$username."&password=".$password."&destination=".$number."&sender=".$sender."&body=".$message;
				
			break;

			case 'resalty':
				$username = "starwallet1";
				$password = "woeiru";
				
				//if we will use resalty the sender should be Starwallet
				$sender = "Starwallet";
					
				$sms_api_url = "http://www.resalty.net/api/sendSMS.php?userid=".$username."&password=".$password."&to=".$number."&msg=".$message."&sender=".$sender."&encoding=utf-8";
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sms_api_url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH,CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		$result = curl_exec($ch);
		curl_close($ch);

		$status='';
		if($provider == 'resalty'){

			$arr_result = explode("<br />",$result);
			$error_code = str_replace(' ', '', $arr_result[0]);
			$arr_error_code = explode(":", $error_code);
			if($arr_error_code[1]==1){
				self::sms_alert();
				return self::send_sms($number,self::strToHex($message),4,$sender,"silverstreet");
			}else{
				$status = $arr_error_code[1].'-'.$provider;
			}
			
		}else{

			$status .= $result.'-'.$provider;

		}

		return $status;
	}
	public static function sms_alert(){

		$error_count=0;
		if(isset($_SESSION['sms_error'])){
			$error_count = $_SESSION['sms_error'];
		}
		if($error_count < 3){
				$error_count++;	
				$_SESSION['sms_error'] = $error_count;
		}else{
			self::send_mail();
			$error_count = 0;
			$_SESSION['sms_error'] = $error_count;
		}

	}

	public static function send_mail(){

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->Host = "mail.starwallet.com";
		$mail->SMTPDebug = 2;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "mail.starwallet.com";
		$mail->Port = 465;
		$mail->Username = "support@starwallet.com";
		$mail->Password = "ggb1o*DVH2Qk";

		$mail->SetFrom('support@starwallet.com','Support');
		$mail->AddReplyTo('support@starwallet.com','Support');
		$mail->Subject = 'Sms failed to send';
		$mail->MsgHTML('<p>Alert!!, failed to send sms has been detected. Check sms_log for more detailed info.</p>');
		$mail->AddAddress('support@starwallet.com', "Support");

		$mail->Send();
	}
	
		public static function strToHex($string){
		   $hex = '';
		   for ($i=0; $i<strlen($string); $i++){
		       $ord = ord($string[$i]);
		       $hexCode = dechex($ord);
		       $hex .= substr('0'.$hexCode, -2);
		   }
		   return strToUpper($hex);
		}
}