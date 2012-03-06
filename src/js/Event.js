var selected = 1;

$(document).ready(function(){
    
    measure(32);
    tracks(new Array("Lead","Rythm","Bass","Drum"));
    
    /// SECTION EVENT PLAYER
    
    $("#back").click(function (){
        if ($(this).attr("src") == "image/playerback.png")
        {
            $(this).attr("src", "image/playerback2.png");
            $("#play").attr("src", "image/playerplay.png");
            $("#pause").attr("src", "image/playerpause.png");
            $("#stop").attr("src", "image/playerstop.png");
            if (selected != 1)
            {
                $("#m_" + selected).attr("src", "image/casegrise.png");
                $("#m_1").attr("src", "image/casebleue.png");
                selected = 1;
            }
            setTimeout(function (){
                $("#back").attr("src", "image/playerback.png");
            }, 500);
        }
    });

    $("#play").click(function (){
        if ($(this).attr("src") == "image/playerplay.png")
        {
            $("#back").attr("src", "image/playerback.png");
            $(this).attr("src", "image/playerplay2.png");
            $("#pause").attr("src", "image/playerpause.png");
            $("#stop").attr("src", "image/playerstop.png");
        }
    });

    $("#pause").click(function (){
        if ($(this).attr("src") == "image/playerpause.png")
        {
            $("#back").attr("src", "image/playerback.png");
            $("#play").attr("src", "image/playerplay.png");
            $(this).attr("src", "image/playerpause2.png");
            $("#stop").attr("src", "image/playerstop.png");
        }
    });

    $("#stop").click(function (){
        if ($(this).attr("src") == "image/playerstop.png")
        {
            $("#back").attr("src", "image/playerback.png");
            $("#play").attr("src", "image/playerplay.png");
            $("#pause").attr("src", "image/playerpause.png");
            $(this).attr("src", "image/playerstop2.png");
            if (selected != 1)
            {
                $("#m_" + selected).attr("src", "image/casegrise.png");
                $("#m_1").attr("src", "image/casebleue.png");
                selected = 1;
            }
            setTimeout(function (){
                $("#stop").attr("src", "image/playerstop.png");
            }, 500);
        }
    });
    
    /// SECTION INITIALISATION GRAPHIQUE MESURES + TRACKS
    
    function measure(i)
    {
        for (var j = 1; j < i; j++)
        {
            if (j == 1)
            {
                $(".progress_bar tr:first-child").append("<td><img id='m_" + j + "' src='image/casebleue.png' /></td>");
            }
            else
            {
                $(".progress_bar tr:first-child").append("<td><img id='m_" + j + "' src='image/casegrise.png' /></td>");
            }
            $(".progress_bar tr:nth-child(2)").append("<td>" + j + "</td>");
        }
    }
    
    function tracks(instruments)
    {
        for (var i = 0; i < instruments.length;i++)
        {
            if (i == 0)
            {
                $(".instruments").prepend("<div class='onglets_pic_selected float-right'><div class='onglets_text_selected'>" + instruments[i] + "</div></div>");
            }
            else
            {
                $(".instruments").prepend("<div class='onglets_pic float-right'><div class='onglets_text'>" + instruments[i] + "</div></div>");
            }
        }
        $(".instruments").append("<section class='clear'></section>");
    }
    
    /// SECTION EVENT MESURES + TRACKS SELECTION
    
    $(".progress_bar img").click(function (){
        if ($(this).attr("src") != "image/casebleue.png")
        {
            $(this).attr("src", "image/casebleue.png");
            $("#m_" + selected).attr("src", "image/casegrise.png");
            var id = $(this).attr("id");
            var array = id.split('_');
            var new_selected = array[1];
            selected = new_selected;
        }
    });
    
    $(".onglets_pic, .onglets_pic_selected").click(function(){
        $(".onglets_pic_selected").children().attr("class", "onglets_text");
        $(".onglets_pic_selected").attr("class", "onglets_pic float-right");
        $(this).children().attr("class", "onglets_text_selected");
        $(this).attr("class", "onglets_pic_selected float-right");
    });
});