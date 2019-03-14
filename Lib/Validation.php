<?php
class  Validation {
    

    public static function isValidEmail($email){

        if(empty($email)){
            return false;
        }


        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }



    public static function isValidPassword($password){
        if(empty($password)){
            return false;
        }


        if(strlen($password)<=5){
            return false;
        }


    
        return true;
    }


    public static function isValidPhone($phone){
        if(strlen($phone) <= 9) {
            return false;
            //Phone is 10 characters in length (###) ###-####
        }

        $isvalid = preg_replace('/[^0-9]/', '', $phone);

    
        return $isvalid;
    }



}