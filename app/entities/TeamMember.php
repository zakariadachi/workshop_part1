<?php

    abstract class TeamMember
    {
        protected int $id;
        protected string $username;
        protected string $email;
        protected string $password;
        protected DateTime $createdAt;

        public function __construct($id,$username,$email,$createdAt)
        {
            $this->id=$id;
            $this->username=$username;
            $this->email=$email;
            $this->createdAt=new DateTime();
        }

        abstract function canCreateProject():bool;
        abstract function canAssignTask():bool;
        abstract function getRolePermission();

        public function verifyPassword(string $password):bool
        {
            if (password_verify($password , $this->password)) {
            return true;
            } 
            else return false;
            
        }

        public function setPassword(string $password):void
        {
            $this->password=password_hash($password,PASSWORD_DEFAULT);
        }
        
    }



?>