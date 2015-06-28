<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$form = new ad();

if ($_POST['dateFrom']) $_SESSION['dateFromFormat'] = $_POST['dateFrom'];
if ($_POST['dateTo']) $_SESSION['dateToFormat'] = $_POST['dateTo'];

if (!$_SESSION['dateFromFormat'] or $_POST['reset']) $_SESSION['dateFromFormat'] = $dtClass -> dFormat();
if (!$_SESSION['dateToFormat'] or $_POST['reset']) $_SESSION['dateToFormat'] = $dtClass -> dFormat();

$dateFrom = $dtClass -> dtInDB($_SESSION['dateFromFormat'].' 00:00:00');
$dateTo = $dtClass -> dtInDB($_SESSION['dateToFormat'].' 23:59:59');
    
        echo '<fieldset>';
        $db -> query("SELECT * FROM `orders` WHERE dtOrder >= '".$dateFrom."' and dtOrder <= '".$dateTo."' or new = 1 ORDER BY new DESC, dtOrder DESC LIMIT 9999");
        if ($db -> getCount() > 0) 
        {
            $orders = $db -> getArray();
            echo '<ul>';
            foreach($orders as $i => $order)
            {
                $client = $DLL -> getClient($order['idClient']);
                $user= $DLL -> getUser($order['idUser']);
                
                $order_=unserialize($order['ordero']);
                $total=0;
                $howMany=0;
                for($i=0;$i<20;$i++)
                {
                    foreach($order_[$i] as $index=>$val)
                    {
                        //достанем цену по id
                        $sql="SELECT discount FROM orders WHERE id=".$order['id'];
                        $db->query($sql);
                        if($db->getCount()>0)
                        {
                            $total+=$val['k']*$val['price']-$val['k']*$val['price']*$db->getValue()/100;
                            $howMany+=$val['k'];
                        }
                    }
                }
                /*$db -> query("SELECT `price`,`howMany` FROM ordersproducts WHERE idOrder = {$order['id']}");
                
                if ($db -> getCount() > 0) 
                {
                    $prices = $db -> getArray();
                    foreach ($prices as $price)
                    {
                        $howMany += $price['howMany'];
                        $total += $price['price'] * $price['howMany'];
                    }
                }
                else 
                {
                    $howMany = 'unknown';
                    $total = '?';
                }*/
                
                
                echo '<a class="order '.(($order['new'])?'neworder':'').'"  href="/admin/indexViewer/veiw/'.$order['id'].'">';
                echo "<table style='width:100%;border:dotted gray 1px;margin-bottom:4px'>";
                    echo "<tr>";
                        
                        echo "<td rowspan='4' align='center' >";
                            echo ($i+1);
                        echo "</td>";
                        
                        echo "<td width='130' style='border-left:dotted gray 1px;padding-left:10px'>";
                            echo '<span style="font-weight:normal">Заказ был создан:</span>';
                        echo "</td>";
                        
                        echo "<td width='310'><b>";
                            echo $dtClass -> dtFromDB($order['dtOrder']);
                        echo "</b></td>";
                        
                        echo "<td width='130' style='border-left:dotted gray 1px;padding-left:10px'  >";
                            if (!$order['new']) echo '<span style="font-weight:normal">Заказ обработан:</span>';
                        echo "</td>";
                        
                        echo "<td width='310'><b>";
                            if (!$order['new']) echo $dtClass -> dtFromDB($order['dtOpen']);
                        echo "</b></td>";
                        
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td style='padding-left:10px'>";
                            echo '<span style="font-weight:normal">ФИО заказчика:</span>';
                        echo "</td>";
                        
                        echo "<td><b>";
                            echo ($client) ? $client : 'unknown';
                        echo "</b></td>";
                        
                        echo "<td style='border-left:dotted gray 1px;padding-left:10px'>";
                            if (!$order['new']) echo '<span style="font-weight:normal">Пользователем:</span>';
                        echo "</td>";
                        
                        echo "<td><b>";
                            if (!$order['new']) echo ($user) ? $user : 'unknown';
                        echo "</b></td>";
                    echo "</tr>";
                    
                    echo "<tr>";
                        echo "<td style='padding-left:10px'>";
                            echo '<span style="font-weight:normal">Кол-во товаров:</span>';
                        echo "</td>";
                        
                        echo "<td><b>";
                            echo $howMany;
                        echo "</b></td>";
                        
                        echo "<td style='border-left:dotted gray 1px;padding-left:10px'></td><td></td>";
                        
                    echo "</tr>";
                    
                    echo "<tr>";
                        echo "<td style='padding-left:10px'>";
                            echo '<span style="font-weight:normal">Сумма:</span>';
                        echo "</td>";
                        
                        echo "<td><b>";
                            echo ($total) ? number_format($total,2,'.','').' грн.' : 'unknown';
                        echo "</b></td>";
                        
                        echo "<td style='border-left:dotted gray 1px;padding-left:10px'></td><td></td>";
                    echo "</tr>";
                    
                echo "</table>";
               /* 
               echo '<div style="float:left;padding:6px;text-align:center;width:38px">'.($i+1).'</div>';
                echo '<li style="text-align:left;padding-left:50px">';
                    
                    echo '<span style="font-weight:normal">Заказ был сделан:</span> '.$dtClass -> dtFromDB($order['dtOrder']);
                    echo '<br /><span style="font-weight:normal">ФИО клиента:</span> '.(($client)?$client:'unknown');
                    if (!$order['new'])
                    {
                        echo '<br /><span style="font-weight:normal">Заказ был прочитан:</span> '.$dtClass -> dtFromDB($order['dtOpen']);
                        echo '<br /><span style="font-weight:normal">Пользователь:</span> '.(($user)?$user:'unknown');
                    }
                echo '</li>';*/
                echo '</a>';  
            }
            echo '</ul>';
            if ($i==9998) echo 'Это не все заказы.<br /> Ограничено на показ 9999 шт.';
        }
        else 
        if ($_SESSION['dateFromFormat'] == $_SESSION['dateToFormat']) 
            {
                if ($_SESSION['dateFromFormat'] == date('d.m.Y')) echo 'Сегодня заказов не было';
                else echo ($dtClass -> dtFromDB($dtClass -> dtInDB($_SESSION['dateFromFormat'].' 00:00:00'), false)).' заказов не было.';
            }
            else echo 'В период с '.$_SESSION['dateFromFormat'].' по '.$dtClass -> dtFromDB($dateTo, false).' заказов не было.';
        echo '</fieldset>';
 
?>
 
