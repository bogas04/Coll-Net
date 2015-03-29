<?php

    class C_User
{
     $document = array(
                $usr_name = “”,
                $usr_dob = “” ,
                $usr_gender = “”,
                $usr_email = “”
                $usr_username = “”,
                $usr_password = “”,
                $usr_cpassword = “”,
                $usr_phone_no =””,
                $usr_address => array($usr_location =””,
                                    $usr_city =””,
                                    $usr_country =”” 
                             );

    
    function __construct() {
                $argv = func_get_args();
                if( func_num_args() == 2)
			self::__construct2( $argv[0], $argv[1] );
            	else
                        self::__construct1($argv);
    }

    function __construct1($argv) {
            $document = $argv;
        	$user = new M_User();
    }
 
    function __construct2($arg1, $arg2) {
            $document = array(
			  $usr_username = $arg1,
                    	  $usr_password = $arg2
			);
            if($this->c_check_existence($arg1, $arg2))
                //login successfull
            else
                //throw exception

    }

    
    public function c_check_existence($arg1, $arg2){
    	     $user = new M_User();
             return $user->m_check_existence($arg1, $arg2);
    }

    function c_create_user($document)
    {
             return $user->m_create_user($document);
    }

    function c_retrieve_user($document)
    {
              return $user->m_retrieve_user($document);
    }

    function c_update_user($document)
    {
              return $user->m_update_user($document); 
    }

    function c_delete_user($document)
    {
	      return $user->m_delete_user($document->$username);
    }

}
?>
