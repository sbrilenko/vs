<?php
require_once "class.invis.db.php";
require_once "class.product.php";
class history {
    private $_db;
    private $_prd;
    private $_ret=array();
    public $arr=array('01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня','07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря');
    public function __construct()
    {
        $args=func_get_args();
        $this ->_db = db::getInstance();
        $this ->_prd = new product();
        if(!empty($args[0]))
        {
            $sql="SELECT idkkz FROM orders WHERE idclient='".$args[0]."' GROUP BY idkkz ORDER BY datetime DESC";
            $this->_db->query($sql);
            if($this->_db->getCount()>0)
            {
                $kkz_arr=$this->_db->getArray();
                foreach($kkz_arr as $kkz_arr_val)
                {
                    $sql_get_to_idkkz="SELECT * FROM orders WHERE idkkz='".$kkz_arr_val['idkkz']."'";
                    $this->_db->query($sql_get_to_idkkz);
                    if($this->_db->getCount()>0)
                    {
                        $count=0;
                        $sum=0;
                        $arr=$this->_db->getArray();
                        foreach($arr as $avl)
                        {

                            $sql_go_to="SELECT * FROM compositionoforder WHERE idorder='".$avl['id']."'";
                            $this->_db->query($sql_go_to);
                            if($this->_db->getCount()>0)
                            {
                                $number=$avl['idkkz'];
                                $data=substr($avl['datetime'],8,2)." ".$this->arr[substr($avl['datetime'],5,2)]." ".substr($avl['datetime'],0,4);
                                $arr_go_to_arr=$this->_db->getArray();

                                foreach($arr_go_to_arr as $arr_go_to_val)
                                {
                                    $factor_user=$this->_prd->getuserfactor($arr_go_to_val['id_ed_iz'],$arr_go_to_val['idgoods']);
                                    $count+=$arr_go_to_val['count']/$factor_user;
                                    $sum+=$arr_go_to_val['sum'];
                                }

                            }

                        }
                        array_push($this->_ret,array('n'=>$number,'date'=>$data,'count'=>$count,'sum'=>$sum,'id'=>$avl['id']));
                    }
                }


            }
        }
    }
    public function get_ret()
    {
        return $this->_ret;
    }

}

?>
