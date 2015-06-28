<?php
class date {
	private $_nowDT = '';                   
	private $_nowD = ''; 
    private $_nowDFormat = '';
    private $_yestarday = '';
    private $_tomorrow = '';
    
	function __construct() {
	    $this -> _nowDT = date('Y-m-d H:i:s');
        $this -> _nowD = date('Y-m-d');
        $this -> _nowDFormat = date('d.m.Y');
        $this -> _yestarday = date("Y-m-d H:i:s",mktime(23,59,59,date('m'),(date('d')-1),date('y')));
        $this -> _tomorrow = date("Y-m-d H:i:s",mktime(0,0,0,date('m'),(date('d')+1),date('y')));
	}
    
    
    public function getTomorrow()
    {
        return $this -> _tomorrow;
    }
    
    public function getYestarday()
    {
        return $this -> _yestarday;
    }
    
    public function dtInDB($datetime=null)
    {
        if ($datetime)
        {
            list($date, $time) = explode(' ',$datetime);
            list($d,$m,$y) = explode('.',$date);
            $dateYYYYMMDD = $y.'-'.$m.'-'.$d;
            $dt = $dateYYYYMMDD;
            if ($time) $dt .= ' '.$time;
            return $dt;
        }
        else return $this -> _nowDT;
    }
    
    public function dFormat()
    {
        return $this -> _nowDFormat;
    }
    
    public function dInDB()
    {
        return $this -> _nowD;
    }
    
	public function dtFromDB($datetime, $widthTime = true)
    {
        list($date, $time) = explode(' ',$datetime);
        $dateDDMMYYYY = $this -> dFromDB($date);
        $dateWord = $this -> dWordRus($dateDDMMYYYY);
        list ($h,$m,$s) = explode(':', $time);
        $timeHM = $h.':'.$m;
        $result = $dateWord;
        if ($widthTime) $result .=' в '.$timeHM;
        return $result;
    }
    
    public function dFromDB($date)
    {
        return substr($date, -2).'.'.substr($date, -5, 2).'.'.substr($date, 0, 4);;
    }
    
    public function dWordRus($date)
    {
        $ymd = explode('.', $date);
        $month_of_year=array('01'=>"янв",'02'=> "фев", '03'=>"мар",'04'=>"апр",'05'=>"май",'06'=>"июн",'07'=>"июл",'08'=>"авг",'09'=>"сен",'10'=>"окт",'11'=>"ноя",'12'=>"дек");
        ($date) ? $date = $ymd[0].' '.$month_of_year[$ymd[1]].' '.$ymd[2].' г.' : $date='';
        return $date;
    }
}

$dtClass = new date();
?>
