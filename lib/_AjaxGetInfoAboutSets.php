<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once 'class.invis.db.php';
require_once 'class.product.php';
$db=db :: getInstance();
$db -> query("SELECT * FROM goods WHERE id = '{$_POST['id']}'");
if ($db -> getCount())
{
    $good = $db -> getRow();
?>
<?php if($_POST['vis']!=1) { ?>
<div class="clear_both forRemove"></div>
<div class="addblock">
    <div class="infoAboutSetShadowT"></div>
    <div class="InfoAboutSet">
<?php } ?>

        <div class="InfoAboutSetContent">
            <div class="infoAboutSetClose"><div>Закрыть<div></div></div></div>
            <div class="infoAboutSetPhoto">
                <?php
                $good['photo']=str_replace('.','',$good['photo']);
                $good['photo']=str_replace('-','_',$good['photo']);
                if(file_exists($root."/img/products/fsize1/".$good['photo'].".jpg"))
                    echo '<img src="../img/products/fsize1/'.$good['photo'].'.jpg">';
                else
                    echo "<div style='text-align:center;'><img src='../img/products/fsize1/nabori-empty.png' /></div>";
                ?>
            </div>
            <div class="infoAboutSetTitle"><?=$good['name']?></div>
            <div class="infoAboutSetText">
            <?=$good['description']?>
            </div>
            <?php
            $sql="SELECT * FROM kit WHERE deleted=0 AND idgoods='".$good['id']."'";
            $db->query($sql);
            if ($db->getCount()>0)
            {
                echo '<div class="infoAboutSetTitle2">Состав:</div>';
                echo '<div class="infoAboutSetComposition">';
                $arr_kit=$db->getArray();
                foreach($arr_kit as $arr_kit_index=>$arr_kit_val)
                {
                    $sql="SELECT * FROM goods WHERE deleted=0 AND displaylisting=1 AND id='".$arr_kit_val['idgoodscomposition']."'";
                    $db->query($sql);
                    {
                      $arr=$db->getArray();
                      echo "<a href='/product/more/id/".$arr[0]['id']."/from/set'>".$arr[0]['name']."</a> - ".number_format($arr_kit_val['count'], 2, '.', '')." ".$prd->getpricefor($arr_kit_val['id_ed_iz']);
                      if($arr_kit_index!=count($arr_kit)-1)
                      {
                        echo ", ";
                      }
                    }
                }
                //echo $good['composition'];
                echo '</div>';
            }
            if ($good['weight'])
            {
                echo '<div class="infoAboutSetWeight">';
                echo 'Общий вес: '.$good['weight'];
                echo '</div>';
            }
            $db -> query("SELECT price FROM priceofgoods WHERE deleted = 0 AND idgoods='".$_POST['id']."'");
            if($db -> getCount() > 0)
            {
                echo '<div class="infoAboutSetCoast">Стоимость: <span>'.number_format($db -> getValue(), 2, '.', '').' грн</span></div>';
            }
            ?>
            <div class="clear_both"></div>
        </div>
<?php if($_POST['vis']!=1) { ?>
    </div>
    <div class="clear_both forRemove"></div>
    <div class="infoAboutSetShadowB"></div>
</div>
<?}?>
<?}?>