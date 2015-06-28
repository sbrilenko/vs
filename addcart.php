<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
$id=htmlspecialchars(strip_tags($_POST['id']));  
$k=htmlspecialchars(strip_tags($_POST['k']));	
$is=0;
foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
    {
        //достанем цену по id
        if($id==$val['id'])
        {
            $is=1;
        }  
    }
if($is==0)
{
    require_once $root."/lib/class.invis.db.php";
    require_once $root."/lib/uservar.php";
    $db = db :: getInstance();	
    $arr=array();
    
    if(!empty($id) AND !empty($k))
    {
        //а если уже есть такой товар в списке этого пользователя,(например осталась старая вкладка открытой), то перейдем в корзину
        /*foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
            {
                //достанем цену по id
                if($id==$val['id'])
                {
                    header('Location:/cart');
                    exit;
                }  
            }*/
        //проветим есть ли такой id в базе
        $sql="SELECT * FROM priceofgoods WHERE idgoods='".$id."'";
        $db->query($sql);
        if($db->getCount()>0)
        {
            
            $arrpr=$db->getArray();
            $price=$k*$arrpr[0]['price']-$k*$arrpr[0]['price']/100;
        array_push($arrayorder[$_SESSION['active']],array('k'=>$k,'id'=>$id,'price'=>$arrpr[0]['price']));
        $_SESSION['arrayorder']=$arrayorder;
        $summ=0;
        $total=0;
        $koltovar=0;
        $ids=array();
        for($i=0;$i<20;$i++)
            {
                foreach($_SESSION['arrayorder'][$i] as $index=>$val)
                {
                    $sql = "SELECT weight FROM goods WHERE id = '".$val['id']."'";
                    $db->query($sql);
                    if($db->getCount()>0){
                        $weight = $db->getValue();
                        //$weight = substr($weight,0,strpos($weight,'±'));
                        $weight = substr($weight,0,strpos($weight,'±'));
                        $weight = str_replace(',','.',$weight);
                        $weight = floatval($weight);
                        //settype($weight,float);
                    }
                    //достанем цену по id
                    $sql="SELECT price FROM priceofgoods WHERE idgoods='".$val['id']."'";
                    $db->query($sql);
                    if($db->getCount()>0)
                    {
                        $total+=number_format($weight*$val['k']*$db->getValue(),2,'.','');
                    }
                }
            }
            foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
            {
                $sql = "SELECT weight FROM goods WHERE id = '".$val['id']."'";
                    $db->query($sql);
                    if($db->getCount()>0){
                        $weight = $db->getValue();
                        //$weight = substr($weight,0,strpos($weight,'±'));
                        $weight = substr($weight,0,strpos($weight,'±'));
                        $weight = str_replace(',','.',$weight);
                        $weight = floatval($weight);
                        //settype($weight,float);
                    }
                //достанем цену по id
                $sql="SELECT price FROM priceofgoods WHERE idgoods='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $summ +=number_format($weight*$val['k']*$db->getValue(),2,'.','');
                    array_push($ids,array('id'=>$val['id'],'k'=>$k,'price'=>$val['price']));
                }
                $koltovar+=$val['k'];   
            }
        $arr = array_merge($arr, array('name'=>$arrayname[$_SESSION['active']]),array('kol'=>$koltovar),array('pricethis'=>$price),array('price'=>$summ),array('total'=>$total),array('ids'=>$ids),array('weight'=>$weight)); 
        echo json_encode($arr);
        }
        
    }
}
else
{
    echo json_encode(array('is'=>1));
}



?>
