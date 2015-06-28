<?php
require_once 'class.invis.db.php';
require_once 'class.date.php';
class DLL {
    
    private $_db;
    private $_dtClass;
    
    function __construct() {
	    $this -> _db =  db :: getInstance();
        $this -> _dtClass = new date();
	}
    
    //функция возвращает 1 значение по $id из таблицы $table
    private function query($id, $whatField, $table)
    { 
        if ($id)
        {    
            $this -> _db -> query("SELECT `".$whatField."` FROM `".$table."` WHERE id = {$id}");
            if ($this -> _db -> getCount() > 0) 
                return $this -> _db -> getValue();
            else return false;
        }
    }
    
    //1 значение по $id из таблицы admin 
    public function getUser($id, $whatField = 'user')
    { 
        return $this -> query($id, $whatField, 'admin');
    }
    
    //1 значение по $id из таблицы client
    public function getClient($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'clients');
    }
    
    //1 значение по $id из таблицы products
    public function getProduct($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'products');
    }
    
    //Возвращает название всех продуктов по id в виде 1~2~3;
    public function getProducts($ids)
    { 
        $idProducts = explode('~',$ids);
        if($idProducts > 0)
        {
            foreach ($idProducts as $i => $idProduct)
            {
                $result .= $this -> query($idProduct, $whatField = 'name', 'products').', ';
            }
            if (strlen($result) > 2) 
            {
                $result = substr($result,0,strlen($result)-2).'.';
                return $result;
            }
            else return false; 
        }
        else return false; 
    }
    
    //1 значение по $id из таблицы sSections
    public function getSection($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'sSections');
    }
    
    //1 значение по $id из таблицы sFirms
    public function getFirm($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'sfirms');
    }
    
    //1 значение по $id из таблицы sGroups
    public function getGroup($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'sgroups');
    }
    
    //1 значение по $id из таблицы sSubgroups
    public function getSubgroup($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'ssubgroups');
    }
    
    //1 значение по $id из таблицы sSubgroups
    public function getGroupGalery($id, $whatField = 'name')
    { 
        return $this -> query($id, $whatField, 'sGroupsGalery');
    }
    
    public function getShortProductInfo($id)
    { 
        if ($id>0) 
        {
            $this -> _db -> query("SELECT id,idFirm,idSection,idGroup,idSubgroup FROM `products` WHERE id = {$id}");
            if ($this -> _db -> getCount() > 0) 
            {
                $find = $this -> _db -> getRow();
                $result = 'Название: <strong>'.$this -> getProduct($find['id']).'</strong><br />';
                $result .= 'Производитель: <strong>'.$this -> getFirm($find['idFirm']).'</strong><br />';
                $result .= 'Раздел: <strong>'.$this -> getSection($find['idSection']).'</strong><br />';
                $result .= 'Группа: <strong>'.$this -> getGroup($find['idGroup']).'</strong><br />';
                $result .= 'Подгруппа: <strong>'.$this -> getSubgroup($find['idSubgroup']).'</strong><br />';
                return $result;
            } 
            else return false;
        }   
        else return false;
    }    
    
    //Фунция создает список из таблицы $name, можно задать первое значение $firstValue и первый заголовок $firstName, и задать стили $style
    public function greateSelect($name, $firstName = null, $firstValue = 0, $style = '', $attr = '',$src=null)
    { 
        $this -> _db -> query("SELECT * FROM `".$name."`");
        if ($this -> _db -> getCount() > 0) 
        {
            $style = ($style == null) ? "" : "style='".$style."'";
            $values = $this -> _db -> getArray();
            $src=($src == null) ? "" : $src;
            $selectB = '<select id="'.$name.'" name="'.$name.'" '.$style.' '.$attr.'>';
            if ($firstName) $optionF = '<option value="'.$firstValue.'">'.$firstName.'</option>';
            foreach ($values as $i => $value)
            {
                if ($firstValue == $value['id']) 
                    $optionF = '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                else 
                    $options .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
            $selectE = '</select>';
            return $selectB.$optionF.$options.$selectE;
        }
    }
    
    //блок кто создал или обновил
    public function getCreateUpdate($idCreate,$dtGreate,$idUpdate,$dtUpdate,$fullInfo = false)
    { 
        if ($idUpdate)
        {
            $update = 'Редактировано: ';
            $update .= $this -> getUser($idUpdate);
            $update .= '<br />('.$this -> _dtClass -> dtFromDB($dtUpdate).')';
        }
        if ($idCreate)
        {
            if ($fullInfo and $idUpdate) $delim = '<br /><br />';
            $create = 'Создано: ';
            $create .= $this -> getUser($idCreate);
            $create .= '<br />('.$this -> _dtClass -> dtFromDB($dtGreate).')';
        }
        $result = '<div class="help" style="float:right;line-height:10px;text-align:right;font-weight: normal;">';
        if ($fullInfo)
            $result .= ($update) ? $update.$delim.$create : $create;
        else 
            $result .= ($update) ? $update : $create;
        $result .='</div>';
        return $result;
    }
    
    //блок фотографии
    public function drowPhotoBlock($md5_mictotime=null)
    {
        $this -> _db -> query("SELECT * FROM productsphotos WHERE  md5_mictotime= '".$md5_mictotime."' ORDER BY dtcreate DESC LIMIT 1");
        if ($this -> _db -> getCount())
        {
            $photos = $this -> _db -> getArray();
            foreach ($photos as $photo)
            {
                
                echo '<div id="block~'.$photo['id'].'" style="width:130px;float:left;margin-bottom:10px">';
                    echo '<img src="/img/products/fsize1/'.$photo['md5_mictotime']."_".$photo['id'].'.png" width="100" style="float:left"/>';
                    echo '<img class="photodel" id="del~'.$photo['id'].'" src="/img/admin/d.png" title="Удалить" style="margin-left:3px">';
                echo '</div>';
            }
        }
        else echo '&emsp;Пусто.';
    }
     //блок фотографии
    public function drowPhotoBlockDial($mictotime,$temp,$idgroup)
    {
        $temp=($temp!=null)?' AND temp="'.$temp.'"':"";
        $idgroup=($idgroup!=null)?' AND id_dial='.$idgroup:"";
        $this -> _db -> query("SELECT * FROM dialphotos WHERE  md5_mictotime= '".$mictotime."' {$temp} {$idgroup} ORDER BY dtcreate DESC LIMIT 1");
        if ($this -> _db -> getCount()>0)
        {
            $photos = $this -> _db -> getArray();
            foreach ($photos as $photo)
            {
                echo '<div id="block~'.$photo['id'].'" style="width:130px;float:left;margin-bottom:10px">';
                    echo '<img src="../img/dial/1000/'.$photo['md5_mictotime']."_".$photo['id'].'.jpg" width="100" style="float:left"/>';
                    echo '<img class="photodeldial" data-del="'.$photo['id'].'" src="/img/admin/d.png" title="Удалить" style="margin-left:3px">';
                echo '</div>';
            }
        }
        else echo '&emsp;Пусто.';
    }
    
    //функция выводит блок товаров/аксессуаров
    public function getBlockForSearchingProducts($title, $getIdAttributes)
    {
        echo '<table width="100%">';
            echo '<tr id="selectedProductsAfterHeadRow">';
                echo '<td colspan="4" align="center"><div class="title">'.$title.'</div></td>';
            echo '</tr>';
            
            $idAttributes = ($getIdAttributes) ? explode('~',$getIdAttributes) : null;
            if ($idAttributes)
            {
                echo '<tr>';
                    echo '<td align="center" width="50">№</td>';
                    echo '<td align="center" width="100">Фото</td>';
                    echo '<td align="center">Краткое инфо</td>';
                    echo '<td align="center" width="100">Выбрать</td>';
                echo '</tr>';
                foreach ($idAttributes as $a => $idAttribute)
                {
                    echo '<tr>';
                        echo '<td align="center" style="vertical-align: middle;">'.($a+1).'</td>';
                        echo '<td style="vertical-align: middle;">';
                            echo $this -> getAva($idAttribute);
                        echo '</td>';
                        echo '<td>';
                            echo $this -> getShortProductInfo($idAttribute);
                        echo '</td>';
                        echo '<td style="vertical-align: middle;" align="center">';
                            echo '<input class="onlyRemoveSelectedProduct" type="checkbox" value="'.$idAttribute.'" checked="checked">';
                        echo '</td>';
                    echo '</tr>';
                }
            }
    
            echo '<tr id="searchBar">';
                echo '<td colspan="4" align="center">';
                    echo '<input type="text" name="search" id="search" />';
                    echo '<input type="button" name="goSearch" id="goSearch" value="Поиск" />';
                    echo '<input type="hidden" name="idsProducts" id="idsProducts" value="'.$getIdAttributes.'~" />';
                    //echo $form -> input_('button','goSearch','goSearch','','Поиск');
                    //echo $form -> input_('hidden','idsProducts','idsProducts','',$product['attributes']);
                echo '</td>';
            echo '</tr>';
            echo '<tbody id="searchResult">';
            echo '</tbody>';
        echo '</table>';
    }
    
    //функция возвращиет склоненное слово день
    public function getWordDays($days)
    { 
        switch ($days)
        {
            case '1': if ((substr ($days,-2,1)!=1)or(strlen($days)==1))return 'день';break;
            case '2': if ((substr ($days,-2,1)!=1)or(strlen($days)==1))return 'дня'; break;
            case '3': if ((substr ($days,-2,1)!=1)or(strlen($days)==1))return 'дня'; break;
            case '4': if ((substr ($days,-2,1)!=1)or(strlen($days)==1))return 'дня'; break;
            default: return 'дней';break;
        }
    }
    
    //функция возвращиет склоненное слово день
    public function getPresenceText($number, $days = 1)
    {
        switch (substr ($number,-1))
        {
            case '0': return 'Нет в наличии';break;
            case '1': return 'Есть в наличии'; break;
            case '2': return 'Доставка в течении 24ч'; break;
            case '3': return 'Возможен заказ, доставка через '.$days.' '.$this -> getWordDays($days); break;
            default: return 'unknown'; break;
        }
    }   
    
    //функция возврашает select наличия
    public function getPresenceSelect($whatSelected = null, $afterDays = null)
    {
        $select = '<select name="presence" class="presence">';
            $select .= '<option value="1" '.(($whatSelected == 1) ? 'selected="selected"':'').'>есть в наличии</option>';
            $select .= '<option value="0" '.(($whatSelected == 0) ? 'selected="selected"':'').'>нет в наличии</option>';
            $select .= '<option value="2" '.(($whatSelected == 2) ? 'selected="selected"':'').'>доставка в течении 24 часов</option>';
            $select .= '<option value="3" '.(($whatSelected == 3) ? 'selected="selected"':'').'>возможен заказ, доставка через '.$afterDays.' '.$this -> getWordDays($afterDays).'</option>';
        $select .= '</select>';
        $select .= '<input type="hidden" name="quickChangeDays" value="'.$afterDays.'">';
        return $select;
    }
    
    
    //функция возврашает строку с описанием способа доставки
    public function getDeliveryTextA($delivery)
    {
        switch ($delivery)
        {
            case 1: $result = 'самовывоз'; break;
            case 2: $result = 'по области (+ стоимость перевозчика)'; break;
            case 3: $result = 'курьером (+ 50грн.)'; break;
            default: $result = 'unknown'; break;
        }
        return $result;
    }
}

$DLL = new DLL();
?>
