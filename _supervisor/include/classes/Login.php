<?php 

class Login extends DB{
    private $email;
    private $password;
    private $errors = false;
    public function __construct($email,$password)
    {
        parent::__construct();
        $this->email = $email;
        $this->password = $password;

        $this->checkEmpty();
    }

    private function checkEmpty()
    {
        if(empty($this->email) && empty($this->password))
        {
            $this->errors = true;
        }
        
    }
    private function encryptString($string)
    {
        return password_hash($string,PASSWORD_DEFAULT);
    }

    public function actionLogin()
    {
        if($this->getErrors() != true)
        {
            $checkEmail = $this->getRows('supervisor',array(
             'where'=>array(
                'email' => $this->email,
             ),
             'return_type' => 'single'
            ));

          
         
            if($checkEmail && $checkEmail['active'] != 0 && password_verify($this->password,$checkEmail['password']))
            {
                    $check = $this->getRows('musharifin_candidate',array(
                    'where'=>array(
                       'idMusharifin' => $checkEmail['id'],
                    ),
                    'return_type' => 'single'
                   ));
                   if(!$check)
                   {
                    return $checkEmail;
                   }else{
                     $checkActiveOrNo = $this->getRows('supervisor',array(
                        'where'=>array(
                           'id' => $check['idCandidate']
                        ),
                        'return_type' => 'single'
                       ));
                       if($checkActiveOrNo['active'] != 0)
                       {
                        return $checkEmail;

                       }
                  
                   }

                   
            }
            return false;
        }
      
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function getErrors(){
        return $this->errors;
    }
}


?>