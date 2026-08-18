<?php
require_once __DIR__ . '/./GlobalView.php';

class SettingsView extends GlobalView {

    function content($user, $name,$avatar) {
        $avatarUrl = !empty($user['avatar']) ? '/DesignConnect/images/' . htmlspecialchars($user['avatar']) : null;
        $initials = htmlspecialchars($this->getAvatar($user['name'] ?? ''));
        ?>
<body>
    <?php $this->nav($name,$avatar) ?>
    <div class="app-shell">
        <?php $this->sidebar('settings') ?>
        <main class="main">
            <div class="main-head">
                <div>
                    <h1>Settings</h1>
                    <p>Manage your profile, security, and notification preferences.</p>
                </div>
            </div>

            <!-- PROFILE INFO -->
            <form class="settings-card" style="animation-delay:.03s" action="/DesignConnect/Client/redirect.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="updateProfile" value="1">
                <h2>Profile Info</h2>
                <div class="avatar-edit">
                    <div class="av-big" style="position:relative; overflow:hidden;">
                        <span style="<?php echo $avatarUrl ? 'display:none;' : ''; ?>"><?php echo $initials; ?></span>
                        <?php if ($avatarUrl): ?>
                            <img src="<?php echo $avatarUrl; ?>" alt="" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                        <?php endif; ?>
                    </div>
                    <label class="btn btn-outline btn-sm" style="cursor:pointer;">
                        Change photo
                        <input type="file" name="avatar" accept="image/*" style="display:none;">
                    </label>
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                <div style="text-align:right; margin-top:16px;">
                    <button type="submit" class="btn btn-solid btn-sm">Save Profile</button>
                </div>
            </form>

            <!-- PASSWORD -->
            <form id="passwordForm" class="settings-card" style="animation-delay:.11s" action="/DesignConnect/Client/redirect.php" method="post">
                <input type="hidden" name="updatePassword" value="1">
                <h2>Password</h2>
                <p style="font-size:12.5px; color:var(--text-on-paper-dim); margin-bottom:16px;">Leave blank to keep your current password.</p>
                <div class="form-grid">
                    <div class="field span-2">
                        <label>Current Password</label>
                        <input type="password" name="current_password" placeholder="Required only if changing password">
                    </div>
                    <div class="field">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="Min 8 characters">
                    </div>
                    <div class="field">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; margin-top:16px;">
                    <button type="submit" class="btn btn-solid btn-sm">Update Password</button>
                </div>
            </form>

            <!-- NOTIFICATIONS -->
            <form class="settings-card" style="animation-delay:.19s" action="/DesignConnect/Client/redirect.php" method="post">
                <input type="hidden" name="updateNotifications" value="1">
                <h2>Notifications</h2>
                <div class="toggle-row">
                    <div class="txt"><strong>New proposals</strong><span>Email me when I get a new proposal on one of my requests</span></div>
                    <label class="switch"><input type="checkbox" name="notify_new_proposal" <?php echo !empty($user['notify_new_proposal']) ? 'checked' : ''; ?>><span class="track"></span></label>
                </div>
                <div class="toggle-row">
                    <div class="txt"><strong>Product updates</strong><span>Email me about new features on DesignConnect</span></div>
                    <label class="switch"><input type="checkbox" name="notify_new_request" <?php echo !empty($user['notify_new_request']) ? 'checked' : ''; ?>><span class="track"></span></label>
                </div>
                <div style="text-align:right; margin-top:16px;">
                    <button type="submit" class="btn btn-solid btn-sm">Save Preferences</button>
                </div>
            </form>

            <!-- DANGER ZONE -->
            <div class="settings-card danger-zone" style="animation-delay:.27s">
                <h2>Danger Zone</h2>
                <p>Deleting your account removes your posted requests and hides them from any designer proposals in progress. This can't be undone.</p>
                <button class="btn btn-danger" type="button" id="deleteBtn">Delete My Account</button>
            </div>
        </main>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <form class="modal" action="/DesignConnect/Client/redirect.php" method="post">
            <h3>Delete your account?</h3>
            <p class="sub">This permanently removes your profile and posted requests. Type <b>DELETE</b> to confirm.</p>
            <div class="field"><input type="text" name="confirm_delete" id="confirmInput" placeholder="Type DELETE to confirm"></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" id="cancelDelete">Cancel</button>
                <button type="submit" class="btn btn-danger" name="deleteAccount" style="flex:1;">Delete Account</button>
            </div>
        </form>
    </div>

    <div class="toast" id="toast"><span class="ic" id="toastIcon">✓</span> <span id="toastText">Changes saved</span></div>

<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        document.getElementById('toastText').textContent = message;
        document.getElementById('toastIcon').textContent = type === 'success' ? '✓' : '✕';
        toast.classList.remove('show');
        void toast.offsetWidth;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2800);
    }

    // auto-submit the profile form when a new photo is picked
    document.querySelector('input[name="avatar"]')?.addEventListener('change', function(){
        this.form.submit();
    });

    // only require current password if the user actually typed a new one
    document.getElementById('passwordForm')?.addEventListener('submit', function(e){
        const current = this.querySelector('[name="current_password"]').value.trim();
        const next = this.querySelector('[name="new_password"]').value.trim();
        const confirm = this.querySelector('[name="confirm_password"]').value.trim();

        if (next === '' && confirm === '' && current === '') {
            e.preventDefault();
            showToast('Enter a new password to update it.', 'error');
            return;
        }
        if (next.length < 8) {
            e.preventDefault();
            showToast('New password must be at least 8 characters.', 'error');
            return;
        }
        if (next !== confirm) {
            e.preventDefault();
            showToast('New passwords do not match.', 'error');
            return;
        }
        if (current === '') {
            e.preventDefault();
            showToast('Enter your current password to confirm the change.', 'error');
        }
    });

    const deleteModal = document.getElementById('deleteModal');
    document.getElementById('deleteBtn').addEventListener('click', () => deleteModal.classList.add('open'));
    document.getElementById('cancelDelete').addEventListener('click', () => deleteModal.classList.remove('open'));
    deleteModal.addEventListener('click', (e) => { if (e.target === deleteModal) deleteModal.classList.remove('open'); });
    deleteModal.querySelector('form').addEventListener('submit', (e) => {
        const val = document.getElementById('confirmInput').value.trim().toUpperCase();
        if (val !== 'DELETE') {
            e.preventDefault();
            document.getElementById('confirmInput').style.borderColor = '#DC2626';
            showToast('Type DELETE to confirm.', 'error');
        }
    });

    <?php if (isset($_SESSION['success'])): ?>
        showToast('<?php echo addslashes($_SESSION['message'] ?? 'Done'); ?>', '<?php echo $_SESSION['success'] ? 'success' : 'error'; ?>');
        <?php unset($_SESSION['success']); unset($_SESSION['message']); ?>
    <?php endif; ?>
</script>
<?php
    }

    function displaySettingsView($user, $name,$avatar) {
        $this->head();
        $this->content($user, $name,$avatar);
        $this->closepage();
    }
}
?>