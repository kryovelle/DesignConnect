<?php
require_once __DIR__ . '/GlobalView.php';

class JoinView extends GlobalView {
  
    function content() {
       $errors = $_SESSION['join_errors'] ?? [];
    $old    = $_SESSION['join_old'] ?? ['name' => '', 'email' => '', 'role' => 'client'];
    unset($_SESSION['join_errors'], $_SESSION['join_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join DesignConnect</title>
    <style>
        :root {
            --ink: #150F20;
            --ink-2: #1E1630;
            --paper: #FBF9FF;
            --text-on-ink: #EDE7FB;
            --text-on-ink-dim: #B3A8D1;
            --text-on-paper: #1B1425;
            --text-on-paper-dim: #6B6280;
            --violet: #8B5CF6;
            --violet-deep: #5B21B6;
            --coral: #FF6B4A;
            --coral-soft: #FFB199;
            --line: rgba(237, 231, 251, 0.14);
            --line-soft: rgba(27, 20, 37, 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text-on-paper);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background:
                radial-gradient(circle at 12% 15%, rgba(139, 92, 246, 0.35), transparent 40%),
                radial-gradient(circle at 88% 85%, rgba(255, 107, 74, 0.18), transparent 40%),
                var(--ink);
        }
        
        a {
            color: inherit;
            text-decoration: none;
        }
        
        .card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 24px;
            padding: 44px 44px 40px;
            box-shadow: 0 60px 100px -40px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 2;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 28px;
        }
        
        .logo-mark {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--violet), var(--coral));
            position: relative;
        }
        
        .logo-mark::after {
            content: "";
            position: absolute;
            inset: 5px;
            border-radius: 4px;
            background: #fff;
        }
        
        .tabs {
            display: flex;
            background: var(--paper);
            border-radius: 100px;
            padding: 5px;
            margin-bottom: 32px;
            position: relative;
        }
        
        .tabs a {
            flex: 1;
            text-align: center;
            padding: 11px 0;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
            color: var(--text-on-paper-dim);
            border-radius: 100px;
        }
        
        .tabs a.active {
            color: #fff;
        }
        
        .tab-pill {
            position: absolute;
            top: 5px;
            right: 5px;
            width: calc(50% - 5px);
            height: calc(100% - 10px);
            background: linear-gradient(135deg, var(--violet), var(--violet-deep));
            border-radius: 100px;
            transition: transform 0.3s ease;
        }
        
        h1 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 24px;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .sub {
            text-align: center;
            font-size: 13.5px;
            color: var(--text-on-paper-dim);
            margin-bottom: 26px;
        }
        
        .role-picker {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 28px;
        }
        
        .role-card {
            border: 2px solid var(--line-soft);
            border-radius: 16px;
            padding: 20px 16px;
            cursor: pointer;
            transition: border-color .25s ease, background .25s ease, transform .25s ease;
            text-align: left;
        }
        
        .role-card:hover {
            transform: translateY(-2px);
        }
        
        .role-card .dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid var(--line-soft);
            margin-bottom: 14px;
            position: relative;
            transition: border-color .25s ease;
        }
        
        .role-card.selected {
            border-color: var(--violet);
            background: #F8F5FF;
        }
        
        .role-card.selected .dot {
            border-color: var(--violet);
        }
        
        .role-card.selected .dot::after {
            content: "";
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            background: var(--violet);
        }
        
        .role-card h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .role-card p {
            font-size: 12.5px;
            color: var(--text-on-paper-dim);
            line-height: 1.4;
        }
        
        .field {
            margin-bottom: 16px;
        }
        
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .field input,
        .field select {
            width: 100%;
            padding: 13px 16px;
            border-radius: 12px;
            border: 1px solid var(--line-soft);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color .25s ease;
            background: var(--paper);
        }
        
        .field input:focus,
        .field select:focus {
            border-color: var(--violet);
        }
        
        .field input.error {
            border-color: #dc3545;
        }
        
        .field .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        
        .field .error-message.show {
            display: block;
        }
        
        .conditional {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height .4s ease, opacity .3s ease, margin .4s ease;
        }
        
        .conditional.show {
            max-height: 120px;
            opacity: 1;
            margin-bottom: 16px;
        }
        
        .terms {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12.5px;
            color: var(--text-on-paper-dim);
            margin: 18px 0 24px;
        }
        
        .terms a {
            color: var(--violet-deep);
            font-weight: 600;
        }
        
        .terms input {
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px;
            border-radius: 100px;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, var(--coral), #FF8A6B);
            color: #1B0F08;
            font-family: inherit;
            font-weight: 700;
            font-size: 15px;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px -12px rgba(255, 107, 74, 0.55);
        }
        
        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .switch-line {
            text-align: center;
            margin-top: 22px;
            font-size: 13.5px;
            color: var(--text-on-paper-dim);
        }
        
        .switch-line a {
            color: var(--violet-deep);
            font-weight: 600;
        }
        
        .back-home {
            position: relative;
            z-index: 2;
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--text-on-ink-dim);
        }
        
        .back-home a:hover {
            color: var(--text-on-ink);
        }
        
        @media (max-width: 480px) {
            .role-picker {
                grid-template-columns: 1fr;
            }
            .card {
                padding: 34px 24px;
            }
        }
        
        .error {
            text-align: center;
            color: #dc3545;
            padding: 10px;
            background: #f8d7da;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        
        .hidden {
            display: none;
        }
        
        .success {
            text-align: center;
            color: #28a745;
            padding: 10px;
            background: #d4edda;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div>
        <div class="card">
            <div class="logo">
                <span class="logo-mark"></span>
                DesignConnect
            </div>

            <div class="tabs">
                <a href="/DesignConnect/Public/Login/">Login</a>
                <a href="#" class="active">Register</a>
                <div class="tab-pill"></div>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
        <?php endif; ?>

            <h1>Join DesignConnect</h1>
            <div class="sub">Are you looking to hire, or to design?</div>

            <div class="role-picker">
              <div class="role-card <?= $old['role'] === 'client' ? 'selected' : '' ?>" data-role="client" id="roleClient">
                <div class="dot"></div>
                <h4>I'm a Client</h4>
                <p>I need design work done</p>
            </div>
            <div class="role-card <?= $old['role'] === 'designer' ? 'selected' : '' ?>" data-role="designer" id="roleDesigner">
                <div class="dot"></div>
                <h4>I'm a Designer</h4>
                <p>I offer design services</p>
            </div>
            </div>

            <form id="joinForm" method="post" action="/DesignConnect/Public/redirect.php" novalidate>
               <input type="hidden" name="role" id="roleInput" value="<?= htmlspecialchars($old['role']) ?>">

                <div class="field">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Your full name" required >
                    <div class="error-message" id="fullNameError">Please enter your full name</div>
                </div>

                <div class="field">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required >
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>

                <div class="field">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8" >
                    <div class="error-message" id="passwordError">Password must be at least 8 characters</div>
                </div>

                <div class="field">
                    <label for="confirmPassword">Confirm Password *</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required >
                    <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
                </div>

                <div class="conditional" id="specialtyField">
                    <div class="field">
                        <label for="specialty">Specialty</label>
                        <select id="specialty" name="specialty">
                            <option value="logo_branding">Logo &amp; Branding</option>
                            <option value="web_ui">Web / UI Design</option>
                            <option value="social_media">Social Media</option>
                            <option value="packaging">Packaging</option>
                            <option value="illustration">Illustration</option>
                            <option value="print">Print</option>
                        </select>
                    </div>
                </div>

                <label class="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span>I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a></span>
                </label>
                <div class="error-message" id="termsError" style="margin-top: -12px; margin-bottom: 12px;">You must agree to the Terms and Privacy Policy</div>

                <button class="submit-btn" type="submit" name="join">Create Account</button>
            </form>

            <div class="switch-line">Already have an account? <a href="/DesignConnect/Public/Login/">Login</a></div>
        </div>
        <div class="back-home"><a href="/DesignConnect/Public/">← Back to home</a></div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const clientCard = document.getElementById('roleClient');
    const designerCard = document.getElementById('roleDesigner');
    const specialtyField = document.getElementById('specialtyField');
    const roleInput = document.getElementById('roleInput');
    const form = document.getElementById('joinForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const submitBtn = form.querySelector('.submit-btn');

    function selectRole(role) {
        clientCard.classList.toggle('selected', role === 'client');
        designerCard.classList.toggle('selected', role === 'designer');
        specialtyField.classList.toggle('show', role === 'designer');
        roleInput.value = role;
    }

    clientCard.addEventListener('click', () => selectRole('client'));
    designerCard.addEventListener('click', () => selectRole('designer'));

    function validateField(input, errorId, condition) {
        const errorElement = document.getElementById(errorId);
        if (!condition) {
            input.classList.add('error');
            errorElement.classList.add('show');
            return false;
        } else {
            input.classList.remove('error');
            errorElement.classList.remove('show');
            return true;
        }
    }

    document.getElementById('password').addEventListener('input', function() {
        validateField(this, 'passwordError', this.value.length >= 8);
        const confirmInput = document.getElementById('confirmPassword');
        if (confirmInput.value) {
            validateField(confirmInput, 'confirmPasswordError', confirmInput.value === this.value);
        }
    });

    document.getElementById('confirmPassword').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        validateField(this, 'confirmPasswordError', this.value === password && password.length >= 8);
    });

    document.getElementById('fullName').addEventListener('input', function() {
        validateField(this, 'fullNameError', this.value.trim().length > 0);
    });

    document.getElementById('email').addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        validateField(this, 'emailError', emailRegex.test(this.value));
    });

    document.getElementById('terms').addEventListener('change', function() {
        document.getElementById('termsError').classList.toggle('show', !this.checked);
    });

    function resetSubmitButton() {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Account';
    }
});
</script>
</body>
</html>
<?php
    }

    function displayJoinView() {
        $this->head();
        $this->content();
    }
}
?>