<?
$action = htmlspecialchars(strip_tags($_POST['action']));
if($action == 'edit')
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once $root."/lib/include.php";
    $db = db :: getInstance(); 
    $form = new ad();
    
    $id = (int)(strip_tags($_POST['id']));
    $show = (int)strip_tags($_POST['show']);
    $dtNow = $dtClass -> dtInDB(); 
    $price = (float)strip_tags($_POST['price']);
    $price = number_format($price,2,'.','');
    $presence = (int)(strip_tags($_POST['presence']));
    $quickChangeDays = ($presence == 3) ? htmlspecialchars(strip_tags($_POST['quickChangeDays'])) : 0;
    $db -> query("UPDATE dial SET 
        show = '".$show."',
        idUserUpdate = '".$_SESSION['userID']."', 
        dtUpdate = '".$dtNow."',
        price = '".$price."',
        presence = '".$presence."',
        orderDay = '".$quickChangeDays."'
    WHERE id = ".$id);
                
    echo $DLL -> DOLLARin('G',$price,true);
    echo '~~~';
    echo $DLL -> DOLLARin('E',$price,true);
    echo '~~~';
    echo $DLL -> getCreateUpdate(0,0,$_SESSION['userID'],$dtNow);
}
?>