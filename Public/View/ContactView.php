<?php
require_once __DIR__ . '/GlobalView.php';

Class ContactView extends GlobalView{


function content($contact,$name,$avatar){

$successFlag = null;
if (isset($_SESSION['success'])){
    $successFlag = $_SESSION['success']; // 1 or 0
    unset($_SESSION['success']);
}

  ?>
<body>
<!-- ================= NAV ================= -->
  <?php $this->nav($name,$avatar) ?>

    <div class="hero-strip">
    <div class="eyebrow">We'd love to hear from you</div>
    <h1>Get in touch</h1>
    <p>Questions, feedback, or something not working right? Send us a message and we'll get back to you.</p>
  </div>

  <div class="contact-wrap">
    <div class="contact-card">
      <div class="form-side">
        <h2>Send a message</h2>
        <div class="sub">We usually reply within a day or two.</div>
        <form action="/DesignConnect/Public/redirect.php" method="post">
          <div class="field">
            <label>Name</label>
            <input type="text" placeholder="Your full name" name="name">
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" placeholder="you@example.com" name="email">
          </div>
          <div class="field">
            <label>Subject</label>
            <select name="subject">
              <option>General question</option>
              <option>Support / bug report</option>
              <option>Report an issue with a request</option>
              <option>Partnership</option>
            </select>
          </div>
          <div class="field">
            <label>Message</label>
            <textarea placeholder="Tell us what's going on..." name="message"></textarea>
          </div>
          <button class="submit-btn" type="submit" name="contactUs">Send Message</button>
        </form>
      </div>
      <div class="info-side">
        <div>
          <h3>Reach us directly</h3>
          <p>Prefer social or email? Here's how to find us outside the form too.</p>
        </div>
        <div class="info-list">
          <a href="mailto:<?php echo htmlspecialchars($contact['email']) ?>"><span class="ic">✉</span><?php echo htmlspecialchars($contact['email']) ?></a>
          <a href="#"><span class="ic">📷</span><?php echo htmlspecialchars($contact['instagram_url']) ?></a>
          <a href="#"><span class="ic">💬</span> <?php echo htmlspecialchars($contact['whatsapp']) ?></a>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= FOOTER ================= -->
    <?php $this->footer($contact)?>

<div id="modalRoot"></div>
<?php if ($successFlag !== null): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    <?php if ($successFlag): ?>
      openModal(msgSent());
    <?php else: ?>
      openModal(msgFailed());
    <?php endif; ?>
  });
</script>
<?php endif; ?>

<script>
  const modalRoot = document.getElementById('modalRoot');

  function openModal(content){
    modalRoot.innerHTML = content;
    modalRoot.classList.add('show');
  }

  function closeModal(){
    modalRoot.classList.remove('show');
  }

  function msgSent(){
    return `
      <div class="modal-box">
        <p>Message sent successfully!</p>
        <button onclick="closeModal()">Close</button>
      </div>
    `;
  }

  function msgFailed(){
    return `
      <div class="modal-box">
        <p>Something went wrong. Please try again.</p>
        <button onclick="closeModal()">Close</button>
      </div>
    `;
  }
</script>
<?php
}

function displayContactView($contact ,$name,$avatar){
  $this->head();
  $this->content($contact,$name,$avatar);
  $this->closePage();
}

}

?>