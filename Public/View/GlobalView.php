<?php

Class GlobalView {
  function head(){
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DesignConnect — Great design, on demand.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="/DesignConnect/Public/index.css" rel="stylesheet">

    </head>
    <?php
  }

  function nav($name,$avatar){
    ?>
    <nav class="nav" id="nav">
    <div class="logo"><span class="logo-mark"></span><span class="logo-text">DesignConnect</span></div>
    <ul class="nav-links">
      <li><a href="/DesignConnect/Public/">Home</a></li>
      <li><a href="/DesignConnect/Public/Browse/">Browse</a></li>
      <li><a href="/DesignConnect/Public/#how">How it works</a></li>
      <li><a href="/DesignConnect/Public/Contact/">Contact</a></li>
    </ul>
    <?php if($name):?>
       <?php
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($avatar);
              $initials = htmlspecialchars($this->getAvatar($name));
        ?>
      <div class="avatar-menu" id="avatarMenu">
      <div class="avatar-btn"id="avatarBtn">
          <span class="avatar-initials av"><?php echo $initials; ?></span>
            <img
                src="<?php echo $imageUrl; ?>"
                alt=""
                onload="this.style.display='block'; this.previousElementSibling.style.display='none';"
                onerror="this.style.display='none';"
                class="image-profile-browse"
                style="height:30px;width:30px"
            >
        <span><?php echo htmlspecialchars($name)?></span>
        <span class="caret">▾</span>
      </div>
            <div class="avatar-dropdown">
            <?php if ($_SESSION['role'] === 'designer'): ?>
                <a href="/DesignConnect/<?php echo htmlspecialchars($_SESSION['role'])?>/Dashboard/">My Proposals</a>
                <a href="/DesignConnect/<?php echo htmlspecialchars($_SESSION['role'])?>/Portfolio/">My Portfolio</a>
            <?php else: ?>
                <a href="/DesignConnect/Client/Dashboard/">My Requests</a>
            <?php endif; ?>
            <a href="/DesignConnect/<?php echo htmlspecialchars($_SESSION['role'])?>/Settings/">Settings</a>
            <a href="/DesignConnect/<?php echo htmlspecialchars($_SESSION['role'])?>/Logout/" class="danger">Logout</a>
        </div>
    </div>
    <?php else:?>
    <div class="nav-actions">
      <a href="/DesignConnect/Public/Login/" class="btn btn-ghost">Login</a>
      <a href="/DesignConnect/Public/Join/" class="btn btn-solid">Join</a>
    </div>
    <?php endif;?>
  </nav>
  <?php
  }

  function footer($contact){
    ?>
    <footer id="contact">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo"><span class="logo-mark"></span>DesignConnect</div>
        <p>A request-and-proposal marketplace built only for design work — logos, web, social, print, illustration, and packaging.</p>
      </div>
      <div>
        <h4>Platform</h4>
        <ul>
          <li><a href="/DesignConnect/Public/">Home</a></li>
          <li><a href="/DesignConnect/Public/Browse/">Browse Requests</a></li>
          <li><a href="/DesignConnect/Public/Browse/">Browse Designers</a></li>
          <li><a href="/DesignConnect/Public/#how">How it works</a></li>
        </ul>
      </div>
      <div>
        <h4>Account</h4>
        <ul>
          <li><a href="/DesignConnect/Public/Join/">Join as Client</a></li>
          <li><a href="/DesignConnect/Public/Join/">Join as Designer</a></li>
          <li><a href="/DesignConnect/Public/Login/">Login</a></li>
        </ul>
      </div>
      <div>
        <h4>Support</h4>
        <ul>
          <li><a href="/DesignConnect/Public/Contact/">Contact us</a></li>
          <li><a href="#">Terms</a></li>
          <li><a href="#">Privacy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 DesignConnect. All rights reserved.</span>
      <div class="socials">
        <a href="<?php echo htmlspecialchars($contact['instagram_url'])?>" aria-label="Instagram">IG</a>
        <a href="<?php echo htmlspecialchars($contact['email'])?>" aria-label="Email">EM</a>
        <a href="<?php echo htmlspecialchars($contact['whatsapp'])?>" aria-label="WhatsApp">WA</a>
      </div>
    </div>
  </footer>
    <?php
  }

  function closePage(){
    ?>
    <script>
  // Nav background on scroll
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 30);
  });

  // Scroll reveal
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if(entry.isIntersecting){
        setTimeout(() => entry.target.classList.add('in'), i * 60);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  revealEls.forEach(el => io.observe(el));

  // subtle parallax on hero floating cards based on mouse
  const hero = document.querySelector('.hero-visual');
  const cards = document.querySelectorAll('.float-card');
  if (hero && window.matchMedia('(hover: hover)').matches) {
    hero.addEventListener('mousemove', (e) => {
      const rect = hero.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      cards.forEach((card, i) => {
        const depth = (i + 1) * 6;
        card.style.transform = `translate(${x * depth}px, ${y * depth}px) rotate(${card.style.getPropertyValue('--r') || 0})`;
      });
    });
  }

         // Avatar dropdown
  const avatarMenu = document.getElementById('avatarMenu');
  if (avatarMenu) {
    document.getElementById('avatarBtn').addEventListener('click', (e) => {
      avatarMenu.classList.toggle('open');
      e.stopPropagation();
    });
    document.addEventListener('click', () => avatarMenu.classList.remove('open'));
  }
</script>
</body>
</html>
<?php
  }

  function getAvatar($name){
    $words = explode(" ",trim($name));
    $avatar="";
    foreach($words as $word){
      $avatar .=strtoupper($word[0]);
    }
    return $avatar;
  }
}

?>

