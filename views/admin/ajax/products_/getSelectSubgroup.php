<?php
if(!empty($_POST))
{
	$root = $_SERVER['DOCUMENT_ROOT'];
	require_once $root."/lib/include.php";
	$db = db :: getInstance(); 
	$form = new ad(); 

    $db -> query("SELECT * FROM sSubgroups WHERE idGroup = {$_POST['idGroup']} ORDER BY name ASC");
    $count = $db -> getCount();
    if ($count > 0) 
    {
        $select[0] = 'Выберите подгруппу';
        $subgroups = $db -> getArray();
        foreach ($subgroups as $subgroup)
        {
            $select[$subgroup['id']] = $subgroup['name'];
        }
    echo $form -> select('sSubgroups','sSubgroups','','width:350px;float:left;',$select);
    } 
}
?>
 
