var menuBtn = document.getElementById('menu-toggle');
var navigation = document.getElementById('main-navigation');
var article = document.getElementById('main-article');

let toggleClassName = function (el, className) {
    if (el.classList.contains(className)) {
        el.classList.remove(className);
    } else {
        el.classList.add(className);
    }
};
menuBtn.addEventListener('click', function (e) {
    e.preventDefault();
    toggleClassName(menuBtn, 'menu-closed');
    toggleClassName(navigation, 'hidden');
    toggleClassName(article, 'hidden');
});