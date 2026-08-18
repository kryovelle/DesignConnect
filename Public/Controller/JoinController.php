<?php
require_once __DIR__ . '/../Model/JoinModel.php';
require_once __DIR__ . '/../View/JoinView.php';

class JoinController {

    function displayJoin() {
        $view = new JoinView();
        $view->displayJoinView();
    }

    function handleJoin($user) {
        $errors = $this->validate($user);

        if (!empty($errors)) {
            $_SESSION['join_errors'] = $errors;
            $_SESSION['join_old']    = [
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role']
            ];
            header('Location: /DesignConnect/Public/Join/');
            exit;
        }

        try {
            $model = new JoinModel();
            $existing = $model->getUserByEmail($user['email']);

            if ($existing) {
                $_SESSION['join_errors'] = ['Email already exists! Please try another.'];
                $_SESSION['join_old']    = [
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];
                header('Location: /DesignConnect/Public/Join/');
                exit;
            }

            $result = $model->InsertUser($user);

            if ($result['success']) {
                // Clear session and set new session
                $_SESSION = [];
                session_regenerate_id(true);
                $_SESSION['id'] = $result['id'];
                $_SESSION['name'] = $user['name'];
                 $_SESSION['avatar'] = $user['avatar'];
                $_SESSION['role'] = $user['role'];
                
                // Set role-specific session flag
                if ($user['role'] === 'client') {
                    $_SESSION['client'] = true;
                } else {
                    $_SESSION['designer'] = true;
                }
                
                // FIX: Redirect to the correct dashboard
                header('Location: /DesignConnect/' . ucfirst($user['role']) . '/Dashboard/');
                exit;
            } else {
                $_SESSION['join_errors'] = ['Could not create account. Please try again.'];
                header('Location: /DesignConnect/Public/Join/');
                exit;
            }

        } catch (PDOException $e) {
            error_log('JoinController DB error: ' . $e->getMessage());
            $_SESSION['join_errors'] = ['A server error occurred. Please try again later.'];
            header('Location: /DesignConnect/Public/Join/');
            exit;
        } catch (Throwable $e) {
            error_log('JoinController error: ' . $e->getMessage());
            $_SESSION['join_errors'] = ['An unexpected error occurred.'];
            header('Location: /DesignConnect/Public/Join/');
            exit;
        }
    }

    private function validate($user) {
        $errors = [];

        if (empty(trim($user['name']))) {
            $errors[] = 'Full name is required.';
        }

        if (empty($user['email']) || !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if (empty($user['password']) || strlen($user['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($user['password'] !== $user['confirmPassword']) {
            $errors[] = 'Passwords do not match.';
        }

        if (!in_array($user['role'], ['client', 'designer'], true)) {
            $errors[] = 'Invalid role.';
        }

        if (!$user['terms']) {
            $errors[] = 'You must agree to the Terms and Privacy Policy.';
        }

        return $errors;
    }
}
?>