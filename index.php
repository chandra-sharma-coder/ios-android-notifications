 $message = str_replace("DateTime",$DateTime,str_replace("PatientName",$PatientName,str_replace("DrName",$DrName,$this->lang->line('notification_add_msg'))));

                                                    $this->common_model->insertData('notifications', 
                                                        array('Id'=>$Id, 
                                                            'notification'=>'New / Add', 
                                                            'message'=>$message, 
                                                            'status'=>0, 
                                                            'notificationFrom'=>$user->id, 
                                                            'notificationTo'=>$customer->id, 
                                                            'notificationFor'=>'Lab', 
                                                            'creatorType'=>'user', 
                                                            'createdDate' => strtotime(date('Y-m-d H:i:s'))
                                                        )
                                                    );

                                                    if($userDetails->notificationSend == 0)
                                                    {
                                                        if($userDetails->deviceType == 'ios')
                                                        {
                                                            $notification=ios_notification($doctorDetails->deviceId,$doctorDetails->deviceType,$message,$type='doctor');
                                                        }

                                                        if($userDetails->deviceType == 'android')
                                                        {
                                                            $notification=android_notification($doctorDetails->deviceId,$doctorDetails->deviceType,$message,$type='doctor');
                                                        }
                                                    }
