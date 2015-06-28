<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$pathToSaveImg = $root.'/img/load/';
$name= $_GET['name'];
if(file_exists($pathToSaveImg.$name))
{
    $a = pathinfo($name);
    $delete_old = $a['filename'];
    //print_r($a);
    set_time_limit(0);
    $delete_old=str_replace('.','',$delete_old);
    $delete_old=str_replace('-','_',$delete_old);
    if(file_exists($root.'/img/gal/fsize1/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/gal/fsize1/'.$delete_old.'.jpg');
    }
    if(file_exists($root.'/img/gal/fsize2/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/gal/fsize2/'.$delete_old.'.jpg');
    }
    if(file_exists($root.'/img/gal/fsize3/'.$delete_old.'.jpg'))
    {
        @unlink($root.'/img/gal/fsize3/'.$delete_old.'.jpg');
    }
    
    $handle = new Upload($pathToSaveImg.$name);
        if ($handle -> uploaded) 
        {  
            if (!$handle -> processed) echo 'Picture was not loaded <br />error : ' . $handle -> error;
            
            if($a['extension']!='jpg')
            {
                echo "<div class='error'>File format must be *.jpg</div>";
                die(); 
            }
             
            if($handle->image_src_x<960)
            {
                echo "<div class='error'>width must be > 960px</div>";
                die();
            }
            if($handle->image_src_y<720)
            {
                echo "<div class='error'>height must be >= 720px</div>";
                die();
            }
            if($handle->file_src_size>1024*1024*5)
            {
                echo "<div class='error'>Very big file</div>";
                die();
            }
            
        $handle -> file_new_name_body = $delete_old;
		$handle->image_convert = "jpg";
        
        if(459*$handle->image_src_x/$handle->image_src_y>459*16/9)
        {
            //сколько нужно обрезать
            $crop=(-1)*(459*16/9-459*$handle->image_src_x/$handle->image_src_y);
             if ($crop%2 == 0){
                $handle->image_crop            = '0 '.($crop/2).' 0 '.($crop/2);
            }
            else
            {
                $handle->image_crop            = '0 '.(int)($crop/2).' 0 '.((int)($crop/2)+1);
            }
        }
        
		$handle->image_resize          = true;
		$handle->image_y               = 459;
		$handle->image_ratio_x         = true;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/gal/fsize1/');
        
        $handle -> file_new_name_body = $delete_old;
		$handle->image_convert = "jpg";
        
        if(576*$handle->image_src_x/$handle->image_src_y>576*16/9)
        {
            //сколько нужно обрезать
            $crop=(-1)*(576*16/9-576*$handle->image_src_x/$handle->image_src_y);
             if ($crop%2 == 0){
                $handle->image_crop            = '0 '.($crop/2).' 0 '.($crop/2);
            }
            else
            {
                $handle->image_crop            = '0 '.(int)($crop/2).' 0 '.((int)($crop/2)+1);
            }
        }
        
		$handle->image_resize          = true;
		$handle->image_y               = 576;
		$handle->image_ratio_x         = true;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
        $handle->process($root.'/img/gal/fsize2/');
        
        $handle -> file_new_name_body =$delete_old;
		$handle->image_convert = "jpg";
        
        if(720*$handle->image_src_x/$handle->image_src_y>720*16/9)
        {
            //сколько нужно обрезать
            $crop=(-1)*(720*16/9-720*$handle->image_src_x/$handle->image_src_y);
             if ($crop%2 == 0){
                $handle->image_crop            = '0 '.($crop/2).' 0 '.($crop/2);
            }
            else
            {
                $handle->image_crop            = '0 '.(int)($crop/2).' 0 '.((int)($crop/2)+1);
            }
        }
        
		$handle->image_resize          = true;
		$handle->image_y               = 720;
		$handle->image_ratio_x         = true;
		$handle->jpeg_quality = 100;
		$handle->image_unsharp = false; //лучше true, но класс выдает ошибку
		$handle->process($root.'/img/gal/fsize3/');
        
		//$handle->clean();
        echo "OK";
       
}
else
{
   //echo $handle -> error;
    echo 'file was not uploaded'; 
}

}else{
    echo 'no file';
}
        

?>
 
