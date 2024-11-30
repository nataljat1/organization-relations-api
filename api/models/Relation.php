<?php
class Relation {
    private $conn;
    private $table = 'relations';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($parent_id, $child_id) {
        $query = "INSERT INTO $this->table (parent_id, child_id) VALUES (:parent_id, :child_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':parent_id', $parent_id);
        $stmt->bindParam(':child_id', $child_id);
        return $stmt->execute();
    }

    public function getRelations($org_id, $page = 1, $pageSize = 100) {
    $offset = ($page - 1) * $pageSize;
        $query = "
            SELECT o.name, 'parent' AS relation FROM relations r
            JOIN organizations o ON r.parent_id = o.id WHERE r.child_id =:org_id
			UNION
            SELECT o.name, 'sister' AS relation FROM relations r
            JOIN organizations o ON r.child_id = o.id WHERE r.parent_id 
            IN (SELECT r.parent_id FROM relations r WHERE r.child_id = :org_id)
            AND r.child_id != :org_id
            UNION 
            SELECT o.name, 'child' AS relation FROM relations r
            JOIN organizations o ON r.child_id = o.id WHERE r.parent_id = :org_id
			
			ORDER BY name
            LIMIT :page OFFSET :offset
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':org_id', $org_id, PDO::PARAM_INT);
		$stmt->bindParam(':page', $pageSize, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public function checkCreate($parent_id, $child_id)  {
		$id = null;
		
		$query = "SELECT * FROM $this->table WHERE parent_id = :parent_id AND child_id = :child_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
		$stmt->bindParam(':child_id', $child_id, PDO::PARAM_INT);
        $stmt->execute();
		
		$existing = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if(is_array($existing) && count($existing) > 0) {
			return $existing['parent_id'];
		} else {
			return $this->create($parent_id, $child_id);
		}
    }
}
?>