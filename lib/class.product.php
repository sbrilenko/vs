<?php
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once 'class.invis.db.php';
    class product {
    public $_public=array();
    private $_db;
   
    function __construct() {
	    $this -> _db =  db :: getInstance();
	}
    public function getkkz()
    {
        $sql="SELECT * FROM goods WHERE deleted=0 AND displaylisting=1 AND setofgoogs = 0";
        $this ->_db->query($sql);
        return ($this ->_db->getCount()>0)?$this ->_db->getArray():$this->_public;
    }
    /*выбираем для главной*/
    public function getkkzinmain()
    {
        $sql="SELECT * FROM goods WHERE deleted=0 AND displaylisting=1 AND setofgoogs = 0 AND show_in_mainpage=1 LIMIT 4 ";
        $this ->_db->query($sql);
        return ($this ->_db->getCount()>0)?$this ->_db->getArray():$this->_public;
    }
    public function formatoutputphoto($photo)
    {
        $photo=str_replace('.','',$photo);
        $photo=str_replace('-','_',$photo);
        return $photo;
    }
    public function kkzphoto($photo,$id)
    {
        $returnphoto='';
        if(!empty($photo))
            {
                $photo=$this->formatoutputphoto($photo);
                if(file_exists($root."/img/products/fsize1/".$photo."_ass.png"))
                {
                    $returnphoto.="<div><a href='/product/more/id/".$id."/from/kkz'><img src='".$root."/img/products/fsize1/".$photo."_ass.png' /></a></div>";
                    $returnphoto.="<div class='clear_both'></div>"; 
                }
                else
                {
                     $returnphoto.="<div><a href='/product/more/id/".$id."/from/kkz'><img src='".$root."/img/products/fsize1/good-empty_ass.png' /></a></div>";
                     $returnphoto.="<div class='clear_both'></div>";
                }
            }
            else
            {
                 $returnphoto.="<div><a href='/product/more/id/".$id."/from/kkz'><img src='".$root."/img/products/fsize1/good-empty_ass.png' /></a></div>";
                 $returnphoto.="<div class='clear_both'></div>";
            }
        return $returnphoto;
    }
    public function getmanufname($id)
    {
        $sql_get_firm="SELECT * FROM manufacturers WHERE deleted=0 AND id='".$id."'";
        $this->_db->query($sql_get_firm);
        if($this->_db->getCount()>0)
        {
            $arr=$this->_db->getArray();
            return (!empty($arr[0]['link']))? "<a class='firm' href='http://vsort.com.ua".$arr[0]['link']."'>".$arr[0]['name']."</a>":"<span class='other'>".$arr[0]['name']."</span>";
            
        }
    }
    public function getpricefor($id_base_ed_iz)
    {
        $sql_price_for="SELECT name FROM unitsofgoods WHERE deleted=0 AND id='".$id_base_ed_iz."'";
        $this->_db->query($sql_price_for);
        return ($this->_db->getCount()>0)?$this->_db->getValue():"";
    }
    public function priceofbaseediz($id)
    {
        $sql_price="SELECT price FROM priceofgoods WHERE deleted=0 AND idgoods='".$id."'";
        $this->_db->query($sql_price);
        return ($this->_db->getCount()>0)?number_format($this->_db->getValue(), 2, '.', ''):"";
    }
    public function getuserfactor($user_ed_iz,$id)
    {
         $sql_price_for="SELECT factor FROM unitsofgoods WHERE deleted=0 AND id='".$user_ed_iz."' AND idgoods='".$id."'";
         $this->_db->query($sql_price_for);
         return ($this->_db->getCount()>0)?$this->_db->getValue():"";
    }
    public function getusernameediz($user_ed_iz,$id)
    {
         $sql_price_for="SELECT name FROM unitsofgoods WHERE deleted=0 AND id='".$user_ed_iz."' AND idgoods='".$id."'";
         $this->_db->query($sql_price_for);
         return ($this->_db->getCount()>0)?$this->_db->getValue():"";
    }
    public function getprice($id)
    {
         $sql_price="SELECT price FROM priceofgoods WHERE deleted=0 AND idgoods='".$id."'";
         $this->_db->query($sql_price);
         return ($this->_db->getCount()>0)?$this->_db->getValue():"";
    }
    public function getuserpriceformat($id,$user_ed_iz,$k)
    {
        return $k*number_format(1*$this->getuserfactor($user_ed_iz,$id)*$this->getprice($id), 2, '.', '');
    }
    public function getallmanufact()
    {
        $sql="SELECT * FROM manufacturers WHERE deleted=0 ORDER BY name";
        $this->_db->query($sql);
        return ($this->_db->getCount()>0)?$this->_db->getArray():"";
    }
    public function getallmanufactlogo()
    {
        $fi=$this->getallmanufact();
        if(count($fi)>0)
        {
            echo "<table><tr>";
            foreach($fi as $fiindex=>$fival)
            {
                $fival['photo']=$this->formatoutputphoto($fival['photo']);
                $img_left=($fiindex!=0)?"imga-ass-left":"";
                
                if(file_exists($root."/img/firm/fsize1/".$fival['photo']."_ass.png"))
                {
                    if(!empty($fival['link']))
                    {
                        echo "<td style='vertical-align:middle;'><a class='".$img_left."' href='/".$fival['link']."'><img alt='".$fival['name']."' title='".$fival['name']."' src='../img/firm/fsize1/".$fival['photo']."_ass.png' /></a></td>";
                    }
                    else
                    {
                        echo "<td style='vertical-align:middle;'><img class='".$img_left."' alt='".$fival['name']."' title='".$fival['name']."' src='../img/firm/fsize1/".$fival['photo']."_ass.png' /></td>";
                    }
                }
                else
                {
                    if(!empty($fival['link']))
                    {
                        echo "<td style='vertical-align:middle;'><a class='".$img_left."' href='/".$fival['link']."'><img alt='".$fival['name']."' title='".$fival['name']."' src='../img/firm/fsize1/firm-empty_ass.png' /></a></td>";
                    }
                    else
                    {
                        echo "<td style='vertical-align:middle;'><img class='".$img_left."' alt='".$fival['name']."' title='".$fival['name']."' src='../img/firm/fsize1/firm-empty_ass.png' /></td>";
                    }
                }
            }
            echo "<tr></table>";
        }
    }
    }
$prd=new product();