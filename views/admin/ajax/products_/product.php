<?php

if(!empty($_POST))
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once $root."/lib/include.php";
    $db = db :: getInstance(); 
    $form = new ad();
    $dtNow = $dtClass -> dtInDB();
    $id = strip_tags($_POST['id']);
    $name = strip_tags($_POST['name']);
    $id1C = strip_tags($_POST['id1C']);
    $idGroup = strip_tags($_POST['sGroups']);
    $idSubgroup = strip_tags($_POST['sSubgroups']);
    $idFirm = strip_tags($_POST['sFirms']);
    $price = (!empty($_POST['price']))?number_format(strip_tags($_POST['price']), 2):"";
    $disc = (!empty($_POST['disc']))?number_format(strip_tags($_POST['disc']), 2):"";
    $presence = ($_POST['presence'])?1:0;
    $description = $form -> replaceToInsert($_POST['description']);
    $description_seach=$form -> replaceToInsert($_POST['description_seach']);
    $tags = (strip_tags($_POST['tags']));
    if($idFirm) $firm = $DLL -> getFirm($idFirm);
    if($idGroup) $group = $DLL -> getGroup($idGroup);
    if($idSubgroup) $subgroup = $DLL -> getSubgroup($idSubgroup);
    $sql = "SHOW TABLE STATUS LIKE 'products'";
	$db->query($sql);
	$arr=$db->getArray();  
	$nid=$arr[0]['Auto_increment'];
    $allocation=$_POST['allocation']; 
    print $allocation;
    $prior=$_POST['prior'];
    $show=($_POST['show_'])?1:0;
    $mera=(!empty($_POST['mera']))?$_POST['mera']:""; 
    foreach($_POST as $in=>$val)
    {
       $first_img=explode('main_',$in);
    }
    if(!empty($first_img[1]))
    {
        /*берем md5*/
        $sql_u="UPDATE products_p SET temp=0 WHERE md5_mictotime='".$_POST['temp']."'";
        $db->query($sql_u);
        /*Теперь установим temp=1 на нужной картинке*/
         $sql_u="UPDATE products_p SET temp=1 WHERE id=".$first_img[1];
         $db->query($sql_u);
    }
    else /*если галочка не установлена, то присваиваем любой из всех*/
    {
        $sql_s="SELECT DISTINCT id FROM products_p WHERE md5_mictotime='".$_POST['temp']."' ORDER BY temp LIMIT 1";
        $db->query($sql_s);
        if($db->getCount()>0)
        {
            $val=$db->getValue();
            $sql_u="UPDATE products_p SET temp=0 WHERE md5_mictotime='".$_POST['temp']."'";
            $db->query($sql_u);
            /*Теперь установим temp=1 на нужной картинке*/
            $sql_u="UPDATE products_p SET temp=1 WHERE id=".$val;
            $db->query($sql_u);
        }
    }
    
    if ($_POST['action'] == 'edit')
    {
        $db -> query("UPDATE products SET 
            name = '".$name."', 
            idUserUpdate = '".$_SESSION['userID']."', 
            dtUpdate = '".$dtNow."',
            id1C = '".$id1C."',
            idGroup = '".$idGroup."',
            idSubgroup = '".$idSubgroup."',
            idFirm = '".$idFirm."',
            description = '".$description."',
            price = '".$price."',
            presence = ".$presence.",
            tags = '".$tags."',
            show_=".$show.",
            allocation=".$allocation.",
            disc='".$disc ."',
            mera='".$mera."',
            prior=".$prior.",
            description_seach='".$description_seach."'
            WHERE id = ".$id);
    }
    if ($_POST['action'] == 'add')
    {
        $tags = ($firm.' '.$group.' '.$subgroup.' '.$name);
        $db -> query("INSERT INTO products 
            (name,idUserCreate,dtCreate,idUserUpdate,dtUpdate,id1C,idGroup,idSubgroup,idFirm,description,price,presence,tags,show_,allocation,disc,mera,prior,description_seach) 
            VALUES 
            ('".$name."', '".$_SESSION['userID']."', '".$dtNow."', '0', '0000-00-00 00:00:00', '".$id1C."', '".$idGroup."', '".$idSubgroup."', '".$idFirm."', '".$description."', '".$price."', ".$presence.", '".$tags."',".$show.",".$allocation.",'".$disc."','".$mera."',".$prior.",'".$description_seach."')");
    }
}
else
{
    print "<span style='color:red;' title='error'>Введите данные</span>";
}
?>
 
