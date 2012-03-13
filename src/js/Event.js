var selected = 1;
var nb_measure = 62;
var line = 0;
var elapsed_time = 0;
var time_func;

$(document).ready(function(){
    
    measure(nb_measure);
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
                $("img[id='m_" + selected+ "']").attr("src", "image/casegrise.png");
                $("img[id='m_1']").attr("src", "image/casebleue.png");
                selected = 1;
            }
            setTimeout(function (){
                $("#back").attr("src", "image/playerback.png");
            }, 500);
            clearInterval(time_func);
            $($("rect[id='cursor']"), svg_inst.root()).stop();
            elapsed_time = 0;
            line = 0;
            $($("rect[id='cursor']"), svg_inst.root()).attr({"y": (20 + (80 * line))});
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(820 0)'}, 10000 - elapsed_time, 'linear', keep_playing);
            time_func = setInterval(chronotime, 100);
        }
    });

    $("#play").click(function (){
        if ($(this).attr("src") == "image/playerplay.png")
        {
            time_func = setInterval(chronotime, 100);
            $("#back").attr("src", "image/playerback.png");
            $(this).attr("src", "image/playerplay2.png");
            $("#pause").attr("src", "image/playerpause.png");
            $("#stop").attr("src", "image/playerstop.png");
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(820 0)'}, 10000 - elapsed_time, 'linear', keep_playing);
        }
    });
    
    function keep_playing(){
        line++;
        elapsed_time = 0;
        if (line < 5)
        {
            $($("rect[id='cursor']"), svg_inst.root()).attr({"y": (20 + (80 * line))});
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(820 0)'}, 10000 - elapsed_time, 'linear', keep_playing);
        }
    };
    
    function chronotime()
    {
        elapsed_time += 100;
    }

    $("#pause").click(function (){
        if ($(this).attr("src") == "image/playerpause.png")
        {
            $("#back").attr("src", "image/playerback.png");
            $("#play").attr("src", "image/playerplay.png");
            $(this).attr("src", "image/playerpause2.png");
            $("#stop").attr("src", "image/playerstop.png");
            clearInterval(time_func);
            $($("rect[id='cursor']"), svg_inst.root()).stop();
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
                $("img[id='m_" + selected+ "']").attr("src", "image/casegrise.png");
                $("img[id='m_1']").attr("src", "image/casebleue.png");
                selected = 1;
            }
            setTimeout(function (){
                $("#stop").attr("src", "image/playerstop.png");
            }, 500);
            clearInterval(time_func);
            $($("rect[id='cursor']"), svg_inst.root()).stop();
            elapsed_time = 0;
            line = 0;
            $($("rect[id='cursor']"), svg_inst.root()).attr({"y": (20 + (80 * line))});
            $($("rect[id='cursor']"), svg_inst.root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
        }
    });
    
    /// SECTION INITIALISATION GRAPHIQUE MESURES + TRACKS
    
    function measure(i)
    {
        for (var j = 1; j <= i; j++)
        {
            if (j == 1)
            {
                $(".progress_bar tr:first-child").append("<td><img id='m_" + j + "' src='image/casebleue.png' /></td>");
                $(".progress_bar tr:nth-child(2)").append("<td>" + j + "</td>");
            }
            else
            {
                if (j < 32)
                {
                    $(".progress_bar tr:first-child").append("<td><img id='m_" + j + "' src='image/casegrise.png' /></td>");
                    $(".progress_bar tr:nth-child(2)").append("<td>" + j + "</td>");
                }
                else
                {
                    $(".progress_bar tr:first-child").append("<td style='display:none'><img id='m_" + j + "' src='image/casegrise.png' /></td>");
                    $(".progress_bar tr:nth-child(2)").append("<td style='display:none'>" + j + "</td>");
                }
            }
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
            $("img[id='m_" + selected+ "']").attr("src", "image/casegrise.png");
            var id = $(this).attr("id");
            var array = id.split('_');
            selected = array[1];
            elapsed_time = (selected % 4) == 0 ? 7500 : (2500 * ((selected % 4) - 1));
            line = (selected % 4 == 0) ? (((selected / 4) | 0) - 1) : ((selected / 4) | 0);
            $($("rect[id='cursor']"), svg_inst.root()).attr({"y": (20 + (80 * line)), transform:"translate("+ ($($("rect[id='m_"+selected+"']"), svg_inst.root()).attr("x") - 60) +" 0)"});
        }
    });
    
    $(".onglets_pic, .onglets_pic_selected").click(function(){
        $(".onglets_pic_selected").children().attr("class", "onglets_text");
        $(".onglets_pic_selected").attr("class", "onglets_pic float-right");
        $(this).children().attr("class", "onglets_text_selected");
        $(this).attr("class", "onglets_pic_selected float-right");
    });
    
    /// SECTION SCROLLBARS
    
    var current_advance = 0;
    
    if (nb_measure > 31)
    {
        $("#scroll_measure").slider({
            animate: "slow",
            step: ((100 / (nb_measure - 31)) | 0),
            slide: function (event, ui){
                var step = ((100 / (nb_measure - 31)) | 0);
                var current_step = ui.value;
                var advance = ((current_step / step) | 0);
                if (advance > (nb_measure - 31))
                        advance = nb_measure - 31;
                
                if (advance > current_advance) /// On se déplace vers la droite
                {
                    for (var i = current_advance + 1; i <= advance; i++)
                    {
                        $(".progress_bar tr:first-child td:nth-child(" + i + ")").css("display", "none");
                        $(".progress_bar tr:nth-child(2) td:nth-child(" + i + ")").css("display", "none");
                    }
                    for (var j = advance + 1; j <= advance + 31; j++)
                    {
                        $(".progress_bar tr:first-child td:nth-child(" + j + ")").css("display", "table-cell");
                        $(".progress_bar tr:nth-child(2) td:nth-child(" + j + ")").css("display", "table-cell");
                    }
                    current_advance = advance;
                }
                else /// On se déplace vers la gauche
                {
                    for (var i2 = advance + 31; i2 <= current_advance + 31; i2++)
                    {
                        $(".progress_bar tr:first-child td:nth-child(" + i2 + ")").css("display", "none");
                        $(".progress_bar tr:nth-child(2) td:nth-child(" + i2 + ")").css("display", "none");
                    }
                    for (var j2 = advance + 1; j2 <= advance + 31; j2++)
                    {
                        $(".progress_bar tr:first-child td:nth-child(" + j2 + ")").css("display", "table-cell");
                        $(".progress_bar tr:nth-child(2) td:nth-child(" + j2 + ")").css("display", "table-cell");
                    }
                    current_advance = advance;
                }
            }
        });
    }
});