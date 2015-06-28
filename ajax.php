<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];	
require_once $root."/lib/class.invis.db.php";
require_once $root."/lib/uservar.php";
require_once $root."/lib/class.product.php";
require_once $root."/lib/class.sms.php";
$db = db :: getInstance();

switch($_POST['action'])
{
    case 'user':
        $name=$_POST['name_'];
        $arrayname[$_SESSION['active']]=$name;
        $_SESSION['arrayname']=$arrayname;
        $array=array();
        $sql="SELECT * FROM goods WHERE deleted=0 AND displatinlisting=0";
        $db->query($sql);
        if($db->getCount()>0)
        {
            $arr=$db->getArray();
            foreach($arr as $arrindex=>$arrval)
            {
                $sql_g="SELECT user_ed_iz FROM goods WHERE id='".$id."'";
                $db->query($sql_g);
                if($db->getCount()>0)
                {
                    $user_ed_iz=$db->getValue();
                    $sql_price_for="SELECT * FROM unitsofgoods WHERE deleted=0 AND id='".$user_ed_iz."' AND idgoods='".$id."'";
                    $db->query($sql_price_for);
                    if($db->getCount()>0)
                    {
                        $arr_ed=$db->getArray();
                        $factor=$arr_ed[0]['factor'];
                        $sql_price="SELECT * FROM priceofgoods WHERE deleted=0 AND idgoods='".$id."'";
                        $db->query($sql_price);
                        if($db->getCount()>0)
                        {
                            $arr_p=$db->getArray();
                            $price=$arr_p[0]['price'];
                            array_merge($array,array('id'=>$arrval['id'],'price'=>number_format(1*$factor*$price, 2, '.', ''),'fact'=>$factor)); 
                        }
                    }
                }
                
            }
        }
        $arr_all=array();
        array_merge($arr_all,array('all'=>$array),array('user'=>$arrayname));
        echo json_encode($arr_all);
    break;
    case 'trpricepm':
        echo json_encode($arrayname);
    break;
    /*case 'phonediscount':
        $arr=array();
        if(empty($_SESSION['client']['id']))
        {
            $val = htmlspecialchars(strip_tags($_POST['val']));
            $sql_get_phone="SELECT * FROM clients WHERE phone='".$val."'";
            $db->query($sql_get_phone);
            if($db->getCount()>0)
            {
                $discount=$db->getArray();
                $_SESSION['client']['id']=$discount[0]['id'];
                $_SESSION['client']['n']=$discount[0]['name'];
                $_SESSION['client']['nc']=$discount[0]['namberCard'];
                $_SESSION['client']['p']=$discount[0]['phone'];
                $_SESSION['client']['d']=$discount[0]['discount'];
                $_SESSION['client']['ad']=$discount[0]['address'];
                $_SESSION['client']['porc']=$discount[0]['cardorphone'];
                $discount=$discount[0]['discount'];
            }
            else
            {
                $discount=0;
            }
        }
        else $discount=0;
        $arr=array_merge($arr,array('discount'=>$discount),$_SESSION['client']);
        echo json_encode($arr);
    break;*/
    case 'oform':
    $alltotal=0;

    if(!empty($_SESSION['arrayorder']))
    {
        foreach($_SESSION['arrayorder'] as $index=>$val)
        {
            $total=0;
            if(!empty($_SESSION['arrayorder'][$index]))
            {
                foreach($_SESSION['arrayorder'][$index] as $in=>$v)
                {
                    $sql="SELECT * FROM goods WHERE id='".$v['id']."'";
                    $db->query($sql);
                    if($db->getCount()>0)
                    {
                        $thispr=$db->getArray();
                        $total+=$prd->getuserpriceformat($thispr[0]['id'],$thispr[0]['user_ed_iz'],$v['k']);
                    }
                }
                $alltotal+=$total;
            }

        }
    }
    if($alltotal==0 OR $alltotal<500)
    {
        echo json_encode(array('err'=>'Минимальная цена заказа 500 грн'));
        exit();
    }
    $phone = htmlspecialchars(strip_tags($_POST['p']));
    $phone=preg_replace('/[\s()]/','',$phone);
    //$phone=str_replace('+38','',$phone);
    $fio = htmlspecialchars(strip_tags($_POST['fio']));
    $address= htmlspecialchars(strip_tags($_POST['ad']));
    if(empty($_SESSION['client']['nc'])) //по телефону
    {
        if(!empty($fio) AND !empty($address))
        {
            $phone=$_SESSION['client']['p'];
            $arr=array();
            $fff=$_SESSION['arrayorder'];
            /*foreach($_POST['a'] as $index=>$val)
                {
                    array_push($arr,$val);
                    if(!empty($val))
                    {
                        $_SESSION['arrayorder'][$index]=array();
                        foreach($val as $val_index=>$val_val)
                        {
                            $sql_pr="SELECT price FROM products WHERE id=".$val_val['id'];
                            $db->query($sql_pr);
                            if($db->getCount()>0)
                            {
                                array_push($_SESSION['arrayorder'][$index],array('id'=>$val_val['id'],'k'=>$val_val['k'],'price'=>$db->getValue()));
                            }
                            else
                            {
                                array_push($_SESSION['arrayorder'][$index],array('id'=>$val_val['id'],'k'=>$val_val['k']));
                            }
                        }
                    }
                }*/
            //запись в базу
            $order=serialize($_SESSION['arrayorder']);
            $real_order=$_SESSION['arrayorder'];
            $orderoname=serialize($_SESSION['arrayname']);
            //проверим есть ли такой клиент по номеру телефона
            //id клиента
            $sql_get_id="SELECT id,idcard FROM clients WHERE phone='".$phone."'";
            $db->query($sql_get_id);
            if($db->getCount()>0)
            {
                $id_cl=$db->getArray();
                $sql="UPDATE clients SET name='".$fio."',address='".$address."', updated=1 WHERE id='".$id_cl[0]['id']."'";
                $db->query($sql);
                $_SESSION['client']['n']=$fio;
                $_SESSION['client']['ad']=$address;
                //
                $sql="SELECT count(id) FROM orders WHERE YEAR(datetime)='".date('Y')."' AND MONTH(datetime)='".date('m')."' AND DAY(datetime)='".date('d')."' GROUP BY idkkz";

                $db->query($sql);
                if($db->getCount()>0)
                {
                    $val=$db->getValue();
                }
                else
                {
                    $val=0;
                }
                if($val+1<10)
                {
                    $dopol='00';
                }
                else if($val+1>=10 AND $val+1<100)
                {
                    $dopol='0';
                }
                else
                {
                    $dopol='';
                }
                $uid=date('ymd').$dopol.$val+1;

                $total=0;
                //теперь записываем по отдельности
                for($i=0;$i<20;$i++)
                {
                    $sql_get_new_id="SELECT MAX(idorder) FROM ids";
                    $db->query($sql_get_new_id);
                    if($db->getCount()>0)
                    {
                        $new_or_id=$db->getValue()+1;
                    }
                    else
                    {
                        $new_or_id=1;
                    }
                    $sql_put_to_ids="UPDATE ids SET idorder=".$new_or_id;
                    $db->query($sql_put_to_ids);
                    if(!empty($_SESSION['arrayorder'][$i]))
                    {
                        $sql="INSERT INTO orders (id,idkkz,`datetime`,idclient,id_dk,note,`updated`) VALUES ('w".$new_or_id."','".$uid."','".date('Y-m-d H:i:s')."','".$id_cl[0]['id']."','".$id_cl[0]['idcard']."','".$_SESSION['arrayname'][$i]."',1)";
                        //if($i==1)  {echo json_encode($sql);exit;}
                        $db->query($sql);
                    }

                    foreach($_SESSION['arrayorder'][$i] as $index=>$val)
                    {
                        if(!empty($val))
                        {
                            $sql_goods="SELECT user_ed_iz FROM goods WHERE id='".$val['id']."'";

                            $db->query($sql_goods);
                            if($db->getCount()>0)
                            {
                                $arr=$db->getArray();
                                $user_iz=$arr[0]['user_ed_iz'];
                                $factor_user=$prd->getuserfactor($user_iz,$val['id']);
                            }
                            else
                            {
                                $factor_user=1;
                            }



                            $sql_goods="SELECT id_base_ed_iz FROM goods WHERE id='".$val['id']."'";
                            $db->query($sql_goods);
                            if($db->getCount()>0)
                            {
                                $arr=$db->getArray();
                                $ed_iz=$arr[0]['id_base_ed_iz'];
                                $factor=$prd->getuserfactor($ed_iz,$val['id']);
                            }
                            else
                            {
                                $ed_iz='';
                                $factor=1;
                            }
                            //достанем по id
                            $sql="SELECT price FROM priceofgoods WHERE idgoods='".$val['id']."'";
                            $db->query($sql);
                            if($db->getCount()>0)
                            {
                                $price=$db->getValue();
                            }
                            else
                            {
                                $price=0;
                            }
                            $sql_get_new_id="SELECT MAX(idorder_more) FROM ids";
                            $db->query($sql_get_new_id);
                            if($db->getCount()>0)
                            {
                                $new_idorder_more=$db->getValue()+1;
                            }
                            else
                            {
                                $new_idorder_more=1;
                            }

                            $sql_put_to_ids="UPDATE ids SET idorder_more=".$new_idorder_more;

                            $db->query($sql_put_to_ids);
                            $sum=(($price*$factor_user)*$val['k']);
                            $sql="INSERT INTO compositionoforder (id,idorder,idgoods,count,factor,price,sum,id_ed_iz,`updated`) VALUES ('w".$new_idorder_more."','w".$new_or_id."','".$val['id']."',".($val['k']*$factor_user).",".$factor.",".($price).",".$sum.",'".$user_iz."',1)";
                            $db->query($sql);
                        }
                        $_SESSION['client']['new']=0;
                    }
                }
            }

            $sms = new sms;
            $sms->setLogin('vsort');
            $sms->setPass('43176');
            if($sms->auth()){
                $sms->sendsms('VsortComUa',$phone,'Ваш заказ № '.$uid.' принят. Ожидайте звонка менеджера');
                $sms->sendsms('VsortComUa','+380992264778','Новый заказ с сайта. № '.$uid.'.');
            }


            $arrayname=array();
            $arrayorder=array();
            for($i=1;$i<=20;$i++)
            {
                array_push($arrayname,"покупатель(".$i.")");
                array_push($arrayorder,array());
            }
            $_SESSION['active']=0;
            $active=0;
            $_SESSION['arrayname']=$arrayname;
            $_SESSION['arrayorder']=$arrayorder;

            $arr = array_merge($arr, array('order'=>$_SESSION['arrayorder']),array('names'=>$_SESSION['arrayname']),array('ord'=>$order),array('real_ord'=> $real_order),array('fff'=>$fff));
            echo json_encode($arr);


        }
        else
        {
                if(empty($fio))
                {
                    echo json_encode(array('err'=>'Введите ФИО'));
                }
                else
                if(empty($address))
                {
                    echo json_encode(array('err'=>'Введите адрес доставки'));
                }
        }
       }
    else //карточка
    {
        if(!empty($phone) AND !empty($fio) AND !empty($address))
        {
            $arr=array();
            $fff=$_SESSION['arrayorder'];
            //запись в базу
            $order=serialize($_SESSION['arrayorder']);
            $real_order=$_SESSION['arrayorder'];
            $orderoname=serialize($_SESSION['arrayname']);
            //проверим есть ли такой клиент по
            $sql_card_id="SELECT d.id as did,c.id as cid FROM discountcard as d,clients as c WHERE d.id=c.idcard AND d.numbercard='".$_SESSION['client']['nc']."' AND d.deleted=0 AND d.statuscard='Выдана'";
            /*echo json_encode(array('err'=>$sql_card_id));
            exit();*/
            $db->query($sql_card_id);
            if($db->getCount()>0) //такая карточка в базе есть
            {
                $id_array=$db->getArray();
                $sql="UPDATE clients SET phone='".$phone."',name='".$fio."',address='".$address."',updated=1 WHERE id='".$id_array[0]['cid']."'"; //записываем новые данные с формы
                $db->query($sql);
                $_SESSION['client']['n']=$fio;
                $_SESSION['client']['ad']=$address;
                $_SESSION['client']['p']=$phone;
                $sql="SELECT count(id) FROM orders WHERE YEAR(datetime)='".date('Y')."' AND MONTH(datetime)='".date('m')."' AND DAY(datetime)='".date('d')."' GROUP BY idkkz";

                $db->query($sql);
                if($db->getCount()>0)
                {
                    $val=$db->getValue();
                }
                else
                {
                    $val=0;
                }
                if($val+1<10)
                {
                    $dopol='00';
                }
                else if($val+1>=10 AND $val+1<100)
                {
                    $dopol='0';
                }
                else
                {
                    $dopol='';
                }
                $uid=date('ymd').$dopol.$val+1;

                $total=0;
                //теперь записываем по отдельности
                for($i=0;$i<20;$i++)
                {
                    $sql_get_new_id="SELECT MAX(idorder) FROM ids";
                    $db->query($sql_get_new_id);
                    $new_or_id=($db->getCount()>0)?$db->getValue()+1:1;
                    $sql_put_to_ids="UPDATE ids SET idorder=".$new_or_id;
                    $db->query($sql_put_to_ids);
                    if(!empty($_SESSION['arrayorder'][$i]))
                    {
                        $sql="INSERT INTO orders (id,idkkz,`datetime`,idclient,id_dk,note,`updated`) VALUES ('w".$new_or_id."','".$uid."','".date('Y-m-d H:i:s')."','".$id_array[0]['cid']."','".$id_array[0]['did']."','".$_SESSION['arrayname'][$i]."',1)";
                        //if($i==1)  {echo json_encode($sql);exit;}
                        $db->query($sql);
                    }

                    foreach($_SESSION['arrayorder'][$i] as $index=>$val)
                    {
                        if(!empty($val))
                        {
                            $sql_goods="SELECT user_ed_iz FROM goods WHERE id='".$val['id']."'";

                            $db->query($sql_goods);
                            if($db->getCount()>0)
                            {
                                $arr=$db->getArray();
                                $user_iz=$arr[0]['user_ed_iz'];
                                $factor_user=$prd->getuserfactor($user_iz,$val['id']);
                            }
                            else
                            {
                                $factor_user=1;
                            }


                            $sql_goods="SELECT id_base_ed_iz FROM goods WHERE id='".$val['id']."'";
                            $db->query($sql_goods);
                            if($db->getCount()>0)
                            {
                                $arr=$db->getArray();
                                $ed_iz=$arr[0]['id_base_ed_iz'];
                                $factor=$prd->getuserfactor($ed_iz,$val['id']);
                            }
                            else
                            {
                                $ed_iz='';
                                $factor=1;
                            }
                            //достанем по id
                            $sql="SELECT price FROM priceofgoods WHERE idgoods='".$val['id']."'";
                            $db->query($sql);
                            if($db->getCount()>0)
                            {
                                $price=$db->getValue();
                            }
                            else
                            {
                                $price=0;
                            }
                            $sql_get_new_id="SELECT MAX(idorder_more) FROM ids";
                            $db->query($sql_get_new_id);
                            if($db->getCount()>0)
                            {
                                $new_idorder_more=$db->getValue()+1;
                            }
                            else
                            {
                                $new_idorder_more=1;
                            }

                            $sql_put_to_ids="UPDATE ids SET idorder_more=".$new_idorder_more;

                            $db->query($sql_put_to_ids);
                            $sum=(($price*$factor_user)*$val['k']);
                            $sql="INSERT INTO compositionoforder (id,idorder,idgoods,count,factor,price,sum,id_ed_iz,`updated`) VALUES ('w".$new_idorder_more."','w".$new_or_id."','".$val['id']."',".($val['k']*$factor_user).",".$factor.",".($price).",".$sum.",'".$user_iz."',1)";
                            $db->query($sql);
                        }
                        $_SESSION['client']['new']=0;
                    }
                }

                $sms = new sms;
                $sms->setLogin('vsort');
                $sms->setPass('43176');
                if($sms->auth()){
                    $sms->sendsms('VsortComUa',$phone,'Ваш заказ № '.$uid.' принят. Ожидайте звонка менеджера');
                    $sms->sendsms('VsortComUa','+380992264778','Новый заказ с сайта. № '.$uid.'.');
                }


                $arrayname=array();
                $arrayorder=array();
                for($i=1;$i<=20;$i++)
                {
                    array_push($arrayname,"покупатель(".$i.")");
                    array_push($arrayorder,array());
                }
                $_SESSION['active']=0;
                $active=0;
                $_SESSION['arrayname']=$arrayname;
                $_SESSION['arrayorder']=$arrayorder;

                $arr = array_merge($arr, array('order'=>$_SESSION['arrayorder']),array('names'=>$_SESSION['arrayname']),array('ord'=>$order),array('real_ord'=> $real_order),array('fff'=>$fff));
                echo json_encode($arr);

            }
            else //ошибка такой карточки не сущ в базе
            {
                echo json_encode(array('err'=>'Данной карточки не найдено в базе'));
                exit();
            }

        }
        else
        {
            if(empty($phone))
            {
                echo json_encode(array('err'=>'Введите телефон'));
            }
            else
                if(empty($fio))
                {
                    echo json_encode(array('err'=>'Введите ФИО'));
                }
                else
                if(empty($address))
                {
                    echo json_encode(array('err'=>'Введите адрес доставки'));
                }
                else
                if($alltotal==0 OR $alltotal<500)
                {
                    echo json_encode(array('err'=>'Минимальная цена заказа 500 грн'));
                }
        }
    }

    break;
    case 'depprouser':
        if(count($_SESSION['arrayorder'][$_POST['user_']])>0)
        {
            foreach($_SESSION['arrayorder'][$_POST['user_']] as $index=>$val)
            {
                if($val['id']==$_POST['id_'])
                {
                    unset($_SESSION['arrayorder'][$_POST['user_']][$index]);
                }
            }
        }
        echo json_encode($_SESSION['arrayorder']);
    break;
    case 'delall':
        if(count($_SESSION['arrayorder'][$_POST['id']])>0)
        {
            $_SESSION['arrayorder'][$_POST['id']]=array();
        }
        echo json_encode($_SESSION['arrayorder']);
    break;
    case 'userofor':
        $name=$_POST['name_'];
        $arrayname[$_POST['n_']]=$name;
        $_SESSION['arrayname']=$arrayname;
        echo json_encode($arrayname[$_POST['n_']]);
    break;
    case 'usernext':
        $arr=array();
        $_SESSION['active']++;
        $active=$_SESSION['active'];
        $summ=0;
        $total=0;
        $koltovar=0;
        $ids=array();
        for($i=0;$i<20;$i++)
        {
            foreach($_SESSION['arrayorder'][$i] as $index=>$val)
            {
                //достанем цену по id
                $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $arr_=$db->getArray();
                    $total+=$prd->getuserpriceformat($arr_[0]['id'],$arr_[0]['user_ed_iz'],$val['k']);
                    //$total+=$val['k']*$db->getValue();
                }
            }
        }
        foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
        {
            //достанем цену по id
            $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
            $db->query($sql);
            if($db->getCount()>0)
            {
                $arr_=$db->getArray();
                $summ+=$prd->getuserpriceformat($arr_[0]['id'],$arr_[0]['user_ed_iz'],$val['k']);
                //$summ+=$val['k']*$db->getValue();
                array_push($ids,array('id'=>$val['id'],'k'=>$val['k'],'price'=>$prd->getuserpriceformat($arr_[0]['id'],$arr_[0]['user_ed_iz'],1)));
            }
            $koltovar+=$val['k'];
        }
        $all=array();
        $sql="SELECT * FROM goods WHERE deleted=0 AND displaylisting=1";
        $db->query($sql);
        if($db->getCount()>0)
        {
            $arr_=$db->getArray();
            foreach($arr_ as $arr_index=>$arr_val)
            {
                array_push($all,array('id'=>$arr_val['id'],'price'=>$prd->getuserpriceformat($arr_val['id'],$arr_val['user_ed_iz'],1)));
            }
        }
        $arr = array_merge($arr, array('active'=>$active),array('name'=>$arrayname[$_SESSION['active']]),array('kol'=>$koltovar),array('price'=>$summ),array('total'=>$total),array('ids'=>$ids),array('all'=>$all)); 
        echo json_encode($arr);
    break;
    case 'userprev':
        $arr=array();
        $_SESSION['active']--;
        $active=$_SESSION['active'];
        $summ=0;
        $total=0;
        $koltovar=0;
        $ids=array();
        for($i=0;$i<20;$i++)
        {
            foreach($_SESSION['arrayorder'][$i] as $index=>$val)
            {
                //достанем цену по id
                $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $arr=$db->getArray();
                    $total+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                    //$total+=$val['k']*$db->getValue();
                }
            }
        }
        foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
        {
            //достанем цену по id
            $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
            $db->query($sql);
            if($db->getCount()>0)
            {
                $arr=$db->getArray();
                $summ+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                //$summ+=$val['k']*$db->getValue();
                array_push($ids,array('id'=>$val['id'],'k'=>$val['k'],'price'=>$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],1)));
            }
            $koltovar+=$val['k'];   
        }
        $all=array();
        $sql="SELECT * FROM goods WHERE deleted=0 AND displaylisting=1";
        $db->query($sql);
        if($db->getCount()>0)
        {
            $arr_=$db->getArray();
            foreach($arr_ as $arr_index=>$arr_val)
            {
                array_push($all,array('id'=>$arr_val['id'],'price'=>$prd->getuserpriceformat($arr_val['id'],$arr_val['user_ed_iz'],1)));
            }
        }
        $arr = array_merge($arr,array('active'=>$active),array('name'=>$arrayname[$_SESSION['active']]),array('kol'=>$koltovar),array('price'=>$summ),array('total'=>$total),array('ids'=>$ids),array('all'=>$all)); 
        echo json_encode($arr);
    break;
    case 'buy':
        
        $arr=array();
        $k=$_POST['k_'];
        $id=$_POST['id_'];
        array_push($arrayorder[$_SESSION['active']],array('k'=>$k,'id'=>$id));
        $_SESSION['arrayorder']=$arrayorder;
        $summ=0;
        $total=0;
        $koltovar=0;
        $ids=array();
        for($i=0;$i<20;$i++)
        {
            foreach($_SESSION['arrayorder'][$i] as $index=>$val)
            {
                //достанем цену по id
                $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $arr=$db->getArray();
                    $total+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                    //$total+=$val['k']*$db->getValue();
                }
            }
        }
        foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
        {
            //достанем цену по id
            $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
            $db->query($sql);
            if($db->getCount()>0)
            {
                $sum+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                //$summ+=$val['k']*$db->getValue();
                array_push($ids,array('id'=>$val['id'],'k'=>$k));
            }
            $koltovar+=$val['k'];   
        }
        $arr = array_merge($arr, array('name'=>$arrayname[$_SESSION['active']]),array('kol'=>$koltovar),array('price'=>$summ),array('total'=>$total),array('ids'=>$ids)); 
        
        echo json_encode($arr);
    break;
    //
    case 'plus':
        $arr=array();
        $price=0;
        $k=$_POST['k'];
        $id=$_POST['id'];
        
        $sql_g="SELECT * FROM goods WHERE id='".$id."'";
        $db->query($sql_g);
        if($db->getCount()>0)
        {
            $arr=$db->getArray();
            $arr = array_merge($arr, array('price'=>$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$k)),array('kol'=>$k)); 
            echo json_encode($arr);
        }
        
    break;
    //
     case 'minus':
        $arr=array();
        $price=0;
        $k=$_POST['k'];
        $id=$_POST['id'];
        
        $sql_g="SELECT * FROM goods WHERE id='".$id."'";
        $db->query($sql_g);
        if($db->getCount()>0)
        {
            $arr=$db->getArray();
            $arr = array_merge($arr, array('price'=>$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$k)),array('kol'=>$k)); 
            echo json_encode($arr);
        }
    break;
}
?>
