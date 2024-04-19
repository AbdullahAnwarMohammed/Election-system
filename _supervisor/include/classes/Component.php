<?php 

class Component extends DB {
    

    private $_tb;
    public $_errors;
    public function __construct($tb)
    {
        parent::__construct();
        $this->_tb = $tb;

        $this->removeImage();
        
    }
    
    private function removeImage(){
      
       if($this->_tb == "supervisor" ){   
        foreach(glob('uploads/supervisor/'.'*') as $filename){
         if($this->getSingleInfo('image','uploads/supervisor/'.basename($filename)) === false)
         {
            unlink('uploads/supervisor/'.basename($filename));
         }
        }
       }

       if($this->_tb == "events" ){   
        foreach(glob('uploads/events/'.'*') as $filename){
         if($this->getSingleInfo('image','uploads/events/'.basename($filename)) === false)
         {
            unlink('uploads/events/'.basename($filename));
         }
        }
       }
       
       if($this->_tb == "infocandidate" ){   
        foreach(glob('uploads/footer/'.'*') as $filename){
         if($this->getSingleInfo('footerImage','uploads/footer/'.basename($filename)) === false)
         {
            unlink('uploads/footer/'.basename($filename));
         }
        }
       }
    
 

    }

    public function getInfo()
    {
        return $this->getRows($this->_tb);
    }

    public function getSingleInfo($key,$value){
        return $this->getRows($this->_tb,array(
            'where' => array($key => $value),
            'return_type' => 'single'
        ));
    }

    // public function checkRegisterData($key,$value,$and = null){
    //     $stmt = $this->db->prepare("SELECT * FROM $this->_tb WHERE $key = '$value' $and");
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }
   
    
    public function deleteSuperVisor($key,$id)
    {
        return $this->delete($this->_tb,array($key=>$id));
        
    }

    public function isSuperVisor(){
        return $_SESSION['rankSuperVisor'] == 1 ? true : false;
    }
    

    public function insertSuperVisor($array = array())
    {
            return $this->insert($this->_tb,$array);
    }
    // public function check($key,$value,$message,$and = null){        
    //     if($data = $this->checkRegisterData($key,$value,$and))
    //     {
    //         $this->_errors = $message;
    //     }else{
    //     return false;
    //     }
    // }

    public function getAll($key = ' ',$value = '',$where = ''){
        if($where){
            return $this->getRows($this->_tb,array(
                'where' => array($key => $value),
            ));
        }else{
            return $this->getRows($this->_tb,array());
        }
    }

    public function innerJoin($column,$to,$on)
    {
        // $column =  supervisor.*,musharifin_candidate.idCandidate
        // $from = musharifin_candidate
        // $to = supervisor
        // $on = supervisor.id = musharifin_candidate.idMusharifin;
       $query =  "
       SELECT  $column
       FROM  $this->_tb INNER JOIN $to ON $on
       ;
       ";
        $stmt = $this->db->prepare(
           $query
        );
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {
            return $stmt->fetchAll();
        }
    }


    public function checkUsername($username,$andD='',$andS ='')
    {
        $checkUsernameDaman = $this->db->prepare("
        SELECT * FROM daman WHERE username = ? $andD
        ");
        $checkUsernameDaman->execute([$username]);

        $checkUsernameSuperVisor = $this->db->prepare("
        SELECT * FROM supervisor WHERE username = ? $andS
        ");
        $checkUsernameSuperVisor->execute([$username]);

        if($checkUsernameDaman->rowCount() > 0 OR $checkUsernameSuperVisor->rowCount() > 0)
        {
            return false;
        }
    }


    public function checkEmail($email,$where = '')
    {
        $checkEmail = $this->db->prepare("
        SELECT * FROM supervisor WHERE email = ? $where
        ");
        $checkEmail->execute([$email]);

      
        if($checkEmail->rowCount() > 0 )
        {
            return false;
        }
    }
  

    public function checkNameEvent($name)
    {
        $check = $this->db->prepare("
        SELECT * FROM events WHERE name = ? 
        ");
        $check->execute([$name]);

      
        if($check->rowCount() > 0 )
        {
            return false;
        }
    }

    public function checkNameEnglish($name,$and = '')
    {
        $nameEnglish = $this->db->prepare("
        SELECT * FROM frontend WHERE nameEnglish = ? $and
        ");
        $nameEnglish->execute([$name]);

      
        if($nameEnglish->rowCount() > 0 )
        {
            return false;
        }

    }



  
   
}