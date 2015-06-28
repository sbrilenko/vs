<?php
require_once 'class.invis.db.php';
require_once 'class.product.php';
$db=db :: getInstance();
$catlink=$link;
$prlink=$link;
$vprlink=$link;
$firmlink=$link;
$sql_cat="";
$sql_pr="";
$sql_vpr="";
$sql_firm="";
$sql_output="";
if($_POST['cat']!=0)
{
    $prlink.="/cat/".$_POST['cat'];
    $vprlink.="/cat/".$_POST['cat'];
    $firmlink.="/cat/".$_POST['cat'];
    $sql_pr.=" AND idcategory=".$_POST['cat'];
    $sql_vpr.=" AND idcategory=".$_POST['cat'];
    $sql_firm.=" AND idcategory=".$_POST['cat'];
    $sql_output.=" AND idcategory=".$_POST['cat'];
}
if($_POST['pr']!=0)
{
    $catlink.="/pr/".$_POST['pr'];
    $vprlink.="/pr/".$_POST['pr'];
    $firmlink.="/pr/".$_POST['pr'];
    $sql_cat.=" AND idtypegoods=".$_POST['pr'];
    $sql_vpr.=" AND idtypegoods=".$_POST['pr'];
    $sql_firm.=" AND idtypegoods=".$_POST['pr'];
    $sql_output.=" AND idtypegoods=".$_POST['pr'];
}
if($_POST['vpr']!=0)
{
    $catlink.="/vpr/".$_POST['vpr'];
    $prlink.="/vpr/".$_POST['vpr'];
    $firmlink.="/vpr/".$_POST['vpr'];
    $sql_cat.=" AND idviewgoods=".$_POST['vpr'];
    $sql_pr.=" AND idviewgoods=".$_POST['vpr'];
    $sql_firm.=" AND idviewgoods=".$_POST['vpr'];
    $sql_output.=" AND idviewgoods=".$_POST['vpr'];
}
if($_POST['firm']!=0)
{
    $catlink.="/firm/".$_POST['firm'];
    $prlink.="/firm/".$_POST['firm'];
    $vprlink.="/firm/".$_POST['firm'];
    $sql_cat.=" AND idmanufacturers=".$_POST['firm'];
    $sql_pr.=" AND idmanufacturers=".$_POST['firm'];
    $sql_vpr.=" AND idmanufacturers=".$_POST['firm'];
    $sql_output.=" AND idmanufacturers=".$_POST['firm'];
}
$sql="SELECT * FROM goods WHERE deleted=0 AND setofgoogs=0 AND displaylisting=1 ".$sql_output. " LIMIT ".((($_POST['c']+1)*20)-20)." , 20";
$db->query($sql);
if($db->getCount()>0)
{
    $arrpro=$db->getArray();
    foreach($arrpro as $arrproindex=>$arrproval)
    {
        if($firstout!=0 AND $firstout==$arrproindex) break;
        if(($arrproindex+1)%4==0)
        {
            echo "<li class='no-margin-r'>";
        }
        else  echo "<li>";
        if(!empty($arrproval['photo']))
        {
            $photo=str_replace('.','',$arrproval['photo']);
            $photo=str_replace('-','_',$photo);
            $new=($arrproval['new']==1)?"<div class='new_sales'></div>":"";
            $hot=($arrproval['hot_products']==1)?"<div class='hit_sales'></div>":"";
            if(file_exists($root."/img/products/fsize1/".$photo."_ass.jpg"))
            {
                echo "<div>".$new." ".$hot."<a href='/product/more/id/".$arrproval['id']."/from/assortiment'><img src='../img/products/fsize1/".$photo."_ass.jpg' /></a></div>";
                echo "<div class='clear_both'></div>";
            }
            else
            {
                echo "<div>".$new." ".$hot."<a href='/product/more/id/".$arrproval['id']."/from/assortiment'><img src='../img/products/fsize1/good-empty_ass.png' /></a></div>";
                echo "<div class='clear_both'></div>";
            }
        }
        else
        {
            echo "<div>".$new." ".$hot."<a href='/product/more/id/".$arrproval['id']."/from/assortiment'><img src='../img/products/fsize1/good-empty_ass.png' /></a></div>";
            echo "<div class='clear_both'></div>";
        }
        //echo $returnphoto;
        // echo  $prd->kkzphoto($arrproval['photo'],$arrproval['id']);

        echo "<a class='name' href='/product/more/id/".$arrproval['id']."/from/assortiment'>".$arrproval['name']."</a>";
        echo "<div class='after-name'></div>";
        echo $prd->getmanufname($arrproval['idmanufacturers']);
        $base_ed=$prd->getpricefor($arrproval['id_base_ed_iz']);
        if(!empty($base_ed))
        {
            echo "<br />";
            echo '<div class="other">Цена за '. $base_ed.': '. $prd->priceofbaseediz($arrproval['id']).' грн</div>';
        }
        if(!empty($arrproval['weight']))
        {
            echo "<div class='other f-l'>Вес : ".$arrproval['weight']."</div>";
        }

        echo "</li>";
    }
}