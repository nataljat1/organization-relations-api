<?php
include_once PROJECT_ROOT_PATH . '/models/Relation.php';
include_once PROJECT_ROOT_PATH . '/models/Organization.php';

class RelationController {
    private $relation;
	private $organization;

    public function __construct($db) {
        $this->relation = new Relation($db);
		$this->organization = new Organization($db);
    }

    public function addRelations($relations) {
        foreach ($relations as $relation) {
			if (is_array($relation)) {
				foreach ($relation as $subrelation) {
					$this->subrelation->create($relation['parent_id'], $relation['child_id']);
				}
			} else {
				$this->relation->create($relation['parent_id'], $relation['child_id']);
			}
        }
        return ['status' => 'success', 'message' => 'Relations added successfully'];
    }

    public function getRelations($org_name, $page = 1, $pageSize = 100) {
		$org = $this->organization->getByName($org_name);
        $relations = $this->relation->getRelations($org['id'], $page, $pageSize);
        return ['status' => 'success', 'relations' => $relations];
    }
}
?>