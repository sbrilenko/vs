<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];	
require_once $root."/lib/class.invis.db.php";
require_once $root."/lib/uservar.php";
require_once $root."/lib/class.product.php";
$db = db :: getInstance();	
$id=htmlspecialchars(strip_tags($_POST['id']));  
$k=htmlspecialchars(strip_tags($_POST['k_']));	
$userid=htmlspecialchars(strip_tags($_POST['userid'])); 
if(!empty($id) AND !empty($k))
{
    $sql="SELECT * FROM goods WHERE id='".$id."'";
    $db->query($sql);
    if($db->getCount()>0)
    {
        $arr=$db->getArray();
        $price=$prd->getuserpriceformat($id,$arr[0]['user_ed_iz'],1);
        //занесем в сессию заказа
        foreach($_SESSION['arrayorder'][$userid] as $index=>$val)
        {
            if($val['id']==$id)
            {
                $_SESSION['arrayorder'][$userid][$index]['k']=$k;
                break;
            }
        }
        echo json_encode(array('price'=>$price,'userid'=>$userid));   
    }   
}
?>
