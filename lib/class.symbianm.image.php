<?php
    /**
     * Версия 11.07.02
     */

    class imageClass {
    	
		private function setTransparency($new_image, $image_source)
 {
         $transparencyIndex = imagecolortransparent($image_source);
         $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);
         
         if ($transparencyIndex >= 0)
             $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);   
         
         $transparencyIndex = imagecolorallocate($new_image, 0, $transparencyColor['green'], $transparencyColor['blue']);
         imagefill($new_image, 0, 0, $transparencyIndex);
         return imagecolortransparent($new_image, $transparencyIndex);
 }
        
	public function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{ if (!file_exists($src)) return false;
$size = getimagesize($src);
if ($size === false) return false;
$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
$icfunc = "imagecreatefrom" . $format;
if (!function_exists($icfunc)) return false;
$x_ratio = $width / $size[0];
$y_ratio = $height / $size[1];
$ratio = min($x_ratio, $y_ratio);
$use_x_ratio = ($x_ratio == $ratio);
$new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
$new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
$new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
$new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
$isrc = $icfunc($src);
$idest = imagecreatetruecolor($width, $height);
imagefill($idest, 0, 0, $rgb);
imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
$new_width, $new_height, $size[0], $size[1]);
imagejpeg($idest, $dest, $quality);
imagedestroy($isrc);
imagedestroy($idest);
return true;  
}
	
        public function getImageInfo ($file = NULL)
        {
            if(!is_file($file)) return false;

            if(!$data = getimagesize($file) or !$filesize = filesize($file)) return false;

            $extensions = array(1  => 'gif',   2  => 'jpg',
                                3  => 'png',   4  => 'swf',
                                5  => 'psd',   6  => 'bmp',
                                7  => 'tiff',  8  => 'tiff',
                                9  => 'jpc',   10 => 'jp2',
                                11 => 'jpx',   12 => 'jb2',
                                13 => 'swc',   14 => 'iff',
                                15 => 'wbmp',  16 => 'xbmp');

            $result = array('width'     =>    $data[0],
                            'height'    =>    $data[1],
                            'extension' =>    $extensions[$data[2]],
                            'size'      =>    $filesize,
                            'mime'      =>    $data['mime']);

            return $result;
        }
        
        public function createImageThumb($src, $out, $maxWidth, $maxHeight, $color = 0xFFFFFF, $quality = 100) 
        {
        	ini_set('memory_limit', '-1');
            if (!file_exists($src)) {
                return false;  
            }
            
            if (!$imageInfo = $this->getImageInfo ($src))
                return false;

            $srcWidth  = $imageInfo['width'];
            $srcHeight = $imageInfo['height'];

            switch ($imageInfo['mime']) {
                 case "image/gif":
                     $src = imagecreatefromgif($src);
                     $ext = ".gif";
                 break;

                 case "image/jpeg":
                     $src = imagecreatefromjpeg($src);
                     $ext = ".jpg";
                 break;

                 case "image/png":
                     $src = imagecreatefrompng($src);
                     $ext = ".png";
                 break;

                 default:
                     return false;
                 break;
             }  

            $kWidth = $srcWidth / $maxWidth;
            $kHeight = $srcHeight / $maxHeight;
            // посольку коэфициент расчитан путем деления большей стороны на меньшую, то выбираем меньший (k>1),
            // если бы делили меньшую на большую, то выбирали бы макс. значение (k < 1)
            $k = min($kHeight, $kWidth);  

            // тут мы получим ширину и высоту изображения
            $tmpWidth = round($srcWidth / $k);
            $tmpHeight = round($srcHeight / $k);

            // вычисляем размеры конечного изображения
            $dstWidth = $tmpWidth - ($tmpWidth - $maxWidth);
            $dstHeight = $tmpHeight - ($tmpHeight - $maxHeight);

            // вичисляем сдвиг (т.е., это ровно половина разницы между соот. сторонами временной миниатюрой и нужной нам миниатурой) 
            $wOffset = $tmpWidth > $maxWidth  ? ($tmpWidth-$maxWidth)/2 : 0;
            $hOffset = $tmpHeight > $maxHeight  ? ($tmpHeight-$maxHeight)/2 : 0;

            // таким образом в $tmp будет лежать фото 
            $tmp = imagecreatetruecolor($tmpWidth, $tmpHeight); 
            
            // а тут будет обрезанное фото
            $dst = imagecreatetruecolor($dstWidth, $dstHeight); // сюда ложим 
            // ресайзим           
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $srcWidth, $srcHeight); // ресайзим

            // копируем область с параметрами
            imagecopy ($dst, $tmp, 0, 0, $wOffset, $hOffset, $dstWidth, $dstHeight); 

            switch ($ext) {
                 case ".jpg": $result = imagejpeg($dst, $out, 100);break;
                 case ".png": $result = imagepng($dst, $out, 5);break;
                 case ".gif": $result = imagegif($dst, $out, 100);break;
             }
             
             unset ($tmp);
             unset ($dst);
             
            return true;
        }  
        
        public function resizeImage($src, $out, $maxWidth, $maxHeight, $color = 0xFFFFFF, $quality = 100) 
        {
        	//ini_set('memory_limit', '-1');
            if (!file_exists($src))
                return false;  
        
            if (!$imageInfo = $this->getImageInfo ($src))
                return false;
            
            $srcWidth  = $imageInfo['width'];
            $srcHeight = $imageInfo['height'];
            
            if ($srcWidth < $maxWidth && $srcHeight < $maxHeight)
                return true;
 
            switch ($imageInfo['mime']) {
                 case "image/gif":
                     $src = imagecreatefromgif($src);
                     $ext = ".gif";
                 break;

                 case "image/jpeg":
                     $src = imagecreatefromjpeg($src);
                     $ext = ".jpg";
                 break;

                 case "image/png":
                     $src = imagecreatefrompng($src);
                     $ext = ".png";
                 break;

                 default:
                     return false;
                 break;
             }  
             
            $kWidth = $srcWidth / $maxWidth;
            $kHeight = $srcHeight / $maxHeight;
            // посольку коэфициент расчитан путем деления большей стороны на меньшую, то выбираем меньший (k>1),
            // если бы делили меньшую на большую, то выбирали бы макс. значение (k < 1)
            $k = $kHeight;  // min - resize по высоте

            // тут мы получим ширину и высоту большего изображения
            $tmpWidth = round($srcWidth / $k);
            $tmpHeight = round($srcHeight / $k);
            
            //echo $maxWidth."x".$maxHeight."<br />";
            //echo $srcWidth."x".$srcHeight."<br />";
            //echo $tmpWidth."x".$tmpHeight."<br />";
            
            // таким образом в $tmp будет лежать фото 
            $tmp = imagecreatetruecolor($tmpWidth, $tmpHeight); 
            
            // ресайзим         
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $srcWidth, $srcHeight); // ресайзим

            switch ($ext) {
                 case ".jpg": $result = imagejpeg($tmp, $out, 100);break;
                 case ".png": $result = imagepng($tmp, $out, 5);break;
                 case ".gif": $result = imagegif($tmp, $out, 100);break;
             }
             unset ($tmp);
            return true;
        }    



		/*Расчет по ширине*/      
		        public function resizeImage_width($src, $out, $maxWidth, $maxHeight, $color = 0xFFFFFF, $quality = 100) 
        {
        	//ini_set('memory_limit', '-1');
            if (!file_exists($src))
                return false;  
        
            if (!$imageInfo = $this->getImageInfo ($src))
                return false;
            
            $srcWidth  = $imageInfo['width'];
            $srcHeight = $imageInfo['height'];
            
            if ($srcWidth < $maxWidth && $srcHeight < $maxHeight)
                return true;
 
            switch ($imageInfo['mime']) {
                 case "image/gif":
                     $src = imagecreatefromgif($src);
                     $ext = ".gif";
                 break;

                 case "image/jpeg":
                     $src = imagecreatefromjpeg($src);
                     $ext = ".jpg";
                 break;

                 case "image/png":
                     $src = imagecreatefrompng($src);
                     $ext = ".png";
                 break;

                 default:
                     return false;
                 break;
             }  
             
            $kWidth = $maxWidth/$srcWidth ;
            $kHeight =  $maxHeight/$srcHeight;
            // посольку коэфициент расчитан путем деления большей стороны на меньшую, то выбираем меньший (k>1),
            // если бы делили меньшую на большую, то выбирали бы макс. значение (k < 1)
            $k = $kWidth;  // min - resize по ширине

            // тут мы получим ширину и высоту большего изображения
            $tmpWidth = round($srcWidth / $k);
            $tmpHeight = round($srcHeight / $k);
            
            //echo $maxWidth."x".$maxHeight."<br />";
            //echo $srcWidth."x".$srcHeight."<br />";
            //echo $tmpWidth."x".$tmpHeight."<br />";
            
            // таким образом в $tmp будет лежать фото 
            $tmp = imagecreatetruecolor($tmpWidth, $tmpHeight); 
            ///устанавливаем прозрачность
 			//$this->setTransparency($tmp, $src);
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
            // ресайзим       
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $srcWidth, $srcHeight); // ресайзим

            switch ($ext) {
                 case ".jpg": $result = imagejpeg($tmp, $out, 100);break;
                 case ".png": $result = imagepng($tmp, $out, 5); break;
                 case ".gif": $result = imagegif($tmp, $out, 100);break;
             }
             unset ($tmp);
            return true;
        } 
		
		
		/*Точный размер*/
				        public function resizeImage_razmer($src, $out, $maxWidth, $maxHeight, $color = 0xFFFFFF, $quality = 100) 
        {
        	//ini_set('memory_limit', '-1');
            if (!file_exists($src))
                return false;  
        
            if (!$imageInfo = $this->getImageInfo ($src))
                return false;
            
            $srcWidth  = $imageInfo['width'];
            $srcHeight = $imageInfo['height'];
            
            if ($srcWidth < $maxWidth && $srcHeight < $maxHeight)
                return true;
 
            switch ($imageInfo['mime']) {
                 case "image/gif":
                     $src = imagecreatefromgif($src);
                     $ext = ".gif";
                 break;

                 case "image/jpeg":
                     $src = imagecreatefromjpeg($src);
                     $ext = ".jpg";
                 break;

                 case "image/png":
                     $src = imagecreatefrompng($src);
                     $ext = ".png";
                 break;

                 default:
                     return false;
                 break;
             }  
             
            $kWidth = $srcWidth / $maxWidth;
            $kHeight = $srcHeight / $maxHeight;
            // посольку коэфициент расчитан путем деления большей стороны на меньшую, то выбираем меньший (k>1),
            // если бы делили меньшую на большую, то выбирали бы макс. значение (k < 1)
          //  $k = $kWidth;  // min - resize по ширине

            // тут мы получим ширину и высоту большего изображения
            $tmpWidth = round($srcWidth / $kWidth);
            $tmpHeight = round($srcHeight / $kHeight);
            
            //echo $maxWidth."x".$maxHeight."<br />";
            //echo $srcWidth."x".$srcHeight."<br />";
            //echo $tmpWidth."x".$tmpHeight."<br />";
            
            // таким образом в $tmp будет лежать фото 
            $tmp = imagecreatetruecolor($tmpWidth, $tmpHeight); 
            
            // ресайзим         
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $srcWidth, $srcHeight); // ресайзим

            switch ($ext) {
                 case ".jpg": $result = imagejpeg($tmp, $out, 100);break;
                 case ".png": $result = imagepng($tmp, $out, 5);break;
                 case ".gif": $result = imagegif($tmp, $out, 100);break;
             }
             unset ($tmp);
            return true;
        } 
		
    }   //конец класса valid
	
	
?>


