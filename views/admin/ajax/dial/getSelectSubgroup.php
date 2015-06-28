<?php
if(!empty($_POST))
{
	$root = $_SERVER['DOCUMENT_ROOT'];
	require_once $root."/lib/include.php";
	$db = db :: getInstance(); 
	$form = new ad(); 

    $db -> query("SELECT * FROM ssubgroups WHERE idGroup = {$_POST['idGroup']} ORDER BY name ASC");
    $count = $db -> getCount();
    $select['null'] = 'Выберите подгруппу';
    if ($count > 0) 
    {
        
        $subgroups = $db -> getArray();
        foreach ($subgroups as $subgroup)
        {
            $select[$subgroup['id']] = $subgroup['name'];
        }
    //echo $form -> select('sSubgroups','sSubgroups','','width:350px;float:left;',$select);
    } 
    //else echo '<select id="sGroups" name="sGroups" style="width:350px"><option value="0">В выбраной группе подгрупп не найденно</option></select>'; 
    echo $form -> select('sSubgroups','sSubgroups','','width:350px;float:left;',$select);
}
?>
 
