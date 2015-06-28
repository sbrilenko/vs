<?php
    /**
     * Версия 11.07.02
     */

    class editorClass {
        
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

            $value = str_replace("[url=", "<a target=\'_blank\' href=\'", $value);
            $value = str_replace("[/url]", "</a>", $value);
            $value = str_replace("]", "\'>", $value);

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
        
        public function drawEditor($name, $value = '')
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
                    /*echo "<div title='Выделить жирным' class='button' onclick=\"replaceSelectedText('".$name."', 'bold');\"><b>B</b></div>";
					echo "<div title='Ссылка' class='button' onclick=\"promtLink('".$name."', 'url');\">URL</div>";
                    echo "<div title='Выделить курсивом' class='button' onclick=\"replaceSelectedText('".$name."', 'cursiv');\"><i>I</i></div>";
                    echo "<div title='Выделить цветом' class='button' onclick=\"replaceSelectedText('".$name."', 'color');\">Цвет</div>";*/
                    
                    echo "</td>";
            echo "</tr>";
            echo "</table>";	
        }

    }   //конец класса
	
	
?>