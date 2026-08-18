<?php
require_once __DIR__ . '/./GlobalView.php';

class DashboardView extends GlobalView {
    function content($requests, $stats, $name,$avatar) {
        $statusMap = [
            'open' => ['text' => 'Open', 'class' => 'badge-open'],
            'in_progress' => ['text' => 'In Progress', 'class' => 'badge-progress'],
            'completed' => ['text' => 'Completed', 'class' => 'badge-completed'],
        ];
        
        $categoryOptions = [
            'Logo & Branding','Web / UI','Social Media','Packaging','Illustration','Print'
        ];
        ?>
<body>
    <?php $this->nav($name,$avatar) ?>
    <div class="app-shell">
        <?php $this->sidebar('dashboard') ?>
        <main class="main">
            <div class="main-head">
                <div>
                    <h1>My Requests</h1>
                    <p>Everything you've posted, and every proposal it's brought in.</p>
                </div>
                <button class="btn btn-solid" id="openPostModal">+ Post New</button>
            </div>

            <div class="stat-row">
                <div class="stat-card" style="animation-delay:.05s">
                    <div class="num" data-count="<?php echo (int)$stats['total']; ?>">0</div>
                    <div class="lbl">Total requests</div>
                </div>
                <div class="stat-card" style="animation-delay:.15s">
                    <div class="num" data-count="<?php echo (int)$stats['open']; ?>">0</div>
                    <div class="lbl">Open</div>
                </div>
                <div class="stat-card" style="animation-delay:.25s">
                    <div class="num" data-count="<?php echo (int)$stats['in_progress']; ?>">0</div>
                    <div class="lbl">In progress</div>
                </div>
                <div class="stat-card" style="animation-delay:.35s">
                    <div class="num" data-count="<?php echo (int)$stats['completed']; ?>">0</div>
                    <div class="lbl">Completed</div>
                </div>
            </div>

            <div class="card-list" id="cardList">
                <?php if (empty($requests)): ?>
                    <p style="text-align:center; color:var(--text-on-paper-dim); padding:40px 0;">
                        You haven't posted any requests yet. Click "Post New" to get started.
                    </p>
                <?php endif; ?>

                <?php foreach ($requests as $r):
                    $status = $statusMap[$r['status']] ?? ['text' => ucfirst($r['status']), 'class' => 'badge-open'];
                ?>
                    <div class="p-card">
                        <div class="p-main">
                            <h3><?php echo htmlspecialchars($r['title']); ?></h3>
                            <div class="p-meta">
                                <span>Category: <b><?php echo htmlspecialchars($r['category']); ?></b></span>
                                <span>Budget: <b>$<?php echo htmlspecialchars($r['budget']); ?></b></span>
                                <span>Proposals: <b><?php echo (int)$r['proposal_count']; ?></b></span>
                                <span>Posted: <?php echo htmlspecialchars($r['created_ago']); ?></span>
                                <span>Deadline: <?php echo htmlspecialchars($r['deadline_formatted']); ?></span>
                            </div>
                        </div>
                        <div class="p-side">
                            <span class="badge <?php echo $status['class']; ?>"><?php echo $status['text']; ?></span>
                            <div class="p-actions">
                                <a href="/DesignConnect/Client/viewProposals/?request_id=<?php echo (int)$r['id']; ?>" class="btn btn-outline btn-sm">View Proposals</a>
                                <button type="button" class="btn btn-outline btn-sm open-edit-modal" data-target="editModal<?php echo $r['id']; ?>">Edit</button>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT MODAL for this request -->
                    <div class="modal-backdrop" id="editModal<?php echo $r['id']; ?>">
                        <form class="modal" style="max-width:520px;" action="/DesignConnect/Client/redirect.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="request_id" value="<?php echo $r['id']; ?>">
                            <h3>Edit your request</h3>
                            <p class="sub">Update the details for "<?php echo htmlspecialchars($r['title']); ?>"</p>

                            <div class="form-grid" style="margin-bottom:6px;">
                                <div class="field span-2">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($r['title']); ?>" required>
                                </div>
                                <div class="field span-2">
                                    <label>Category</label>
                                    <select name="category" required>
                                        <?php foreach ($categoryOptions as $cat): ?>
                                            <option value="<?php echo $cat; ?>" <?php echo ($r['category'] === $cat) ? 'selected' : ''; ?>>
                                                <?php echo $cat; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="field span-2">
                                    <label>Description</label>
                                    <textarea name="description" required><?php echo htmlspecialchars($r['description']); ?></textarea>
                                </div>
                                <div class="field">
                                    <label>Budget ($)</label>
                                    <input type="number" name="budget" value="<?php echo htmlspecialchars($r['budget']); ?>" required>
                                </div>
                                <div class="field">
                                    <label>Deadline</label>
                                    <input type="date" name="deadline" value="<?php echo $r['deadline']; ?>" required>
                                </div>          
                                <div class="field span-2">
                                    <label>Status</label>
                                    <select name="status" required>
                                        <option value="open" <?php echo ($r['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                                        <option value="in_progress" <?php echo ($r['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed" <?php echo ($r['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>

                                <div class="field span-2">
                                  <label>Reference Image</label>
                                  <?php if ($r['reference_image']): ?>
                                      <div style="margin-bottom: 8px;">
                                          <img src="/DesignConnect/images/<?php echo htmlspecialchars($r['reference_image']); ?>" alt="Current reference" style="max-width: 100%; max-height: 150px; border-radius: 4px;">
                                          <p style="font-size: 12px; color: var(--text-on-paper-dim); margin-top: 4px;">Current image</p>
                                      </div>
                                  <?php endif; ?>
                                  <input type="file" name="reference_image" accept="image/*">
                                  <p style="font-size: 12px; color: var(--text-on-paper-dim); margin-top: 4px;">Upload a new image to replace the current one (optional)</p>
                              </div>
                            </div>

                            <div class="modal-actions">
                                <button type="button" class="btn btn-outline close-edit-modal">Cancel</button>
                                <button type="submit" class="btn btn-solid" name="editRequest">Save Changes</button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- POST A REQUEST MODAL -->
    <div class="modal-backdrop" id="postModal">
        <form class="modal" style="max-width:520px;" action="/DesignConnect/Client/redirect.php" method="post" enctype="multipart/form-data">
            <h3>Post a design request</h3>
            <p class="sub">Designers in the matching category will be able to see and respond to this.</p>

            <div class="form-grid" style="margin-bottom:6px;">
                <div class="field span-2">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="e.g. Logo for a coffee roastery" required>
                </div>
                <div class="field span-2">
                    <label>Category</label>
                    <select name="category" required>
                        <?php foreach ($categoryOptions as $cat): ?>
                            <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field span-2">
                    <label>Description</label>
                    <textarea name="description" placeholder="What do you need, and any details a designer should know..." required></textarea>
                </div>
                <div class="field">
                    <label>Budget ($)</label>
                    <input type="number" name="budget" placeholder="150" required>
                </div>
                <div class="field">
                    <label>Deadline</label>
                    <input type="date" name="deadline" required>
                </div>

                <div class="field span-2">
                  <label>Reference Image (optional)</label>
                  <input type="file" name="reference_image" accept="image/*">
                  <p style="font-size: 12px; color: var(--text-on-paper-dim); margin-top: 4px;">Upload a reference image to help designers understand your request</p>
              </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" id="cancelPost">Cancel</button>
                <button type="submit" class="btn btn-solid" name="createRequest">Post Request</button>
            </div>
        </form>
    </div>

    <script>
        // Avatar menu toggle
        const avatarMenu = document.getElementById('avatarMenu');
        if (document.getElementById('avatarBtn')) {
            document.getElementById('avatarBtn').addEventListener('click', (e) => {
                avatarMenu.classList.toggle('open');
                e.stopPropagation();
            });
        }
        document.addEventListener('click', () => {
            if (avatarMenu) avatarMenu.classList.remove('open');
        });

        // Sidebar navigation
        const sideNav = document.getElementById('sideNav');
        const sidePill = document.getElementById('sidePill');
        function placePill(el) {
            if (el && sidePill) {
                sidePill.style.transform = `translateY(${el.offsetTop}px)`;
                sidePill.style.height = el.offsetHeight + 'px';
            }
        }
        requestAnimationFrame(() => placePill(sideNav ? sideNav.querySelector('a.active') : null));

        // Count-up stats
        document.querySelectorAll('.stat-card .num').forEach(el => {
            const target = parseInt(el.dataset.count, 10);
            let current = 0;
            const step = Math.max(1, Math.round(target / 24));
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = current;
            }, 35);
        });

        // Staggered reveal for request cards
        document.querySelectorAll('#cardList .p-card').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.08}s`;
            card.classList.add('reveal-in');
        });

        // Post modal
        const postModal = document.getElementById('postModal');
        const openPostBtn = document.getElementById('openPostModal');
        const cancelPostBtn = document.getElementById('cancelPost');
        
        if (openPostBtn) {
            openPostBtn.addEventListener('click', () => postModal.classList.add('open'));
        }
        if (cancelPostBtn) {
            cancelPostBtn.addEventListener('click', () => postModal.classList.remove('open'));
        }
        if (postModal) {
            postModal.addEventListener('click', (e) => {
                if (e.target === postModal) postModal.classList.remove('open');
            });
        }

        // Edit modal open/close
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

        // Toast notification on success
        <?php if (isset($_SESSION['success']) && $_SESSION['success']): ?>
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.innerHTML = `<span class="ic">✓</span> <span>Request updated successfully!</span>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.remove('show'), 3000);
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    </script>

<?php
    }

    function displayDashboardView($requests, $stats, $name,$avatar) {
        $this->head();
        $this->content($requests, $stats, $name,$avatar);
        $this->closepage();
    }
}
?>