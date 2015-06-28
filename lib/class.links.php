<?php
    interface Ilink
    {
    	public function greturn_link($start_date,$end_date,$photo);
    }
	class link implements Ilink
	{
		
        public function return_link($start_date,$end_date,$photo)
		{
			$date_strat_explade=explode('-',$start_date);
			$date_end_explade=explode('-',$end_date);
			$databaseDate = $date_strat_explade[2].'-'.$date_strat_explade[1].'-'.$date_strat_explade[0];
			$databaseDateend = $date_end_explade[2].'-'.$date_end_explade[1].'-'.$date_end_explade[0];
			$dataBase = strtotime($databaseDate);
			$dataBaseend = strtotime($databaseDateend);
			$todayDate2 =  mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			if ($dataBase > $todayDate2)  { $link="<a href='/events#".$photo."'>";}
			else 
			if ($dataBaseend > $todayDate2)  { $link="<a href='/events#".$photo."'>";}
			else 
			{
				$link="<a href=''>";
			}
			return $link;
			}
		
	}
?>