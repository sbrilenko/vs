<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
//require_once $root."/lib/class.image.php";
$db = db :: getInstance(); 
$pathToSaveImg = $root.'/img/products/';
$id = $_POST['id'];
$action = $_POST['action'];

//удаление фото
if ($action == 'photodel')
{
    $db -> query("SELECT md5_mictotime FROM productsphotos WHERE id = {$id}");
    if ($db -> getCount())
    {
        $tmp = $db -> getValue();
        list($name,$ext) = explode('.',$tmp);
        $ext = '.'.$ext;
        if(@unlink($pathToSaveImg.$name.$ext))
        {
           @unlink($pathToSaveImg.$name.'_100'.$ext);
        }
        $db -> query("DELETE FROM productsphotos WHERE id = {$id}");
    }
}
else
//загрузка фото
if($_FILES['photo'])
{
    /*$files = array();
    foreach ($_FILES['photo'] as $k => $l)
    {
        foreach ($l as $i => $v)
        {
            if (!array_key_exists($i, $files))
            $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }*/
    
    //сохраняю фото
        $handle = new Upload($_FILES['photo']['tmp_name']);
        if ($handle -> uploaded) 
        {  
            $uniq = $_POST['temp'];
            $id_last=$db->getai('productsphotos');
            if (!$handle -> processed) echo 'Картинка не была загружена <br />error : ' . $handle -> error;
             
            if($handle->image_src_x!=480)
            {
                echo "<div class='error'>Ширина должна быть 480</div>";
                die();
            }
            if($handle->image_src_y!=480)
            {
                echo "<div class='error'>Высота должна быть 480</div>";
                die();
            }
            if($handle->file_src_size>1500000)
            {
                echo "<div class='error'>Загрузите фотографию меньшего размера</div>";
                die();
            }
            /*if($handle->image_src_x*3/2!=$handle->image_src_y)
            {
                echo "<div class='error'>Файл изображения должен быть в соотношении 2*3</div>";
                die();
            }*/
        $handle -> file_new_name_body = $uniq."_".$id_last;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 360;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize1/');
        
         $handle -> file_new_name_body = $uniq."_".$id_last;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 396;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/products/fsize2/');
        
        $handle -> file_new_name_body =$uniq."_".$id_last;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 480;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize3/');
		
        ///для страницы ассортимента
        $handle -> file_new_name_body = $uniq."_".$id_last."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 214;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize1/');
        
         $handle -> file_new_name_body = $uniq."_".$id_last."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 267;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/products/fsize2/');
        
         $handle -> file_new_name_body =$uniq."_".$id_last."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 360;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize3/');
        
		$handle->clean();
            
            $db -> query("INSERT INTO productsphotos SET dtcreate='".date('Y-m-d H:i:s')."', md5_mictotime = '".$uniq."', temp = 0, id_product = 0");
           $DLL -> drowPhotoBlock($uniq);
           }
      

}

//вывожу фото



?>
 
