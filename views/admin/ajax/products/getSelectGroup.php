<?php
if(!empty($_POST))
{
	$root = $_SERVER['DOCUMENT_ROOT'];
	require_once $root."/lib/include.php";
	$db = db :: getInstance(); 
	$form = new ad(); 

    $db -> query("SELECT * FROM sgroups WHERE idSection = {$_POST['idSection']} ORDER BY name ASC");
    $count = $db -> getCount();
    $select['null'] = 'Выберите группу';
    if ($count > 0) 
    {
        
        $groups = $db -> getArray();
        foreach ($groups as $group)
        {
            $select[$group['id']] = $group['name'];
        }
    
    } 
    //else echo '<select id="sGroups" name="sGroups" style="width:350px"><option value="0">В выбраном разделе групп не найденно</option></select>';
    echo $form -> select('sGroups','sGroups','','width:350px;float:left;',$select); 
}
?>
 
