<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$pathToSaveImg = $root.'/img/dial/';
$id = $_POST['id'];
$action = $_POST['action'];

//удаление фото
if ($action == 'photodel')
{
    $db -> query("SELECT * FROM dialphotos WHERE id = {$id}");
    if ($db -> getCount())
    {
        $del_photos=$db -> getArray();
        foreach($del_photos as $del_photos_index=>$del_photos_val)
        {
            if (@unlink($root.'/img/dial/1000/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg'))
            {
                @unlink($root.'/img/dial/1000/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg');
            }
            if (@unlink($root.'/img/dial/1240/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg'))
            {
                @unlink($root.'/img/dial/1240/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg');
            }
            if (@unlink($root.'/img/dial/1320/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg'))
            {
                @unlink($root.'/img/dial/1320/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg');
            }
            if (@unlink($root.'/img/dial/1400/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg'))
            {
                @unlink($root.'/img/dial/1400/'.$del_photos_val['md5_mictotime'].'_'.$del_photos_val['id'].'.jpg');
            }
            $db -> query("DELETE FROM dialphotos WHERE id = {$del_photos_val[id]}");
        } 
        echo $DLL -> drowPhotoBlockDial($del_photos[0]['md5_mictotime'],null,null);
    }
    
}
else
//загрузка фото
if($_FILES['photo'])
{
    $files = array();
    /*foreach ($_FILES['photo'] as $k => $l)
    {
        foreach ($l as $i => $v)
        {
            if (!array_key_exists($i, $files))
            $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }*/
    
    //сохраняю фото
    //foreach($files as $file)
    //{
        $handle = new Upload($_FILES['photo']['tmp_name']);
        if ($handle -> uploaded) 
        {  
            $namephoto=$_POST['temp'].'_'.$db->getai('dialphotos');
        if (!$handle -> processed) echo 'Картинка не была загружена <br />error : ' . $handle -> error;
        $handle -> file_new_name_body = $namephoto;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 687;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/dial/1000');
        
         $handle -> file_new_name_body = $namephoto;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 825;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/dial/1240');
        
         $handle -> file_new_name_body = $namephoto;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 882;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/dial/1320');
		
         $handle -> file_new_name_body = $namephoto;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 999;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/dial/1400');
		$handle->clean();
        $db -> query("INSERT INTO dialphotos SET dtcreate='".date('Y-m-d H:i:s')."', md5_mictotime = '".$_POST['temp']."', temp = 0, id_dial = 0");
        
        }
    //}
    $DLL -> drowPhotoBlockDial($_POST['temp'],null,null);
}

//вывожу фото




?>
 
