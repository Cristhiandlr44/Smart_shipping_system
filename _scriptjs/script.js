window.addEventListener('resize', function() {
    if(window.outerWidth < 300) {
        window.resizeTo(300, window.outerHeight);
    }
}, true);
