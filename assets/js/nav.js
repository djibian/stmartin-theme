// this function runs when the DOM is ready, i.e. when the document has been parsed
document.addEventListener("DOMContentLoaded", function() {

/*
 * Ajout d'un element div de classe .soulignement sous chaque item du menu principal
 */
var menuItems = Array.from(document.querySelectorAll('.primary-navigation .menu > li'));
menuItems.forEach(menuItem => {
      var divSoulignement = document.createElement('div');
      divSoulignement.classList.add('soulignement');
      menuItem.append(divSoulignement);
    });

/*
 * Fonction qui détermine la hauteur à l'écran en pixels des sous-menus contenant toutes les catégories de produits
 */
  function getMenuHeight(menu) {
    // la hauteur à l'écran en pixels du sous-menu
    if (menu) {
      var myHeight = menu.clientHeight;
    }
    else{
      return 0;
    }
    // on cible le premier sous-menu suivant
    var firstsubmenu = menu.querySelector(':scope .sub-menu');
    if (firstsubmenu) {
      // on cible tous les autres sous-menus
      var submenus = Array.from(firstsubmenu.querySelectorAll(':scope + .sub-menu'));
      // on ajoute le premier sous-menu à la liste
      submenus.unshift(firstsubmenu);
      // on relance la fonction récursivement sur l'ensemble des sous-menus pour déterminer la hauteur totale
      var n=0;
      var subMenuMaxHeight = 0;
      submenus.forEach(submenu => {
        var submenuHeight = getMenuHeight(submenu) + n*myHeight/submenus.length;
        if (submenuHeight > subMenuMaxHeight) {
          subMenuMaxHeight = submenuHeight;
        }
        n=n+1;
      });
      if (subMenuMaxHeight > myHeight) {
        myHeight = subMenuMaxHeight;
      }
    }
    return myHeight;
  }

  /*
  * Collage du menu lors du scroll (.storefront-primary-navigation)
  */
  var navbar = document.querySelector('.storefront-primary-navigation');
  var submenu = navbar.querySelector('.sub-menu');
  var menuHeight = navbar.clientHeight + getMenuHeight(submenu);
  var masthead = document.querySelector('#masthead');
  var sticky = navbar.offsetTop + navbar.clientHeight;

  window.addEventListener('scroll', function() {
    if (window.scrollY >= sticky && document.documentElement.clientHeight >= menuHeight) {
      navbar.classList.add("sticky");
      masthead.classList.add("stickymasthead");
    } else {
      navbar.classList.remove("sticky");
      masthead.classList.remove("stickymasthead");
    }
  });

  /*
  * initialise la taille de la page
  */
  var tailleEcran = jQuery(window).width();
  
  jQuery(window).resize(function() {
    if((tailleEcran >= 768 && jQuery(window).width() < 768) || (tailleEcran < 768 && jQuery(window).width() >= 768))
    {
      tailleEcran = jQuery(window).width();
      self.location.href=self.location.href + "?"; /* recharge la page +"?ts"+new Date()*/
    }
  });


  /* Dans le menu mobile, remplacer l'action sur les catégories de produits par l'ouverture des sous-catégories */
  const handheld = document.getElementsByClassName('handheld-navigation');

  if ( handheld.length > 0 ) {
    [].forEach.call(
      handheld[ 0 ].querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a'),
      function ( anchor ) {
        anchor.removeAttribute('href');
        anchor.setAttribute('role','link');
        anchor.setAttribute('aria-disabled','true');

        // Add event listener
        anchor.addEventListener( 'click', function () {
          anchor.nextSibling.click();
        } );
      }
    );
  }


  
});