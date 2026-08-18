<?php
require_once __DIR__ . '/./GlobalView.php';
Class PortfolioView extends GlobalView{

  function content($samples,$name,$avatar){
    ?>
<body>
  <?php $this->nav($name,$avatar) ?>
  <div class="app-shell">
   <?php $this->sidebar() ?>
    <main class="main">
      <div class="main-head">
        <div>
          <h1>My Portfolio</h1>
          <p>Samples shown on your public profile </p>
        </div>
        <button class="btn btn-solid" type="button" id="openModalBtn">+ Add Sample</button>
      </div>

      <div class="portfolio-grid" id="grid">
        <?php if (empty($samples)): ?>
          <p style="grid-column:1/-1; text-align:center; color:var(--text-on-paper-dim);">You haven't added any portfolio samples yet.</p>
        <?php endif; ?>

        <?php foreach ($samples as $i => $sample):
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($sample['image_path']);
              $caption = htmlspecialchars($sample['caption'] ?? '');
        ?>
        <div class="p-sample" style="animation-delay:<?php echo $i * 0.06; ?>s">
          <img src="<?php echo $imageUrl; ?>" alt="<?php echo $caption; ?>">
          <div class="overlay">
            <span class="cap"><?php echo $caption; ?></span>
            <form action="/DesignConnect/Designer/redirect.php/" method="post"
                  onsubmit="return confirm('Delete this portfolio sample? This can\'t be undone.');">
              <input type="hidden" name="sample_id" value="<?php echo (int)$sample['id']; ?>">
              <button type="submit" name="deleteSample" class="del">Delete</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>

        <div class="add-tile" id="addTile" type="button">
          <div class="plus">+</div>
          Add Sample
        </div>
      </div>
    </main>
  </div>

  <!-- ADD SAMPLE MODAL -->
  <div class="modal-backdrop" id="modalBackdrop">
    <form class="modal" action="/DesignConnect/Designer/redirect.php/" method="post" enctype="multipart/form-data">
      <h3>Add a portfolio sample</h3>
      <p class="sub">This will appear on your public profile.</p>

      <div class="dropzone" id="dropzone">
        <div class="icon">⬆</div>
        <label for="photoInput" id="dzText">Drag &amp; drop an image, or click to browse</label>
        <input type="file" name="photo" id="photoInput" accept="image/*" required hidden>
      </div>

      <div class="field" style="margin:16px 0 6px;">
        <label>Caption (optional)</label>
        <input type="text" name="caption" placeholder="e.g. Coffee shop logo concept">
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
        <button type="submit" class="btn btn-solid" name="addSample">Save Sample</button>
      </div>
    </form>
  </div>

<script>
  const modalBackdrop = document.getElementById('modalBackdrop');
  document.getElementById('openModalBtn').addEventListener('click', () => modalBackdrop.classList.add('open'));
  document.getElementById('addTile').addEventListener('click', () => modalBackdrop.classList.add('open'));
  document.getElementById('cancelBtn').addEventListener('click', () => modalBackdrop.classList.remove('open'));
  modalBackdrop.addEventListener('click', (e) => { if (e.target === modalBackdrop) modalBackdrop.classList.remove('open'); });

  const dropzone = document.getElementById('dropzone');
  const dzText = document.getElementById('dzText');
  const photoInput = document.getElementById('photoInput');
  photoInput.addEventListener('change', () => {
    if (photoInput.files[0]) dzText.textContent = `Selected: ${photoInput.files[0].name}`;
  });
  ['dragenter','dragover'].forEach(evt => dropzone.addEventListener(evt, (e) => { e.preventDefault(); dropzone.classList.add('drag'); }));
  ['dragleave','drop'].forEach(evt => dropzone.addEventListener(evt, (e) => { e.preventDefault(); dropzone.classList.remove('drag'); }));
  dropzone.addEventListener('drop', (e) => {
    const file = e.dataTransfer.files[0];
    if (file){ photoInput.files = e.dataTransfer.files; dzText.textContent = `Selected: ${file.name}`; }
  });
</script>
<?php
  }

  function displayPortfolioView($samples,$name,$avatar){
    $this->head();
    $this->content( $samples,$name,$avatar);
    $this->closepage();
  }
}
?>