<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$pathToSaveImg = $root.'/img/load/';
$sql="SELECT * FROM goods WHERE photo<>''";
$db->query($sql);
if($db->getCount()>0)
{
    $arr=$db->getArray();
    foreach($arr as $arr_index=>$arr_val)
    {
        $name= $arr_val['photo'];
if(file_exists($pathToSaveImg.$name))
{
    set_time_limit(0);
    $delete_old=str_replace('.','',$name);
    $delete_old=str_replace('-','_',$delete_old);
    if(file_exists($root.'/img/products/fsize1/'.$delete_old.'.png'))
    {
        @unlink($root.'/img/products/fsize1/'.$delete_old.'.png');
    }
    if(file_exists($root.'/img/products/fsize2/'.$delete_old.'.png'))
    {
        @unlink($root.'/img/products/fsize2/'.$delete_old.'.png');
    }
    if(file_exists($root.'/img/products/fsize3/'.$delete_old.'.png'))
    {
        @unlink($root.'/img/products/fsize3/'.$delete_old.'.png');
    }
    if(file_exists($root.'/img/products/fsize1/'.$delete_old.'_ass.png'))
    {
        @unlink($root.'/img/products/fsize1/'.$delete_old.'_ass.png');
    }
    if(file_exists($root.'/img/products/fsize2/'.$delete_old.'_ass.png'))
    {
        @unlink($root.'/img/products/fsize2/'.$delete_old.'_ass.png');
    }
    if(file_exists($root.'/img/products/fsize3/'.$delete_old.'_ass.png'))
    {
        @unlink($root.'/img/products/fsize3/'.$delete_old.'_ass.png');
    }
    //order
    if(file_exists($root.'/img/products/fsize1/'.$delete_old.'_or.png'))
    {
        @unlink($root.'/img/products/fsize1/'.$delete_old.'_or.png');
    }
    if(file_exists($root.'/img/products/fsize2/'.$delete_old.'_or.png'))
    {
        @unlink($root.'/img/products/fsize2/'.$delete_old.'_or.png');
    }
    if(file_exists($root.'/img/products/fsize3/'.$delete_old.'_or.png'))
    {
        @unlink($root.'/img/products/fsize3/'.$delete_old.'_or.png');
    }
    $handle = new Upload($pathToSaveImg.$name);
        if ($handle -> uploaded) 
        {  
            if (!$handle -> processed) echo 'Картинка не была загружена <br />error : ' . $handle -> error;
             
            if($handle->image_src_x!=480)
            {
                echo "<div class='error'>".$name." Ширина должна быть 480</div>";
                //die();
            }
            if($handle->image_src_y!=480)
            {
                echo "<div class='error'>".$name." Высота должна быть 480</div>";
                //die();
            }
            if($handle->file_src_size>1500000)
            {
                echo "<div class='error'>".$name." Загрузите фотографию меньшего размера</div>";
                //die();
            }
        $handle -> file_new_name_body = $name;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 360;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize1/');
        
        $handle -> file_new_name_body = $name;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 396;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/products/fsize2/');
        
        $handle -> file_new_name_body =$name;
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 480;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize3/');
		
        ///для страницы ассортимента
        $handle -> file_new_name_body = $name."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 214;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize1/');
        
        $handle -> file_new_name_body = $name."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 267;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/products/fsize2/');
        
        $handle -> file_new_name_body =$name."_ass";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 360;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize3/');
        
        //для заказа маленкие 
        ///для страницы ассортимента
        $handle -> file_new_name_body = $name."_or";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 40;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize1/');
        
        $handle -> file_new_name_body = $name."_or";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 40;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/products/fsize2/');
        
        $handle -> file_new_name_body =$name."_or";
		$handle->image_convert = "png";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 50;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/products/fsize3/');
        
        
		//$handle->clean();
        echo "OK";
}
else
echo $handle -> error;
}
    }
}

        

?>
 
