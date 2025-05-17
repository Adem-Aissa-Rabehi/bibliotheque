<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<link rel="stylesheet" href="../assets/css/navbar.css">
<body id="body-pd">
    <div class="l-navbar expander" id="navbar">
        <nav class="nav">
            <div>
                <div class="logo-container">
                    <img class="logo-img" src="../assets/svg/cropped-Fichier-1.png" alt="Logo">
                </div>
                <div class="nav__list">
                   
                    
                    <div class="nav__link collapse">
                        <ion-icon name="document-text-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Documents</span>
                        <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>
                        <ul class="collapse__menu">
                            <a href="afficher_documents.php" class="collapse__sublink">Gérer les Documents</a>
                            <a href="ajouter_documents.php" class="collapse__sublink">Ajouter un Document</a>
                        </ul>
                    </div>

                    <div class="nav__link collapse">
                        <ion-icon name="settings-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Administration</span>
                        <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>
                        <ul class="collapse__menu">
                            <a href="gestion_admin.php" class="collapse__sublink">Gestion des Admins</a>
                            <a href="ajouter_type.php" class="collapse__sublink">Ajouter un Type</a>
                            <a href="gerer_types.php" class="collapse__sublink">Gérer les Types</a>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="user-section">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <div class="user-name"><?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?></div>
                        <div class="user-role">Administrateur</div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <ion-icon name="log-out-outline" class="logout-icon"></ion-icon>
                    <span>Déconnexion</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- ===== IONICONS ===== -->
    <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle collapse menus
            const collapseLinks = document.querySelectorAll(".nav__link.collapse");
            
            collapseLinks.forEach(function(link) {
                link.addEventListener("click", function(e) {
                    // Prevent navigation when clicking on the parent element
                    if (e.target.classList.contains('collapse__link') || 
                        e.target.classList.contains('nav__icon') || 
                        e.target.classList.contains('nav__name')) {
                        e.preventDefault();
                        
                        // Toggle the collapse menu
                        const collapseMenu = this.querySelector('.collapse__menu');
                        collapseMenu.classList.toggle('showCollapse');
                        
                        // Toggle the chevron icon
                        const chevronIcon = this.querySelector('.collapse__link');
                        chevronIcon.style.transform = collapseMenu.classList.contains('showCollapse') 
                            ? 'rotate(180deg)' 
                            : 'rotate(0)';
                    }
                });
            });

            // Set active link based on current page
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav__link, .collapse__sublink');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPage) {
                    link.classList.add('active');
                    
                    // If it's a sublink, expand the parent collapse menu
                    if (link.classList.contains('collapse__sublink')) {
                        const parentCollapse = link.closest('.collapse');
                        const collapseMenu = parentCollapse.querySelector('.collapse__menu');
                        collapseMenu.classList.add('showCollapse');
                        
                        // Rotate the chevron icon
                        const chevronIcon = parentCollapse.querySelector('.collapse__link');
                        chevronIcon.style.transform = 'rotate(180deg)';
                    }
                }
            });
        });
    </script>
</body>
</rewritten_file> 