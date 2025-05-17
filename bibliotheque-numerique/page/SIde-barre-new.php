<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    /*===== GOOGLE FONTS =====*/
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");

    /*===== VARIABLES CSS =====*/
    :root{
      --nav-width: 280px;
      --header-height: 60px;
      
      /*===== Colors =====*/
      --primary-color: #2563eb;
      --primary-light: #3b82f6;
      --primary-dark: #1d4ed8;
      --bg-color: #ffffff;
      --text-color: #1f2937;
      --text-light: #6b7280;
      --border-color: #e5e7eb;
      --hover-color: #f3f4f6;
      --active-color: #eff6ff;
      
      /*===== Font and typography =====*/
      --body-font: 'Poppins', sans-serif;
      --normal-font-size: 0.95rem;
      --small-font-size: 0.875rem;
      
      /*===== z index =====*/
      --z-fixed: 100;
    }

    /*===== BASE =====*/
    *,::before,::after{
      box-sizing: border-box;
    }
    body{
      position: relative;
      margin: 0;
      padding: 0 0 0 var(--nav-width);
      transition: .5s;
      font-family: var(--body-font);
      background-color: #f9fafb;
    }
    .logo-container {
      display: flex;
      align-items: center;
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      background-color: var(--bg-color);
    }
    .logo-img {
      height: 45px;
      width: auto;
      transition: transform 0.3s ease;
    }
    .logo-img:hover {
      transform: scale(1.05);
    }
    h1{
      margin: 0;
    }
    ul{
      margin: 0;
      padding: 0;
      list-style: none;
    }
    a{
      text-decoration: none;
      color: var(--text-color);
    }

    /*===== NAVBAR =====*/
    .l-navbar{
      position: fixed;
      top: 0;
      left: 0;
      width: var(--nav-width);
      height: 100vh;
      background-color: var(--bg-color);
      padding: 0;
      transition: .5s;
      z-index: var(--z-fixed);
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    /*===== NAV =====*/
    .nav{
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
    }
    .nav__brand{
      display: flex;
      align-items: center;
      padding: 1rem;
    }
    .nav__toggle{
      font-size: 1.25rem;
      padding: .75rem;
      cursor: pointer;
      color: var(--text-color);
    }
    .nav__logo{
      color: var(--text-color);
      font-weight: 600;
    }

    .nav__list {
      padding: 1.5rem 0;
    }

    .nav__link{
      display: flex;
      align-items: center;
      padding: 0.875rem 1.25rem;
      color: var(--text-color);
      border-radius: 0.75rem;
      margin: 0.25rem 1rem;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nav__link:hover{
      background-color: var(--hover-color);
      color: var(--primary-color);
      transform: translateX(5px);
    }

    .nav__link.active {
      background-color: var(--active-color);
      color: var(--primary-color);
      font-weight: 500;
      box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
    }

    .nav__icon{
      font-size: 1.35rem;
      margin-right: 0.75rem;
      color: var(--text-light);
      transition: color 0.3s ease;
    }

    .nav__link:hover .nav__icon {
      color: var(--primary-color);
    }

    .nav__name{
      font-size: var(--normal-font-size);
      font-weight: 500;
    }

    /*Expander menu*/
    .expander{
      width: var(--nav-width);
    }

    /*===== COLLAPSE =====*/
    .collapse{
      flex-direction: column;
    }
    .collapse__link{
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.875rem 1.25rem;
      color: var(--text-color);
      transition: .3s;
    }
    .collapse__menu{
      display: none;
      padding: 0.5rem 0 0.5rem 1rem;
      margin-left: 1rem;
      border-left: 2px solid var(--border-color);
    }
    .collapse__sublink{
      display: flex;
      align-items: center;
      padding: 0.625rem 1rem;
      color: var(--text-light);
      font-size: var(--small-font-size);
      border-radius: 0.5rem;
      margin: 0.25rem 0;
      transition: all 0.3s ease;
    }
    .collapse__sublink:hover{
      background-color: var(--hover-color);
      color: var(--primary-color);
      transform: translateX(5px);
    }

    /*Show collapse*/
    .showCollapse{
      display: block;
    }

    /* User section */
    .user-section {
      padding: 1.5rem;
      border-top: 1px solid var(--border-color);
      margin-top: auto;
      background-color: var(--bg-color);
    }

    .user-info {
      display: flex;
      align-items: center;
      padding: 0.75rem;
      border-radius: 0.75rem;
      background-color: var(--hover-color);
      transition: all 0.3s ease;
    }

    .user-info:hover {
      background-color: var(--active-color);
      transform: translateY(-2px);
    }

    .user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.75rem;
      font-weight: 500;
      font-size: 1.1rem;
      box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .user-details {
      flex: 1;
    }

    .user-name {
      font-size: var(--normal-font-size);
      font-weight: 500;
      color: var(--text-color);
      margin-bottom: 0.25rem;
    }

    .user-role {
      font-size: var(--small-font-size);
      color: var(--text-light);
    }

    .logout-btn {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      color: #ef4444;
      margin-top: 0.75rem;
      border-radius: 0.75rem;
      transition: all 0.3s ease;
      background-color: #fee2e2;
    }

    .logout-btn:hover {
      background-color: #fecaca;
      transform: translateX(5px);
    }

    .logout-icon {
      margin-right: 0.75rem;
      font-size: 1.25rem;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
      body {
        padding: 0;
      }
      .l-navbar {
        left: -100%;
      }
      .show-nav {
        left: 0;
      }
    }
</style>
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