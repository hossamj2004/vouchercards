<?php
/**
 * Description of forgetPassword
 *
 * @author hossam
 */
namespace app\library;

class forgetPassword {
    public $client;
    public $validationMessage;
    //------------------------------------------------------------------------
    // password function
    //------------------------------------------------------------------------
    /**
     * @return bool
     * generate jwt token and send it to user email
     */
    public function resetPasswordMessage($email){
        if($client = $this->setCurrentAccountByMail($email)){
            $config= \Phalcon\Di::getDefault()->getShared('configuration') ;
            $code = $this->encode($this->client->email,'resetPassword');
            $link = 'http://' . $_SERVER['SERVER_NAME'] . $config->application->baseUri . '/client/#/setPassword/'.$code;

            //todo move to file
            $message='
                 <table cellspacing="0" cellpadding="0" style="width: 100%;">
                     <tr>
                         <td style="padding:0 1em;">
                             <h3 style="color:#48b5df; font-size:1.4em; text-align:center; font-weight:bold; margin:30px 0;">Reset Password</h3>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:0 1em; text-align:center;">
                             <p style="text-align:left; margin:0; font-size:1em; line-height:1.5em;color: #999; -webkit-font-smoothing: antialiased;">
                                 A request to reset your Naqla account password has been made. Click the following button to reset your password
                             </p>

                             <a style="background-color: #48b5df; color:#FFFFFF; display:inline-block; text-decoration:none; padding:10px 30px; margin:15px; border-radius:5px;" href="'.$link.'">
                                 Reset Password
                             </a>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:1em;">
                             <p style="margin:0; font-size:1em; line-height:1.5em; color: #999; -webkit-font-smoothing: antialiased;">
                                 If the button above does not work, try copying and pasting the following URL into your browser
                                 <span style="color:#48b5df; word-wrap: break-word; word-break: break-all; display:block !important; padding:20px 0;"> '.$link.' </span>
                                 <strong>Note:</strong> If you did not make this request, simply ignore this email.
                             </p>

                         </td>
                     </tr>

                 </table>
            ';

            if( $this->sendUserMessage('Naala password reset',$message) )
                return true;
            else{
                $this->validationMessage = "Error sending Email";
                return false;
            }
        }

        else return false;
    }
    /**
     * @param $jwt
     * @return bool|Model
     * this function to detect if $jwt is correct
     */
    public function resetPasswordLinkIsValid($jwt){
        if(  $email = $this->decode($jwt,'resetPassword') ){
            if($client = $this->setCurrentAccountByMail($email)){
                return $client;
            }
            else return false;
        } else{
            $this->validationMessage = "الرابط غير صحيح أو منتهي الصلاحية الرجاء التأكد من صحة الرابط.";
            return false ;
        }
    }
    public function setUserPassword($newPassword){
        $client = $this->client;
        $security = \Phalcon\Di::getDefault()->getShared('security');
        if(strlen($newPassword) < 6 ) {
            $this->validationMessage ="password must be 6 characters";
            return false;
        }
        $client->password= $security->hash(($newPassword) );
        if($client){
            if( $client->save()){
                return true;
            }else{
                $this->validationMessage = $client->getMessages();
                return false;
            }
        }else{
            $this->validationMessage = 'Email does not exist.';
            return false;
        }
    }
    /**
     * @param $email
     * @return bool|Model
     * set $this->client by Email address
     */
    public function setCurrentAccountByMail($email){
        if( $email)
            $client = \Client::findFirst(array(
                'conditions'=>'LOWER(email) = LOWER(:email:) ',
                'bind' => array('email'=>$email)
            ));
        else{
            $this->validationMessage = 'No Client Exist';
            return false;
        }
        return $this->setClient($client);
    }
    public function getValidationMessageText(){
        return $this->validationMessage;
    }
    /**
     * @param $client
     * @return bool
     * a simple function to set $this->client
     */
    public function setClient($client){
        if( $client ){
            $this->client =$client ;
            return $client;
        }
        else{
            $this->validationMessage = " هذا البريد الإلكتروني غير مسجل لدينا";
            return false ;
        }
    }
    //----------------------------------------------------------------------------
    // general functions
    //----------------------------------------------------------------------------
    /**
     * @param $message
     * @return bool
     * read text of the message and send it as email to $this->client
     */
    public function sendUserMessage($title,$message){

        $notification = new \Notification();
        $template=   $notification->getTemplateHtml('layout',
            ['subject'=>$title,
                'content'=>$message]);

        return \Email::sendEmail($template,$title,$this->client->email);
    }
    public $key ='$%$2jjsdppkjy';
    public function encode($message,$page=''){
        if( $page =='api') $expTime= @time() +60*24*60*60;
        elseif( $page=='draws')$expTime= @time() +10*24*60*60;
        else $expTime= @time() +24*60*60;
        $token = array(
            "message" => $message,
            "page" => $page,
            "iss" => "http://rewardskit.com",
            "iat" => @time(),
            "nbf" =>  @time(),
            "exp" =>  $expTime,
        );


        $jwt = \JWT::encode($token, $this->key);
        return $jwt;
    }
    public function decode($jwt,$page=''){
        try{
            $decoded = \JWT::decode($jwt, $this->key,false );
            if( !$this->verifyJWT($decoded) )
                return false ;
        }catch (\Exception $e){
            return false;
        }

        if($decoded && $decoded->iss=="http://rewardskit.com" && $page == $decoded->page  )
            return $decoded->message;

        $this->validationMessage = 'Invalid token';
        return false ;


    }


    public function verifyJWT($payload){
        if ($payload) {

            if (isset($payload->nbf) && $payload->nbf > time()) {
               $this->validationMessage='Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->nbf);
                return false;
            }

            if (isset($payload->iat) && $payload->iat > time()) {
                $this->validationMessage='Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->iat);
                return false;
            }


            if (isset($payload->exp) && time() >= $payload->exp) {
                $this->validationMessage='Expired token';
                return false;
            }
        }
        return $payload;
    }
}
