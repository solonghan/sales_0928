<?
defined('BASEPATH') OR exit('No direct script access allowed');

class Goal_model extends Base_Model {



    //取得用戶指定年分、指定月目標
    public function getGoals( $user_id , $id = '', $year= '' ,$month ='' )
    {

        if( $id != ''){
           $syntax = array(
                'user_id'   => $user_id ,
                'id'        => $id ,
                'is_delete' => 0
           );
        }
        else if($year != '' && $month!='' ){

            $syntax = array(
                'user_id'   => $user_id ,
                'year'      => $year , 
                'month'     => $month,
                'is_delete' => 0
            );
        }
        else return null  ;


        return $this->db->select()
                        ->from($this->goal_table)
                        ->where($syntax)
                        ->get()
                        ->result_array();

    }

    //新增數組月目標
    public function addGoals( $data ){
    
        $this->db->insert($this->goal_table,$data);
     
        return $this->db->insert_id();    
    }


    public function delGoal ( $goalId ) {

        return $this->db->set('is_delete',1)
                        ->where(array( 'id'=> $goalId ,'is_delete' => 0))
                        ->update($this->goal_table);
                        
    }

    // 查詢目標id 的顧客
    public function getGoalCustomers( $id , $syntax ){

        if ( is_array($id) ) {

            $resultArray = array();
            $count          = 0 ;

            foreach( $id as $d ){

                $result = $this->db->select()
                                   ->from($this->goal_customer_table)
                                   ->where($syntax)
                                   ->get()
                                   ->result_array();

                $resultArray    = array_merge($resultArray , $result)  ;                
                               
            }

            return $resultArray ;
        }
        
        else {

            $result = $this->db->select()
                               ->from($this->goal_customer_table)
                               ->where($syntax)
                               ->get()
                               ->result_array();

            return $result ; 
        }


    }




    public function insertGoalCustomers( $data ){

        if( count($data) == 1)
            $this->db->insert( $this->goal_customer_table ,$data) ;
        else
            $this->db->insert_batch( $this->goal_customer_table , $data ) ; 


        return $this->db->insert_id();    
    }


  
}

?>