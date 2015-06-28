<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance();  
$arrvalselected=array();
foreach($_POST as $index=>$val)
{
    $expl=explode('sostav',$index);
    if($expl[1])
    {
        array_push($arrvalselected,$val);
    }
}
if(!empty($_POST['nametosearch']))
{
$words='';
//preg_match_all("/\w+/u",$_POST['nametosearch'],$w_arr);
$tmp = strip_tags(htmlspecialchars(substr($_POST['nametosearch'], 0, 64)));
$tmp = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $tmp);
$tmp = preg_replace("/ +/", " ", $tmp);
$tmp=explode(' ',$tmp);
foreach($tmp as $in=>$val)
{
    if($val!='')
    {
        if(!empty($words))
        {
            $words.=" or nameSearch like '%".$val."%'";
        }
        else
        {
            $words.=" nameSearch like '%".$val."%'";
        }
            
        
    }
}
    if(!empty($words))
    {
    $sql="SELECT * FROM products WHERE ".$words;
    $db->query($sql);
    if($db->getCount()>0)
    {
        $arr=$db->getArray();
        echo "<table style='width:100%;'>";
        echo "<tr><td style='text-align: center;background-color: #BBB9B9;font-weight: bold;'>Результаты поиска:</td></tr>";
        foreach($arr as $arr_index=>$arr_val)
        {
            echo "<tr>";
            echo "<td >";
            echo "<table class='searchtable'>";
            echo "<tr>";
            echo "<td rowspan='6'>";
            $sql_ph="SELECT md5_mictotime,id FROM productsphotos WHERE temp=1 AND id_product=".$arr_val['id']." ORDER BY dtcreate LIMIT 1";
            $db->query($sql_ph);
            if($db->getCount()>0)
            {
                $arr_ph=$db->getArray();
                echo "<img style='width: 185px;height: 102px;' src='/img/products/1000/".$arr_ph[0]['md5_mictotime']."_".$arr_ph[0]['id'].".jpg'/>";
            }
            else
            {
                echo "<div style='width: 127px;height: 102px;'></div>";
            }
            
            echo "</td>";
            echo "</tr>";
            echo "<tr class='border-top'>";
            echo "<td colspan='2'>";
            echo "<b>Название</b>: ".$arr_val['nameSearch'];
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'>";
            $sql_get_firm="SELECT name FROM sfirms WHERE id=".$arr_val['idFirm'];
            $db->query($sql_get_firm);
            if($db->getCount()>0)
            {
                echo "<div><b>Производитель</b>: ".$db->getValue()."</div>";
            }
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'>";
            $sql_get_firm="SELECT name FROM category WHERE id=".$arr_val['category'];
            $db->query($sql_get_firm);
            if($db->getCount()>0)
            {
                echo "<div><b>Категория</b>: ".$db->getValue()."</div>";
            }
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'>";
            $sql_get_firm="SELECT name FROM sgroups WHERE id=".$arr_val['idGroup'];
            $db->query($sql_get_firm);
            if($db->getCount()>0)
            {
                echo "<div><b>Продукция</b>: ".$db->getValue()."</div>";
            }
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'>";
            $sql_get_firm="SELECT name FROM ssubgroups WHERE id=".$arr_val['idSubgroup'];
            $db->query($sql_get_firm);
            if($db->getCount()>0)
            {
                echo "<div><b>Вид родукции</b>: ".$db->getValue()."</div>";
            }
            echo "</td>";
            echo "</tr>";
            echo "</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td colspan='3'>";
            echo "<input type='hidden' name='idname' value='".$arr_val['id']."' />";
            echo "</td>";
            echo "</tr>";
            
            echo "<tr class='vidvis'>";
            /*echo "<td colspan='3'>";
            echo "<span style='width:25px;'>Нарезан?</span><select name='narezannot' style='margin-left:10px;width:150px;'><option value='0'>Не нарезан</option><option value='1'>Нарезан</option></select>";
            echo "<span style='width:25px;'>Вес</span><input name='vesnarez' style='margin-left:10px;width:150px;'/>";
            $db->query("SELECT * FROM izm");
            if($db->getCount()>0)
            {
                $arr_=$db->getArray();
                echo "<select style='width:50px;'>";
                foreach($arr_ as $arr_index_=>$arr_val_)
                {
                    echo "<option value='".($arr_index_+1)."'>".$arr_val_['name']."</option>  ";
                }
                echo "</select>";
            }
            echo "</td>";*/
            echo "</tr>";
            
            echo "<tr>";
            echo "<td colspan='3' style='text-align:center;'>";
            if(in_array($arr_val['id'],$arrvalselected))
            {
                echo "<div class='alreadyin' style='color:red;'>Уже в составе</div>";
            }
            else
            {
                echo "<input type='button' class='addtosostav' value='Добавить в состав' data-id='".$arr_val['id']."' />";
            }
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        }
        echo "</table>";
    }
    else
    {
        echo "<table style='width:100%;'>";
        echo "<tr><td style='text-align: center;background-color: #BBB9B9;font-weight: bold;'>Результаты поиска:</td></tr>";
        echo "<tr><td style='text-align: center;'>Поиск не дал результатов</td></tr>";
        echo "</table>";
    }
    }
    else
    {
        echo "<table style='width:100%;'>";
        echo "<tr><td style='text-align: center;background-color: #BBB9B9;font-weight: bold;'>Результаты поиска:</td></tr>";
        echo "<tr><td style='text-align: center;'>Поиск не дал результатов</td></tr>";
        echo "</table>";
    }
}

?>
 
