<style>
      /*===== GOOGLE FONTS =====*/
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

    /*===== VARIABLES CSS =====*/
    :root{
      --nav-width: 100px;

      /*===== Colores =====*/
      --first-color: #e2e4e7;
      --bg-color: rgba(0, 0, 0, 0);
      --sub-color: rgb(0, 0, 0);
      --white-color: #303030;
      --black-color:rgb(20, 76, 124);
      
      /*===== Fuente y tipografia =====*/
      --body-font: 'Poppins', sans-serif;
      --normal-font-size: 1rem;
      --small-font-size: .875rem;
      
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
      padding: 2rem 0 0 16rem;
      transition: .5s;
    }
    .svg{
      display : inline-block;
      height : 74px;
      max-width : 100%;
      width : 97.0833px;
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
      font-size: 1rem;
      text-decoration: none;
    }

    /*===== l NAV =====*/
    .l-navbar{
      position: fixed;
      top: 0;
      left: 0;
      width: var(--nav-width);
      height: 100vh;
      background-color: var(--bg-color);
      color: var(--white-color);
      padding: 1.5rem 1.5rem 2rem;
      transition: .5s;
      z-index: var(--z-fixed);
      border:solid .5px;
      border-color: black;
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
      display: grid;
      grid-template-columns: max-content max-content;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    .nav__toggle{
      font-size: 1rem;
      padding: .75rem;
      cursor: pointer;
    }
    .nav__logo{
      color: var(--white-color);
      font-weight: 600;
    }

    .nav__link{
      display: grid;
      grid-template-columns: max-content max-content;
      align-items: center;
      column-gap: .75rem;
      padding: .10rem;
      color: var(--white-color);
      border-radius: .5rem;
      margin-bottom: 1rem;
      transition: .3s;
      cursor: pointer;
    }

    .nav__link:hover{
      color: var(--black-color);
    }

    .nav__icon{
      font-size: 1rem;
      margin-bottom: 5px;
    }


    /*Expander menu*/
    .expander{
      width: calc(var(--nav-width) + 9.25rem);
      
    }




    /*===== COLLAPSE =====*/
    .collapse{
      grid-template-columns: 20px max-content 1fr;
    }
    .collapse__link{
      justify-self: flex-end;
      transition: .5s;
    }
    .collapse__menu{
      display: none;
      padding: .75rem 1rem;
    }
    .collapse__sublink{
      display: grid;
      grid-template-columns: max-content max-content;
      column-gap: .75rem;
      border-radius: .5rem;
      margin-bottom: 1rem;
      color: var(--sub-color);
      font-size: var(--small-font-size);
      
    }
    .collapse__sublink:hover{
      color: var(--black-color);
    }

    /*Show collapse*/
    .showCollapse{
      display: block;
    }
</style>
<body id="body-pd">
        <div class="l-navbar expander" id="navbar">
            <nav class="nav ">
                <div>
                    <div>
                      <img class="svg" src="../assets/svg/cropped-Fichier-1.png" >
                    </div>
                    <div class="nav__brand">
                    </div>
                    <div class="nav__list">
                        <a href="index.php" class="nav__link ">
                            <ion-icon name="home-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Dashboard</span>
                        </a>
                        <div  class="nav__link collapse">
                            <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">MEMOIRES</span>

                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu showCollapse">
                                <a href="Ajouter_Memoires.php" class="collapse__sublink">AJOUTS DES MEMOIRES</a>
                                <a href="liste_des_memoire.php" class="collapse__sublink">LISTES DES MEMOIRES </a>
                            </ul>
                        </div>

                        <div class="nav__link collapse">
                            <ion-icon name="settings-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">LIVRE</span>
                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu showCollapse">
                                <a href="Ajouter_Livres.php" class="collapse__sublink">AJOUTS DES LIVRES</a>
                                <a href="liste_Livres.php" class="collapse__sublink">LISTE DES LIVRES</a>
                                
                            </ul>
                         </div>
                        <div class="nav__link collapse">
                            <ion-icon name="people-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">INFORMATION</span>

                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu ">
                                <a href="liste_Encadrant.php" class="collapse__sublink">LISTE DES ENCADRANT</a>
                                <a href="liste_cas.php" class="collapse__sublink">LISTE DES CAS</a>
                                <a href="liste_auteur.php" class="collapse__sublink">LISTE DES AUTEUR</a>
                                <a href="liste_editeur.php" class="collapse__sublink">LISTE DES EDITEUR</a>
                                <a href="liste_theme.php" class="collapse__sublink">LISTE DES THEMES</a>
                                
                            </ul>
                        </div>

                        <div class="nav__link collapse">
                            <ion-icon name="information-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">FORMATION</span>

                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu ">
                                <a href="liste_Encadrant.php" class="collapse__sublink">LISTE DES SPECIALITER</a>
                                <a href="liste_cas.php" class="collapse__sublink">LISTE DES OPTION </a>
                            </ul>
                        </div>


                        <div class="nav__link collapse">
                            <ion-icon name="archive-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">ARCHIVES</span>

                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu ">
                                <a href="archives_formation.php" class="collapse__sublink">ARCHIVES FORMATION</a>
                                <a href="archives_infromation.php" class="collapse__sublink">ARCHIVES INFORMATION</a>
                            </ul>
                        </div>
                    </div>
                </div>

               
            </nav>
        </div>

        
        <!-- ===== IONICONS ===== -->
        <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
        <script>
            let collaps = document.querySelectorAll(".nav__link.collapse");

            collaps.forEach(function(link, index) {
              link.addEventListener("click", function() {
                // Sélectionne le menu correspondant
                let menu = this.querySelector(".collapse__menu");

                // Si le menu est déjà ouvert, on le ferme sans changer les autres
                if (menu.classList.contains("showCollapse")) {
                  menu.classList.remove("showCollapse");
                } else {
                  // Ferme tous les menus sauf le premier
                  collaps.forEach(function(otherLink, otherIndex) {
                    if (otherIndex !== 0) {  // Exclut la première section (index 0)
                      let otherMenu = otherLink.querySelector(".collapse__menu");
                      if (otherMenu) {
                        otherMenu.classList.remove("showCollapse");
                      }
                    }
                  });

                  // Ouvre le menu cliqué
                  menu.classList.add("showCollapse");
                }
              });
            });
        </script>
        
        
    </body>