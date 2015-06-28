 
 <?php 
       
 echo '<div class="f-l">';
    if(!isset($_SESSION['active'])) 
    {
         echo " <div class='f-l prevuser' id='prevuser'></div>
               <div class='f-l nextuser active' id='nextuser' ></div>";
        echo "<div class='f-l'><input id='user' class='user-block-text' name='user' type='text' value='Имя(1)'/></div>";
    }
    else 
    {
        if($_SESSION['active']==0)
        {
            echo " <div class='f-l prevuser' id='prevuser'></div>
               <div class='f-l nextuser active' id='nextuser'></div>";
        }
        else
        if($_SESSION['active']==19)
        {
            echo " <div class='f-l prevuser  active' id='prevuser'></div>
               <div class='f-l nextuser ' id='nextuser'></div>";
        }
        else
        {
            echo " <div class='f-l prevuser  active' id='prevuser'></div>
            <div class='f-l nextuser active' id='nextuser'></div>";
        }
       echo "<div class='f-l'><input id='user' name='user' class='user-block-text' type='text' value='".$arrayname[$_SESSION['active']]."'/></div>";
   
    }
$summ=0;
$total=0;
$koltovar=0;
if(isset($_SESSION['active']) AND !empty($_SESSION['arrayorder'][$_SESSION['active']]))
{
        for($i=0;$i<20;$i++)
        {
            foreach($_SESSION['arrayorder'][$i] as $index=>$val)
            {
                //достанем цену по id
                $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $arr=$db->getArray();
                    $total+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                    //$total+=$val['k']*$db->getValue();
                }
            }
        }
        
        foreach($_SESSION['arrayorder'][$_SESSION['active']] as $index=>$val)
        {
            //достанем цену по id
            $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
            $db->query($sql);
            if($db->getCount()>0)
            {
                $arr=$db->getArray();
                $summ+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                //$summ+=$val['k']*$db->getValue();
            }
            $koltovar+=$val['k'];
        }
        echo "<div class='f-l zakazkol user-info-block-text margin-left-ots'>Товаров: <span class='b'>".$koltovar."</span></div>";
        echo "</div>";
        echo '<div class="f-r">';
        echo "<div class='oformlenie-link'><a href='/cart'>оформить заказ</a></div>";
        echo "<div class='f-l user-info-block-text zakazsum'>На сумму: <span class='b'>".number_format($summ,2, '.', '')." грн</span></div>
              <div class='f-l user-info-block-text margin-left-ots zakaztotal'>Сумма заказа: <span class='b'>".number_format($total,2, '.', '')." грн</span></div></div>";
}
else
{
     
     for($i=0;$i<20;$i++)
        {
            foreach($_SESSION['arrayorder'][$i] as $index=>$val)
            {
                //достанем цену по id
                $sql="SELECT * FROM goods WHERE id='".$val['id']."'";
                $db->query($sql);
                if($db->getCount()>0)
                {
                    $arr=$db->getArray();
                    $total+=$prd->getuserpriceformat($arr[0]['id'],$arr[0]['user_ed_iz'],$val['k']);
                    //$total+=$val['k']*$db->getValue();
                }
            }
        }
        echo '<div class="f-l user-info-block-text zakazkol margin-left-ots">Товаров: <span class="b">0</span></div></div>';
        echo "<div class='f-r'>";
        echo "<div class='oformlenie-link'><a class='oformlenie_link_disabled' href='/cart'>оформить заказ</a></div>";
    echo '<div class="f-l user-info-block-text zakazsum">На сумму: <span class="b">0 грн</span></div>
    <div class="f-l user-info-block-text margin-left-ots zakaztotal">Сумма заказа: <span class="b">'.number_format($total,2, '.', '').' грн</span></div></div>';
}