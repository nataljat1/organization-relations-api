<?php
class Organization {
    private $conn;
    private $table = 'organizations';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name) {
        $query = "INSERT INTO $this->table (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
		return $this->conn->lastInsertId();
    }

    public function getByName($name) {
        $query = "SELECT * FROM $this->table WHERE LOWER(name) = LOWER(:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
	
	public function checkCreate($name) {
		$id = null;
		$existing = $this->getByName($name);
		
		if(is_array($existing) && count($existing) > 0) {
			return $existing['id'];
		} else {
			return $this->create($name);
		}
    }
}
?>