$(function(){
    var path = window.location.pathname;
    var parts = path.split('/');
    var fileName = parts.pop() || parts.pop();

    if(fileName == 'about.html' || fileName == 'index.html' || fileName == 'login.html'){
        $("#header").load("/html/header.html");
    }else{
        $("#header").load("/html/headerLogged.html");
    }

    $("#footer").load("/html/footer.html");
});