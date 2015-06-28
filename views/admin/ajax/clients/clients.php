<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();
//проверим нет ли уже занятого номера карты
$_POST['namberCard']=trim($_POST['namberCard']);
if(!empty($_POST['namberCard']))
{
    if($_POST['id'])
    {
        $sql_get_card="SELECT namberCard FROM clients WHERE id<>".$_POST['id']." AND namberCard='".$_POST['namberCard']."'";
    }
    else
    {
        $sql_get_card="SELECT namberCard FROM clients WHERE namberCard='".$_POST['namberCard']."'";
    }
    
    $db->query($sql_get_card);
    if($db->getCount()>0)
    {
        $cardis=1;
    }
    else
    {
        $cardis=0;
    }
}
else
{
    $cardis=0;
}
$_POST['email']=trim($_POST['email']);
if(!empty($_POST['email']))
{
    if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $_POST['email'])) 
       { 
        $email=$_POST['email'];
       } 
       else 
       { 
          $email='';   
       }
}
if(!empty($_POST['phone']) AND $cardis==0)
{
    $cardorphone=($_POST['cardorphone'])?1:0;
    $namberCard = htmlspecialchars(strip_tags($_POST['namberCard']));
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $org = htmlspecialchars(strip_tags($_POST['org']));
    $discount = (float)strip_tags($_POST['discount']);
    $discount = number_format($discount,1,'.','');
    $zakaz = (float)strip_tags($_POST['zakaz']);
    $zakaz = number_format($zakaz,3,'.','');
    $address = htmlspecialchars(strip_tags($_POST['address']));
    if($_POST['action']=='add')
    {
       $sql="INSERT INTO clients (id,namberCard,phone,name,discount,address,cardorphone,idUserCreate,dtCreate,email,org,zakaz) VALUES(NULL,'".$namberCard."','".$phone."','".$name."',".$discount.",'".$address."',{$cardorphone},'".$_SESSION['userID']."','".$dtClass -> dtInDB()."','".$email."','".$org."','".$zakaz."')";
       $db->query($sql);
    }
    else
    {
       $sql="UPDATE clients SET zakaz='".$zakaz."',org='".$org."',idUserUpdate='".$_SESSION['userID']."',dtUpdate='".$dtClass -> dtInDB()."',namberCard='".$namberCard."',phone='".$phone."',name='".$name."',discount=".$discount.",address='".$address."',cardorphone={$cardorphone},email='".$email."' WHERE id=".$_POST['id'];
       $db->query($sql);
    }
    if($_POST['action']=='add')
    {
        echo $db -> last('clients');
    }
    else
    {
        echo $_POST['id'];
    }
}
else
{
    if(empty($email))
    {
        echo "<span style='color:red;' title='error'>Не правильный формат Email</span>";
    }
    else
    if($cardis==1)
    {
        echo "<span style='color:red;' title='error'>Такой номер карты уже существует введите другой</span>";
    }
    else
    {
        echo "<span style='color:red;' title='error'>Номер телефона обязательное поле для заполнения</span>";
    }
    
}
?>
 
