<?php
    /**
     * Версия 11.07.02
     */

    class validClass {
        
        public static function validNumber ($value)
        {
            if ($value == -1) return $value;
            
            if ($value == '' || $value == 0)
                return false;
            
            if (preg_match("|^\d+$|", $value)) 
                return $value; // int
            else
                if (preg_match("|^\d+(\.\d+)$|", $value)) 
                    return $value; // float
                else 
                    return false;
        }
                
        public static function sanitiseString ($value)
        {
            if ($value == '')
                return false;
            
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES);
            $value = preg_replace("/insert|delete|update/i","", $value);
            
            if ($value == '')
                return false;
            else
                return $value;
        }
        
        public static function cutText ($text, $len = 80)
        {
           // указываем кодировку
            mb_internal_encoding("UTF-8");
            if (strlen($text) > $len) {
                $description = mb_substr( $text, 0, $len )."...";
            }
            else {
                $description = $text;
            }
            return $description;
        }

    }   //конец класса valid
	
	
?>