<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$pathToSaveImg = $root.'/img/load/';
$name= $_GET['name'];
if(file_exists($pathToSaveImg.$name))
{
    set_time_limit(0);
    $delete_old=str_replace('.','',$name);
    $delete_old=str_replace('-','_',$delete_old);
    if(file_exists($root.'/img/firm/fsize1/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/firm/fsize1/'.$delete_old.'.jpg');
    }
    if(file_exists($root.'/img/firm/fsize2/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/firm/fsize2/'.$delete_old.'.jpg');
    }
    if(file_exists($root.'/img/firm/fsize3/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/firm/fsize3/'.$delete_old.'.jpg');
    }
    if(file_exists($root.'/img/firm/fsize1/'.$delete_old.'_ass.jpg'))
    {
        @unlink($root.'/img/firm/fsize1/'.$delete_old.'_ass.jpg');
    }
    if(file_exists($root.'/img/firm/fsize2/'.$delete_old.'_ass.jpg'))
    {
        @unlink($root.'/img/firm/fsize2/'.$delete_old.'_ass.jpg');
    }
    if(file_exists($root.'/img/firm/fsize3/'.$delete_old.'_ass.jpg'))
    {
        @unlink($root.'/img/firm/fsize3/'.$delete_old.'_ass.jpg');
    }
    $handle = new Upload($pathToSaveImg.$name);
        if ($handle -> uploaded) 
        {  
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
        $handle -> file_new_name_body = $name;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 51;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/firm/fsize1/');
        
        $handle -> file_new_name_body = $name;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 64;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/firm/fsize2/');
        
        $handle -> file_new_name_body =$name;
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 107;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/firm/fsize3/');
		
        ///для страницы ассортимента
        $handle -> file_new_name_body = $name."_ass";
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 47;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/firm/fsize1/');
        
        $handle -> file_new_name_body = $name."_ass";
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 47;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/firm/fsize2/');
        
        $handle -> file_new_name_body =$name."_ass";
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 70;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/firm/fsize3/');
		//$handle->clean();
        echo "OK";
}
else
echo $handle -> error;
}
        

?>
 
