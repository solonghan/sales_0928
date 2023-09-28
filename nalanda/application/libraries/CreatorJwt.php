<?php 
//application/libraries/CreatorJwt.php
    require APPPATH . '/libraries/JWT.php';

    class CreatorJwt
    {
        //自訂key值
        private $key = "jwtkey"; 

        /*************This function generate token private key**************/ 

        
        public function GenerateToken($data)
        {          
            $jwt = JWT::encode($data, $this->key);
            return $jwt;
        }
        

       /*************This function DecodeToken token **************/

        public function DecodeToken($token)
        {          
            
            $decoded = JWT::decode($token, $this->key, array('HS256'));

            
            $decodedData = (array) $decoded;
            return $decodedData;
        }
    }