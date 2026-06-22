<?php
// Set a default title if the specific page didn't declare one
$pageTitle = $pageTitle ?? 'System Administrator Console';
?>
<header class="top-navbar">
    <div style="display: flex; align-items: center; gap: 15px;">
        <button class="hamburger-trigger" id="menuToggle" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #5e6278;">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div style="font-weight: 600; font-size: 18px;">
            <?php echo htmlspecialchars($pageTitle); ?>
        </div>
    </div>
    <div style="font-weight: 500; color: #3f4254;">
        <i class="fa-solid fa-user-shield" style="color: var(--primary-green); margin-right: 5px;"></i> 
        Admin: <?php echo htmlspecialchars($_SESSION['firstname'] ?? 'User'); ?>
    </div>
</header>
<script>
    (function() {
        const initMenu = function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.getElementById('content-wrapper');

            if (menuToggle && sidebar && wrapper) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    wrapper.classList.toggle('expanded');
                });
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMenu);
        } else {
            initMenu();
        }
    })();
</script>