<?php
require_once __DIR__ . '/GlobalView.php';
Class RequestDetailView extends GlobalView{


function content($contact,$request,$name,$avatar){

$showLoginRequired = isset($_SESSION['proposalSent']) && $_SESSION['proposalSent'] === false;
$showSuccess = isset($_SESSION['proposalSent']) && $_SESSION['proposalSent'] === true;
$showAlreadySent = isset($_SESSION['alreadySent']) && $_SESSION['alreadySent'] === true;
unset($_SESSION['proposalSent'], $_SESSION['alreadySent']);

  ?>
<body>

<!-- ================= NAV ================= -->
  <?php $this->nav($name,$avatar) ?>


  <div class="page-head" style="height:312px">
    <div class="eyebrow" style="color:var(--coral-soft); justify-content:center; display:flex; margin-top:20px;">Request</div>
    <h1 style="margin-top:14px;"><?php echo htmlspecialchars($request['title'] ?? '')?></h1>
    <p><?php echo htmlspecialchars($request['category'] ?? '')?></p>
  </div>


<section class="detail-wrap">
  <a href="/DesignConnect/Public/Browse/" class="back-link">← Back to Browse</a>

  <div>
    <span class="req-tag"><?php echo htmlspecialchars($request['category'] ?? '')?></span>
    <h2 style="font-family:'Fraunces',serif; font-weight:600; font-size:24px; margin:10px 0;"><?php echo htmlspecialchars($request['title'] ?? '')?></h2>
    <div class="req-detail-meta">
      <span>Posted by: <b><?php echo htmlspecialchars($request['name'] ?? '')?></b></span>
      <span>Budget: <b>$<?php echo htmlspecialchars($request['budget'] ?? '')?></b></span>
      <span>Deadline: <b><?php echo htmlspecialchars($request['deadline'] ?? '')?></b></span>
    </div>
  </div>

  <div class="req-detail-body">
          <?php   $randomClass = 'r' . rand(1, 6);
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($request['reference_image']); ?>
    <div class="req-thumb <?php echo $randomClass;?>" >
                  <img
                      src="<?php echo $imageUrl; ?>"
                      alt="Reference Image"
                      onerror="this.style.display='none'"
                      class="img-browse"
                  >
              </div>
    <p class="req-detail-desc"><?php echo htmlspecialchars($request['description'] ?? '')?></p>
  </div>

 <div class="lock-notice">
  <div class="lock-icon">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="5" y="11" width="14" height="10" rx="2"></rect>
      <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
    </svg>
  </div>
  <div>
    <strong>Proposals are hidden</strong>
    <span>Only the client who posted this request can view submitted proposals in their dashboard.</span>
  </div>
</div>

  <div style="margin-top:26px;">
    <button class="btn btn-solid" id="openProposalModal" type="button">Submit a Proposal</button>
  </div>
</section>

<!-- SUBMIT PROPOSAL MODAL -->
<div class="modal-backdrop" id="proposalModal">
  <form class="modal" style="max-width:480px;" action="/DesignConnect/Public/redirect.php/" method="post" enctype="multipart/form-data">
    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id'] ?? '') ?> ">

    <h3>Submit your proposal</h3>
    <p class="sub">Only the client who posted this request will see it.</p>

    <div class="field" style="margin-bottom:16px;">
      <label>Your pitch</label>
      <textarea placeholder="Introduce yourself and how you'd approach this..." name="pitch" required></textarea>
    </div>

    <div class="field" style="margin-bottom:16px;">
      <label>Portfolio link (optional)</label>
      <input type="text" placeholder="https://..." name="portfolio_link">
    </div>

    <div class="dropzone" id="dropzone">
      <div class="icon">⬆</div>
      <label for="photoInput" id="dzText">Upload a sample image (optional)</label>
      <input type="file" name="photo" id="photoInput" accept="image/*" hidden>
    </div>

    <div class="field" style="margin-top:14px; margin-bottom:6px;">
      <label>Sample image caption</label>
      <input type="text" name="caption" placeholder="e.g. Logo concept sketch">
    </div>

    <div class="modal-actions">
      <button class="btn btn-outline" id="cancelProposal" type="reset">Cancel</button>
      <button class="btn btn-solid" id="sendProposal" type="submit" name="sendProposal">Send Proposal</button>
    </div>
  </form>
</div>


<!-- LOGIN REQUIRED MODAL -->
<div class="modal-backdrop" id="loginRequiredModal">
  <div class="modal" style="max-width:420px; text-align:center;">
    <div class="lock-icon" style="margin:0 auto 14px;">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="5" y="11" width="14" height="10" rx="2"></rect>
        <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
      </svg>
    </div>
    <h3>Login required</h3>
    <p class="sub">You need to be logged in as a designer to submit a proposal.</p>
    <div class="modal-actions" style="justify-content:center; margin-top:20px;">
      <button class="btn btn-outline" id="closeLoginRequired" type="button">Cancel</button>
      <a class="btn btn-solid" href="/DesignConnect/Public/Login/">Login as Designer</a>
    </div>
  </div>
</div>

<!-- PROPOSAL SUCCESS MODAL -->
<div class="modal-backdrop" id="proposalSuccessModal">
  <div class="modal" style="max-width:420px; text-align:center;">
    <div class="lock-icon" style="margin:0 auto 14px;">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 6L9 17l-5-5"></path>
      </svg>
    </div>
    <h3>Proposal sent!</h3>
    <p class="sub">Your proposal has been submitted successfully. The client will review it soon.</p>
    <div class="modal-actions" style="justify-content:center; margin-top:20px;">
      <button class="btn btn-solid" id="closeProposalSuccess" type="button">Close</button>
    </div>
  </div>
</div>

<!-- ALREADY SENT / ERROR MODAL -->
<div class="modal-backdrop" id="alreadySentModal">
  <div class="modal" style="max-width:420px; text-align:center;">
    <div class="lock-icon" style="margin:0 auto 14px;">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
      </svg>
    </div>
    <h3>Couldn't send proposal</h3>
    <p class="sub">You may have already submitted a proposal for this request, or something went wrong.</p>
    <div class="modal-actions" style="justify-content:center; margin-top:20px;">
      <button class="btn btn-outline" id="closeAlreadySent" type="button">Close</button>
    </div>
  </div>
</div>



<script>
  const proposalModal = document.getElementById('proposalModal');
  const openBtn = document.getElementById('openProposalModal');
  const cancelBtn = document.getElementById('cancelProposal');

  openBtn.addEventListener('click', () => proposalModal.classList.add('open'));
  cancelBtn.addEventListener('click', () => proposalModal.classList.remove('open'));
  proposalModal.addEventListener('click', (e) => {
    if (e.target === proposalModal) proposalModal.classList.remove('open');
  });

  const dropzone = document.getElementById('dropzone');
  const dzText = document.getElementById('dzText');
  const photoInput = document.getElementById('photoInput');
  photoInput.addEventListener('change', () => {
    if (photoInput.files[0]) dzText.textContent = `Selected: ${photoInput.files[0].name}`;
  });

  <?php if ($showLoginRequired): ?>
    document.getElementById('loginRequiredModal').classList.add('open');
  <?php endif; ?>

  <?php if ($showSuccess): ?>
    document.getElementById('proposalSuccessModal').classList.add('open');
  <?php endif; ?>

  <?php if ($showAlreadySent): ?>
    document.getElementById('alreadySentModal').classList.add('open');
  <?php endif; ?>

  document.getElementById('closeLoginRequired').addEventListener('click', () => {
    document.getElementById('loginRequiredModal').classList.remove('open');
  });

  document.getElementById('closeProposalSuccess').addEventListener('click', () => {
    document.getElementById('proposalSuccessModal').classList.remove('open');
  });

  document.getElementById('closeAlreadySent').addEventListener('click', () => {
    document.getElementById('alreadySentModal').classList.remove('open');
  });
</script>

  <!-- ================= FOOTER ================= -->
    <?php $this->footer($contact)?>
<?php
}

function displayRequestDetailView($contact,$request,$name,$avatar){
  $this->head();
  $this->content($contact,$request,$name,$avatar);
  $this->closePage();
}

}

?>