<?php
namespace app\library;
/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Firebase {
    public $error='';
    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $this->createFromArray($message),
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $this->createFromArray($message),
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $this->createFromArray($message),
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {


        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $config= \Phalcon\Di::getDefault()->getShared('configuration');

        $headers = array(
            'Authorization: key=' .$config->google_fcm_token ,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            $this->error='Curl failed: ' . curl_error($ch);
            return false;
        }
        curl_close($ch);

        // check if json is valid
        if( ! ($jsonResult = json_decode($result) )) {
            $this->error=$result;
            return false;
        }

        //handle json error
        if( ( !isset( $jsonResult->success ) || !$jsonResult->success ) //if no success message return
            && !isset( $jsonResult->message_id) // and no message_id returned
        ) {
            $this->error= (isset( $jsonResult->results) && isset( $jsonResult->results[0]) && isset( $jsonResult->results[0]->error)) ?
                $jsonResult->results[0]->error : $result;
            return false;
        }
        // Close connection


        return $result;
    }

    function createFromArray($data=[]){
        $res = array();
        if( isset( $data['title']) )$res['data']['title'] = $data['title'];
        $res['data']['message'] = isset( $data['message'])  ? $data['message'] : 'no-message' ;
        $res['data']['is_background'] = isset( $data['is_background'])  ? $data['is_background'] : false ;
        $res['data']['image'] = isset( $data['image'])  ? $data['image'] :'' ;
        $res['data']['payload'] = isset( $data['payload'])  ? (object)$data['payload'] : new \StdClass() ;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
        return  $res;
    }

    public function getUrlOfFile(){

    }
    //  Save the image file
    public function saveFile($file, $width = 0, $height = 0)
    {
        // load the image
        $image = new \UploadImage();

        $image->load($file['tmp_name'], 'push_notifications');
        if(! ($extension = $image->FileExtension($file["type"])))
        {
            $this->error ='invalid image type';
            return false;
        }
        if ($width != 0 && $height != 0) {
            $image->resize($width, $height);
        }
        $file_name='img_'.uniqid();


        $path = "img/" . date("Y") . "/push_notifications/" . $file_name . $image->FileExtension($file["type"]);
        $config = \Phalcon\Di::getDefault()->getShared('configuration');
        if (!is_dir($config->imgPath."" . date("Y") )){@mkdir($config->imgPath."" . date("Y"), 0777);}
        if (!is_dir($config->imgPath ."" . date("Y")."/push_notifications")){@mkdir($config->imgPath ."" . date("Y")."/push_notifications", 0777);}

        $image->save(
            $config->imgPath. "" . date("Y") . "/push_notifications/" . $file_name . $image->FileExtension($file["type"])
        );
        $url=\Phalcon\Di::getDefault()->getShared('url');
        $url=$url->getBaseUri();
        $src = $url.$path;

        // check if file uploaded
        // if (@getimagesize($src)) {
        //   $this->error = 'unable to save file';
        //   return false;
        //  }

        return $url.$path;
    }
    public function getValidationMessageText(){
        return $this->error;
    }
}
?>
