<?
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
$search = new Search ('products','tags','popular');

$query = $search -> clearQueryForSearch($_POST['query']);
if ((strlen($query) >= 3) and ($query)) 
{
    $ids = explode('~',$_POST['idsProducts']);
    $words = explode (' ',$query);
    echo '<tr>';
    echo '<td align="center" colspan="4">'; 
    if (!($finds = $search -> searchResultLike($words)))
    {
        $r .= 'По резултату запроса <b style>'.$query.'</b> ничего не найдено.<br />';
        if ($finds = $search -> searchResultMatch($words,'*')) $r .= 'Возможно Вас заинтересует:<br />';
        else 
            {
                if ($finds = $search -> searchResultMatch($search -> partitionWords($query,2),'*')) $r .= 'Возможно Вы имели в виду:<br />';
                else 
                if ($finds = $search -> searchResultMatch($search -> partitionWords(substr($query,1),2),'*')) $r .= 'Возможно Вы имели в виду:<br />';
            }
    }
    echo 'Найдено: '.sizeof($finds);
    echo ($r) ? '<br />'.$r : '';
    echo '</td>';
    echo '</tr>';
    
    if ($finds)
    {
        echo '<tr>';
            echo '<td align="center" width="50">№</td>';
            echo '<td align="center" width="100">Фото</td>';
            echo '<td align="center">Краткое инфо</td>';
            echo '<td align="center" width="100">Выбрать</td>';
        echo '</tr>';
        foreach ($finds as $n => $find)
        {
            echo '<tr>';
                echo '<td class="number" align="center" style="vertical-align: middle;">';
                    echo ($n + 1);
                echo '</td>';
                echo '<td style="vertical-align: middle;">';
                    echo $DLL -> getAva($find['id']);
                echo '</td>';
                echo '<td>';
                    echo $DLL -> getShortProductInfo($find['id']);
                echo '</td>';
                echo '<td  style="vertical-align: middle;" align="center">';
                    if (in_array($find['id'],$ids)) 
                        echo 'Уже выбран';
                    else
                        echo '<input class="selectProduct" type="checkbox" value='.$find['id'].'>';
                echo '</td>';
            echo '</tr>';
        }
    }
}
?>
 
