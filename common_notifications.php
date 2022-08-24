<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

//get all doctor list
if(!function_exists('ios_notification')){
    function ios_notification($deviceId,$deviceType,$notification,$type){

        $CI = & get_instance();
        $CI->load->database();

        if($deviceType=='ios'){

            if(isset($deviceId) && !empty($deviceId)){

                $deviceToken =$deviceId;

                $passphrase = 'think360';

                $ctx = stream_context_create();
                
                if($type=="doctor"){
                    $path=APPPATH.'third_party/DadDoctorProduction.pem';
                }else{
                    $path=APPPATH.'third_party/dadPatientPushNotifications.pem';
                }
                stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
                stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
                //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
                if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);
                $body['aps']['alert']=$notification;
                $body['aps']['sound'] = 'default';
                $body['aps']['badge'] = '1';
                $payload = json_encode($body);
                @$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
                fclose($fp);
                return $result;
            }
        }
    }
}

//get all health category list
if(!function_exists('android_notification')){
    function android_notification($deviceId,$deviceType,$notification,$type){
        $CI = & get_instance();
        $CI->load->database();
        if($deviceType=='android'){
            if(isset($deviceId) && !empty($deviceId)){
                $url = 'https://fcm.googleapis.com/fcm/send';
                if($type=="doctor"){
                    $serverApiKey = "AAAAcY4pk3o:APA91bFcLVWtHYFNRANZdG_BTOBevF9UU_9NJ6j0DVtTY8kO3diCxcy5VQvmkG10q-X98WZD515XzHQe4ar9jwZL4FgSJXxjVU3dwvmWBIpi4Owg9Q0gFzN-IqcxCo6_AA3vLZB_3DUp";
                }else{
                    $serverApiKey = "AAAA5Uh4oZU:APA91bG6_e-ol696n5nNiChzlIS-bKS4d-dQxY5NMR6cwA4uykZ2QH9KzvghwpU4eSWhL7E-ODAVUBsICLH1Ax0RWNoNOoUXIz5qb8wMfna8VAqp0urGgpLRspBRNqHJ0vyZBgNi97gA";
                }
                $deviceToken =$deviceId;
                if(!empty($deviceToken)){
                    $headers = array(
                                    'Content-Type:application/json',
                                    'Authorization:key=' . $serverApiKey
                                );
                    $data = array(
                                'registration_ids' => array($deviceToken),
                                'data' =>$notification
                            );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    return $result;
                }
            }
        }
    }
}
