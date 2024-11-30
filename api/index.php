<?php
define('PROJECT_ROOT_PATH', __DIR__);

include_once './db.php';
include_once './controllers/OrganizationController.php';
include_once './controllers/RelationController.php';

header("Content-Type: application/json");

$db = (new Database())->connect();
$organizationController = new OrganizationController($db);
$relationController = new RelationController($db);

$request_method = $_SERVER["REQUEST_METHOD"];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

//https:/uri[0] (in my case root folder)/uri[1] (in my case it is index.php) / $uri[2] (in my case it is 'organizations' or 'relations')

switch ($uri[2]) {
    case 'organizations':
        if ($request_method === 'POST') {
			$data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($organizationController->addOrganizations($data));
        }
        break;

    case 'relations':
        if ($request_method === 'GET' && isset($_GET['org_name'])) {
			if (isset($_GET['page']) && isset($_GET['page_size'])) {
				$page = intval($_GET['page']);
				$page_size = intval($_GET['page_size']);
				if ($page < 1) {
					$page = 1;
				} 
				if ($page_size < 1 || $page_size > 100) {
					$page_size = 100;
				}
				echo json_encode($relationController->getRelations($_GET['org_name'], $page, $page_size));
			} else {
				echo json_encode($relationController->getRelations($_GET['org_name']));
			}
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid endpoint']);
}
?>