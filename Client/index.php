<?php
require_once __DIR__ . '/Controller/DashboardController.php';
require_once __DIR__ . '/Controller/ProposalsController.php';
require_once __DIR__ . '/Controller/SettingsController.php';
require_once __DIR__ . '/Controller/LogoutController.php';

$request = $_SERVER['REQUEST_URI'];
$request = explode('?', $request)[0];

switch ($request) {
    case '/DesignConnect/client/':
    case '/DesignConnect/Client/':
    case '/DesignConnect/client/Dashboard/':
    case '/DesignConnect/Client/Dashboard/':
        $controller = new DashboardController();
        $controller->displayDashboard();
        break;
    
    case '/DesignConnect/Client/viewProposals/':
    case '/DesignConnect/client/viewProposals/':
        $controller = new ProposalsController();
        $controller->displayProposals();
        break;
    case '/DesignConnect/Client/Settings/':
    case '/DesignConnect/client/Settings/':
        $controller = new SettingsController();
        $controller->displaySettings();
        break;
    case '/DesignConnect/Client/Logout/':
    case '/DesignConnect/client/Logout/':
        $controller= new LogoutController();
        $controller->handleLogout();
    break;
    
    default:
        $controller = new DashboardController();
        $controller->displayDashboard();
        break;
}
?>