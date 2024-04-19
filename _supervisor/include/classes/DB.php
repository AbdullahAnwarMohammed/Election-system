<?php 

class DB {
    protected $dbHost     = "localhost"; 
    protected $dbUsername = "root"; 
    protected $dbPassword = ""; 
    protected $dbName     = "election"; 
    public  $lastID;
    public $db;

    public function __construct(){ 
        if(!isset($this->db)){ 
            // Connect to the database 
            try{ 
                $this->db = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword,
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,

            )
            ); 
                $this->db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
              
            }catch(PDOException $e){ 
                die("Failed to connect with MySQL: " . $e->getMessage()); 
            } 
            
        } 
      
        $this->db->exec("UPDATE voters SET familyName = TRIM(familyName)");
        // $this->removeImage();
    } 
    
    public function getRows($table,$conditions = array()){ 
        $sql = 'SELECT '; 
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*'; 
        $sql .= ' FROM '.$table; 
        if(array_key_exists("where",$conditions)){ 
            $sql .= ' WHERE '; 
            $i = 0; 
            foreach($conditions['where'] as $key => $value){ 
                $pre = ($i > 0)?' AND ':''; 
                $sql .= $pre.$key." = '".$value."'"; 
                $i++; 
            } 
        } 
        if(array_key_exists("order_by",$conditions)){ 
            $sql .= ' ORDER BY '.$conditions['order_by'];  
        } 
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){ 
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];  
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){ 
            $sql .= ' LIMIT '.$conditions['limit'];  
        } 
        $query = $this->db->prepare($sql); 
        $query->execute(); 
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all')
        {
            switch($conditions['return_type']){ 
                case 'count': 
                    $data = $query->rowCount(); 
                    break; 
                case 'single': 
                    $data = $query->fetch(PDO::FETCH_ASSOC); 
                    break; 
                default: 
                    $data = ''; 
            } 
        }
        else{ 
            if($query->rowCount() > 0){ 
                $data = $query->fetchAll(); 
            } 
        } 
        return !empty($data)?$data:false; 
    }
    public function insert($table,$data){ 
        if(!empty($data) && is_array($data)){ 
            $columns = ''; 
            $values  = ''; 
            $i = 0; 
         /*   if(!array_key_exists('created',$data)){ 
                $data['created'] = date("Y-m-d H:i:s"); 
            } 
            if(!array_key_exists('modified',$data)){ 
                $data['modified'] = date("Y-m-d H:i:s"); 
            } */
            
            $columnString = implode(',', array_keys($data)); 
            $valueString = ":".implode(',:', array_keys($data)); 
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")"; 
            $query = $this->db->prepare($sql); 
            foreach($data as $key=>$val){ 
                 $query->bindValue(':'.$key, $val); 
            } 
            $insert = $query->execute(); 
            $this->lastID = $this->db->lastInsertId();
            return $insert?$this->db->lastInsertId():false; 
        }else{ 
            return false; 
        } 
    } 

    public function update($table,$data,$conditions){ 
        if(!empty($data) && is_array($data)){ 
            $colvalSet = ''; 
            $whereSql = ''; 
            $i = 0; 
           /* if(!array_key_exists('modified',$data)){ 
                $data['modified'] = date("Y-m-d H:i:s"); 
            } */
            foreach($data as $key=>$val){ 
                $pre = ($i > 0)?', ':''; 
                $colvalSet .= $pre.$key."='".$val."'"; 
                $i++; 
            } 
            if(!empty($conditions)&& is_array($conditions)){ 
                $whereSql .= ' WHERE '; 
                $i = 0; 
                foreach($conditions as $key => $value){ 
                    $pre = ($i > 0)?' AND ':''; 
                    $whereSql .= $pre.$key." = '".$value."'"; 
                    $i++; 
                } 
            } 
            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql; 
            $query = $this->db->prepare($sql); 
            $update = $query->execute(); 
            return $update?$query->rowCount():false; 
        }else{ 
            return false; 
        } 
    } 

    public function delete($table,$conditions){ 
        $whereSql = ''; 
        if(!empty($conditions)&& is_array($conditions)){ 
            $whereSql .= ' WHERE '; 
            $i = 0; 
            foreach($conditions as $key => $value){ 
                $pre = ($i > 0)?' AND ':''; 
                $whereSql .= $pre.$key." = '".$value."'"; 
                $i++; 
            } 
        } 
         $sql = "DELETE FROM ".$table.$whereSql; 
        $delete = $this->db->exec($sql); 
        return $delete?true:false; 
    } 

    // New 

    
    private function removeImage(){
      
         foreach(glob('uploads/supervisor/'.'*') as $filename){
          if($this->getSingleInfo('supervisor','image','uploads/supervisor/'.basename($filename)) === false)
          {
             unlink('uploads/supervisor/'.basename($filename));
          }
         }
      
 
         foreach(glob('uploads/events/'.'*') as $filename){
          if($this->getSingleInfo('events','image','uploads/events/'.basename($filename)) === false)
          {
             unlink('uploads/events/'.basename($filename));
          }
         }
     
        
         foreach(glob('uploads/footer/'.'*') as $filename){
          if($this->getSingleInfo('infocandidate','footerImage','uploads/footer/'.basename($filename)) === false)
          {
             unlink('uploads/footer/'.basename($filename));
          }
         }
     
     
  
 
     }
 
     public function getInfo($tb)
     {
         return $this->getRows($tb);
     }
 
     public function getSingleInfo($tb,$key,$value){
         return $this->getRows($tb,array(
             'where' => array($key => $value),
             'return_type' => 'single'
         ));
     }
 
     // public function checkRegisterData($key,$value,$and = null){
     //     $stmt = $this->db->prepare("SELECT * FROM $this->_tb WHERE $key = '$value' $and");
     //     $stmt->execute();
     //     return $stmt->fetch();
     // }
    
     
     public function deleteSuperVisor($tb,$key,$id)
     {
         return $this->delete($tb,array($key=>$id));
         
     }
 
     public function isSuperVisor(){
         return $_SESSION['rankSuperVisor'] == 1 ? true : false;
     }
     
 
     public function insertSuperVisor($db,$array = array())
     {
             return $this->insert($db,$array);
     }
  
 
     public function getAll($tb,$key = ' ',$value = '',$where = ''){
         if($where){
             return $this->getRows($tb,array(
                 'where' => array($key => $value),
             ));
         }else{
             return $this->getRows($tb,array());
         }
     }
 
     public function innerJoin($db,$column,$to,$on)
     {
        
        $query =  "
        SELECT  $column
        FROM  $db INNER JOIN $to ON $on
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

?>