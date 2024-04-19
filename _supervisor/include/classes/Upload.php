<?php 

class Upload {
    private $_direction;
    private $_file;
    private $_target;
    private $_type = array();
    public $_errors;
    private $_size;
    public function __construct($file,$target,$type,$size)
    {
        $this->_file = $file;
        $this->_target = $target;
        $this->_type = $type;
        $this->_size = $size;

        $this->setDirection($target);
        $this->_direction;
    }
    public function upload()
    {
        if(empty($_FILES[$this->_file]['name'])) { 
            $this->_errors = "من فضلك قم بتحميل الملف";
         }

         else if($this->checkFileType() === false)
         {
            $this->_errors = "هذا الملف غير مدعوم";
         }

         else if($_FILES[$this->_file]['size'] > $this->_size)
         {
            $this->_errors = "حجم الصورة كبير";
         }
         else {
            if (move_uploaded_file($_FILES[$this->_file]["tmp_name"], $this->_direction)) {
                return true;
            } else {
                $this->_errors = "حدث خطأ";
            }
         }

        return $this->_errors;
    }

    function checkIsUpload()
    {
        if($_FILES[$this->_file]['name']) { 
          return true;
         }
         return false;
    }
    public function setDirection($target){
        return $this->_direction = $target . time().".".$this->fileType();
    }
    public function getDirection()
    {
        return $this->_direction;
    }

    public function fileType()
    {
        return strtolower(pathinfo($_FILES[$this->_file]['name'],PATHINFO_EXTENSION));
    }

    public function checkFileType()
    {
      return in_array($this->fileType(),$this->_type) ? true : false;
     
    }

    public static function removeImage($path)
    {
        if(file_exists($path))
            unlink($path);
        
        return false;
    }
    
}

?>


