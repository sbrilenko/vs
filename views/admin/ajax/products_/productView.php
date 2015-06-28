<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();



if ($_POST['reset']) 
{
    $_SESSION['FilterFirm'] = null;
    $_SESSION['FilterGroup'] = null;
    $_SESSION['FilterSubgroup'] = null;
    $_SESSION['FilterName'] = null;
    $filter = '';
}
else 
{
    $_SESSION['FilterFirm'] = ($_POST['sFirms']) ? $_POST['sFirms'] : $_SESSION['FilterFirm'];
    $_SESSION['FilterGroup'] = ($_POST['sGroups']) ? $_POST['sGroups'] : $_SESSION['FilterGroup'];
    $_SESSION['FilterSubgroup'] = ($_POST['sSubgroups']) ? $_POST['sSubgroups'] : $_SESSION['FilterSubgroup'];
    $_SESSION['FilterName'] = ($_POST['name']) ? $_POST['name'] : $_SESSION['FilterName'];
    
    if ($_SESSION['FilterFirm']) {$filter .= ' AND idFirm = '.$_SESSION['FilterFirm'];}
    if ($_SESSION['FilterGroup']) {$filter .= ' AND idGroup = '.$_SESSION['FilterGroup'];}
    if ($_SESSION['FilterSubgroup']) {$filter .= ' AND idSubgroup = '.$_SESSION['FilterSubgroup'];}
    if ($_SESSION['FilterName']) {$filter .= ' AND name like "%'.$_SESSION['FilterName'].'%"';}
}


    
        echo '<fieldset>';
        //print_r($_POST);
        echo '<a href="/admin/productEditor/add">+ ДОБАВИТЬ ТОВАР</a>';
        $db -> query("SELECT * FROM products WHERE 1 ".$filter." ORDER BY dtCreate DESC LIMIT 9999");
        $count = $db -> getCount();
        if ($count > 0) 
        {
            $products = $db -> getArray();
            echo '<ul>';
            foreach($products as $i => $product)
            {
                $firm = $DLL -> getFirm($product['idFirm']);
                $group = $DLL -> getGroup($product['idGroup']);
                $subgroup = $DLL -> getSubgroup($product['idSubgroup']);
                $userCreate = $DLL -> getUser($product['idUserCreate']);
                if ($product['idUserUpdate']) $userUpdate = $DLL -> getUser($product['idUserUpdate']);
                 
                echo '<li style="text-align:left;">';
                    echo '<div style="float:right;width:350px">';
                        echo '<span class="nf">Товар создан:</span> ('.$dtClass -> dtFromDB($product['dtCreate']).')';
                        echo '<br /><span class="nf">Пользователем:</span> '.$userCreate;
                        if ($userUpdate) 
                        {
                            echo '<br /><span class="nf">Товар редактирован:</span> ('.$dtClass -> dtFromDB($product['dtUpdate']).')';
                            echo '<br /><span class="nf">Пользователем:</span> '.$userUpdate;
                        }
                    echo '</div>';
                    echo '<span class="nf">Название:</span> '.$product['name'];
                    echo '&emsp;<a href="/admin/productEditor/edit/'.$product['id'].'"><img src="/img/admin/e.png" title="Редактировать"/></a>';
                    echo '&emsp;<a href="/admin/productEditor/delete/'.$product['id'].'"><img src="/img/admin/d.png" title="Удалить"/></a>';
                    echo '<br /><span class="nf">Наличие:</span> '.(($product['presence'])?'Есть на складе':'Нет');
                    echo '<br /><span class="nf">Производитель:</span> '.$firm;
                    echo '<br /><span class="nf">Группа:</span> '.$group.' &rArr; '.$subgroup;
                echo'</li>';
                 
                
            }
            echo '</ul>';
            if ($i==9998) echo 'Это не все товары.<br /> Ограничено на показ 9999 шт.';
        }
        else
        {
             if ($_SESSION['FilterFirm']) $firm = $DLL -> getFirm($_SESSION['FilterFirm']);
             if ($_SESSION['FilterGroup']) $group = $DLL -> getGroup($_SESSION['FilterGroup']);
             if ($_SESSION['FilterSubgroup']) $subgroup = $DLL -> getSubgroup($_SESSION['FilterSubgroup']);
             $name = $_SESSION['FilterName'];
             echo '<br /><br />Ничего не найдено!<br />';
             if ($firm) $userQuery .= $firm.' &rArr; ';
             if ($group) $userQuery .= $group.' &rArr; ';
             if ($subgroup) $userQuery .= $subgroup.' &rArr; ';
             if ($name) $userQuery .= $name.' &rArr; ';
             $userQuery = ($userQuery) ? 'По запросу '.(substr($userQuery,0,(strlen($userQuery) - 8))) : null;
             echo $userQuery;
        }
        echo '</fieldset>';


?>
 
