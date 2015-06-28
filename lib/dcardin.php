<div class="f-r">
<!--<div class="text-align-right" style="margin-right:35px;"><a href="#" class="disc-link">Дисконтная система</a></div>-->
<?php 
$page->addScript('js/json2.js','js/in.js','js/mask-card.js','js/mask-phone.js');
if(!empty($_SESSION['client']))
{
   //print_r($_SESSION['client']);
   if($_SESSION['client']['iscard']==0)
   {
       if($_SESSION['client']['new']==1)
       {
            $disc_table="<table class='cart-table'><tr><td style='vertical-align: middle;'><div class='cart-table-text'>Добрый день,<br />уважаемый клиент,<br />выбирайте и наслаждайтесь<br />лучшими продуктами</div></td></tr></table>";
       }
       else
       {
        $explode_card="";
        for($i=0;$i<strlen($_SESSION['client']['nc']);$i++)
        {
            if($i!=0 AND $i%4==0)
            {
                $explode_card.=" ";
            }
            $explode_card.=$_SESSION['client']['nc'][$i];
        }
         $disc_table="<table class='cart-table'><tr><td style='height: 44px;'>
         <div style='margin: 13px 21px 0 21px;'><div class='f-l cart-table-text-num-card'>".$explode_card."</div><div class='f-r cart-little-logo'></div></div>
         </td></tr>
         <tr><td style='vertical-align: middle;'><div class='cart-table-text'>".$_SESSION['client']['n']."</div></td></tr>
         </table>";
             
       }
    //по телефону
    echo "<div class='discount-cart f-r'>
    ".$disc_table."
    <div class='divinput'><form method='post' class='kkz-form'><input readonly class='saminput temp_class_den' type='text' name='pactive' value='".$_SESSION['client']['p']."'/></form></div></div>";
    echo "<div class='clear_both'></div><div style='width: 264px;'>";
    if($_SESSION['client']['new']==0)
    {
       echo "<div class='f-l' style='margin-left: 7px;'><a style='text-align:left;' href='/historycart' class='disc-link'>Ваши покупки</a></div>";
    }
    echo "<div class='f-r' style='margin-right: 7px;'><a style='text-align:right;' class='disc-link' href='/out/fromsystem/from".$_SERVER['REQUEST_URI']."'>Выход</a></div></div>";
   
   }
   else
   {
    if($_SESSION['client']['new']==1)
       {
            $disc_table="<table class='cart-table'><tr><td style='vertical-align: middle;'><div class='cart-table-text'>Добрый день,<br />уважаемый клиент,<br />выбирайте и наслаждайтесь<br />лучшими продуктами</div></td></tr></table>";
       }
       else
       {
             $disc_table="<table class='cart-table'><tr><td style='height: 44px;'>
        <div style='margin: 13px 21px 0 21px;'><div class='f-l cart-table-text-num-card'>".$explode_card."</div><div class='f-r cart-little-logo'></div></div>
        </td></tr>
        <tr><td style='vertical-align: middle;'><div class='cart-table-text'>".$_SESSION['client']['n']."</div></td></tr>
    </table>";
       }
    $explode_card="";
    for($i=0;$i<strlen($_SESSION['client']['nc']);$i++)
    {
        if($i!=0 AND $i%4==0)
        {
            $explode_card.=" ";
        }
        $explode_card.=$_SESSION['client']['nc'][$i];
    }
    echo "<div class='discount-cart f-r'>
    ".$disc_table."
    <div class='divinput'><form method='post' class='kkz-form'><input readonly class='saminput temp_class_den' type='text' name='nc' value='".$_SESSION['client']['nc']."'/></form></div></div>";
    echo "<div class='clear_both'></div><div style='width: 264px;'>";
    if($_SESSION['client']['new']==0)
    {
        echo "<div class='f-l' style='margin-left: 7px;'><a style='text-align:left;' href='/historycart' class='disc-link'>Ваши покупки</a></div>";
    }
    echo "<div class='f-r' style='margin-right: 7px;'><a style='text-align:right;' class='disc-link' href='/out/fromsystem/from".$_SERVER['REQUEST_URI']."'>Выход</a></div></div>";
   } 
}
else
{
    echo "<div class='discount-cart f-r'>
        <div class='divinput'>
        <table class='cart-table'><tr><td style='vertical-align: middle;'><div class='cart-big-logo'></div></td></tr></table><form method='post' class='kkz-form'><input class='saminput temp_class_den' name='in' type='text'/></form></div></div>";
    echo "  <div class='clear_both'></div> <div class='after-disc-card-text disc'>Введите <span class='nodotted thiso'>номер телефона</span> или <span class='dotted thist'>номер карты</span></div>";
}
?>
</div>