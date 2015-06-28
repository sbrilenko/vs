
<?php

class ad 
{ 
  
    /*Это функция для будущего form*/
    public function form_($name,$id,$method,$enctype,$action = null,$content=null)
		{
		  return "<form name='{$name}' id='{$id}' method='{$method}' enctype='{$enctype}' action='{$action}'>
                <fieldset>
                {$this->Content}
                </fieldset>
          </form>";
		}
        /* функция для input:text,checkbox,submit и прочие
        *  $type - тип input:text,checkbox,submit,hidden ...
        *  $name - имя input
        *  $id - id input для обращения к нему по id,не является обязательным полем, если не нада ставим ''
        *  $class - класс не является обязательным полем, если не нада ставим ''
        *  $value - значение не является обязательным полем, если не нада ставим ''
        *  $style - стиль не является обязательным полем, если не нада ставим ''
        *  $accept - image/jpg, не является обязательным полем, если не нада ставим ''
        *  $multiple - груповая загрузка, по умоляанию 0 если нужен то ставим 1
        *  $title и  alt думаю понятно
        *
        */
    public function input_($type,$name,$id=null,$class='',$value=null,$style=null,$accept=null,$multiple=0,$title=null,$alt=null,$attr=null)
		{
		  $id=($id==null)? "" :"id='{$id}'";
		  $class=($class==null) ? "" : "class='{$class}'"; 
          $value=($value==null) ? "" : "value='{$value}'";
          $style=($style==null) ? "" : "style='{$style}'";
          $accept=($accept==null) ? "" : "accept='{$accept}'";
          $multiple=($multiple==0)?"":'multiple="multiple"';
          $title=($title==null) ? "" : "title='{$title}'";
          $alt=($alt==null) ? "" : "alt='{$alt}'";
          $attr=($attr==null) ? "" : $attr;
          return "<input type='{$type}' {$class} name='{$name}' {$title} {$value} {$style} {$id} {$accept} {$multiple} {$attr}>";    
		}
        /* функция для названия поля и для описания-помощи по нему
        *  $name - название поля
        *  $help - текст описания, не обязательный параметр
        *  $stylehelp - стиль для описания не является обязательным полем, если не нада ставим ''
        */
    public function titleandhelp($name,$help='',$stylehelp='')
    {
        $help=($help=='') ? "" : $help; 
        $stylehelp=($stylehelp==null) ? "" : "style='{$stylehelp}'";
        return "{$name}<div class='help' {$stylehelp}>{$help}</div>";
    }
        /* функция для выпадающего списка select
        *  $name - имя
        *  $id - id для обращения к нему по id,не является обязательным полем, если не нада ставим ''
        *  $class - класс не является обязательным полем, если не нада ставим ''
        *  $value - значение не является обязательным полем, если не нада ставим ''
        *  $style - стиль не является обязательным полем, если не нада ставим ''
        *  $mass - массив опций в формате array("foo", "bar", "hallo", "world"), нумерация начинается с нуля
        */
    public function select($name,$id,$class='',$style=null,$mass,$title=null,$alt=null,$firstOptionValue = null,$firstOptionName = null,$firstIsNull = false)
    {
        $class=($class==null) ? "" : "class='{$class}'"; 
        $value=($value==null) ? "" : "value='{$value}'";
        $style=($style==null) ? "" : "style='{$style}'";
        $title=($title==null) ? "" : "title='{$title}'";
        $alt=($alt==null) ? "" : "alt='{$alt}'";
        if ($firstOptionValue or $firstIsNull) $options = "<option {$title} {$alt} value={$firstOptionValue}>{$firstOptionName}</option>";
        foreach($mass as $index => $value)
        {
            if ($firstOptionValue != $index)
            $options.="<option {$title} {$alt} value={$index}>{$value}</option>";
        }
        return "<select {$class} id='{$id}' name='{$name}' {$style}>
                {$options}
                </select>";
    }
        /* начало form
        *  $name - имя
        *  $id - id для обращения к нему по id,не является обязательным полем, если не нада ставим ''
        *  $method - метод посылки $_POST, $_GET
        *  $enctype - формат передачи файлов
        *  $action - кому посылаем данные, но мы посылаем скриптом поэтому ставим ""
        *  $toscript - кому посылаем данные через скрипт
        *  $before - javascript который выполняется до посылки в php
        *  $after - javascript который выполняется после обработки в php
        */
    public function formb($name,$id,$method,$enctype,$action=null,$toscript,$before,$after)
    {
        //$action=($action==null)?"":"action='{$action}'";
        return "<form name='{$name}' id='{$id}' method='{$method}' action='{$action}' enctype='{$enctype}'><fieldset>
        <script type='text/javascript'>var optionsUpdate = {
        url:   '".$toscript."',
        beforeSubmit: function(jqForm) {
        ".$before."
        },
        success: function(responseText) { // Здесь проверяем ответ
          ".$after."
        }
    };
    $('#".$id."').live('submit',function() { 
        $(this).ajaxSubmit(optionsUpdate); 
        return false;
    });
    </script>";
    }
     /* конец form */
     public function forme()
    {
        return "</fieldset></form>";
        
    }
    public function replaceToInsert($value) 
        {
            
            $value=trim($value);
            $value=htmlspecialchars($value);
            $value=strip_tags($value);
            $value = str_replace("'", "&rsquo;", $value);
            $value = str_replace("`", "&lsquo;", $value);
            $value = str_replace("[strong]", "<strong>", $value);
            $value = str_replace("[/strong]", "</strong>", $value);
            $value = str_replace("[em]", "<em>", $value);
            $value = str_replace("[/em]", "</em>", $value);
            $value = str_replace("[color]", "<span class=\'color\'>", $value);
            $value = str_replace("[/color]", "</span>", $value);
            $value = str_replace("[url=", "<a target='_blank' href=", $value);
            $value = str_replace("[url", "<a", $value);
            $value = str_replace("[/url]", "</a>", $value);
            $value = str_replace("]", ">", $value);
            $value = str_replace("\r\n", "<br />", $value);
			$value = str_replace("\n", "<br />", $value);
            //$value=preg_replace("<[А-Я][A-Z]{1,}>", "", $value);
            $value=preg_replace("/insert|delete|update/i","", $value);
            return $value;
        }
       public function replaceToDraw($value) 
       {

            $value = str_replace("<strong>", "[strong]", $value);
            $value = str_replace("</strong>", "[/strong]", $value);
            
            $value = str_replace("<em>", "[em]", $value);
            $value = str_replace("</em>", "[/em]", $value);            

            $value = str_replace("<span class='color'>", "[color]", $value);
            $value = str_replace("</span>", "[/color]", $value);

            $value = str_replace("<a target='_blank' href='", "[url=", $value);
            $value = str_replace("</a>", "[/url]", $value);
            $value = str_replace("'>", "]", $value);

            $value = str_replace("<br />", "\r\n", $value);

            return $value;
        }
        
        public function drawEditor($name, $value = '',$bold,$url,$curs)
        {
            echo "<table cellpadding='0' cellspaicing='0' class='editor'>";
            echo "<tr >";
                    echo "<td colspan='2'>";
                    echo"<div class='help'>";		
                    echo"<p style='color:#97978d;'>*Перенос строки - один Enter<br />Абзац - два Enter'а</p>";
                    echo "</div>";		
                    echo "</td>";
            echo "</tr>";
            echo "<tr>";
                    echo "<td style='text-align:left;'>";
                    echo "<textarea name='".$name."' id='".$name."'>".$value."</textarea>";
                    echo "</td>";
                    echo "<td class='right'>";
                    echo ($bold==1)?"<div title='Выделить жирным' class='button' onclick=\"replaceSelectedText('".$name."', 'bold');\"><b>B</b></div>":""; 
					echo ($url)?"<div title='Ссылка' class='button' onclick=\"promtLink('".$name."', 'url');\">URL</div>":"";
                    echo ($curs)?"<div title='Выделить курсивом' class='button' onclick=\"replaceSelectedText('".$name."', 'cursiv');\"><i>I</i></div>":"";
                    /*echo "<div title='Выделить цветом' class='button' onclick=\"replaceSelectedText('".$name."', 'color');\">Цвет</div>";*/
                    echo "</td>";
            echo "</tr>";
            echo "</table>";	
        }
}

?>