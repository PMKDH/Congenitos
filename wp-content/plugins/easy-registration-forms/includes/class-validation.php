<?php

class ERForms_Validation {

    public static function text($subtype,$value){
       switch($subtype){
           case 'email': 
           case 'user_email':    
           case 'url': if(method_exists('ERForms_Validation',$subtype)){
                              return ERForms_Validation::{$subtype}($value);
                         }              
       } 
       return true;
    }
    
    public static function email($value) {
        $value= trim($value);
        if(empty($value))
            return true;
        return (filter_var($value, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    public static function maxlength($value, $len) {
        return (strlen($value) <= $len) ? true : false;
    }

    public static function required($value) {
        if(is_array($value))
            return empty($value) ? false : true;
        else
            return (trim($value) == '') ? false : true;
    }
    
    public static function is_file_uploaded($param_name){
        if(empty($_FILES)) {
        return false;       
        } 
        
        $file = $_FILES[$param_name];
        if(!file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])){
            return false;
        }   
        return true;
    }
    
    public static function minlength($value, $len) {
        
        return (strlen($value) > $len) ? true : false;
    }

    public static function url($value) {
        $value= trim($value);
        if(empty($value))
            return true;
        return (filter_var($value, FILTER_VALIDATE_URL)) ? true : false;
    }
    
    public static function verify_file_type($allowed,$FILE,$param_name){
        $filename = $FILE['name'];
        $allowed= array_map('strtolower',$allowed); // To avoid case sensitive
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!in_array($ext,$allowed) ) {
            return false;
        }
        return true;
    }
    
    public static function user_email($value){
        return self::email($value);  
    }
    
    public static function date($value){
        if(empty($value))
            return true;
        
        $timestamp= strtotime($value);
        if(empty($timestamp))
            return false;
        return true;
        
    }
    
    public static function maxDate($value, $maxDate) {
        $timestamp= strtotime($value);
        if(empty($timestamp))
            return true;
        
        $maxTimestamp= strtotime($maxDate);
        if(empty($maxTimestamp))
            return true;
        
        return $timestamp>$maxTimestamp ? false : true;
    }
    
    public static function minDate($value, $minDate) {
        
        $timestamp= strtotime($value);
        if(empty($timestamp))
            return true;
        
        $minTimestamp= strtotime($minDate);
        if(empty($minTimestamp))
            return true;

        return $timestamp<$minTimestamp ? false : true;
    }
    
    public static function is_unique($value,$name,$form_id,$submission_id=0){
        return erforms()->submission->is_unique_value($value,$name,$form_id,$submission_id);
    }
    
    public static function number($value){
        return is_numeric($value);
    }
}

?>
