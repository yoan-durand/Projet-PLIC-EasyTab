$(document).ready(function(){
    $("#back").click(function (){
        if ($(this).attr("src") == "image/playerback.png")
        {
            $(this).attr("src", "image/playerback2.png");
        }
        else
        {
            $(this).attr("src", "image/playerback.png");
        }
    });

    $("#play").click(function (){
        if ($(this).attr("src") == "image/playerplay.png")
        {
            $(this).attr("src", "image/playerplay2.png");
        }
        else
        {
            $(this).attr("src", "image/playerplay.png");
        }
    });

    $("#pause").click(function (){
        if ($(this).attr("src") == "image/playerpause.png")
        {
            $(this).attr("src", "image/playerpause2.png");
        }
        else
        {
            $(this).attr("src", "image/playerpause.png");
        }
    });

    $("#stop").click(function (){
        if ($(this).attr("src") == "image/playerstop.png")
        {
            $(this).attr("src", "image/playerstop2.png");
        }
        else
        {
            $(this).attr("src", "image/playerstop.png");
        }
    });
});