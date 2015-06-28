<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
if ($_POST['sFirms'] and $_POST['sFirms'] != 'null') $_SESSION['Firm'] = $_POST['sFirms'];
if ($_POST['sGroups'] and $_POST['sGroups'] != 'null') $_SESSION['Group'] = $_POST['sGroups'];
if ($_POST['category'] and $_POST['category'] != 'null') $_SESSION['category'] = $_POST['category'];
if ($_POST['sSubgroups'] and $_POST['sSubgroups'] != 'null') $_SESSION['Subgroup'] = $_POST['sSubgroups'];

if ($_POST['reset']) 
{
    unset($_SESSION['Firm']);
    unset($_SESSION['Group']);
    unset($_SESSION['category']);
    unset($_SESSION['Subgroup']);
}

$and = '';
if ($_SESSION['Firm']) $and .= ' and idFirm = '.$_SESSION['Firm'];
if ($_SESSION['Group']) $and .= ' and idGroup = '.$_SESSION['Group'];
if ($_SESSION['category']) $and .= ' and category = '.$_SESSION['category'];
if ($_SESSION['Subgroup']) $and .= ' and idSubgroup = '.$_SESSION['Subgroup'];
$db = db :: getInstance(); 
$form = new ad();
$search = new Search ('products','tags','popular','dtCreate DESC',9999,$and);

echo '<fieldset>';
echo '<a href="/admin/productEditor/add">+ ДОБАВИТЬ ТОВАР</a>';
    $db -> query("SELECT * FROM products WHERE 1 AND archive=0 $and ORDER BY dtCreate DESC LIMIT 9999");
    $count = $db -> getCount();
    $products = $db -> getArray();
    $ifSearch = null;


if ($count > 0) 
{
    echo ($ifSearch) ? $ifSearch : '';
    
    foreach($products as $i => $product)
    {
        
        echo '<table width="100%" style="margin-bottom:4px">';
            echo '<tr>';
                echo '<td width="50px" align="center" rowspan="10">';
                    echo ($i + 1);
                echo '</td>';
                
                echo '<td width="160" rowspan="10" style="text-align:center;vertical-align:middle">';
                $sql_select_photo="SELECT * FROM productsphotos WHERE temp=1 AND id_product=".$product['id']." ORDER BY dtcreate DESC LIMIT 1";
                $db->query($sql_select_photo);
                if($db->getCount()>0)
                {
                    $arr_photo=$db->getArray();
                    echo "<img style='width:115px;height:160px;' src='/img/products/fsize1/".$arr_photo[0]['md5_mictotime']."_".$arr_photo[0]['id'].".png'/>";
                }
                echo '</td>';
                
                echo '<td colspan="2">';
                    echo '&emsp;<a href="/admin/productEditor/edit/'.$product['id'].'"><img src="/img/admin/e.png" title="Редактировать"/></a>';
                    echo '&emsp;<a class="delp" href="/admin/productEditor/delete/'.$product['id'].'"><img src="/img/admin/d.png" title="Удалить"/></a>';
                echo '</td>';
                
                echo '<td rowspan="2"  width="200">';
                    echo '<span id="updateBlock'.$product['id'].'">'.$DLL -> getCreateUpdate($product['idUserCreate'],$product['dtCreate'],$product['idUserUpdate'],$product['dtUpdate']).'</span>';
                echo '</td>'; 
            echo '</tr>';
            
            echo "<tr>";
                echo "<td width='120' >";
                    echo $form -> titleandhelp('Название:','','text-align:left');
                echo "</td>";
                    
                echo "<td><b>";
                    echo $product['nameSearch'];
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Цена:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                   echo ($product['price']!=0)?$product['price']." грн.":"Цена не указана";
                   echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Наличие:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    $days = ($product['orderDay']) ? $product['orderDay'] : $defaulDeliveryDay;
                    echo ($product['presence']==1)?"Есть в наличии":"Нет в наличии";
                    
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Отображение на сайте:','','text-align:left');
                echo "</td>";
                    
                echo "<td  colspan='2'><b>";
                    echo ($product['show']==1) ? "Отображается на сайте" : "Не отображается на сайте";
                    echo "</b></td>";
            echo "</tr>";
            
            /*
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Фирма:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                     echo $DLL -> getFirm($product['idFirm']);
                echo "</b></td>";
            echo "</tr>";
                                
            echo "<tr>";
                echo "<td>";
                     echo $form -> titleandhelp('Раздел:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                     echo $DLL -> getSection($product['idSection']);
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Группа:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    echo $DLL -> getGroup($product['idGroup']);
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Подгруппа:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    echo $DLL -> getSubgroup($product['idSubgroup']);
                echo "</b></td>";
            echo "</tr>";
            
            
            */
            
        echo '</table>';      
    }
    
    if ($i==9998) echo 'Это не все товары.<br /> Ограничено на показ 9999 шт.';
}else echo '<br />Ничего не найдено!<br />';
echo '</fieldset>';

//<input class="colors" type="hidden" value="000000"/>
?>
 
