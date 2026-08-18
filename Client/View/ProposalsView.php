<?php
require_once __DIR__ . '/./GlobalView.php';

class ProposalsView extends GlobalView {

    private $categoryOptions = ['Logo & Branding','Web / UI','Social Media','Packaging','Illustration','Print'];

    function content($request, $proposals, $name,$avatar) {
        $statusMap = [
            'open' => ['text' => 'Open', 'class' => 'badge-open'],
            'in_progress' => ['text' => 'In Progress', 'class' => 'badge-progress'],
            'completed' => ['text' => 'Completed', 'class' => 'badge-completed'],
        ];
        $status = $statusMap[$request['status']] ?? ['text' => ucfirst($request['status']), 'class' => 'badge-open'];
        $deadlineDays = ceil((strtotime($request['deadline']) - time()) / 86400);
        $deadlineText = $deadlineDays > 0 ? $deadlineDays . ' days' : 'Expired';
        ?>
<body>
    <?php $this->nav($name,$avatar) ?>
    <div class="app-shell">
       <?php $this->sidebar('dashboard') ?>
        <main class="main">
            <a href="/DesignConnect/Client/Dashboard/" class="back-link">← Back to My Requests</a>

            <div class="req-detail-head">
                <div>
                    <h2><?php echo htmlspecialchars($request['title']); ?></h2>
                    <div class="req-detail-meta">
                        <span>Category: <b><?php echo htmlspecialchars($request['category']); ?></b></span>
                        <span>Budget: <b>$<?php echo htmlspecialchars($request['budget']); ?></b></span>
                        <span>Deadline: <b><?php echo $deadlineText; ?></b></span>
                    </div>
                </div>
                <div class="req-detail-actions">
                    <form method="post" action="/DesignConnect/Client/redirect.php" id="statusForm">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="status" id="statusInput" value="<?php echo $request['status']; ?>">
                        <input type="hidden" name="updateStatus" value="1">
                        <div class="status-dd" id="statusDd">
                            <div class="status-dd-btn" id="statusBtn">
                                <span class="badge <?php echo $status['class']; ?>" id="statusBadge"><?php echo $status['text']; ?></span>
                                <span class="car">▾</span>
                            </div>
                            <div class="status-dd-menu">
                                <button type="button" data-status="open">Open</button>
                                <button type="button" data-status="in_progress">Mark as In Progress</button>
                                <button type="button" data-status="completed">Mark as Completed</button>
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-outline btn-sm open-edit-modal" data-target="editModal<?php echo $request['id']; ?>">Edit</button>
                </div>
            </div>

            <h3 style="font-family:'Fraunces',serif; font-weight:600; font-size:18px; margin-bottom:18px;">
                Proposals (<?php echo count($proposals); ?>)
            </h3>

            <div id="proposalsList">
                <?php if (empty($proposals)): ?>
                    <p style="text-align:center; color:var(--text-on-paper-dim); padding:40px 0;">
                        No proposals yet. Designers will respond to your request soon.
                    </p>
                <?php endif; ?>

                <?php foreach ($proposals as $p):?>
                <div class="proposal-review">
                    <div class="pr-top">
                        <div class="pr-who">
                            <div class="av a1"><?php echo htmlspecialchars($this->getAvatar($p['designer_name'])); ?></div>
                            <div>
                                <strong><?php echo htmlspecialchars($p['designer_name']); ?></strong>
                                <span><?php echo htmlspecialchars($p['specialty'] ?? 'Designer'); ?></span>
                            </div>
                        </div>
                        <div class="pr-links">
                            <?php if (!empty($p['portfolio_link'])): ?><a href="<?php echo htmlspecialchars($p['portfolio_link']); ?>" target="_blank" rel="noopener">Portfolio</a><?php endif; ?>
                            <?php if (!empty($p['instagram'])): ?><a href="<?php echo htmlspecialchars($p['instagram']); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
                            <?php if (!empty($p['behance'])): ?><a href="<?php echo htmlspecialchars($p['behance']); ?>" target="_blank" rel="noopener">Behance</a><?php endif; ?>
                            <?php if (!empty($p['whatsapp'])): ?><a href="https://wa.me/<?php echo htmlspecialchars($p['whatsapp']); ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?>
                        </div>
                    </div>
                    <p class="pr-pitch"><?php echo htmlspecialchars($p['pitch']); ?></p>

                    <?php if (!empty($p['samples'])): ?>
                    <div class="pr-samples">
                        <?php foreach ($p['samples'] as $samplePath): ?>
                            <span style="background-image:url('/DesignConnect/images/<?php echo htmlspecialchars($samplePath); ?>'); background-size:cover; background-position:center;"></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="pr-foot">
                        <a href="/DesignConnect/Public/DesignerDetail/?id=<?php echo (int)$p['designer_id']; ?>" class="btn btn-outline btn-sm">View Profile</a>
                        <a href="mailto:<?php echo htmlspecialchars($p['designer_email']); ?>" class="btn btn-solid btn-sm">Contact</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal-backdrop" id="editModal<?php echo $request['id']; ?>">
        <form class="modal" style="max-width:520px;" action="/DesignConnect/Client/redirect.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
            <h3>Edit your request</h3>
            <p class="sub">Update the details for "<?php echo htmlspecialchars($request['title']); ?>"</p>

            <div class="form-grid" style="margin-bottom:6px;">
                <div class="field span-2">
                    <label>Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($request['title']); ?>" required>
                </div>
                <div class="field span-2">
                    <label>Category</label>
                    <select name="category" required>
                        <?php foreach ($this->categoryOptions as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($request['category'] === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field span-2">
                    <label>Description</label>
                    <textarea name="description" required><?php echo htmlspecialchars($request['description']); ?></textarea>
                </div>
                <div class="field">
                    <label>Budget ($)</label>
                    <input type="number" name="budget" value="<?php echo htmlspecialchars($request['budget']); ?>" required>
                </div>
                <div class="field">
                    <label>Deadline</label>
                    <input type="date" name="deadline" value="<?php echo $request['deadline']; ?>" required>
                </div>
                <div class="field span-2">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="open" <?php echo ($request['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                        <option value="in_progress" <?php echo ($request['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="completed" <?php echo ($request['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="field span-2">
                    <label>Reference Image</label>
                    <?php if (!empty($request['reference_image'])): ?>
                        <div style="margin-bottom:8px; width:100%; height:150px; overflow:hidden; border-radius:8px;">
                            <img src="/DesignConnect/images/<?php echo htmlspecialchars($request['reference_image']); ?>" alt="" style="width:100%; height:100%; object-fit:cover; display:block;">
                        </div>
                        <p style="font-size:12px; color:var(--text-on-paper-dim); margin-top:4px;">Current image</p>
                    <?php endif; ?>
                    <input type="file" name="reference_image" accept="image/*">
                    <p style="font-size:12px; color:var(--text-on-paper-dim); margin-top:4px;">Upload a new image to replace the current one (optional)</p>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline close-edit-modal">Cancel</button>
                <button type="submit" class="btn btn-solid" name="editRequest">Save Changes</button>
            </div>
        </form>
    </div>

    <div class="toast" id="toast"><span class="ic" id="toastIcon">✓</span> <span id="toastText">Status updated</span></div>

    <script>
        const avatarMenu = document.getElementById('avatarMenu');
        const avatarBtn = document.getElementById('avatarBtn');
        if (avatarBtn) avatarBtn.addEventListener('click', (e) => { avatarMenu.classList.toggle('open'); e.stopPropagation(); });
        document.addEventListener('click', () => {
            if (avatarMenu) avatarMenu.classList.remove('open');
            const statusDd = document.getElementById('statusDd');
            if (statusDd) statusDd.classList.remove('open');
        });

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            document.getElementById('toastText').textContent = message;
            document.getElementById('toastIcon').textContent = type === 'success' ? '✓' : '✕';
            toast.classList.remove('show');
            void toast.offsetWidth;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        }

        document.querySelectorAll('.proposal-review').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.08}s`;
            card.classList.add('reveal-in');
        });

        const statusDd = document.getElementById('statusDd');
        const statusBtn = document.getElementById('statusBtn');
        const statusBadge = document.getElementById('statusBadge');
        const statusMap = {
            open: { text: 'Open', cls: 'badge-open' },
            in_progress: { text: 'In Progress', cls: 'badge-progress' },
            completed: { text: 'Completed', cls: 'badge-completed' },
        };
        if (statusBtn) statusBtn.addEventListener('click', (e) => { statusDd.classList.toggle('open'); e.stopPropagation(); });
        document.querySelectorAll('.status-dd-menu button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('statusInput').value = btn.dataset.status;
                document.getElementById('statusForm').submit();
            });
        });

        document.querySelectorAll('.open-edit-modal').forEach(btn => {
            btn.addEventListener('click', () => document.getElementById(btn.dataset.target).classList.add('open'));
        });
        document.querySelectorAll('.close-edit-modal').forEach(btn => {
            btn.addEventListener('click', () => btn.closest('.modal-backdrop').classList.remove('open'));
        });
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', (e) => { if (e.target === backdrop) backdrop.classList.remove('open'); });
        });

        <?php if (isset($_SESSION['success'])): ?>
            showToast('<?php echo addslashes($_SESSION['message'] ?? 'Done'); ?>', '<?php echo $_SESSION['success'] ? 'success' : 'error'; ?>');
            <?php unset($_SESSION['success']); unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>
<?php
    }

    function displayProposalsView($request, $proposals, $name,$avatar) {
        $this->head();
        $this->content($request, $proposals, $name,$avatar);
        $this->closepage();
    }
}
?>