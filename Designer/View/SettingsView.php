<?php
require_once __DIR__ . '/./GlobalView.php';
Class SettingsView extends GlobalView{

  function content( $designer, $name,$avatar){
    $successFlag = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);

    $avatarUrl = !empty($designer['avatar']) ? '/DesignConnect/images/' . htmlspecialchars($designer['avatar']) : null;
    $initials = htmlspecialchars($this->getAvatar($designer['name'] ?? ''));
    ?>
<body>
  <?php $this->nav($name,$avatar) ?>
  <div class="app-shell">
   <?php $this->sidebar() ?>
    <main class="main">
      <div class="main-head">
        <div>
          <h1>Settings</h1>
          <p>Manage your profile, designer details, security, and notifications.</p>
        </div>
      </div>

      <!-- PROFILE INFO -->
      <form class="settings-card" style="animation-delay:.03s" action="/DesignConnect/Designer/redirect.php/" method="post">
        <h2>Profile Info</h2>
        <div class="form-grid">
          <div class="field"><label>Full Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($designer['name'] ?? '')?>"></div>
          <div class="field"><label>Email</label><input type="email" name="email" value="<?php echo htmlspecialchars($designer['email'] ?? '')?>"></div>
        </div>
        <div style="text-align:right; margin-top:16px;">
          <button class="btn btn-solid" type="submit" name="updateProfile">Save Profile</button>
        </div>
      </form>

      <!-- AVATAR -->
      <form class="settings-card" style="animation-delay:.06s" action="/DesignConnect/Designer/redirect.php/" method="post" enctype="multipart/form-data">
        <h2>Profile Photo</h2>
        <div class="avatar-edit">
          <div class="av-big" style="position:relative; overflow:hidden;">
            <span style="<?php echo $avatarUrl ? 'display:none;' : ''; ?>"><?php echo $initials; ?></span>
            <?php if ($avatarUrl): ?>
              <img src="<?php echo $avatarUrl; ?>" alt="" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
            <?php endif; ?>
          </div>
          <label class="btn btn-outline btn-sm" style="cursor:pointer;">
            Change photo
            <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="this.form.submit()">
          </label>
        </div>
        <input type="hidden" name="updateAvatar" value="1">
      </form>

      <!-- DESIGNER INFO -->
      <form class="settings-card" style="animation-delay:.09s" action="/DesignConnect/Designer/redirect.php/" method="post">
        <h2>Designer Info</h2>
        <div class="form-grid">
          <div class="field">
            <label>Specialty</label>
            <select name="specialty">
              <?php
                $specialties = ['Logo & Branding','Web / UI Design','Social Media','Packaging','Illustration','Print'];
                $current = $designer['specialty'] ?? '';
                foreach ($specialties as $s):
              ?>
                <option value="<?php echo htmlspecialchars($s)?>" <?php echo $current === $s ? 'selected' : ''; ?>><?php echo htmlspecialchars($s)?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field"><label>Portfolio Link</label><input type="text" name="portfolio_link" value="<?php echo htmlspecialchars($designer['portfolio_link'] ?? '')?>" placeholder="https://..."></div>
          <div class="field span-2"><label>Bio</label><textarea name="bio" placeholder="A short line about what you do..."><?php echo htmlspecialchars($designer['bio'] ?? '')?></textarea></div>
          <div class="field"><label>Instagram</label><input type="text" name="instagram" value="<?php echo htmlspecialchars($designer['instagram'] ?? '')?>" placeholder="@yourhandle"></div>
          <div class="field"><label>Behance</label><input type="text" name="behance" value="<?php echo htmlspecialchars($designer['behance'] ?? '')?>" placeholder="behance.net/..."></div>
          <div class="field"><label>WhatsApp</label><input type="text" name="whatsapp" value="<?php echo htmlspecialchars($designer['whatsapp'] ?? '')?>" placeholder="+1 000 000 0000"></div>
        </div>
        <div style="text-align:right; margin-top:16px;">
          <button class="btn btn-solid" type="submit" name="updateDesignerInfo">Save Designer Info</button>
        </div>
      </form>

      <!-- PASSWORD -->
      <form class="settings-card" style="animation-delay:.15s" action="/DesignConnect/Designer/redirect.php/" method="post">
        <h2>Password</h2>
        <div class="form-grid">
          <div class="field span-2"><label>Current Password</label><input type="password" name="current_password" placeholder="••••••••"></div>
          <div class="field"><label>New Password</label><input type="password" name="new_password" placeholder="••••••••"></div>
          <div class="field"><label>Confirm Password</label><input type="password" name="confirm_password" placeholder="••••••••"></div>
        </div>
        <div style="text-align:right; margin-top:16px;">
          <button class="btn btn-solid" type="submit" name="updatePassword">Update Password</button>
        </div>
      </form>

      <!-- NOTIFICATIONS -->
      <form class="settings-card" style="animation-delay:.21s" action="/DesignConnect/Designer/redirect.php/" method="post">
        <h2>Notifications</h2>
        <div class="toggle-row">
          <div class="txt"><strong>New client contact</strong><span>Email me when a client reaches out about a proposal</span></div>
          <label class="switch"><input type="checkbox" name="notify_new_proposal" <?php echo !empty($designer['notify_new_proposal']) ? 'checked' : ''; ?>><span class="track"></span></label>
        </div>
        <div class="toggle-row">
          <div class="txt"><strong>New open requests</strong><span>Email me when a request matches my specialty</span></div>
          <label class="switch"><input type="checkbox" name="notify_new_request" <?php echo !empty($designer['notify_new_request']) ? 'checked' : ''; ?>><span class="track"></span></label>
        </div>
        <div style="text-align:right; margin-top:16px;">
          <button class="btn btn-solid" type="submit" name="updateNotifications">Save Preferences</button>
        </div>
      </form>

      <!-- DANGER ZONE -->
      <div class="settings-card danger-zone" style="animation-delay:.27s">
        <h2>Danger Zone</h2>
        <p>Deleting your account removes your profile, portfolio samples, and all sent proposals. This can't be undone.</p>
        <button class="btn btn-danger" type="button" id="deleteBtn">Delete My Account</button>
      </div>
    </main>
  </div>

  <!-- DELETE CONFIRM MODAL -->
  <div class="modal-backdrop" id="deleteModal">
    <form class="modal" action="/DesignConnect/Designer/redirect.php/" method="post">
      <h3>Delete your account?</h3>
      <p class="sub">This permanently removes your profile, portfolio, and proposal history. Type <b>DELETE</b> to confirm.</p>
      <div class="field"><input type="text" name="confirm_text" id="confirmInput" placeholder="Type DELETE to confirm"></div>
      <div class="modal-actions">
        <button type="button" class="btn btn-outline" id="cancelDelete">Cancel</button>
        <button type="submit" class="btn btn-danger" name="deleteAccount" style="flex:1;">Delete Account</button>
      </div>
    </form>
  </div>

  <div class="toast" id="toast"><span class="ic">✓</span> <span id="toastText">Changes saved</span></div>

<script>
  const deleteModal = document.getElementById('deleteModal');
  document.getElementById('deleteBtn').addEventListener('click', () => deleteModal.classList.add('open'));
  document.getElementById('cancelDelete').addEventListener('click', () => deleteModal.classList.remove('open'));
  deleteModal.addEventListener('click', (e) => { if(e.target === deleteModal) deleteModal.classList.remove('open'); });

  deleteModal.querySelector('form').addEventListener('submit', (e) => {
    const val = document.getElementById('confirmInput').value.trim().toUpperCase();
    if (val !== 'DELETE'){
      e.preventDefault();
      document.getElementById('confirmInput').style.borderColor = '#DC2626';
    }
  });

  <?php if ($successFlag !== null): ?>
  window.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    document.getElementById('toastText').textContent = <?php echo $successFlag ? "'Changes saved'" : "'Something went wrong, please try again'"; ?>;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2600);
  });
  <?php endif; ?>
</script>
<?php
  }

  function displaySettingsView($designer, $name,$avatar){
    $this->head();
    $this->content( $designer, $name,$avatar);
    $this->closepage();
  }
}
?>