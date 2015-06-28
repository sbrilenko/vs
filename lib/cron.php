<?//
//require_once 'config.php';
//require_once 'class.invis.db.php';
//$db = db::getInstance();
//$start_day = 9;
//$workhour  = 11;
//$time = (int)date("H");
//if($time == $start_day)
//{
//    $weekDay = date('w');
//    if ($weekDay == 0 or $weekDay == 6)
//    {
//        $db -> query("SELECT number FROM counter WHERE name = 'order_from_weekend'");
//        $ordersFromWeekend = $db->getValue();
//        $db -> query("SELECT number FROM counter WHERE name = 'order_to_weekend'");
//        $ordersToWeekend = $db->getValue();
//
//        $ordersRand = rand($ordersFromWeekend, $ordersToWeekend);
//    }
//    else
//    {
//        $sql = "SELECT number FROM counter WHERE name = 'order_from'";
//        $db -> query($sql);
//        $ordersFrom = $db->getValue();
//        $db -> query("SELECT number FROM counter WHERE name = 'order_to'");
//        $ordersTo = $db->getValue();
//        $orderRand = rand($ordersFrom, $ordersTo);
//    }
//    $db ->query("UPDATE counter SET number = '{$orderRand}' WHERE name = 'order_rand'");
//    $orderFactorPlus = rand(1, 2);
//    $db -> query("UPDATE counter SET number = {$orderFactorPlus} WHERE name = 'order_today'");
//}
//else
//{
//    if($time ==($start_day + $workhour))
//    {
//        $sql = "SELECT number FROM counter WHERE name = 'order_rand'";
//        $db->query($sql);
//        $orderToday = $db->getValue();
//        $db -> query("UPDATE counter SET number = {$orderToday} WHERE name = 'order_today'");
//    }
//    else
//    {
//        $sql = "SELECT number FROM counter WHERE name = 'order_rand'";
//        $db->query($sql);
//        $orderRand=($db->getCount()>0)?$db->getValue():0;
//        $sql = "SELECT number FROM counter WHERE name = 'order_today'";
//        $db->query($sql);
//        $orderToday =($db->getCount()>0)?$db->getValue():0;
//        $orderFactorPlus = ($orderRand - $orderToday)/($workhour - ($time - $start_day));
//        $orderFactorPlus = rand(0, $orderFactorPlus) + 1;
//        $orderToday += $orderFactorPlus;
//        $db -> query("UPDATE counter SET number = {$orderToday} WHERE name = 'order_today'");
//    }
//}
//
//?>