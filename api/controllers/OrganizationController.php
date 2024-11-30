<?php
include_once PROJECT_ROOT_PATH . '/models/Organization.php';
include_once PROJECT_ROOT_PATH . '/models/Relation.php';

class OrganizationController {
    private $organization;
	private $relation;

    public function __construct($db) {
        $this->organization = new Organization($db);
		$this->relation = new Relation($db);
    }

    public function addOrganizations($data, $parent_id = null) {
		foreach ($data as $key => $value) {
			if (strtolower($key) == 'org_name') {
				$record_id = $this->organization->checkCreate($value);
				if ($parent_id == null) {
					$parent_id = $record_id;
				} else {
					$this->relation->checkCreate($parent_id, $record_id);
					$parent_id = $record_id;
				}
			}
			
			if (strtolower($key) == 'daughters') {
				if (is_array($value)) {
					foreach ($value as $line) {
						$this->addOrganizations($line, $parent_id);
					}
				}
			}
			
		}
        return ['status' => 'success', 'message' => 'Organizations added successfully'];
    }
}
?>