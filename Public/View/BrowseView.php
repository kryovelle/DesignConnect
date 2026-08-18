<?php
require_once __DIR__ . '/GlobalView.php';
Class BrowseView extends GlobalView{


function content($contact, $designers, $requests, $mode = 'designers', $filters = [],$name,$avatar) {
  // $filters holds whatever was submitted via GET, e.g.:
  // ['specialty'=>'', 'q'=>'', 'category'=>'', 'budget'=>'', 'sort'=>'', 'q_req'=>'']
  $specialty  = $filters['specialty'] ?? '';
  $qDesigners = $filters['q'] ?? '';
  $category   = $filters['category'] ?? '';
  $budget     = $filters['budget'] ?? '';
  $sort       = $filters['sort'] ?? '';
  $qRequests  = $filters['q_req'] ?? '';

  $specialties = ['Logo & Branding','Web / UI','Social Media','Packaging','Illustration','Print'];

  // small inline SVG search icon, reused for both search buttons
  $searchIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
  ?>
<body data-mode="<?php echo htmlspecialchars($mode); ?>">
<!-- ================= NAV ================= -->
  <?php $this->nav($name,$avatar) ?>


  <div class="page-head">
    <div class="eyebrow" style="color:var(--coral-soft); justify-content:center; display:flex; margin-top:20px;">Browse</div>
    <h1 style="margin-top:14px;">Find your next designer — project</h1>
    <p>Switch between browsing designer profiles or open design requests.</p>
    <div class="toggle-wrap">
      <div class="toggle" id="toggle">
        <button type="button" data-mode="designers" class="<?php echo $mode === 'designers' ? 'active' : ''; ?>">Designers</button>
        <button type="button" data-mode="requests" class="<?php echo $mode === 'requests' ? 'active' : ''; ?>">Requests</button>
        <div class="toggle-pill"></div>
      </div>
    </div>
  </div>

  <!-- DESIGNERS FILTERS -->
  <form class="filters view-panel <?php echo $mode === 'designers' ? 'active' : ''; ?>" id="filters-designers" method="GET" action="">
    <input type="hidden" name="mode" value="designers">
    <div class="filter-group">
      <select name="specialty" onchange="this.form.submit()">
        <option value="">All specialties</option>
        <?php foreach ($specialties as $s): ?>
          <option value="<?php echo htmlspecialchars($s); ?>" <?php echo $specialty === $s ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($s); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="search-box">
      <button type="submit" class="search-icon-btn" aria-label="Search"><?php echo $searchIcon; ?></button>
      <input type="text" name="q" value="<?php echo htmlspecialchars($qDesigners); ?>" placeholder="Search designers...">
    </div>
  </form>

  <!-- REQUESTS FILTERS -->
  <form class="filters view-panel <?php echo $mode === 'requests' ? 'active' : ''; ?>" id="filters-requests" method="GET" action="">
    <input type="hidden" name="mode" value="requests">
    <div class="filter-group">
      <select name="category" onchange="this.form.submit()">
        <option value="">All categories</option>
        <?php foreach ($specialties as $s): ?>
          <option value="<?php echo htmlspecialchars($s); ?>" <?php echo $category === $s ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($s); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select name="budget" onchange="this.form.submit()">
        <option value="">Any budget</option>
        <option value="0-100" <?php echo $budget === '0-100' ? 'selected' : ''; ?>>Under $100</option>
        <option value="100-300" <?php echo $budget === '100-300' ? 'selected' : ''; ?>>$100 – $300</option>
        <option value="300-999999" <?php echo $budget === '300-999999' ? 'selected' : ''; ?>>$300+</option>
      </select>
      <select name="sort" onchange="this.form.submit()">
        <option value="newest" <?php echo ($sort === 'newest' || $sort === '') ? 'selected' : ''; ?>>Newest first</option>
        <option value="budget_desc" <?php echo $sort === 'budget_desc' ? 'selected' : ''; ?>>Budget: high to low</option>
        <option value="deadline_asc" <?php echo $sort === 'deadline_asc' ? 'selected' : ''; ?>>Deadline: soonest</option>
      </select>
    </div>
    <div class="search-box">
      <button type="submit" class="search-icon-btn" aria-label="Search"><?php echo $searchIcon; ?></button>
      <input type="text" name="q_req" value="<?php echo htmlspecialchars($qRequests); ?>" placeholder="Search requests...">
    </div>
  </form>

  <main>
    <!-- DESIGNERS GRID -->
    <div class="view-panel <?php echo $mode === 'designers' ? 'active' : ''; ?>" id="panel-designers">
      <div class="grid">
        <?php if (empty($designers)): ?>
          <p style="grid-column:1/-1; text-align:center; color:var(--text-on-paper-dim);">No designers match your filters yet.</p>
        <?php endif; ?>
        <?php foreach($designers as $designer) :
              $randomClass = 'a' . rand(1, 6);
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($designer['avatar']);
              $initials = htmlspecialchars($this->getAvatar($designer['name']));
        ?>
        <div class="designer-card">
          <div class="avatar <?php echo $randomClass; ?>">
            <span class="avatar-initials"><?php echo $initials; ?></span>
            <img
                src="<?php echo $imageUrl; ?>"
                alt=""
                onload="this.style.display='block'; this.previousElementSibling.style.display='none';"
                onerror="this.style.display='none';"
                class="image-profile-browse"
            >
          </div>
          <h3> <?php echo htmlspecialchars($designer['name']) ?> </h3>
          <div class="spec"><?php echo htmlspecialchars($designer['specialty']) ?></div>
          <a href="/DesignConnect/Public/DesignerDetail/?id=<?php echo htmlspecialchars($designer['id'])?>" class="view-btn">View Profile</a>
        </div>
        <?php endforeach;?>
      </div>
    </div>

    <!-- REQUESTS GRID -->
    <div class="view-panel <?php echo $mode === 'requests' ? 'active' : ''; ?>" id="panel-requests">
      <div class="grid">
        <?php if (empty($requests)): ?>
          <p style="grid-column:1/-1; text-align:center; color:var(--text-on-paper-dim);">No requests match your filters yet.</p>
        <?php endif; ?>
        <?php foreach($requests as $request):
              $randomClass = 'r' . rand(1, 6);
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($request['reference_image']);
        ?>
            <div class="req-card">
              <div class="req-thumb <?php echo $randomClass; ?>">
                  <img
                      src="<?php echo $imageUrl; ?>"
                      alt="Reference Image"
                      onerror="this.style.display='none'"
                      class="img-browse"
                  >
              </div>
              <div class="req-body"><span class="req-tag"><?php echo htmlspecialchars($request['category'])?></span><h3><?php echo htmlspecialchars($request['title'])?></h3>
                <div class="req-foot"><span class="budget">$<?php echo htmlspecialchars($request['budget'])?></span><a class="view" href="/DesignConnect/Public/RequestDetail/?id=<?php echo htmlspecialchars($request['id'])?>">View</a></div></div>
            </div>
        <?php endforeach;?>
      </div>
    </div>
  </main>

<script>
  // Cosmetic tab switching (which panel is visible). The actual filtering
  // happens server-side via the forms above submitting ?mode=designers/requests.
  const toggle = document.getElementById('toggle');
  const buttons = toggle.querySelectorAll('button');
  const panels = {
    designers: [document.getElementById('filters-designers'), document.getElementById('panel-designers')],
    requests: [document.getElementById('filters-requests'), document.getElementById('panel-requests')],
  };
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      toggle.classList.toggle('requests-active', btn.dataset.mode === 'requests');
      Object.entries(panels).forEach(([key, els]) => {
        els.forEach(el => el.classList.toggle('active', key === btn.dataset.mode));
      });
    });
  });

  // Submit search forms on Enter as well as the icon-button click
  document.querySelectorAll('.search-box input').forEach(input => {
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') { e.preventDefault(); input.form.submit(); }
    });
  });
</script>

  <!-- ================= FOOTER ================= -->
    <?php $this->footer($contact)?>
<?php
}

function displayBrowseView($contact, $designers, $requests, $mode = 'designers', $filters = [],$name,$avatar){
  $this->head();
  $this->content($contact, $designers, $requests, $mode, $filters,$name,$avatar);
  $this->closePage();
}

}

?>