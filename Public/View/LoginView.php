<?php
require_once __DIR__ . '/GlobalView.php';
Class LoginView extends GlobalView{
  

function content(){
  ?>
  <style>
     :root{
    --ink:#150F20; --ink-2:#1E1630;
    --paper:#FBF9FF;
    --text-on-ink:#EDE7FB; --text-on-ink-dim:#B3A8D1;
    --text-on-paper:#1B1425; --text-on-paper-dim:#6B6280;
    --violet:#8B5CF6; --violet-deep:#5B21B6;
    --coral:#FF6B4A; --coral-soft:#FFB199;
    --line:rgba(237,231,251,0.14); --line-soft:rgba(27,20,37,0.1);
    --radius:20px;
  }
  *{ box-sizing:border-box; margin:0; padding:0; }
  body{
    font-family:'Space Grotesk', sans-serif; color:var(--text-on-paper); -webkit-font-smoothing:antialiased;
    min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 20px;
    background:
      radial-gradient(circle at 12% 15%, rgba(139,92,246,0.35), transparent 40%),
      radial-gradient(circle at 88% 85%, rgba(255,107,74,0.18), transparent 40%),
      var(--ink);
  }
  a{ color:inherit; text-decoration:none; }

  .card{
    width:100%; max-width:420px; background:#fff; border-radius:24px;
    padding:44px 40px; box-shadow:0 60px 100px -40px rgba(0,0,0,0.6);
    position:relative; z-index:2;
  }
  .logo{
    display:flex; align-items:center; justify-content:center; gap:10px;
    font-family:'Fraunces', serif; font-weight:600; font-size:20px; margin-bottom:28px;
  }
  .logo-mark{ width:28px; height:28px; border-radius:8px; background:linear-gradient(135deg, var(--violet), var(--coral)); position:relative; }
  .logo-mark::after{ content:""; position:absolute; inset:5px; border-radius:4px; background:#fff; }

  .tabs{
    display:flex; background:var(--paper); border-radius:100px; padding:5px; margin-bottom:32px; position:relative;
  }
  .tabs a{
    flex:1; text-align:center; padding:11px 0; font-size:14px; font-weight:600; z-index:1;
    color:var(--text-on-paper-dim); border-radius:100px; transition:color .3s ease;
  }
  .tabs a.active{ color:#fff; }
  .tab-pill{
    position:absolute; top:5px; left:5px; width:calc(50% - 5px); height:calc(100% - 10px);
    background:linear-gradient(135deg, var(--violet), var(--violet-deep)); border-radius:100px;
  }
  .tabs a:nth-child(2).active ~ .tab-pill{ transform:translateX(100%); }

  h1{ font-family:'Fraunces', serif; font-weight:600; font-size:24px; text-align:center; margin-bottom:8px; }
  .sub{ text-align:center; font-size:13.5px; color:var(--text-on-paper-dim); margin-bottom:30px; }

  .field{ margin-bottom:18px; }
  .field label{ display:block; font-size:13px; font-weight:600; margin-bottom:8px; }
  .field input{
    width:100%; padding:13px 16px; border-radius:12px; border:1px solid var(--line-soft);
    font-family:inherit; font-size:14px; outline:none; transition:border-color .25s ease; background:var(--paper);
  }
  .field input:focus{ border-color:var(--violet); }

  .row-between{ display:flex; justify-content:space-between; align-items:center; margin:4px 0 26px; font-size:13px; }
  .remember{ display:flex; align-items:center; gap:8px; color:var(--text-on-paper-dim); }
  .row-between a{ color:var(--violet-deep); font-weight:600; }

  .submit-btn{
    width:100%; padding:15px; border-radius:100px; border:none; cursor:pointer;
    background:linear-gradient(135deg, var(--coral), #FF8A6B); color:#1B0F08;
    font-family:inherit; font-weight:700; font-size:15px; transition:transform .25s ease, box-shadow .25s ease;
  }
  .submit-btn:hover{ transform:translateY(-2px); box-shadow:0 16px 30px -12px rgba(255,107,74,0.55); }

  .switch-line{ text-align:center; margin-top:24px; font-size:13.5px; color:var(--text-on-paper-dim); }
  .switch-line a{ color:var(--violet-deep); font-weight:600; }

  .back-home{
    position:relative; z-index:2; text-align:center; margin-top:22px;
    font-size:13px; color:var(--text-on-ink-dim);
  }
  .back-home a:hover{ color:var(--text-on-ink); }

  .error {
            text-align: center;
            color: #dc3545;
            padding: 10px;
            background: #f8d7da;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

  </style>
<body>
 <div>
    <div class="card">
      <div class="logo"><span class="logo-mark"></span>DesignConnect</div>

      <div class="tabs">
        <a href="#" class="active">Login</a>
        <a href="/DesignConnect/Public/Join/">Register</a>
        <div class="tab-pill"></div>
      </div>
      <?php if(isset($_SESSION['loginfailed']) &&$_SESSION['loginfailed']):
       $_SESSION['loginfailed']=false;
        ?> 
        <p class="error">Email or Password are incorrect</p>
      <?php endif;?>

      <h1>Welcome back</h1>
      <div class="sub">Log in to manage your requests or proposals.</div>

      <form method="post" action="/DesignConnect/Public/redirect.php">
        <div class="field">
          <label>Email</label>
          <input type="email" placeholder="you@example.com" name="email" required>
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" placeholder="••••••••" name="password" required>
        </div>
        <div class="row-between">
          <label class="remember"><input type="checkbox" name="remember-me"> Remember me</label>
          <a href="#">Forgot password?</a>
        </div>
        <button class="submit-btn" type="submit" name="login">Login</button>
      </form>

      <div class="switch-line">Don't have an account? <a href="/DesignConnect/Public/Join/">Register</a></div>
    </div>
    <div class="back-home"><a href="/DesignConnect/Public/">← Back to home</a></div>
  </div>
</body>
</html>

<?php
}

function displayLoginView(){
  $this->head();
  $this->content();
}

}

?>