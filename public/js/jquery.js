
////////////////////////////////////////////////////////////
//--------------------------------------------------------//
//                                                        //
//--------------------------------------------------------//
//                                                        //
//--------------------------------------------------------//
//  NO HAY VALIDACIONES VIA CLIENTE, NO DESACTIVES EL JS. //
//--------------------------------------------------------//
//                                                        //
//--------------------------------------------------------//
//                                                        //
//--------------------------------------------------------//
////////////////////////////////////////////////////////////

$(document).ready(function() {

    //Animacion Filtros.
    var showFilters = true;
    var showNavLateral = true;

    $('#showfilters').bind('click', function() {
        if (showFilters) {
            $('#showfilters').css("color", "rgb(44, 44, 85)");
            $("#container-filter-header").removeClass("hideMainFilters");
        } else {
            $('#showfilters').css("color", "white");
            $("#container-filter-header").addClass("hideMainFilters");
        }

        showFilters = !showFilters;
    });

    // Nav lateral.
    function NavLateral() {
        if (showNavLateral) {
            $('#openNavLateral').css("color", "#650800");
            $('#sideNav').removeClass("sideNavHidden");
            showNavLateral = false;
        } else {
            $('#openNavLateral').css("color", "white");
            $('#sideNav').addClass("sideNavHidden");
            showNavLateral = true;
        }
    }

    $('#openNavLateral').bind('click', function() { NavLateral() });
    $('#closeNavLateral').bind('click', function() { NavLateral() });


    // Modal y Overlay.
    $('.auth').bind('click', function() {
        $('#overlay').addClass("overlayShow");
        $('#modal').removeClass("modalHidden");
    });

    $('#overlay').bind('click', function() {
        $('#overlay').removeClass("overlayShow");
        $('#modal').addClass("modalHidden");
    });

    // Animacion Articles (Index).
    var time = 100;
    $(".event-card").each( function() {
        // $(this).delay(time).animate({"opacity": "1"}, 100);
        var article = $(this);

        setTimeout(function() {
            //alert("aaaahhhhh");
            article.css("animation", "drop 1s");
            article.css("opacity", "1");
        }, time);
        time += 100;
    });

    $('.userformdetect').on('change', function() {
        $('.userformdetect').submit();
    });

    $('.eventformdetect').on('change', function() {
        $('.eventformdetect').submit();
    });


/*  var objDiv = $("#com-pan-chat-msg>div");
    console.log(objDiv);
    objDiv.scrollTop = objDiv.scrollHeight; */
});