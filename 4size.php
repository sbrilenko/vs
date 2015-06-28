<?php 

 
 $myFile = "css/s1600.css"; /*файл который парсим*/
 $handle = fopen($myFile, 'r'); 
 $data='';
 $line_count=0;
 $find=0;
 $data= fgets($handle);

 $ereg1d="([0-9]+)";
 $ereg2d="(.*)";
 $ereg1=$ereg1d;
 $ereg2=$ereg2d;
 	
			ereg("\\/\\*([0-9]+)\\*\\/.*",$data,$num);
			for($j=1;$j<$num[1];$j++)
			{
				$ereg1.=';'.$ereg1d;
				$ereg2.='~'.$ereg2d;
			}
	//print $ereg1.'<br />'.$ereg2;
   	$data= fgets($handle);
	//print $data;
		eval('ereg("\\/\\*'.$ereg1.'\\*\\/.*",$data,$Files);');
		for($k=1;$k<count($Files);$k++)
		{
			$name_file = "css/s".$Files[$k].".css"; /*выходной файл*/
		    eval('$handler'.$k.'= fopen($name_file, "w");');
		}	
 	
 while (!feof($handle)) 
 {

		$data= fgets($handle);
				 $line_count++;
		
				 if($find==1)
			{
				if(strpos($data,'}')!=0)
				{
					$find=0;
					//print $data.'<br />';	
					for($k=1;$k<count($Files);$k++)
								{
									  eval('fputs($handler'.$k.',"$data");');
								}
					continue; 
				}
				
				if(strpos($data,'/*')!=0)
				{
					$err=ereg("(.*):(.*);.*\\/\\*". $ereg2."\\*\\/.*",$data,$Pockets);
								for($k=1;$k<count($Files);$k++)
								{
									  eval('fputs($handler'.$k.',"$Pockets[1]:$Pockets['.($k+2).'];\n");');
								}
							 
						   
					         
							
							
				}
				else
				{
					//print $data.'<br />';
					for($k=1;$k<count($Files);$k++)
								{
									  eval('fputs($handler'.$k.',"$data");');
								}
					
					
				}
					
		 	}
			else
			{
				if(strpos($data,'{')!=0)
				{
					$find=1;
					//print $data.'<br />';
					for($k=1;$k<count($Files);$k++)
								{
									  eval('fputs($handler'.$k.',"$data");');
								}
					
				}
			}
		
		 
 } 
for($k=1;$k<count($Files);$k++)
		{
			$name_file = "css/styles_".$Files[$k].".css"; /*выходной файл*/
			//print $name_file;
			fclose("$handler".$k);
		}	
 //$buffer=preg_replace('/\s+$/m', '', $data);
  
$str="	background:#1f2952;/*101%~102%*/";
		// Разбиваем строку на куски при помощи ereg
		$err=ereg("(.*):(.*);.*\\/\\*(.*)~(.*)\\*\\/.*",$str,$Pockets);

		//echo $Pockets[1].':'.$Pockets[2].';';
		//print "<br>---<br>";
		//echo $Pockets[1].':'.$Pockets[3].';';
		//print "<br>---<br>";
		//echo $Pockets[1].':'.$Pockets[4].';';

 	   fclose($handle); 
							     for($k=1;$k<count($Files);$k++)
								{
									  eval('fclose($handler'.$k.');');
								}

?>
