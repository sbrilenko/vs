<?
require_once 'class.invis.db.php';
class Search
{
    private $_db = null;
    private $_table = null;
    private $_fieldsearch = null; 
    private $_fieldpopular = null;
    private $_limit = null;
    private $_orderBy = null; 
    private $_and = null;
    
    public function __construct($table, $fieldsearch, $fieldpopular, $orderBy = 'id DESC', $limit = 50, $and = '')
    {
        $this -> _db =  db :: getInstance();
        $this -> _table = $table;
        $this -> _fieldsearch = $fieldsearch;
        $this -> _fieldpopular = $fieldpopular;
        $this -> _limit = $limit;
        $this -> _orderBy = $orderBy;
        $this -> _and = $and;
        
    } 
 
    /*
    функция поиска
    выводит резельтаты где есть
    если параметр $all задать как '*' то находит по словам  
    если параметр $plus задать как '+' то находит только по фразе  
    */
    public function searchResultMatch($q, $all = null, $plus = null)
    {
        if ($q)
        {
            foreach ($q as $i => $word)
            { 
                if ($word != '')
                {
                    if (sizeof($q)>1)
                    {
                        $words .= $plus.$all.$word.$all.' ';
                    }
                    else 
                    {
                        if (strlen($word)>1)
                        {
                            $words .= $plus.$all.$word.$all.' ';
                        }
                    }
                }
            }
            $rel = "MATCH (".$this -> _fieldsearch.") AGAINST ('".$words."' IN BOOLEAN MODE)";
            $this -> _db -> query("SELECT * FROM ".$this -> _table." WHERE ".$rel." != 0 ".$this -> _and." ORDER BY ".$rel." DESC, `".$this -> _fieldpopular."` DESC,  ".$this -> _orderBy." LIMIT {$this -> _limit}");
            return $this -> _db -> getArray();
        }
        else return false;
    }

    public function searchResultLike($q)
    {
        if ($q)
        {
            foreach ($q as $i => $word)
            { 
                if ($word != '') 
                {
                    $words .= " and ".$this -> _fieldsearch." like '%".$word."%' ";
                }
            }
            $this -> _db -> query("SELECT * FROM ".$this -> _table." WHERE 1 ".$words." ".$this -> _and." ORDER BY `".$this -> _fieldpopular."` DESC, ".$this -> _orderBy." LIMIT {$this -> _limit}");
            return $this -> _db -> getArray();
        }
        else return false;
    }
    
    /*
    функция разбивает строку $string на "слоги" по $howChars символов
    */
    public function partitionWords($string,$howChars)
    {
        $tmp = preg_replace("/ +/", " ", $string);
        $tmp = explode (' ',$tmp);
        foreach ($tmp as $word)
        {
            $syllables = str_split($word, $howChars);
            foreach ($syllables as $syllable)
            {
                if (strlen($syllable) >= 1)
                $result[] = $syllable;
            }
        }
        return $result;
    }
    
    /*
    функция очищает запрос от мусора
    */
    public function clearQueryForSearch($query)
    {
        $tmp = strip_tags(htmlspecialchars(substr($query, 0, 64)));
        $tmp = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $tmp);
        $result = preg_replace("/ +/", " ", $tmp);
        return $result;
    }
    
    /*
    функция устанавливает популярность товара увеличивает на 1
    */
    public function popular($id)
    {
        $this -> _db -> query("SELECT `".$this -> _fieldpopular."` FROM ".$this -> _table." WHERE `id` = {$id}");
        $popular = (int)$this -> _db -> getValue();
        $popular++;
        $this -> _db -> query("UPDATE  ".$this -> _table." SET `".$this -> _fieldpopular."` =  '".$popular."' WHERE `id` = {$id}");
    }
}



?>