import './index.css';

var menuBtn = document.getElementById('menu-toggle');
var navigation = document.getElementById('main-navigation');
var article = document.getElementById('main-article');
var prevNext = document.querySelectorAll('.prev-next');

var toggleClassName = function (el, className) {
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

var previous = undefined;
var next = undefined;
var active = undefined;
var navigationLinks = navigation.querySelectorAll('a');

navigationLinks.forEach(function(link) {
    if (next !== undefined || link.href.includes('/docs/') === false) {
        return;
    }
    if (link.classList.contains('text-red')) {
        active = link;
    } else if (active === undefined) {
        previous = link;
    } else if (next === undefined) {
        next = link;
    }
});

if (active !== undefined) {
    prevNext.forEach(function(p) { p.classList.remove('hidden'); });
    previous && renderPreviousNext('.link-previous', previous);
    next && renderPreviousNext('.link-next', next);
}

function renderPreviousNext(which, originalLink) {
    var links = document.querySelectorAll(which);

    links.forEach(function (link) {
        var label = link.querySelector('span');
        label.innerHTML = originalLink.innerHTML.replace(/\d+\. /, '').replace(/<(\S*?)[^>]*>.*?<\/\1>|<.*?\/>/, '');
        link.href = originalLink.href;
        link.classList.remove('hidden');
        link.classList.remove('sm:hidden');
    });
}
