jQuery(document).ready(function($){

    var body = $('body');
    var siteHeader = $('#site-header');
    var titleContainer = $('#title-container');
    var toggleNavigation = $('#toggle-navigation');
    var menuPrimaryContainer = $('#menu-primary-container');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items');
    var toggleDropdown = $('.toggle-dropdown');
    //var toggleSidebar = $('#toggle-sidebar');
    //var sidebarPrimary = $('#sidebar-primary');
    //var sidebarPrimaryContent = $('#sidebar-primary-content');
    //var sidebarWidgets = $('#sidebar-primary-widgets');
    //var socialMediaIcons = siteHeader.find('.social-media-icons');
    var menuLink = $('.menu-item').children('a');

    toggleNavigation.on('click', openPrimaryMenu);
    body.on('click', '#search-icon', openSearchBar);

    function openPrimaryMenu() {

        if( menuPrimaryContainer.hasClass('open') ) {
            menuPrimaryContainer.removeClass('open');
            $(this).removeClass('open');

            // change screen reader text
            $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {
            menuPrimaryContainer.addClass('open');
            $(this).addClass('open');

            // change screen reader text
            $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    }

    // display the dropdown menus
    toggleDropdown.on('click', openDropdownMenu);

    function openDropdownMenu() {

        // get the buttons parent (li)
        var menuItem = $(this).parent();
        var subMenu = $(this).siblings('ul');
        var parentList = menuItem.parent();

        // if already opened
        if( menuItem.hasClass('open') ) {

            // remove open class
            menuItem.removeClass('open');

            // change screen reader text
            $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

            subMenu.css('max-height', '0');
        } else {

            // add class to open the menu
            menuItem.addClass('open');

            // change screen reader text
            $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');

            var subMenuHeight = 0;
            subMenu.children('li').each(function(){
                subMenuHeight = subMenuHeight + $(this).height();
            });
            subMenu.css('max-height', subMenuHeight);

            // parent ul - expand to include open child submenu
            if ( parentList.hasClass('sub-menu') ) {
                parentList.css('max-height', parseInt(parentList.css('max-height')) + subMenuHeight + 'px');
            }
        }
    }
    /***** Need to refactor *****/

    var counter = 0;
    menuPrimaryItems.find('ul').each(function() {
        $(this).children('li').each(function(){
            $(this).css('transition-delay', '0.' + counter + 's');
            counter++;
        });
        counter = 0;
    });

    function openSearchBar(){

        if( $(this).hasClass('open') ) {

            $(this).removeClass('open');
            socialMediaIcons.removeClass('fade');

            // make search input inaccessible to keyboards
            siteHeader.find('.search-field').attr('tabindex', -1);

            // handle mobile width search bar sizing
            if( window.innerWidth < 900 ) {
                siteHeader.find('.search-form').attr('style', '');
            }
        } else {

            $(this).addClass('open');
            socialMediaIcons.addClass('fade');

            // make search input keyboard accessible
            siteHeader.find('.search-field').attr('tabindex', 0);

            // handle mobile width search bar sizing
            if( window.innerWidth < 800 ) {

                // distance to other side (35px is width of icon space)
                var leftDistance = window.innerWidth * 0.83332 - 35;

                siteHeader.find('.search-form').css('left', -leftDistance + 'px')
            }
        }
    }

    /* allow keyboard access/visibility for dropdown menu items */
    menuLink.focus(function(){
        $(this).parents('ul').addClass('focused');
    });
    menuLink.focusout(function(){
        $(this).parents('ul').removeClass('focused');
    });
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);