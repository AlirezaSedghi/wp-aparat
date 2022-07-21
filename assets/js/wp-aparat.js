(function () {
    if (typeof window.aparat_iframes !== 'undefined') {
        window.aparat_iframes.forEach(function(item, index) {
            let element = document.getElementById(item);
            console.log(item, element);
            element.onload = function() {
                element.style.height = ( 9 * element.offsetWidth / 16 ) + "px";
            }
            window.addEventListener('resize', function() {
                element.style.height = ( 9 * element.offsetWidth / 16 ) + "px";
            });
        });
    }
})();