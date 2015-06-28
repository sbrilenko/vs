<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); //Подключение базы
$imageClass = new imageClass(); // класс ля работы с изображением
$pathimg='../../../../img/products/';
if(empty($_FILES['image']['tmp_name'][0]))
{
       $err.='<p style=\'color:red;\'>Файлы отсутствует или возможно файлы слишком большие! Попробуйте загрузить фотографии меньшего объема</p>';
	   print $err; 
	   return false;
}
else
{
        $err=''; //ошибки для каждого из файлов
        foreach($_FILES['image']['tmp_name'] as $index => $value) 
        {
	    if(!$image_info = $imageClass->getImageInfo($value)) 
						{
			            	$err.='<p style=\'color:red;\'>'.$_FILES['image']['name'][$index].' - Обработка файла изображения невозможна</p>';
			            }	
				        if ($image_info['extension'] != "jpg") {
				           $err.='<p style=\'color:red;\'>'.$_FILES['image']['name'][$index].' - Допустимые расширения для изображения *.jpg</p>';
				        }
						
					  if ($image_info['width'] <999) {
				           $err.='<p style=\'color:red;\'>'.$_FILES['image']['name'][$index].' - Ширина должна быть не меньше 999 пикселей</p>';
				       } 
						 if ($image_info['height'] <666) {
				           $err.='<p style=\'color:red;\'>'.$_FILES['image']['name'][$index].' - Высота должна быть не меньше 666 пикселей</p>';
				       } 
							
				        if ($image_info['size'] > (1024 * 1024 * 40)) {
				           $err.='<p style=\'color:red;\'>'.$_FILES['image']['name'][$index].' - Файл изображения больше 40 Мб</p>';
				        }
		if(empty($err)) {
		set_time_limit(0);
        $sql = "SHOW TABLE STATUS LIKE 'products_p'";
		$db->query($sql);
		$arr=$db->getArray();  
		$nid=$arr[0]['Auto_increment'];
        $name=$_POST['temp'].'_'.$nid;
		$handle = new upload($value);
		$handle->file_new_name_body   = $name;	
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
	    $handle->image_x               = 170;
		
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($pathimg.'1000');
		
		$handle->file_new_name_body   = $name;	
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
	    $handle->image_x               = 212;
		
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($pathimg.'1240');
		
		$handle->file_new_name_body   = $name;	
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 230;
		
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($pathimg.'1320');
		
		
		$handle->file_new_name_body   = $name;	
		$handle->image_convert = "jpg";
		$handle->image_resize          = true;
		$handle->image_ratio_y         = true;
	    $handle->image_x               = 254;
		
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($pathimg.'1400');
		$handle->clean();	
		$sql="INSERT INTO products_p (id,md5_mictotime,products_id,data_create,temp) VALUES (NULL,'{$_POST[temp]}',0,'".date('Y-m-d')."',0)";
	    $db->query($sql);
       
		}
		else
		{
			 print $err;	
		}						
}
}
if(empty($err))
{
     $sql="SELECT * FROM products_p WHERE md5_mictotime='".$_POST['temp']."' ORDER BY temp";
    $db->query($sql);
    if($db->getCount()>0)
    {
        $arr=$db->getArray();
        foreach($arr as $index=>$value)
        {
            ($value['main']==1)?$ch='checked="checked"':$ch="";
             print "<div style='float:left;'><img style='width:100px;height:150px;margin-left:7px;' src='../../../img/products/1000/".$value['md5_mictotime'].'_'.$value['id'].".jpg'/><div style='float:right;width: 25px;'><img title='Удалить' class='del_img' data-del='{$value[id]}' style='cursor:pointer;margin-left:7px' {$ch} src='../../../img/admin/d.png'/><input style='margin-left:7px' title='Сделать основной' type='checkbox' /></div></div>";  
        }
    }
}
			 
?>  
