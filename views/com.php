<?php
    $arr=array('один','ляляля',"два");
    //phpinfo();
    $app = new COM("v82.COMConnector") or die("Невозможно создать COM соединение"); 
    print "<br />Loaded 1Cn<br />"; 
    
    //Путь к базе и имя пользователя. Третьим параметром 
    //может выступать пароль пользователя (если задан 
    //в конфигураторе) 
    
    $path = "C:\\1c_bases\extra_abz"; 
    $user = "root"; 
    $pass='';
    
    //Подсоединяемся к нужной базе 
    echo 'File="'.$path.'";Usr="'.$user.'";pwd="'.$pass.'"';
    try {
        $con = $app->Connect('File="'.$path.'";Usr="'.$user.'";pwd="'.$pass.'"'); 
    }
    catch(Exception $e)
    {
        print_r($e);
    }
    echo "<br />";
    echo $con->test($arr)."|<br />";
    //echo iconv("ASCII", "UTF-8", $con->test($arr));
    //В качестве проверки результата получаем глобальную 
    //переменную glCurrUser определенную в модуле внешенего 
    //соединения 1С Предприятия v8 и инициализированную 
    //при начале работы системы 
    
    $con=null;
    $app= null; 
?>
