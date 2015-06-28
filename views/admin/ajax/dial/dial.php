<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();
$name = htmlspecialchars(strip_tags($_POST['name']));
if(!empty($name))
{
    $show = ($_POST['vis']) ? 1 : 0;
    $new=($_POST['new'])?1:0;
    $bestseller=($_POST['bestseller'])?1:0;
    $presence=($_POST['presence'])?1:0;
    $article = htmlspecialchars(strip_tags($_POST['article']));
    $event=($_POST['event'])?1:0;
    $dtNow = $dtClass -> dtInDB();
    $id = htmlspecialchars(strip_tags($_POST['id']));
    
    $price = (float)strip_tags($_POST['price']);
    $price = number_format($price,2,'.','');
    $priority = $_POST['priority'];
    $weight = htmlspecialchars(strip_tags($_POST['weight']));
    $description = $form -> replaceToInsert($_POST['description']);
    if ($_POST['action'] == 'edit')
    {
        $db -> query("UPDATE dial SET 
        vis = '".$show."',
        name = '".$name."',
        idUserUpdate = '".$_SESSION['userID']."', 
        dtUpdate = '".$dtNow."',
        description = '".$description."',
        price = '".$price."',
        priority = '".$priority."',weight='".$weight."',
        weight_val='".$_POST['weight_val']."',
        new=".$new.",bestseller=".$bestseller.",event=".$event.",article='".$article."',
        presence=".$presence."
        WHERE id = ".$id);   
    }
    if ($_POST['action'] == 'add')
    {
        $db -> query("INSERT INTO dial (vis,name,idUserCreate,dtCreate,description,price,priority,weight,weight_val,new,bestseller,event,article,presence) VALUES  (".$show.", '".$name."', '".$_SESSION['userID']."', '".$dtNow."', '".$description."', '".$price."', ".$priority.",'".$weight."','".$_POST['weight_val']."',".$new.",".$bestseller.",".$event.",'".$article."',".$presence.")");
        $id = $db -> last();
    }
    //удалим вес состав
    $sql_del_sostav="DELETE FROM sostav WHERE id_dial=".$id;
    $db->query($sql_del_sostav);
    //добавлем состав если есть
    $arrvalselected=array();
    foreach($_POST as $index=>$val)
    {
        $expl=explode('sostav',$index);
        if($expl[1])
        {
            array_push($arrvalselected,$val);
        }
    }
    foreach($arrvalselected as $arrvalselected_index=>$arrvalselected_val)
    {
        $vesnarez=(empty($_POST['vesnarez'.$arrvalselected_val]))?0:$_POST['vesnarez'.$arrvalselected_val];
        $sql_add_sostav="INSERT INTO sostav (id,id_ass,narezannot,vesnarez,ed,id_dial) VALUES (NULL,{$arrvalselected_val},".$_POST['narezannot'.$arrvalselected_val].",".$vesnarez.",".$_POST['narezed'.$arrvalselected_val].",{$id})";
        $db->query($sql_add_sostav);
    }
    //
     //поставим всем фоткам с md5_mictotime temp=0-типа все временные
        $sql_temp_z_all="UPDATE dialphotos SET temp=0 WHERE md5_mictotime='".$_POST['temp']."'";
        $db->query($sql_temp_z_all);  
        //выберем самую последнюю загруженную фото
        $sql_get_last_photo="SELECT id FROM dialphotos WHERE md5_mictotime='".$_POST['temp']."' ORDER BY dtcreate DESC LIMIT 1";
        $db->query($sql_get_last_photo);
        if($db->getCount()>0)
        {
            //устанавливаем последней фотке статус постоянная
            $id_last_photos=$db->getValue();
            $sql_set_const_photo="UPDATE dialphotos SET temp=1,id_dial = $id WHERE id=".$id_last_photos;
            $db->query($sql_set_const_photo);
            //а остальные временные можно удалить
            $sql_select_all_temp_to_delete="SELECT * FROM dialphotos WHERE temp=0 AND md5_mictotime='".$_POST['temp']."'";
            $db->query($sql_select_all_temp_to_delete);
            if($db->getCount()>0)
            {
                $arr_temp_photos_to_delete=$db->getArray();
                foreach($arr_temp_photos_to_delete as $arr_temp_photos_to_delete_index=>$arr_temp_photos_to_delete_val)
                {
                    if (@unlink($root.'/img/dial/1000/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg'))
                        {
                            @unlink($root.'/img/dial/1000/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg');
                        }
                        if (@unlink($root.'/img/dial/1240/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg'))
                        {
                            @unlink($root.'/img/dial/1240/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg');
                        }
                        if (@unlink($root.'/img/dial/1320/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg'))
                        {
                            @unlink($root.'/img/dial/1320/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg');
                        }
                        if (@unlink($root.'/img/dial/1400/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg'))
                        {
                            @unlink($root.'/img/dial/1400/'.$arr_temp_photos_to_delete_val['md5_mictotime'].'_'.$arr_temp_photos_to_delete_val['id'].'.jpg');
                        }
                        
                        $db -> query("DELETE FROM dialphotos WHERE id = {$arr_temp_photos_to_delete_val[id]}");
                }
            }
        }

}
else
{
    print "<span style='color:red;' title='error'>Название обязательное поле</span>";
}
?>
 
