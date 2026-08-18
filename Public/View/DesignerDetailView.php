<?php
require_once __DIR__ . '/GlobalView.php';
Class DesignerDetailView extends GlobalView{
  

function content($contact,$designer,$portfolio_samples,$name,$avatar){


  ?>
<body>
<!-- ================= NAV ================= -->
  <?php $this->nav($name,$avatar) ?>

  
  <div class="page-head" style="height:312px">
    <div class="eyebrow" style="color:var(--coral-soft); justify-content:center; display:flex; margin-top:20px;">Designer Profile</div>
    <h1 style="margin-top:14px;"><?php echo htmlspecialchars($designer['name'] ?? '')?></h1>
    <p><?php echo htmlspecialchars($designer['specialty'] ?? '')?></p>
</div>

<section class="detail-wrap">
  <a href="/DesignConnect/Public/Browse/" class="back-link">← Back to Browse</a>

  <div class="profile-head">
    <?php
      $randomClass = 'a' . rand(1, 6);
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($designer['avatar']);
              $initials = htmlspecialchars($this->getAvatar($designer['name']));
        ?>

    <div class="avatar <?php echo $randomClass; ?>" style="width:96px;height:96px">
            <span class="avatar-initials"><?php echo $initials; ?></span>
            <img
                src="<?php echo $imageUrl; ?>"
                alt=""
                onload="this.style.display='block'; this.previousElementSibling.style.display='none';"
                onerror="this.style.display='none';"
                class="image-profile-browse"
                style="width:96px;height:96px"
            >
          </div>


    <div class="profile-info">
      <h1><?php echo htmlspecialchars($designer['name']) ?? ''?></h1>
      <span class="profile-spec"><?php echo htmlspecialchars($designer['specialty'])?></span>
      <p class="profile-bio"><?php echo htmlspecialchars($designer['bio']) ?? ''?></p>
      <div class="profile-links">
        <a href="<?php echo htmlspecialchars($designer['portfolio_link']) ?? ''?>">Portfolio</a>
        <a href="<?php echo htmlspecialchars($designer['instagram']) ?? ''?>">Instagram</a>
        <a href="<?php echo htmlspecialchars($designer['whatsapp']) ?? ''?>">WhatsApp</a>
        <a href="<?php echo htmlspecialchars($designer['behance']) ?? ''?>">Behance</a>
      </div>
    </div>
  </div>

    <h2 class="section-title">Portfolio Samples</h2>
    <div class="portfolio-grid">
      <?php if (empty($portfolio_samples)): ?>
        <p style="grid-column:1/-1; text-align:center; color:var(--text-on-paper-dim);">No portfolio samples yet.</p>
      <?php endif; ?>

      <?php foreach ($portfolio_samples as $i => $sample):
            $gradClass = 'g' . (($i % 7) + 1);
            $imageUrl = '/DesignConnect/images/' . htmlspecialchars($sample['image_path']);
            $caption = htmlspecialchars($sample['caption'] ?? '');
      ?>
        <div class="p-sample" style="animation-delay:<?php echo $i * 0.06; ?>s">
          <div class="fill <?php echo $gradClass; ?>"></div>
          <img
              src="<?php echo $imageUrl; ?>"
              alt="<?php echo $caption; ?>"
              onload="this.style.display='block'; this.previousElementSibling.style.display='none';"
              onerror="this.style.display='none';"
          >
          <?php if ($caption !== ''): ?>
            <div class="overlay"><span class="cap"><?php echo $caption; ?></span></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>


</section>

  <!-- ================= FOOTER ================= -->
    <?php $this->footer($contact)?>
<?php
}

function displayDesignerDetailView($contact,$designer,$portfolio_samples,$name,$avatar){
  $this->head();
  $this->content($contact,$designer,$portfolio_samples,$name,$avatar);
  $this->closePage();
}

}

?>