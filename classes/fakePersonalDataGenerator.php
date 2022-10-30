<?php

  


class PersonalData {

  public string $bulk;
  public string $bulkNumber;
  private string $cpr;    
  private string $firstName;
  private string $lastName;
  private string $gender;
  private string $date;
  private string $adress;
  private string $phoneNumber;
  
  private const DOORS = ["th", "mf", "tv"];
  private const SINGLEDIGITS = [2, 30, 31, 40, 41, 42, 50, 51, 52, 53, 60, 61, 71, 81, 91, 92, 93, 342, 
                          359, 362, 389, 398, 431, 441, 462, 466,  468,  472,  474,  476,  478, 545,  
                          556, 577, 579, 584,  589,  627, 629, 641, 649, 658, 667, 697, 829];
  private const INTERVALDIGITS = [["from"=>344,"to"=>349], ["from"=>356,"to"=>357], ["from"=>365,"to"=>366], 
                          ["from"=>485,"to"=>486],  ["from"=>488,"to"=>489],  ["from"=>493,"to"=>496],  ["from"=>498,"to"=>499],  
                          ["from"=>542,"to"=>543], ["from"=>551,"to"=>552],  ["from"=>571,"to"=>574], ["from"=>586,"to"=>587], 
                          ["from"=>597,"to"=>598],  ["from"=>662,"to"=>665],  ["from"=>692,"to"=>694], ["from"=>771,"to"=>772], 
                          ["from"=>782,"to"=>783], ["from"=>785,"to"=>786], ["from"=>788,"to"=>789], ["from"=>826,"to"=>827]];

  // RANGE -- range(1,5)
  
  public function setCpr($temp = "true") {
    //generate 10 random digits 
    if ($temp == "true") {
      $tempGender = $this->generateNumber(['iteration'=>1, 'from'=>0 , 'to'=>1 ]);
      if ($tempGender == 0){
        $this->gender = "female";
      }else {
        $this->gender = "male";
      }
    }
    $randomTime = mt_rand(1, time());
    $randomTime = date("dmY ", $randomTime);
    $randomTime = strval($randomTime);
    $randomTime = substr($randomTime, 0 , 4) . substr($randomTime, 6 , 8) ;
    $cpr = $this->matchGender(['randomTime'=>$randomTime]);
    $this->cpr = $cpr;
    
  }


  public function setFullNameGender() {
    //fetch full name and gender form person-name.json
    $personInfo = $this->fetchFullNameAndGender();
    $this->firstName = $personInfo["name"] ;
    $this->lastName = $personInfo["surname"] ;
    $this->gender = $personInfo["gender"] ; 
    return true;
  }



  public function setFullNameGenderDate() {
    $this->setFullNameGender();
    $randomTime = mt_rand(1, time());
    $randomTime = date("d-m-Y ", $randomTime);
    if (preg_match('/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/', $randomTime)) {
      return false;
    } else {
        $this->date = $randomTime;
        return true;
    };
    //fetch full name and gender form person-name.json and generate a random date
  }


  public function setCprFullNameGender() {
    $this->setFullNameGender();
    $this->setCpr('false');
    return true;
    //fetch full name and gender form person-name.json generate a CPR that match gender
  }


  public function setCprFullNameGenderDate() {
    //fetch full name and gender form person-name.json generate a CPR that match gender and bith date
    $this->setFullNameGender();
    
    $randomTime = mt_rand(1, time());
    $date = $randomTime;
    $date = date("d-m-Y ", $date);
    $this->date = $date;
    $randomTime = date("dmY ", $randomTime);
    $randomTime = strval($randomTime);
    $randomTime = substr($randomTime, 0 , 4) . substr($randomTime, 6 , 8) ;
    $cpr = $this->matchGender(['randomTime'=>$randomTime]);
    if (preg_match('/^(0[1-9]|[12]\d|3[01])(0[1-9]|1[0-2])\d{2}[-]?\d{4}$/', $cpr) && preg_match('/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/', $date)) {
      return false;
    } else {
        $this->date = $date;
        $this->cpr = $cpr;
        return true;
    };
  }


  public function setAdress() {
    //generate random string  + random number + randomize if in florr and generate random number from 1 to 99 + randomize  and generate random number from 1 to 99 
    //+ randomize door side "th", "mf", "tv" and generate number from  1 to 50 + fetch from postal-code.json a postal code and town
    
    $streetLen = $this->generateNumber(['iteration'=>1, 'from'=>10 , 'to'=>20]);
    $street = $this->generateRandomString(['srtLength'=>$streetLen]);
    $streetNumber = $this->generateNumber(['iteration'=>1, 'from'=>1 , 'to'=>999]);
    $floorNumber = $this->generateNumber(['iteration'=>1, 'from'=>0 , 'to'=>99]);
    if ($floorNumber == 0 ) {
      $floorNumber = "st";
    }
    $door = $this->generateNumber(['iteration'=>1, 'from'=>1 , 'to'=>50]) . self::DOORS[array_rand( self::DOORS , 1)] ;

    $postalInfos = $this->fetchPostalCode();
    $postalCode = $postalInfos["postal_code"];
    $town = $postalInfos["town_name"];

    $this->adress = "<b>Street:</b> $street  $streetNumber  <b>Floor n:</b> $floorNumber , $door <br> <b>Postal code:</b> $postalCode , $town  ";
  }


  public function setPhoneNumber() {
    //generate 8 numbers and apend them to one of the digits from the DIGITS array 
    $chose = $this->generateNumber(['iteration'=>1, 'from'=>1 , 'to'=>2]) ;
    if ($chose == 1){
      $starterDigits =  self::SINGLEDIGITS[array_rand( self::SINGLEDIGITS , 1)] ;
      $missingDigist = 8 - strlen(strval($starterDigits));
      $phoneNumber =" $starterDigits - ". $this->generateNumber(['iteration'=>$missingDigist, 'from'=>0 , 'to'=>9]) ;
      $this->phoneNumber = $phoneNumber ;
    }else{
      $starterDigits =  self::INTERVALDIGITS[array_rand( self::INTERVALDIGITS , 1)] ;
      $starterDigits =  $this->generateNumber(['iteration'=>1, 'from'=>$starterDigits["from"] , 'to'=>$starterDigits["to"]]);
      $phoneNumber =" $starterDigits - ". $this->generateNumber(['iteration'=>5, 'from'=>0 , 'to'=>9]) ;
      $this->phoneNumber = $phoneNumber ;
    }

  }

  public function setOneFullInformations(){
    $this->setCprFullNameGenderDate();
    $this->setAdress();
    $this->setPhoneNumber();
    
  }
  

  public function getCpr()                           {    return (isset($this->cpr) ? $this->cpr : 'yes'); }
  public function getFullNameGender()                {  isset($this->firstName) ? $this->firstName : 'yes'; isset($this->lastName) ? $this->lastName : 'yes';  isset($this->gender) ? $this->gender : 'yes';
    return <<<PERSON
    <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
    <b>Gender</b>: {$this->gender} <br>
    PERSON;}
  
  public function getFullNameGenderDate()            {  isset($this->firstName) ? $this->firstName : 'yes'; isset($this->lastName) ? $this->lastName : 'yes';  isset($this->gender) ? $this->gender : 'yes'; isset($this->date) ? $this->date : 'yes';
    return <<<PERSON
    <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
    <b>Gender</b>: {$this->gender} <br>
    <b>Date</b>: {$this->date} <br>
    PERSON;}
  
  public function getCprFullNameGender()             {  isset($this->firstName) ? $this->firstName : 'yes'; isset($this->lastName) ? $this->lastName : 'yes';  isset($this->gender) ? $this->gender : 'yes'; isset($this->cpr) ? $this->cpr : 'yes';
    return <<<PERSON
    <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
    <b>Gender</b>: {$this->gender} <br>
    <b>Cpr</b>: {$this->cpr} <br>
    PERSON;}
  
  public function getCprFullNameGenderDate()         {  isset($this->firstName) ? $this->firstName : 'yes'; isset($this->lastName) ? $this->lastName : 'yes';  isset($this->gender) ? $this->gender : 'yes'; isset($this->cpr) ? $this->cpr : 'yes';  isset($this->date) ? $this->date : 'yes';
    return <<<PERSON
    <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
    <b>Gender</b>: {$this->gender} <br>
    <b>Date</b>: {$this->date} <br>
    <b>Cpr</b>: {$this->cpr} <br>
    PERSON;}
    
  public function getAdress()                        {    return (isset($this->adress) ? $this->adress : 'yes'); }
  public function getPhoneNumber()                   {    return (isset($this->phoneNumber) ? $this->phoneNumber : 'yes'); }
  public function getOneFullInformations()                     {  isset($this->firstName) ? $this->firstName : 'yes'; isset($this->lastName) ? $this->lastName : 'yes';  isset($this->gender) ? $this->gender : 'yes'; isset($this->cpr) ? $this->cpr : 'yes';  isset($this->date) ? $this->date : 'yes'; isset($this->adress) ? $this->adress : 'yes'; isset($this->phoneNumber) ? $this->phoneNumber : 'yes'; 
    return <<<PERSON
    <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
    <b>Gender</b>: {$this->gender} <br>
    <b>Date</b>: {$this->date} <br>
    <b>Cpr</b>: {$this->cpr} <br>
    <b>Adress</b>: {$this->adress} <br>
    <b>Phone Number</b>: {$this->phoneNumber} <br>
    PERSON;}
  
  
  
  

  
  
  
  
  
  
  
  
  public function bulkFullInformations($args=[]){
    $bulkInfos = '';
    for ($i = 0; $i < $args['infoNumber']; $i++) {
            $this->setOneFullInformations();
              $bulkInfos .= <<<PERSON
              <b>Full name</b>: {$this->firstName} {$this->lastName}<br>
              <b>Gender</b>: {$this->gender} <br>
              <b>Date</b>: {$this->date} <br>
              <b>Cpr</b>: {$this->cpr} <br>
              <b>Adress</b>: {$this->adress} <br>
              <b>Phone Number</b>: {$this->phoneNumber} <br>
              <br>
              PERSON;
        }
        return $bulkInfos;
        
    
  }

  //PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE   PRIVATE




  private function generateNumber($args=[]) {
    //generate as much numbers as iteration and return
    $iteration = $args['iteration'];
    $from = $args['from'] ?? 0;
    $to = $args['to'] ?? 10;
    $number = [];
    for ($iteration; $iteration >= 1 ; $iteration--) {
      $number[] = rand($from,$to);
    } 
    $number = implode("", $number);
    return $number;
    
  }

  private function fetchFullNameAndGender (){
    $preson_names_json = file_get_contents("data/person-names.json");
    $person_names = json_decode($preson_names_json, true);
    $persons = $person_names['persons'];  
    $randomPerson = $this->generateNumber(['iteration'=>1, 'from'=>0 , 'to'=>count($persons) - 1 ]);
    $PERSONS = $persons[$randomPerson];
    return $PERSONS;
    //fetch from person-names.json a person
  }
  private function fetchPostalCode(){
    $postalCodesFile = file_get_contents("data/postal-codes.json");
    $postalCodes = json_decode($postalCodesFile, true);
    $randomPostalSelect = $this->generateNumber(['iteration'=>1, 'from'=>0 , 'to'=>count($postalCodes) - 1 ]);
    $randomPostalCode = $postalCodes[$randomPostalSelect];
    return $randomPostalCode;
    //fetch from postal-codes a location
  }

  private function matchGender($args=[]){
    //return an enven or odd number depending on gender passed even for male and odd for women
    $randomTime = $args['randomTime'];
    $randomValue = mt_rand(0,9);

    $even = $randomValue & ~1;
    $odd = $randomValue | 1;

  


    $randomTime = $randomTime . $this->generateNumber(['iteration'=>3 , 'from'=>0 , 'to'=>9]) ;
    

    if ($this->gender === "female") {
      return $randomTime = $randomTime . $even;
    } else {
      return $randomTime = $randomTime . $odd;

    }
  }
  private function generateRandomString($args=[]){
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $args['srtLength']; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}