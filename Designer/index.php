<?php
require_once __DIR__ . '/../session_config.php';
require_once __DIR__ . '/Controller/DashboardController.php';
require_once __DIR__ . '/Controller/PortfolioController.php';
require_once __DIR__ . '/Controller/SettingsController.php';
require_once __DIR__ . '/Controller/LogoutController.php';

$request = $_SERVER['REQUEST_URI'];

$request = explode('?', $request)[0];


switch ($request) {
  case '/DesignConnect/designer/':
  case '/DesignConnect/Designer/':
  case '/DesignConnect/Designer/Dashboard/':
    $controller= new DashboardController();
    $controller->displayDashboard();
  break;

  case '/DesignConnect/Designer/Portfolio/':
    case '/DesignConnect/designer/Portfolio/':
    $controller = new PortfolioController();
    $controller->displayPortfolio();
  break;

    case '/DesignConnect/Designer/Settings/':
    case '/DesignConnect/designer/Settings/':
        $controller = new SettingsController();
        $controller->displaySettings();
        break;
    case '/DesignConnect/Designer/Logout/':
    case '/DesignConnect/designer/Logout/':
    $controller = new LogoutController();
    $controller->handleLogout();
  break;
      
  default:
     $controller= new DashboardController();
     $controller->displayDashboard();
  break;

}

?>