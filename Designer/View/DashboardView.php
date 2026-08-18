<?php
require_once __DIR__ . '/./GlobalView.php';
Class DashboardView extends GlobalView{

  function content( $proposals, $stats,$name,$avatar){
    $statusMap = [
      'open'        => ['text' => 'Open',        'class' => 'badge-open'],
      'in_progress' => ['text' => 'In Progress',  'class' => 'badge-progress'],
      'completed'   => ['text' => 'Completed',    'class' => 'badge-completed'],
    ];
    ?>
<body>
  <?php $this->nav($name,$avatar) ?>
  <div class="app-shell">
   <?php $this->sidebar() ?>
    <main class="main">
      <div class="main-head">
        <div>
          <h1>My Proposals</h1>
          <p>Track every proposal you've sent and the live status of each request.</p>
        </div>
        <a href="/DesignConnect/Public/Browse/" class="btn btn-solid">Browse Requests</a>
      </div>

      <div class="stat-row">
        <div class="stat-card" style="animation-delay:.05s"><div class="num" data-count="<?php echo (int)$stats['total']; ?>">0</div><div class="lbl">Total sent</div></div>
        <div class="stat-card" style="animation-delay:.15s"><div class="num" data-count="<?php echo (int)$stats['open']; ?>">0</div><div class="lbl">Open</div></div>
        <div class="stat-card" style="animation-delay:.25s"><div class="num" data-count="<?php echo (int)$stats['in_progress']; ?>">0</div><div class="lbl">In progress</div></div>
        <div class="stat-card" style="animation-delay:.35s"><div class="num" data-count="<?php echo (int)$stats['completed']; ?>">0</div><div class="lbl">Completed</div></div>
      </div>

      <div class="card-list" id="cardList">
        <?php if (empty($proposals)): ?>
          <p style="text-align:center; color:var(--text-on-paper-dim); padding:40px 0;">You haven't sent any proposals yet.</p>
        <?php endif; ?>

        <?php foreach ($proposals as $p):
              $status = $statusMap[$p['status']] ?? ['text' => ucfirst($p['status']), 'class' => 'badge-open'];
        ?>
        <div class="p-card">
          <div class="p-main">
            <h3><?php echo htmlspecialchars($p['title']); ?></h3>
            <div class="p-meta">
              <span>Client: <b><?php echo htmlspecialchars($p['client_name']); ?></b></span>
              <span>Budget: <b>$<?php echo htmlspecialchars($p['budget']); ?></b></span>
              <span>Sent: <?php echo htmlspecialchars($p['sent_ago']); ?></span>
            </div>
          </div>
          <div class="p-side">
            <span class="badge <?php echo $status['class']; ?>"><?php echo $status['text']; ?></span>
            <div class="p-actions">
              <a href="/DesignConnect/Public/RequestDetail/?id=<?php echo (int)$p['request_id']; ?>" class="btn btn-outline btn-sm">View Request</a>
              <button type="button" class="btn btn-outline btn-sm open-edit-modal" data-target="editModal<?php echo $p['proposal_id']; ?>">Edit</button>
            </div>
          </div>
        </div>

        <!-- EDIT MODAL for this proposal -->
        <div class="modal-backdrop" id="editModal<?php echo $p['proposal_id']; ?>">
          <form class="modal" style="max-width:460px;" action="/DesignConnect/Designer/redirect.php/" method="post">
            <input type="hidden" name="proposal_id" value="<?php echo $p['proposal_id']; ?>">
            <h3>Edit your proposal</h3>
            <p class="sub">Editing your pitch for "<?php echo htmlspecialchars($p['title']); ?>"</p>

            <div class="field" style="margin-bottom:16px;">
              <label>Your pitch</label>
              <textarea name="pitch" required><?php echo htmlspecialchars($p['pitch']); ?></textarea>
            </div>
            <div class="field" style="margin-bottom:6px;">
              <label>Portfolio link (optional)</label>
              <input type="text" name="portfolio_link" value="<?php echo htmlspecialchars($p['portfolio_link'] ?? ''); ?>">
            </div>

            <div class="modal-actions">
              <button type="button" class="btn btn-outline close-edit-modal">Cancel</button>
              <button type="submit" class="btn btn-solid" name="editProposal">Save Changes</button>
            </div>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
    </main>
  </div>

<script>


  // Count-up stats
  document.querySelectorAll('.stat-card .num').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    let current = 0;
    const step = Math.max(1, Math.round(target / 24));
    const timer = setInterval(() => {
      current += step;
      if (current >= target){ current = target; clearInterval(timer); }
      el.textContent = current;
    }, 35);
  });

  // Staggered reveal for proposal cards
  document.querySelectorAll('#cardList .p-card').forEach((card, i) => {
    card.style.animationDelay = `${i * 0.08}s`;
    card.classList.add('reveal-in');
  });

  // Edit modal open/close (event delegation, works for every card)
  document.querySelectorAll('.open-edit-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById(btn.dataset.target).classList.add('open');
    });
  });
  document.querySelectorAll('.close-edit-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.modal-backdrop').classList.remove('open');
    });
  });
  document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) backdrop.classList.remove('open');
    });
  });
</script>

<?php
  }

  function displayDashboardView( $proposals, $stats,$name,$avatar){
    $this->head();
    $this->content($proposals, $stats,$name,$avatar);
    $this->closepage();
  }
}
?>