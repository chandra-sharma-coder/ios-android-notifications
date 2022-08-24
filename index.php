 $message = str_replace("DateTime",$DateTime,str_replace("Name",$Name,str_replace("D",$D,$this->lang->line('notification_add_msg'))));

                                                    $this->common_model->insertData('notifications', 
                                                        array('Id'=>$Id, 
                                                            'notification'=>'New / Add', 
                                                            'message'=>$message, 
                                                            'status'=>0, 
                                                            'notificationFrom'=>$user->id, 
                                                            'notificationTo'=>$customer->id, 
                                                            'notificationFor'=>'sales', 
                                                            'creatorType'=>'user', 
                                                            'createdDate' => strtotime(date('Y-m-d H:i:s'))
                                                        )
                                                    );

                                                    if($userDetails->notificationSend == 0)
                                                    {
                                                        if($userDetails->deviceType == 'ios')
                                                        {
                                                            $notification=ios_notification($d->deviceId,$d->deviceType,$message,$type='usr');
                                                        }

                                                        if($userDetails->deviceType == 'android')
                                                        {
                                                            $notification=android_notification($d->deviceId,$d->deviceType,$message,$type='d');
                                                        }
                                                    }
