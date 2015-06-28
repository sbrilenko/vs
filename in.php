<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];	
require_once $root."/lib/class.invis.db.php";
$db = db :: getInstance();	
if(strpos($_POST['in'],'+38')!==false)
{
    $phone_who=preg_replace('/[\s()]/','',$_POST['in']);
    $sql_phone="SELECT * FROM clients WHERE phone='".$phone_who."' AND idcard=''";
    $db->query($sql_phone);
    if($db->getCount()>0)
    {
        $arrphone=$db->getArray();
        $client=array();
        //посмотрим есть ли заказы, если есть то он не новенький, иначе новенький
        $sql_get_or="SELECT * FROM orders WHERE deleted=0 AND idclient='".$arrphone[0]['id']."'";
        $db->query($sql_get_or);
        $new=($db->getCount()>0)?0:1;
        $sql_get_ncard="SELECT numbercard FROM discountcard WHERE id='".$arrphone[0]['idcard']."' AND deleted=0 AND statuscard='выдана'";
        $db->query($sql_get_ncard);
        $numbercard=($db->getCount()>0)?$db->getValue():"";
        $client=array_merge($client,array('id'=>$arrphone[0]['id']),array('n'=>$arrphone[0]['name']),array('nc'=>$numbercard),array('p'=>$arrphone[0]['phone']),array('ad'=>$arrphone[0]['address']),array('new'=>$new),array('iscard'=>0));
        $_SESSION['client']=$client;
        $arr=array();
        $arr=array_merge($arr,array('client'=>$_SESSION['client']),array('arr'=>$arrphone),array('lo'=>$_POST['l']));
        echo json_encode($arr);
    }        
    else
    {
            //такого нет в базе
            //добавим его в базу
            $sql_get_new_id="SELECT MAX(idclient) FROM ids";
            $db->query($sql_get_new_id);
            if($db->getCount()>0)
            {
                $new_cl_id=$db->getValue()+1;
                $sql_put_to_ids="UPDATE ids SET idclient=".$new_cl_id;
                $db->query($sql_put_to_ids);
            }
            else
            {
                $new_cl_id=1;
            }
            
            $sql="INSERT INTO clients (id,phone,updated) VALUES ('w".$new_cl_id."','".$phone_who."',1)";
            $db->query($sql);
            $client=array();
            $client=array_merge($client,array('id'=>'w'.$new_cl_id),array('n'=>''),array('nc'=>''),array('p'=>$phone_who),array('ad'=>''),array('new'=>1),array('iscard'=>0));
            $_SESSION['client']=$client;
            $arr=array();
            $arr=array_merge($arr,array('client'=>$_SESSION['client']),array('arr'=>$client),array('lo'=>$_POST['l']));
            echo json_encode($arr);
        
}

}
else
{  
        if($_POST['phone'])
        {
            $phone_who=preg_replace('/[\s()]/','',$_POST['phone']);
        }
        $card=preg_replace('/[\s()]/','',$_POST['in']);
        //$phone_who=str_replace('+38','',$phone_who);
        $sql_phone="SELECT * FROM discountcard WHERE numbercard='".$card."' AND deleted=0 AND statuscard='Выдана'";
        $db->query($sql_phone);
        if($db->getCount()>0)
        {
            $arrphone=$db->getArray();
            $numbercard=(!empty($arrphone[0]['numbercard']))?$arrphone[0]['numbercard']:"";
            $client=array();
            $user_data="SELECT * FROM clients WHERE id='".$arrphone[0]['idclient']."' AND deleted=0";
            $db->query($user_data);
            if($db->getCount()>0)
            {
                $user=$db->getArray();
                $sql_get_or="SELECT * FROM orders WHERE deleted=0 AND idclient='".$user[0]['id']."'";
                $db->query($sql_get_or);
                $new=($db->getCount()>0)?0:1;
                $client=array_merge($client,array('id'=>$user[0]['id']),array('n'=>$user[0]['name']),array('nc'=>$numbercard),array('p'=>$user[0]['phone']),array('ad'=>$user[0]['address']),array('new'=>$new),array('iscard'=>1));
                $_SESSION['client']=$client;
                $arr=array();
                $arr=array_merge($arr,array('client'=>$_SESSION['client']),array('arr'=>$arrphone),array('lo'=>$_POST['l']));
                echo json_encode($arr);
            }
            else //если карта есть но не привязна к клиенту
            {
               //echo json_encode(array('err'=>'Данной карты не существует'));
               //Добавить нового клиента с такой картой
                //удалим то что есть карту и не привязана к нужному id
                $sql_del_card="DELETE FROM clients WHERE idcard='".$arrphone[0]['id']."'";
                $db->query($sql_del_card);
                $sql_get_new_id="SELECT MAX(idclient) FROM ids";
                $db->query($sql_get_new_id);
                if($db->getCount()>0)
                {
                    $new_cl_id=$db->getValue()+1;
                    $sql_put_to_ids="UPDATE ids SET idclient=".$new_cl_id;
                    $db->query($sql_put_to_ids);
                }
                else
                {
                    $new_cl_id=1;
                }

                //обновим таблицу дисконтных карт
                $sql_update_d_c="UPDATE discountcard SET idclient='w".$new_cl_id."' WHERE id='".$arrphone[0]['id']."'";
                $db->query($sql_update_d_c);
                $sql="INSERT INTO clients (id,idcard,updated,phone,name,address) VALUES ('w".$new_cl_id."','".$arrphone[0]['id']."',1,'".$phone_who."','".$_POST['fio']."','".$_POST['address']."')";
                $db->query($sql);
                $client=array_merge($client,array('id'=>'w'.$new_cl_id),array('n'=>''),array('nc'=>$numbercard),array('p'=>''),array('ad'=>''),array('new'=>1),array('iscard'=>1));
                $_SESSION['client']=$client;
                $arr=array();
                $arr=array_merge($arr,array('client'=>$_SESSION['client']),array('arr'=>$arrphone),array('lo'=>$_POST['l']));
                echo json_encode($arr);
            }
            
            //header('Location:'.$_POST['l']);
            //exit;
        }
        else
        {
            echo json_encode(array('err'=>'Данной карты не существует'));
        }
}
?>
