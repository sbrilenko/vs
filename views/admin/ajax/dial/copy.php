<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();
 
if(!empty($_POST))
{
    $id = (int)(strip_tags($_POST['id']));
    $action = htmlspecialchars(strip_tags($_POST['action']));
    $dtNow = $dtClass -> dtInDB();
    
    $db -> query("SELECT * FROM dial WHERE id = {$id}");
    $p = $db -> getRow();
    
    
    
    if ($action == 'copy')
    {
        $db -> query("INSERT INTO dial 
            (`show`,article,name,nameSearch,idUserCreate,dtCreate,idUserUpdate,dtUpdate,id1C,idSection,idGroup,idSubgroup,idFirm,description,grayText,price,presence,orderDay,newOrSecond,new,bestseller,event,priority,rating,tags,attributes) 
            VALUES 
            ('".$p['show']."', '".$p['article']."', '".$p['name']."', '".$p['nameSearch']."', '".$_SESSION['userID']."', '".$dtNow."', '0', '0000-00-00 00:00:00', '".$p['id1C']."', '".$p['idSection']."', '".$p['idGroup']."', '".$p['idSubgroup']."', '".$p['idFirm']."', '".$p['description']."', '".$p['grayText']."', '".$p['price']."', '".$p['presence']."', '".$p['orderDay']."', '".$p['newOrSecond']."', '".$p['new']."', '".$p['bestseller']."', '".$p['event']."', '".$p['priority']."', '".$p['rating']."', '".$p['tags']."', '".$p['attributes']."')");
        $idNew = $db -> last();
    }
    
    //запись характеристик
    $db -> query("SELECT * FROM characteristics WHERE idProduct = {$id}");
    $cs1 = $db -> getArray();
    if ($cs1)
    {
        foreach ($cs1 as $i => $c1)
        {
            $db -> query("INSERT INTO characteristics 
                (nameShort,descriptionShort,showInShortDescription,idProduct) 
            VALUES 
                ('{$c1['nameShort']}','{$c1['descriptionShort']}','{$c1['showInShortDescription']}',{$idNew})");
        } 
    }
    //запись цветов
    $db -> query("SELECT * FROM productsColors WHERE idProduct = {$id}");
    $cs2 = $db -> getArray();
    if ($cs2)
    {
        foreach ($cs2 as $i => $c2)
        {
            $db -> query("INSERT INTO productsColors 
                (name,article,rgb,idProduct) 
            VALUES 
                ('{$c2['name']}','{$c2['article']}','{$c2['rgb']}',{$idNew})");
        }
    }
    
    echo $idNew;
}
else
{
    print "<span style='color:red;' title='error'>Введите данные</span>";
}
?>
 
