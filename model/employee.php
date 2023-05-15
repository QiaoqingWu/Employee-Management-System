<!-- 
	Student Name: Qiaoqing Wu
	Due Date: 2023-04-10
	Section: CST8285 Lab section 313
	Description: an entity model php file provide all 
	getters and setters methods as well as constructor(s).
-->
<?php
	class Employee{		

		private $id;
		private $name;
		private $birthdate;
		private $address;
		private $salary;
		private $image;
				
		function __construct($id, $name, $birthdate, $address, $salary, $image){
			$this->setId($id);
			$this->setName($name);
			$this->setBirthdate($birthdate);
			$this->setAddress($address);
			$this->setSalary($salary);
			$this->setImage($image);
			}		
		
		public function getName(){
			return $this->name;
		}
		
		public function setName($name){
			$this->name = $name;
		}
		
		public function getBirthdate() {
			return $this->birthdate;
		}

		public function setBirthdate($birthdate) {
			$this->birthdate = $birthdate;
		}

		public function getAddress(){
			return $this->address;
		}
		
		public function setAddress($address){
			$this->address = $address;
		}

		public function getsalary(){
			return $this->salary;
		}

		public function setSalary($salary){
			$this->salary = $salary;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

		public function setImage($image) {
			$this->image = $image;
		}
	
		public function getImage() {
			return $this->image;
		}
	}
?>