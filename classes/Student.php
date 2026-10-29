<?php


require_once __DIR__ . '/Person.php';

class Student extends Person
{
    // Additional properties specific to Student
    public $course;
    public $address;
    public $enrolmentDate;
    public $gender;
    
  
    public function __construct($name, $email, $phone, $course, $address, $enrolmentDate, $gender)
    {
        // Call parent constructor
        parent::__construct($name, $email, $phone);
        
        $this->course = $course;
        $this->address = $address;
        $this->enrolmentDate = $enrolmentDate;
        $this->gender = $gender;
    }
    
 
    public function getDetails()
    {
        // Call parent method and extend with student info
        $parentDetails = parent::getDetails();
        return $parentDetails . ", Course: " . $this->course . ", Enrolment Date: " . $this->enrolmentDate;
    }
    
   
    public function getPhoneNumber()
    {
        return $this->getPhone(); // Accessing protected method from parent
    }
    
   
    public function toArray()
    {
        return [
            'name'         => $this->name,
            'email'        => $this->getEmail(),
            'phone'        => $this->getPhoneNumber(),
            'course'       => $this->course,
            'address'      => $this->address,
            'enrolment_date' => $this->enrolmentDate,
            'gender'       => $this->gender
        ];
    }
}