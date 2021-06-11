<?php



class Storage extends Model{
    // public function __contsruct(string $user_id){
    //     parent::__construct();
    // }
    public function getUser(string $user_id){
        // if($user_id == null)
        //     $user_id = $this->user_id;
        return $this->db->query("SELECT * FROM users WHERE user_id = :user_id", ["user_id" => $user_id], 0) ?? false;
    }

    public function getRole(string $user_id){
        // if($user_id == null)
        //     $user_id = $this->user_id;
        return $this->db->query("SELECT role FROM users WHERE user_id = :user_id", ["user_id" => $user_id], 0)['role'] ?? false;
    }

    public function existUser($user_id){
        $in_user = $this->db->query("SELECT * FROM users WHERE user_id = :user_id", ["user_id" => $user_id], 0) ? true : false;
        if(($this->getNameTeacher($user_id) || $this->getGroup($user_id)) && $in_user)
            return true;
        else
            return false;
    }

    
    public function getInstituts(){
        return $this->db->query("SELECT * FROM edu_institut");
    }

    public function getRoles(){
        return $this->db->query("SELECT * FROM roles");
    }

    public function setNameTeacher(string $user_id = null, string $name){
        $this->db->query("INSERT INTO teachers (user_id, name) VALUES (:user_id, :name)", ["user_id" => $user_id, "name" => $name]);
    }
    public function getNameTeacher(string $user_id = null){
        // if($user_id == null)
        //     $user_id = $this->user_id;
        $teacher_name = $this->db->query("SELECT name FROM teachers WHERE user_id = :user_id", ["user_id" => $user_id], 0);
        return $teacher_name['name'] ?? false;
    }
    public function updateNameTeacher(string $user_id = null, string $name){
        $this->db->query("UPDATE teachers SET name = :name WHERE user_id = :user_id", ["user_id" => $user_id, "name" => $name]);
    }

    public function setGroup(string $user_id = null, string $group){
        $this->db->query("INSERT INTO students (user_id, group_name) VALUES (:user_id, :group)", ["user_id" => $user_id, "group" => $group]);
    }

    public function getGroup(string $user_id = null){
        // if($user_id == null)
        //     $user_id = $this->user_id;
        return $this->db->query("SELECT group_name FROM students WHERE user_id = :user_id", ["user_id" => $user_id], 0)['group_name'] ?? false;
    }

    public function updateGroup(string $user_id = null, string $group){
        $this->db->query("UPDATE students SET name = :name WHERE user_id = :user_id", ["user_id" => $user_id, "group" => $group]);
    }

    public function addUser(string $user_id, int $role, int $edu = 1){
        $exist_user = $this->getUser($user_id);
        if(!$exist_user){
            $added = $this->db->query("INSERT INTO users (user_id, edu, role) VALUES (:user_id, :edu, :role)",
                ["user_id" => $user_id, "edu" => $edu, "role" => $role]
            );
            // if($added){
            //     $this->user_id = $user_id;
            //     $this->role = $role;
            //     $this->edu = $edu;
            // }
            return $added;
        }
    }

}