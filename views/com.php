<?php
    $arr=array('����','������',"���");
    //phpinfo();
    $app = new COM("v82.COMConnector") or die("���������� ������� COM ����������"); 
    print "<br />Loaded 1Cn<br />"; 
    
    //���� � ���� � ��� ������������. ������� ���������� 
    //����� ��������� ������ ������������ (���� ����� 
    //� �������������) 
    
    $path = "C:\\1c_bases\extra_abz"; 
    $user = "root"; 
    $pass='';
    
    //�������������� � ������ ���� 
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
    //� �������� �������� ���������� �������� ���������� 
    //���������� glCurrUser ������������ � ������ ��������� 
    //���������� 1� ����������� v8 � ������������������ 
    //��� ������ ������ ������� 
    
    $con=null;
    $app= null; 
?>
