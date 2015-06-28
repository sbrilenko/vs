<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();
$err='';
if(!empty($_POST['nameSearch']))
{
    $show = ($_POST['show']) ? 1 : 0;
    $dtNow = $dtClass -> dtInDB();
    $id = htmlspecialchars(strip_tags($_POST['id']));
    $article = htmlspecialchars(strip_tags($_POST['article']));
    //проверим артикл
    if(!empty($article))
    {
        $sql_s="SELECT article FROM products WHERE article='".$article."' AND id<>".$id;
        $db->query($sql_s);
        if($db->getCount()>0)
        {
            $err.="такой артикул уже существует";
        }
    }
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $category = htmlspecialchars(strip_tags($_POST['category']));
    $nameSearch = htmlspecialchars(strip_tags($_POST['nameSearch']));
    $id1C = htmlspecialchars(strip_tags($_POST['id1C']));
    $idSection = htmlspecialchars(strip_tags($_POST['sSections']));
    $idGroup = htmlspecialchars(strip_tags($_POST['sgroups']));
    $idSubgroup = htmlspecialchars(strip_tags($_POST['ssubgroups']));
    $composition = htmlspecialchars(strip_tags($_POST['composition']));
    $safe = htmlspecialchars(strip_tags($_POST['safe']));
    $shell = htmlspecialchars(strip_tags($_POST['shell']));
    $pack = htmlspecialchars(strip_tags($_POST['pack']));
    $gmo = ($_POST['gmo']) ? 1 : 0;
    $storageconditions=htmlspecialchars(strip_tags($_POST['storageconditions']));
    $idFirm = htmlspecialchars(strip_tags($_POST['sfirms']));
    $price = (float)strip_tags($_POST['price']);
    $price = number_format($price,2,'.','');
    $presence = $_POST['presence'];
    $orderDay = ($presence == 3) ? htmlspecialchars(strip_tags($_POST['orderDay'])) : 0;
    $newOrSecond = ($_POST['newOrSecond']) ? 1 : 0;
    $new = ($_POST['new']) ? 1 : 0;
    $bestseller = ($_POST['bestseller']) ? 1 : 0;
    $event = ($_POST['event']) ? 1 : 0;
    $priority = $_POST['priority'];
    $rating = $_POST['rating'];
    $ava = $_POST['ava'];
    $description = $form -> replaceToInsert($_POST['description']);
    $grayText = $form -> replaceToInsert($_POST['grayText']);
    $tags = $form -> replaceToInsert($_POST['tags']);
    $attributes = $_POST['idsProducts'];
    $kkz = ($_POST['kkz']) ? 1 : 0;
    $pricekkz = (float)strip_tags($_POST['pricekkz']);
    $pricekkz = number_format($pricekkz,2,'.','');
    $weightkkz = (float)strip_tags($_POST['weightkkz']);
    $weightkkz = number_format($weightkkz,2,'.','');
    $pricekkzed = htmlspecialchars(strip_tags($_POST['pricekkzed']));
    $weightkkzed = htmlspecialchars(strip_tags($_POST['weightkkzed']));
    $weight =  htmlspecialchars(strip_tags($_POST['weight']));
    if ($attributes) 
    {
        $attributes = (substr($attributes,strlen($attributes)-1) != '~') ? $attributes : substr($attributes,0,strlen($attributes)-1);
        $attributes = (substr($attributes,0,1) != '~') ? $attributes : substr($attributes,1,strlen($attributes));
    }
    
    if ($idFirm) $firm = $DLL -> getFirm($idFirm);
    if ($idSection) $section = $DLL -> getFirm($idSection);
    if ($idGroup) $group = $DLL -> getGroup($idGroup);
    if ($idSubgroup) $subgroup = $DLL -> getSubgroup($idSubgroup);
    if(empty($err))
    {
    if ($_POST['action'] == 'edit')
    {
        $db -> query("UPDATE products SET 
            `show` = '".$show."',
            article = '".$article."',
            name = '".$name."', 
            nameSearch = '".$nameSearch."',
            idUserUpdate = '".$_SESSION['userID']."', 
            dtUpdate = '".$dtNow."',
            id1C = '".$id1C."',
            idSection = '".$idSection."',
            idGroup = '".$idGroup."',
            idSubgroup = '".$idSubgroup."',
            idFirm = '".$idFirm."',
            description = '".$description."',
            grayText = '".$grayText."',
            price = '".$price."',
            presence = '".$presence."',
            orderDay = '".$orderDay."',
            newOrSecond = '".$newOrSecond."',
            new = '".$new."',
            bestseller = '".$bestseller."',
            event = '".$event."',
            priority = '".$priority."',
            `rating` = '".$rating."',
            tags = '".$tags."',
            attributes = '".$attributes."',
            category=".$category.",
            composition='".$composition."',
            safe='".$safe."',
            shell='".$shell."',
            pack='".$pack."',
            gmo=".$gmo.",
            storageconditions='".$storageconditions."',
            kkz=". $kkz.",
            pricekkz=".$pricekkz.",
            weightkkz=".$weightkkz.",
            pricekkzed='".$pricekkzed."',
            weightkkzed='".$weightkkzed."',
            weight='".$weight."'
                WHERE id = ".$id);
    }
    if ($_POST['action'] == 'add')
    {
        $tags = ($section.' '.$firm.' '.$group.' '.$subgroup.' '.$nameSearch).' '.$tags;
        $db -> query("INSERT INTO products 
            (`show`,article,name,nameSearch,idUserCreate,dtCreate,idUserUpdate,dtUpdate,id1C,idSection,idGroup,idSubgroup,idFirm,description,grayText,price,presence,orderDay,newOrSecond,new,bestseller,event,priority,`rating`,tags,attributes,category,composition,safe,shell,pack, gmo,storageconditions,kkz,pricekkz,weightkkz,pricekkzed,weightkkzed,weight) 
            VALUES 
            ('".$show."', '".$article."', '".$name."', '".$nameSearch."', '".$_SESSION['userID']."', '".$dtNow."', '0', '0000-00-00 00:00:00', '".$id1C."', '".$idSection."', '".$idGroup."', '".$idSubgroup."', '".$idFirm."', '".$description."', '".$grayText."', '".$price."', '".$presence."', '".$orderDay."', '".$newOrSecond."', '".$new."', '".$bestseller."', '".$event."', '".$priority."', '".$rating."', '".$tags."', '".$attributes."',".$category.",'".$composition."','".$safe."','".$shell."','".$pack."', ".$gmo.",'".$storageconditions."',". $kkz.",".$pricekkz.",".$weightkkz.",'".$pricekkzed."','".$weightkkzed."','".$weight."')");
         $id=$db->last();
   }
   //поставим всем фоткам с md5_mictotime temp=0-типа все временные
        $sql_temp_z_all="UPDATE productsphotos SET temp=0 WHERE md5_mictotime='".$_POST['temp']."'";
        $db->query($sql_temp_z_all);  
        //выберем самую последнюю загруженную фото
        $sql_get_last_photo="SELECT id FROM productsphotos WHERE md5_mictotime='".$_POST['temp']."' ORDER BY dtcreate DESC LIMIT 1";
        $db->query($sql_get_last_photo);
        if($db->getCount()>0)
        {
            //устанавливаем последней фотке статус постоянная
            $id_last_photos=$db->getValue();
            $sql_set_const_photo="UPDATE productsphotos SET id_product = $id,temp=1,md5_mictotime = '".$_POST['temp']."' WHERE id=".$id_last_photos;
            echo $sql_set_const_photo;
            $db->query($sql_set_const_photo);
            //а остальные временные можно удалить
            $sql_select_all_temp_to_delete="SELECT * FROM productsphotos WHERE temp=0 AND md5_mictotime='".$_POST['temp']."'";
            $db->query($sql_select_all_temp_to_delete);
            if($db->getCount()>0)
            {
                $arr_temp_photos_to_delete=$db->getArray();
                foreach($arr_temp_photos_to_delete as $arr_temp_photos_to_delete_index=>$arr_temp_photos_to_delete_val)
                {
                    if (@unlink($root.'/img/products/fsize1/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png'))
                        {
                            @unlink($root.'/img/products/fsize1/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png');
                        }
                        if (@unlink($root.'/img/products/fsize2/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png'))
                        {
                            @unlink($root.'/img/products/fsize2/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png');
                        }
                        if (@unlink($root.'/img/products/fsize3/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png'))
                        {
                            @unlink($root.'/img/products/fsize3/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.png');
                        }
                        
                        ///
                        
                        if (@unlink($root.'/img/products/fsize1/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png'))
                        {
                            @unlink($root.'/img/products/fsize1/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png');
                        }
                        if (@unlink($root.'/img/products/fsize2/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png'))
                        {
                            @unlink($root.'/img/products/fsize2/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png');
                        }
                        if (@unlink($root.'/img/products/fsize3/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png'))
                        {
                            @unlink($root.'/img/products/fsize3/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'_ass.png');
                        }
                        
                        $db -> query("DELETE FROM productsphotos WHERE id = {$arr_temp_photos_to_delete_val[id]}");
                }
            }
        }
   }
   else
   {
    print "<span style='color:red;' title='error'>".$err."</span>";
   }
}
else
{
   
        print "<span style='color:red;' title='error'>Наименовние товара обзательное поле</span>";

}
?>
 
