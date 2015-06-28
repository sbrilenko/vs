<?php
$root = $_SERVER['DOCUMENT_ROOT'];	
require_once $root."/lib/class.invis.db.php";
$db = db :: getInstance();	

if($_POST['db'])
{
    $db->query("SELECT gallery_photo FROM goods WHERE deleted = 0 AND id =".$_POST['db']);
    if($db->getCount()>0){
        
        $arr_photos=$db->getValue();
        $photo = str_replace('-','_',$arr_photos);
            $elements = $photo;
        
        
        echo $elements;
    }
    exit();
}else{
  
}	

if(!empty($_POST['dir']))
{
    
    if($_POST['action']==-1)
    {
        //по всем папкам
    }
    else
    {
        
        $dir = $root."/".$_POST['dir']."fsize1";
        	$array=null;
            if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $usl = $prewiew == null?0:substr($file,0,strpos($file,'_'));
                   // echo "файл: $file : тип: " . filetype($dir . $file) . "";
                    if((substr(strrchr($file, '.'), 1)=='jpg')&&($usl == $current))
        			{
        				 $array[]=$file;
        				 /*$elements .= '"'.$file.'"';
                   		 if ($count!=$number) $elements .= ",";
        				 $number++;*/
        			}	
                } 
            }
            
            
            for($i=0;$i<count($array);$i++)
            {
                
                   $elements = '"'.$array[$i].'"';
                    if ($i+1!=count($array)) $elements .= ","; 
              
            	 
            }
            closedir($dh);
        }
       
        echo $elements;
    }

}

 
?>