<?php


class Person
{
    
    
    public $name;
    
   
    private $email;
    
    protected $phone;
    

    public function __construct($name, $email, $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
 
    public function getName()
    {
        return $this->name;
    }
    
    
    public function getEmail()
    {
        return $this->email;
    }
    
    protected function getPhone()
    {
        return $this->phone;
    }
    
  
    public function getDetails()
    {
        return "Name: " . $this->name . ", Email: " . $this->email;
    }
    
    
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
            return true;
        }
        return false;
    }
}