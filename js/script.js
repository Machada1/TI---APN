$(function () {
    var path = window.location.pathname;
    var parts = path.split('/');
    var fileName = parts.pop();

    while (fileName != null) {
        if (fileName.endsWith('.html') || fileName.endsWith('.php')) {
            if (fileName == 'about.html' || fileName == 'index.html' || fileName == 'login.html') {
                $("#header").load("../html/header.html");
            } else {
                $("#header").load("../html/headerLogged.php");
            }
        }

        fileName = parts.pop();

    }
});