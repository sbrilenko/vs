<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();
$search = new Search ('products','tags','popular','dtCreate DESC',9999,$and);

echo '<fieldset>';
echo '<a href="/admin/dialEditor/add">+ ДОБАВИТЬ НАБОР</a>';

$db -> query("SELECT * FROM dial ORDER BY dtCreate DESC LIMIT 9999");
$count = $db -> getCount();
$products = $db -> getArray();
$ifSearch = null;
if ($count > 0) 
{
    echo ($ifSearch) ? $ifSearch : '';
    
    foreach($products as $i => $product)
    {
        
        echo '<table width="100%" id="id~'.$product['id'].'" style="margin-bottom:4px">';
            echo '<tr>';
                echo '<td width="50px" align="center" rowspan="10">';
                    echo ($i + 1);
                echo '</td>';
                
                echo '<td width="160" rowspan="10" style="text-align:center;vertical-align:middle">';
                $sql_select_photo="SELECT * FROM dialphotos WHERE temp=1 AND id_dial=".$product['id']." ORDER BY dtcreate DESC LIMIT 1";
                $db->query($sql_select_photo);
                if($db->getCount()>0)
                {
                    $arr_photo=$db->getArray();
                    echo "<img style='max-height:124px;max-width:160px' src='/img/dial/1000/".$arr_photo[0]['md5_mictotime']."_".$arr_photo[0]['id'].".jpg' />";
                }
                echo '</td>';
                
                echo '<td colspan="2">';
                    echo '&emsp;<a href="/admin/dialEditor/edit/'.$product['id'].'"><img src="/img/admin/e.png" title="Редактировать"/></a>';
                    echo '&emsp;<a class="del" href="/admin/dialEditor/delete/'.$product['id'].'"><img src="/img/admin/d.png" title="Удалить"/></a>';
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
                    echo $product['name'];
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Цена:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    echo (!empty($product['price']) AND $product['price']!=0) ? $product['price']." грн.":"Цена не указана";
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Наличие:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    echo ($product['presence']==1)?"Есть в наличии":"Нет в наличии";
                echo "</b></td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Отображение на сайте:','','text-align:left');
                echo "</td>";
                    
                echo "<td  colspan='2'><b>";
                        echo ($product['vis']==1)? "отображается на сайте":"не отображается на сайте";
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
            
            
            
            if ($product['attributes'])
            {
            echo "<tr>";
                echo "<td>";
                    echo $form -> titleandhelp('Аксессуары:','','text-align:left');
                echo "</td>";
                    
                echo "<td colspan='2'><b>";
                    echo $DLL -> getProducts($product['attributes']);
                echo "</b></td>";
            echo "</tr>";
            }
            */
        echo '</table>';      
    }
    
    if ($i==9998) echo 'Это не все товары.<br /> Ограничено на показ 9999 шт.';
}else echo '<br />Ничего не найдено!<br />';
echo '</fieldset>';

//<input class="colors" type="hidden" value="000000"/>
?>
 
