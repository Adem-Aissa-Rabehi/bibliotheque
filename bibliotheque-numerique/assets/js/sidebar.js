document.addEventListener('DOMContentLoaded', function() {
    // Get current page
    const currentPage = window.location.pathname.split('/').pop();
    
    // Initialize all collapse menus
    const collapseMenus = document.querySelectorAll('.collapse__menu');
    collapseMenus.forEach(menu => {
        menu.style.display = 'none';
    });

    // Set active link and expand parent menu
    const navLinks = document.querySelectorAll('.nav__link, .collapse__sublink');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
            
            // If it's a sublink, expand the parent menu
            if (link.classList.contains('collapse__sublink')) {
                const parentCollapse = link.closest('.collapse');
                const collapseMenu = parentCollapse.querySelector('.collapse__menu');
                const chevronIcon = parentCollapse.querySelector('.collapse__link');
                
                collapseMenu.style.display = 'block';
                chevronIcon.style.transform = 'rotate(180deg)';
            }
        }
    });

    // Add click handlers for collapse menus
    document.querySelectorAll('.nav__link.collapse').forEach(link => {
        link.addEventListener('click', function(e) {
            // Only handle clicks on the collapse trigger elements
            if (e.target.classList.contains('collapse__link') || 
                e.target.classList.contains('nav__icon') || 
                e.target.classList.contains('nav__name')) {
                e.preventDefault();
                
                const menu = this.querySelector('.collapse__menu');
                const chevron = this.querySelector('.collapse__link');
                
                if (menu.style.display === 'block') {
                    menu.style.display = 'none';
                    chevron.style.transform = 'rotate(0)';
                } else {
                    menu.style.display = 'block';
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
        });
    });

    // Special case for ajouter_documents.php
    if (currentPage === 'ajouter_documents.php') {
        const documentsMenu = document.querySelector('.nav__link.collapse:first-of-type');
        if (documentsMenu) {
            const menu = documentsMenu.querySelector('.collapse__menu');
            const chevron = documentsMenu.querySelector('.collapse__link');
            
            menu.style.display = 'block';
            chevron.style.transform = 'rotate(180deg)';
            
            // Also set the active state for the sublink
            const sublink = documentsMenu.querySelector('a[href="ajouter_documents.php"]');
            if (sublink) {
                sublink.classList.add('active');
            }
        }
    }
}); 