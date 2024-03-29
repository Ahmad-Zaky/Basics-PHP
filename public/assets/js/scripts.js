"use strict";

const toggleUserEl = document.querySelector("#user-menu-button");
const signoutEl = document.querySelectorAll(".user-signout");
if (toggleUserEl) toggleUserEl.onclick = e => toggleUserMenu(e);
if (signoutEl && signoutEl.length) signoutEl.forEach( el => el.onclick = e => signout(e));

function toggleUserMenu(e) {
    const showClasses = ["show", "transform", "opacity-100", "scale-100"]
    const hideClasses = ["hide", "transform", "opacity-0", "scale-95"]
    
    let menu = document.querySelector('[aria-labelledby="user-menu-button"]');

    // Show
    if (menu.classList.contains("hide")) {
        menu.classList.add(...showClasses);
        menu.classList.remove(...hideClasses);
        
        return;
    }

    // Hide
    if (menu.classList.contains("show")) {
        menu.classList.add(...hideClasses);    
        menu.classList.remove(...showClasses);

        return;
    }
}

function signout(e) {
    e.preventDefault();
    e.stopPropagation();

    e.target.closest("form").submit();
}

document.querySelector("#completed")
    .addEventListener('change', function() {
        const completed = document.querySelector("#completed-hidden");
        completed.value = this.checked ? 1 : 0;
    });
