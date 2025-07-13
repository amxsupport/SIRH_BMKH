<?php
// Start output buffering early
ob_start();

// Define security constant for configuration files
define('SIRH_BMKH', true);

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configure error logging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Start session
session_start();

// Database connection test
try {
    $db_config = require_once 'config/database.php';
    $testDb = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8",
        $db_config['username'],
        $db_config['password']
    );
    error_log("Initial database connection test successful");
} catch (PDOException $e) {
    error_log("Initial database connection test failed: " . $e->getMessage());
}

// Autoload classes
spl_autoload_register(function ($class_name) {
    if (file_exists('controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
    } elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
    }
});

// Initialize controllers
// Create a shared database connection
try {
    // Load database config once since it's already been loaded
    global $db_config;
    if (!isset($db_config)) {
        $db_config = require_once 'config/database.php';
    }
    $db = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8",
        $db_config['username'],
        $db_config['password']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check the error logs.");
}

// Initialize controllers with the shared database connection
$employeeController = new EmployeeController();
$internController = new InternController();
$leaveController = new LeaveController();
$userController = new UserController($db);

// Router
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Helper function to extract common globals
function extractGlobals($keys) {
    $data = [];
    foreach ($GLOBALS as $key => $value) {
        if (in_array($key, $keys)) {
            $data[$key] = $value;
        }
    }
    return $data;
}

try {
    // Start output buffering
    ob_start();

    // Route based on controller type
    if ($controller === 'leave') {
        // Leave management routes
        switch ($action) {
            case 'index':
                $leaveController->index();
                extract(extractGlobals(['leaves', 'employees']));
                $content = 'views/leaves/index.php';
                break;

            case 'approve':
                $leaveController->approve();
                extract(extractGlobals(['leaves']));
                $content = 'views/leaves/approve.php';
                break;

            case 'create':
                $leaveController->create();
                extract(extractGlobals(['employees']));
                $content = 'views/leaves/create.php';
                break;

            case 'edit':
                if (!$id) throw new Exception("ID du congé non spécifié");
                $leaveController->edit($id);
                extract(extractGlobals(['leave']));
                $content = 'views/leaves/edit.php';
                break;

            case 'view':
                if (!$id) throw new Exception("ID du congé non spécifié");
                $leaveController->view($id);
                extract(extractGlobals(['leave']));
                $content = 'views/leaves/view.php';
                break;

            case 'delete':
                if (!$id) throw new Exception("ID du congé non spécifié");
                $leaveController->delete($id);
                break;

            case 'updateStatus':
                $leaveController->updateStatus();
                break;

            case 'printDecision':
                if (!$id) throw new Exception("ID du congé non spécifié");
                $leaveController->printDecision($id);
                break;

            case 'balance':
                $leaveController->balance();
                extract(extractGlobals(['employees', 'leaveTypes', 'balances']));
                $content = 'views/leaves/balance.php';
                break;

            case 'getRemainingDays':
                $leaveController->getRemainingDays();
                break;

            case 'statistics':
                $leaveController->statistics();
                break;

            default:
                throw new Exception("Action non valide pour le contrôleur de congés");
        }
    } elseif ($controller === 'user') {
    switch ($action) {
        case 'login':
            $userController->login(); // This will handle everything including layout
            break;
            
        case 'logout':
            $userController->logout();
            break;
            
        case 'register':
            $userController->register();
            $content = 'views/users/register.php';
            break;
            
        case 'index':
            $userController->index();
            extract(extractGlobals(['users']));
            $content = 'views/users/index.php';
            break;
            
        case 'edit':
            if (!$id) throw new Exception("ID de l'utilisateur non spécifié");
            $userController->edit($id);
            extract(extractGlobals(['user']));
            $content = 'views/users/register.php';
            break;
            
        case 'toggleStatus':
            if (!$id) throw new Exception("ID de l'utilisateur non spécifié");
            $userController->toggleStatus($id);
            break;
            
        default:
            throw new Exception("Action non valide pour le contrôleur utilisateur");
    }
} elseif (in_array($action, ['internDashboard', 'interns', 'createIntern', 'viewIntern', 'updateIntern', 'deleteIntern', 'internAttestation', 'exportInternList', 'internAuthorization', 'internCertificate'])) {
        switch ($action) {
            case 'internDashboard':
                $internController->dashboard();
                $view_data = extractGlobals(['interns', 'stats']);
                extract($view_data);
                $content = 'views/interns/dashboard.php';
                break;

            case 'interns':
                $internController->index();
                $view_data = extractGlobals(['interns']);
                extract($view_data);
                $content = 'views/interns/index.php';
                break;

            case 'createIntern':
                $internController->create();
                $view_data = extractGlobals(['superviseurs']);
                extract($view_data);
                $content = 'views/interns/create.php';
                break;

            case 'viewIntern':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->view($id);
                $view_data = extractGlobals(['intern']);
                extract($view_data);
                $content = 'views/interns/view.php';
                break;

            case 'updateIntern':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->update($id);
                $view_data = extractGlobals(['intern', 'superviseurs']);
                extract($view_data);
                $content = 'views/interns/edit.php';
                break;

            case 'deleteIntern':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->delete($id);
                break;

            case 'internAttestation':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->attestation($id);
                break;

            case 'exportInternList':
                $format = isset($_GET['format']) ? $_GET['format'] : 'xlsx';
                $internController->exportInternList($format);
                break;

            case 'internAuthorization':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->authorization($id);
                break;

            case 'internCertificate':
                if (!$id) throw new Exception("ID du stagiaire non spécifié");
                $internController->certificate($id);
                break;
        }
    } else {
        // Default to employee management routes
        switch ($action) {
        case 'dashboard':
            $view_data = [];
            ob_start();
            $employeeController->dashboard();
            // Extract controller variables into view data
            if (isset($GLOBALS['employees'])) $view_data['employees'] = $GLOBALS['employees'];
            if (isset($GLOBALS['stats'])) $view_data['stats'] = $GLOBALS['stats'];
            if (isset($GLOBALS['pendingNotifications'])) $view_data['pendingNotifications'] = $GLOBALS['pendingNotifications'];
            extract($view_data);
            $content = 'views/employees/dashboard.php';
            break;

        case 'index':
            $view_data = [];
            ob_start();
            $employeeController->index();
            // Extract controller variables into view data
            if (isset($GLOBALS['employees'])) {
                $view_data['employees'] = $GLOBALS['employees'];
            }
            if (isset($GLOBALS['pendingNotifications'])) {
                $view_data['pendingNotifications'] = $GLOBALS['pendingNotifications'];
            }
            extract($view_data);
            $content = 'views/employees/index.php';
            break;

        case 'create':
            $view_data = [];
            ob_start();
            $employeeController->create();
            if (isset($GLOBALS['pendingNotifications'])) {
                $view_data['pendingNotifications'] = $GLOBALS['pendingNotifications'];
            }
            extract($view_data);
            $content = 'views/employees/create.php';
            break;

        case 'view':
            if (!$id) {
                throw new Exception("ID de l'employé non spécifié");
            }
            $view_data = [];
            ob_start();
            $employeeController->view($id);
            foreach ($GLOBALS as $key => $value) {
                if (in_array($key, ['employee', 'pendingNotifications'])) {
                    $view_data[$key] = $value;
                }
            }
            extract($view_data);
            $content = 'views/employees/view.php';
            break;

        case 'update':
            if (!$id) {
                throw new Exception("ID de l'employé non spécifié");
            }
            $view_data = [];
            ob_start();
            $employeeController->update($id);
            foreach ($GLOBALS as $key => $value) {
                if (in_array($key, ['employee', 'pendingNotifications'])) {
                    $view_data[$key] = $value;
                }
            }
            extract($view_data);
            $content = 'views/employees/edit.php';
            break;

        case 'delete':
            if (!$id) {
                throw new Exception("ID de l'employé non spécifié");
            }
            $employeeController->delete($id);
            break;

        case 'statistics':
            $view_data = [];
            ob_start();
            $employeeController->statistics();
            foreach ($GLOBALS as $key => $value) {
                if (in_array($key, ['stats', 'pendingNotifications'])) {
                    $view_data[$key] = $value;
                }
            }
            extract($view_data);
            $content = 'views/employees/statistics.php';
            break;

        case 'checkRetirements':
            $view_data = [];
            ob_start();
            $employeeController->checkRetirements();
            foreach ($GLOBALS as $key => $value) {
                if (in_array($key, ['notifications', 'pendingNotifications'])) {
                    $view_data[$key] = $value;
                }
            }
            extract($view_data);
            $content = 'views/employees/retirement_notifications.php';
            break;

        case 'markNotificationAsRead':
            if (!$id) {
                throw new Exception("ID de l'employé non spécifié");
            }
            $employeeController->markNotificationAsRead($id);
            break;

        case 'exportToExcel':
            $employeeController->exportEmployeeList();
            break;

        case 'attestation':
            if (!$id) {
                throw new Exception("ID de l'employé non spécifié");
            }
            $view_data = [];
            ob_start();
            $employeeController->attestation($id);
            foreach ($GLOBALS as $key => $value) {
                if (in_array($key, ['employee', 'pendingNotifications'])) {
                    $view_data[$key] = $value;
                }
            }
            extract($view_data);
            $content = 'views/employees/attestation.php';
            break;

        case 'reports':
            $view_data = [];
            ob_start();
            $employeeController->reports();
            if (isset($GLOBALS['pendingNotifications'])) {
                $view_data['pendingNotifications'] = $GLOBALS['pendingNotifications'];
            }
            extract($view_data);
            $content = 'views/employees/reports.php';
            break;

        case 'exportEmployeeList':
            $format = isset($_GET['format']) ? $_GET['format'] : 'xlsx';
            $employeeController->exportEmployeeList($format);
            break;

        case 'exportStats':
            $format = isset($_GET['format']) ? $_GET['format'] : 'xlsx';
            $employeeController->exportStats($format);
            break;

        case 'exportCustomReport':
            $employeeController->exportCustomReport();
            break;

        case 'getAgeDistribution':
            $corps = isset($_GET['corps']) ? $_GET['corps'] : 'all';
            $employeeController->getFilteredAgeDistribution($corps);
            break;

        case 'test':
            $content = 'views/employees/test.php';
            break;

        default:
            throw new Exception("Action non valide");
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    $content = 'views/error.php';
}

// Only include the main layout if we're not on the login page (login has its own layout)
if (!($controller === 'user' && $action === 'login')) {
    require_once 'views/layout.php';
}

ob_end_flush();

// Clear sensitive data
unset($db_config);
?>
