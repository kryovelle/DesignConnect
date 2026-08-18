<?php

Class GlobalView{

function head(){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Proposals — DesignConnect</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/DesignConnect/Client/index.css">
</head>

<?php
}

function nav($name,$avatar){
  ?>
    <nav class="nav" id="nav">
    <div class="logo"><span class="logo-mark "></span><span class="logo-text">DesignConnect</span></div>
    <ul class="nav-links">
      <li><a href="/DesignConnect/Public/">Home</a></li>
      <li><a href="/DesignConnect/Public/Browse/">Browse</a></li>
      <li><a href="#how">How it works</a></li>
      <li><a href="/DesignConnect/Public/Contact/">Contact</a></li>
    </ul>
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
        <a href="/DesignConnect/Client/Dashboard/">My requests</a>
        <a href="/DesignConnect/Client/Settings/">Settings</a>
        <a href="/DesignConnect/Client/Logout/" class="danger">Logout</a>
      </div>
    </div>
  </nav>
  <?php
}

function sidebar($active = ''){
?>
 <aside class="sidebar">
      <nav class="side-nav" id="sideNav">
        <a href="/DesignConnect/Client/Dashboard/" class="non<?php echo $active === 'dashboard' ? ' active' : ''; ?>" data-i="0"><span class="ic">📄</span> My Requests</a>

        <a href="/DesignConnect/Client/Settings/" class="non<?php echo $active === 'settings' ? ' active' : ''; ?>" data-i="2"><span class="ic">⚙</span> Settings</a>

      </nav>
      <div class="side-foot">
        <a href="/DesignConnect/Client/Logout/"><span class="ic">↪</span> Logout</a>
      </div>
    </aside>
<?php
}
  function closePage(){
    ?>
    <script>

    
        // Avatar dropdown
  const avatarMenu = document.getElementById('avatarMenu');
  if (avatarMenu) {
    document.getElementById('avatarBtn').addEventListener('click', (e) => {
      avatarMenu.classList.toggle('open');
      e.stopPropagation();
    });
    document.addEventListener('click', () => avatarMenu.classList.remove('open'));
  }

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.non').forEach(link => {
    link.addEventListener('click', function() {
      document.querySelectorAll('.non').forEach(a => a.classList.remove('active'));
      this.classList.add('active');
    });
  });
});

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