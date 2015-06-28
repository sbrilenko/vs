<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
if($_POST['sort'])
{
    switch($_POST['sort'])
    {
        case 'name':
        $sql="SELECT * FROM clients WHERE archive=0 ORDER BY name DESC LIMIT 9999";
        
        break;
        case 'phone':
        $sql="SELECT * FROM clients WHERE archive=0 ORDER BY phone DESC LIMIT 9999";
        break;
        case 'card':
        $sql="SELECT * FROM clients WHERE archive=0 ORDER BY namberCard DESC LIMIT 9999";
        break;
    }
$db ->query($sql);
     if ($db -> getCount()>0) 
        {
            $products = $db -> getArray();
            foreach($products as $i => $product)
            {
                $firm = $DLL -> getFirm($product['idFirm']);
                $group = $DLL -> getGroup($product['idGroup']);
                $subgroup = $DLL -> getSubgroup($product['idSubgroup']);
                $userCreate = $DLL -> getUser($product['idUserCreate']);
                if ($product['idUserUpdate']) $userUpdate = $DLL -> getUser($product['idUserUpdate']);
                 
                echo '<tr style="text-align:left;">';
                echo '<td style="width: 69px;vertical-align:middle;">';
                echo '&emsp;<a href="/admin/clientsEditor/edit/'.$product['id'].'"><img src="/img/admin/e.png" title="Редактировать"/></a>';
                echo '&emsp;<a class="deleteclient" href="/admin/clientsEditor/delete/'.$product['id'].'"><img src="/img/admin/d.png" title="Удалить"/></a>';
                echo '</td>';
                echo '<td>';
                echo '</div>';
                echo '<div style="float:left;">';
                if(!empty($product['name']))
                {
                    echo '<span class="nf">ФИО:</span> '.$product['name']."<br />";
                }
                 if(!empty($product['org']))
                {
                    echo '<span class="nf">Название организации:</span> '.$product['ord']."<br />";
                }
                if(!empty($product['namberCard']))
                {
                    echo '<span class="nf">Номер карты:</span> '.$product['namberCard']."<br />";
                }
                if(!empty($product['discount']))
                {
                    echo '<span class="nf">Скидка:</span> '.$product['discount']." %<br />";
                }
                if(!empty($product['zakaz']))
                {
                    echo '<span class="nf">Общая сумма заказа:</span> '.$product['zakaz']." <br />";
                }
                if(!empty($product['email']))
                {
                    echo '<span class="nf">Email:</span> '.$product['email']." <br />";
                }
                echo '<span class="nf">Телефон:</span> '.$product['phone'];
                echo '</td>';
                echo '<td style="line-height: 10px;text-align: right;font-weight: normal;font-size: 9px;font-style: italic;color: #97978D;">';
                echo '<span class="nf">Товар создан:</span> ('.$dtClass -> dtFromDB($product['dtCreate']).')';
                echo '<br /><span class="nf">Пользователем:</span> '.$userCreate;
                if ($userUpdate) 
                {
                    echo '<br /><span class="nf">Товар редактирован:</span> ('.$dtClass -> dtFromDB($product['dtUpdate']).')';
                    echo '<br /><span class="nf">Пользователем:</span> '.$userUpdate;
                }
                echo '</td>';
                echo'</tr>';
                 
                
            }
            if ($i==9998) echo 'Это не все товары.<br /> Ограничено на показ 9999 шт.';
        }
}
else
if($_POST['action']=='search')
{
    switch($_POST['s'])
    {
        case 'name':
        $sort='name';
        break;
        case 'phone':
        $sort='phone';
        break;
        case 'card':
        $sort='namberCard';
        break;
    }
    $input=htmlspecialchars(strip_tags($_POST['input']));
    switch($_POST['sel'])
    {
        case 'name':
        $sql="SELECT * FROM clients WHERE archive=0 AND name='".$input."' ORDER BY ".$sort." DESC LIMIT 9999";
        break;
        case 'phone':
        $sql="SELECT * FROM clients WHERE archive=0 AND phone='".$input."' ORDER BY ".$sort." DESC LIMIT 9999";
        break;
        case 'card':
        $sql="SELECT * FROM clients WHERE archive=0 AND namberCard='".$input."' ORDER BY ".$sort." DESC LIMIT 9999";
        break;
    }
$db ->query($sql);
     if ($db -> getCount()>0) 
        {
            $products = $db -> getArray();
            foreach($products as $i => $product)
            {
                $firm = $DLL -> getFirm($product['idFirm']);
                $group = $DLL -> getGroup($product['idGroup']);
                $subgroup = $DLL -> getSubgroup($product['idSubgroup']);
                $userCreate = $DLL -> getUser($product['idUserCreate']);
                if ($product['idUserUpdate']) $userUpdate = $DLL -> getUser($product['idUserUpdate']);
                 
                echo '<tr style="text-align:left;">';
                echo '<td style="width: 69px;vertical-align:middle;">';
                echo '&emsp;<a href="/admin/clientsEditor/edit/'.$product['id'].'"><img src="/img/admin/e.png" title="Редактировать"/></a>';
                echo '&emsp;<a class="deleteclient" href="/admin/clientsEditor/delete/'.$product['id'].'"><img src="/img/admin/d.png" title="Удалить"/></a>';
                echo '</td>';
                echo '<td>';
                echo '</div>';
                echo '<div style="float:left;">';
               if(!empty($product['name']))
                {
                    echo '<span class="nf">ФИО или название организации:</span> '.$product['name']."<br />";
                }
                if(!empty($product['namberCard']))
                {
                    echo '<span class="nf">Номер карты:</span> '.$product['namberCard']."<br />";
                }
                if(!empty($product['discount']))
                {
                    echo '<span class="nf">Скидка:</span> '.$product['discount']." %<br />";
                }
                echo '<span class="nf">Телефон:</span> '.$product['phone'];
                echo '</td>';
                echo '<td style="width:350px">';
                echo '<span class="nf">Товар создан:</span> ('.$dtClass -> dtFromDB($product['dtCreate']).')';
                echo '<br /><span class="nf">Пользователем:</span> '.$userCreate;
                if ($userUpdate) 
                {
                    echo '<br /><span class="nf">Товар редактирован:</span> ('.$dtClass -> dtFromDB($product['dtUpdate']).')';
                    echo '<br /><span class="nf">Пользователем:</span> '.$userUpdate;
                }
                echo '</td>';
                echo'</tr>';
                 
                
            }
            if ($i==9998) echo 'Это не все товары.<br /> Ограничено на показ 9999 шт.';
        }
}

?>
 
