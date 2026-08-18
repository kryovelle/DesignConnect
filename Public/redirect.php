<?php
require_once __DIR__ . '/../session_config.php';

require_once __DIR__ . '/Controller/ContactController.php';
require_once __DIR__ . '/Controller/BrowseController.php';
require_once __DIR__ . '/Controller/LoginController.php';
require_once __DIR__ . '/Controller/JoinController.php';

if (isset($_POST['contactUs'])) {
    $contact_messages = [
        'name'    => $_POST['name'],
        'email'   => $_POST['email'],
        'subject' => $_POST['subject'],
        'message' => $_POST['message']
    ];
    $controller = new ContactController();
    $controller->saveContactDetails($contact_messages);
    header('Location: /DesignConnect/Public/Contact/');
    exit;
}




if (isset($_POST['sendProposal'])) {
    $photo_url = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('item_') . '.' . $ext;
        $destination = __DIR__ . '/../images/' . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $photo_url = $filename;
        }
    }
    $proposal = [
        'request_id'     => $_POST['request_id'],
        'pitch'          => $_POST['pitch'],
        'portfolio_link' => $_POST['portfolio_link'],
        'sample_photo'   => $photo_url,
        'caption'        => $_POST['caption']
    ];
    $request_id= $_POST['request_id'];
    $controller = new BrowseController();
    $controller->handleProposals($proposal);
   header('Location: /DesignConnect/Public/RequestDetail/?id=' . urlencode($request_id));
exit;
}

if (isset($_POST['login'])) {
    $user = [
        'email'       => $_POST['email'],
        'password'    => $_POST['password'],
        'remember-me' => $_POST['remember-me'] ?? 'off'
    ];
    $controller = new LoginController();
    $controller->handleLogin($user);
    exit;
}

if (isset($_POST['join'])) {
    $user = [
        'name'      => $_POST['fullName'] ?? '',
        'email'     => $_POST['email'] ?? '',
        'password'  => $_POST['password'] ?? '',
        'confirmPassword' => $_POST['confirmPassword'] ?? '',
        'role'      => $_POST['role'] ?? 'client',
        'specialty' => $_POST['specialty'] ?? '',
        'terms'     => isset($_POST['terms'])
    ];

    if(!$user['terms']){
      $_SESSION['join_errors']="You must agree to the Terms and Privacy Policy";

      header('Location: /DesignConnect/Public/Join/');
      exit();
    }
    $controller = new JoinController();
    $controller->handleJoin($user);
    exit;
}
?>