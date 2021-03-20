function getHome(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/"){
        hideMenu();
        document.title = "Home | Culrav";
        history.pushState({urlPath:'./'},"Home | Culrav",'./');
        window.location.href = window.location.pathname+"#";
        return;
    }
    var data = {'action':"getContentPage",'page':"home"};
    $.ajax({
        url:'index',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){hideMenu();
            generateNoiseOnImages();
            if(inverted == true){setColors();}
        }
    });
    document.title = "Home | Culrav";
    history.pushState({urlPath:'./'},"Home | Culrav",'./');
    window.location.href = window.location.pathname+"#";
}
function getEvents(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/events"){
        hideMenu(); return;
    }
    var data = {'action':"getContentPage",'page':"events"};
    $.ajax({
        url:'events',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){hideMenu();
            generateNoiseOnImages();setColors();}
    });
    document.title = "Events | Culrav";
    history.pushState({urlPath:'./events'},"Events | Culrav",'./events');
    if(inverted == true){setColors();}
}
function getRegister(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/register"){
        return;
    }
    var data = {'action':"getContentPage",'page':"register"};
    $.ajax({
        url:'register',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
            if(data == ""){
                window.location.href = "dashboard";
            }
        },
        complete:function(){
            generateNoiseOnImages();setColors();}
    });
    document.title = "Register | Culrav";
    history.pushState({urlPath:'./register'},"Register | Culrav",'./register');
    if(inverted == true){setColors();}
}
function getCelebs(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/celebs"){
        hideMenu(); return;
    }
    var data = {'action':"getContentPage",'page':"celebs"};
    $.ajax({
        url:'celebs',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){hideMenu();
            generateNoiseOnImages();setColors();}
    });
    document.title = "Celebs | Culrav";
    history.pushState({urlPath:'./celebs'},"Celebs | Culrav",'./celebs');
    if(inverted == true){setColors();}
}
function getTeam(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/team"){
        hideMenu(); return;
    }
    var data = {'action':"getContentPage",'page':"team"};
    $.ajax({
        url:'team',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){hideMenu();
            generateNoiseOnImages();setColors();}
    });
    document.title = "Team | Culrav";
    history.pushState({urlPath:'./team'},"Team | Culrav",'./team');
    if(inverted == true){setColors();}
}
function getContacts(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/contacts"){
        hideMenu(); return;
    }
    var data = {'action':"getContentPage",'page':"contacts"};
    $.ajax({
        url:'contacts',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){hideMenu();
            generateNoiseOnImages();setColors();}
    });
    document.title = "Contacts | Culrav";
    history.pushState({urlPath:'./contacts'},"Contacts | Culrav",'./contacts');
    if(inverted == true){setColors();}
}
function getFAQ(){
    var pathname = window.location.pathname;
    if(pathname == "/culrav/faq"){
        return;
    }
    var data = {'action':"getContentPage",'page':"faq"};
    $.ajax({
        url:'faq',
        method:"POST",
        data:data,
        dataType: "text",
        success:function(data){
            $('#main-page-content').html(data);
            createGrid();
            AOS.init();
        },
        complete:function(){
            generateNoiseOnImages();
            $(".question").on("click",function(){
                if($(this).hasClass("show")){
                    $(this).removeClass("show");
                }
                else{
                    $(this).addClass("show");
                }
            });
            setColors();
        }
    });
    document.title = "FAQ | Culrav";
    history.pushState({urlPath:'./faq'},"FAQ | Culrav",'./faq');
    if(inverted == true){setColors();}
}
function getAbout(){
    var pathname = window.location.pathname;
    if(pathname != "/culrav/"){
        var data = {'action':"getContentPage",'page':"about"};
        $.ajax({
            url:'index',
            method:"POST",
            data:data,
            dataType: "text",
            success:function(data){
                $('#main-page-content').html(data);
                createGrid();
                AOS.init();
            },
            complete:function(){
                setTimeout(function(){
                    pathname = window.location.pathname;
                    if(pathname == "/culrav/"){
                        window.location.href = window.location.pathname+"#about_us";
                    }
                },500);
                hideMenu();
                generateNoiseOnImages();
                document.title = "About | Culrav";
                history.pushState({urlPath:'./#about_us'},"About | Culrav",'./#about_us');
                if(inverted == true){setColors();}
            }
        });
    }
    else{
        pathname = window.location.pathname;
        if(pathname == "/culrav/"){
            window.location.href = window.location.pathname+"#about_us";
        }
        document.title = "About | Culrav";
        hideMenu();
    }
}
function getSponsors(){
    var pathname = window.location.pathname;
    if(pathname != "/culrav/"){
        var data = {'action':"getContentPage",'page':"home"};
        $.ajax({
            url:'index',
            method:"POST",
            data:data,
            dataType: "text",
            success:function(data){
                $('#main-page-content').html(data);
                createGrid();
                AOS.init();
            },
            complete:function(){
                setTimeout(function(){
                    pathname = window.location.pathname;
                    if(pathname == "/culrav/"){
                        window.location.href = window.location.pathname+"#sponsors";
                    }
                },500);
                hideMenu();
                generateNoiseOnImages();
                document.title = "Sponsors | Culrav";
                history.pushState({urlPath:'./#sponsors'},"Sponsors | Culrav",'./#sponsors');
                if(inverted == true){setColors();}
            }
        });
    }
    else{
        pathname = window.location.pathname;
        if(pathname == "/culrav/"){
            window.location.href = window.location.pathname+"#sponsors";
        }
        document.title = "Sponsors | Culrav";
        hideMenu();
        generateNoiseOnImages();
        if(inverted == true){setColors();}
    }
}
function hideMenu(){$('.menu-right .menu-marker > input[type=checkbox]').trigger("click");}
function generateNoiseOnImages(){
    $('.glitch-image').append('<div class="noise-over-images"></div>');
}
generateNoiseOnImages();
var inverted = false;

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function invertColors(){
    if(getComputedStyle(document.documentElement).getPropertyValue('--background-color') == "#00624E"){
        document.documentElement.style.setProperty('--background-color', '#00E7B6');
        document.documentElement.style.setProperty('--highlight-color', '#cccccc77');
        document.documentElement.style.setProperty('--text-color', 'black');
        $('#silhouette').css("filter","brightness(0) invert(1)");
        $('.light-image').css("filter","brightness(1)");
        var pathname = window.location.pathname;
        $('.noise::before,.noise-over-images').css("display","none");
        if((pathname == '/culrav/') || (window.location.hash != "")){
            $('.noise::before,.noise-over-images').css("background","url('images/noise-white.png')");
        }
        else{
            $('.noise::before,.noise-over-images').css("background","url('../culrav/images/noise-white.png')");
        }
        $('.scanlines::before').css("animation-name","scanlines-white");
        var style = ".glitch-image:hover::before{animation-name:scanlines-white !important;}";
        $('#new-style').html(style);
        createCookie('color','0',2);
        inverted = true;
    }
    else{
        document.documentElement.style.setProperty('--background-color', '#00624E');
        document.documentElement.style.setProperty('--highlight-color', '#55555577');
        document.documentElement.style.setProperty('--text-color', 'white');
        $('#silhouette').css("filter","brightness(20%)");
        $('.light-image').css("filter","brightness(0) invert(1)");
        var pathname = window.location.pathname;
        if((pathname == '/culrav/') || (window.location.hash != "")){
            $('.noise::before,.noise-over-images').css("background","url('images/noise.png')");
        }
        else{
            $('.noise::before,.noise-over-images').css("background","url('../culrav/images/noise.png')");
        }
        $('#new-style').remove();
        $('.scanlines::before').css("animation-name","scanlines");
        createCookie('color','1',2);
        inverted = false;
    }
}
function setColors(){
    if(inverted == true){
        document.documentElement.style.setProperty('--background-color', 'white');
        document.documentElement.style.setProperty('--highlight-color', '#cccccc77');
        document.documentElement.style.setProperty('--text-color', 'black');
        $('#silhouette').css("filter","brightness(0) invert(1)");
        $('.light-image').css("filter","brightness(1)");
        var pathname = window.location.pathname;
        $('.noise::before,.noise-over-images').css("display","none");
        if((pathname == '/culrav/') || (window.location.hash != "")){
            $('.noise-over-images').css("background","url('images/noise-white.png')");
        }
        else{
            $('.noise-over-images').css("background","url('../culrav/images/noise-white.png')");
        }
        $('.scanlines::before').css("animation-name","scanlines-white");
        var style = ".glitch-image:hover::before{animation-name:scanlines-white !important;}";
        $('#new-style').html(style);
    }
    else{
        document.documentElement.style.setProperty('--background-color', 'black');
        document.documentElement.style.setProperty('--highlight-color', '#55555577');
        document.documentElement.style.setProperty('--text-color', 'white');
        $('#silhouette').css("filter","brightness(20%)");
        $('.light-image').css("filter","brightness(0) invert(1)");
        var pathname = window.location.pathname;
        if((pathname == '/culrav/') || (window.location.hash != "")){
            $('.noise-over-images').css("background","url('images/noise.png')");
        }
        else{
            $('.noise-over-images').css("background","url('../culrav/images/noise.png')");
        }
        $('#new-style').remove();
        $('.scanlines::before').css("animation-name","scanlines");
    }
}
$(".question").on("click",function(){
    if($(this).hasClass("show")){
        $(this).removeClass("show");
    }
    else{
        $(this).addClass("show");
    }
});
function createGrid()
{   
    var cellWidth = 60; 
    var cellHeight =  60;
    var griddiv = document.getElementById("neon-grid");
    if(griddiv == undefined) return;
    if ((cellWidth==0) && (cellHeight==0)) 
    { griddiv.style.display = "none"; return; }
    griddiv.style.display ="block"; 
    griddiv.style.width = window.innerWidth + "px";
    if (cellWidth== 0) cellWidth = window.innerWidth;
    if (cellHeight==0) cellHeight = window.innerHeight;
    var cols= Math.floor(window.innerWidth/cellWidth); 
    var rows= Math.floor(window.innerHeight/cellHeight);
    var lastcellWidth = window.innerWidth - cols*cellWidth; 
    var lastcellHeight = 0; 
    // alert(lastcellWidth); 
    if (cellWidth*cols < window.innerWidth) cols++; 
    if (cellHeight*rows < window.innerHeight)
    {  lastcellHeight = window.innerHeight - rows*cellHeight; 
       rows++; 
    }
    var tbody = document.getElementById("gridTable");
    tbody.innerHTML = "";
    rows = rows * 15;
    for(var i=0; i < rows; i++)
    {  var row = document.createElement("tr");
      for(var j=0; j < cols; j++)
      {  var cell= document.createElement("td");
         if ((cols >1) && (j== cols-1)) cell.setAttribute("style","width:" + lastcellWidth + "px");
         //cell.innerHTML = "&nbsp;";
         row.appendChild(cell);
      }
      tbody.appendChild(row);
    }
    var lastRowStyle = "";
    if (lastcellHeight != 0) // decide the height of cells in lastRow
    { lastRowStyle = "#neon-grid table tr:last-child td { height:"+ lastcellHeight + "px; }"; }
    document.getElementById("gridExtra").innerHTML= "#neon-grid table td { width:" + cellWidth +"px; height:"+ cellHeight + "px; } " + lastRowStyle ;
}
function removeGrid(){
    var x = document.getElementById("gridTable");
    if(x == undefined) return;
    x = x.getElementsByTagName("tr");
    for(const a of x){
        a.remove();
    }
}
window.addEventListener("load", createGrid , false);
window.addEventListener("resize",removeGrid, false);
window.addEventListener("resize",createGrid, false);
$(document).ready(function(){

document.querySelector('.menu-right .menu-marker > input[type=checkbox]').addEventListener("click",function(){
    if(document.querySelector(".menu-right .menu-marker > input[type=checkbox]:checked") !== null){
      document.querySelector('#modal-menu').classList.add("menu-active");
    }
    else{
      document.querySelector('#modal-menu').classList.remove("menu-active");
    }
});

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}
if(readCookie('color') == '0'){ invertColors(); }

$(window).on("mousemove",function(event){
    $('#mouse').css("display","block");
    if((event.target.type === "submit")||(event.target.tagName.toUpperCase() == 'A')||(event.target.tagName.toUpperCase() == 'INPUT')||(event.target.tagName.toUpperCase() == 'SELECT')||(event.target.tagName.toUpperCase() == 'BUTTON')||
    (event.target.parentNode != null && event.target.parentNode !== document && event.target.parentNode.getAttribute('href')!=null)||
    (event.target.parentNode.parentNode != null && event.target.parentNode.parentNode !== document && event.target.parentNode.parentNode.getAttribute('href')!=null)||
    (event.target.type && event.target.type === 'checkbox')){
        $('#mouse').addClass("clickable-target");
    }
    else{
        $('#mouse').removeClass("clickable-target");
    }
    var x = event.clientX;
    var y = event.clientY;
    $('#mouse').css("top",y+"px");
    $('#mouse').css("left",x+"px");
});
$(window).on("mouseout",function(event){$('#mouse').css("display","none");});

AOS.init();

});