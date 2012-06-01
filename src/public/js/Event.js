var selected = 1;
var nb_measure;
var line = 0;
var elapsed_time = 0;
var time_func;
var speed;

$(document).ready(function(){
    
    nb_measure = partition._instruments_list[0]._track_part._measure_list.length;
    var tempo = partition._instruments_list[0]._track_part._measure_list[0]._sound_params._tempo;
    var beat = partition._instruments_list[0]._track_part._measure_list[0]._attributes._time_beat;
    speed = (((beat * 4) * 60) / tempo) * 1000;
    measure(nb_measure);
    tracks(partition._instruments_list);
    
    /// SECTION EVENT PLAYER
    
    $("#back").click(function (){
        if ($(this).attr("src") == "image/playerback.png")
        {
            javascript:document.demo.Stop();
            javascript:document.demo.SetTime(0);
            javascript:document.demo.Play();
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
            elapsed_time = 0;
            line = 0;
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).stop();
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr({"y": (20 + (80 * line))});
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(800 0)'}, speed - elapsed_time, 'linear', keep_playing);
            time_func = setInterval(chronotime, 100);
        }
    });

    $("#play").click(function (){
        if ($(this).attr("src") == "image/playerplay.png")
        {
            javascript:document.demo.Play();
            time_func = setInterval(chronotime, 100);
            $("#back").attr("src", "image/playerback.png");
            $(this).attr("src", "image/playerplay2.png");
            $("#pause").attr("src", "image/playerpause.png");
            $("#stop").attr("src", "image/playerstop.png");
            setTimeout(function () {
                    $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(800 0)'}, speed - elapsed_time, 'linear', keep_playing);
            }, 500);
        }
    });
    
    function keep_playing(){
        line++;
        elapsed_time = 0;
        if (line < (nb_measure / 4))
        {
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr({"y": (30 + (80 * line))});
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(800 0)'}, speed - elapsed_time, 'linear', keep_playing);
        }
    };
    
    function chronotime()
    {
        elapsed_time += 100;
    }

    $("#pause").click(function (){
        if ($(this).attr("src") == "image/playerpause.png")
        {
			javascript:document.demo.Stop();
            $("#back").attr("src", "image/playerback.png");
            $("#play").attr("src", "image/playerplay.png");
            $(this).attr("src", "image/playerpause2.png");
            $("#stop").attr("src", "image/playerstop.png");
            clearInterval(time_func);
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).stop();
        }
    });

    $("#stop").click(function (){
        if ($(this).attr("src") == "image/playerstop.png")
        {
			javascript:document.demo.Stop();
			javascript:document.demo.SetTime(0);
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
            elapsed_time = 0;
            line = 0;
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).stop();
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr({"y": (20 + (80 * line))});
            $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).animate({svgTransform: 'translate(0 0)'}, 0, 'linear');
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
                $(".progress_bar tr:first-child").append("<td><img id='m_" + j + "' src='image/casegrise.png' /></td>");
                $(".progress_bar tr:nth-child(2)").append("<td>" + j + "</td>");
            }
        }
    }
    
    function tracks(instruments)
    {
        for (var i = 0; i < instruments.length;i++)
        {
            if (i == 0)
            {
                $(".instruments").prepend("<div id='t_"+i+"' class='onglets_pic_selected float-right'><div class='onglets_text_selected'>" + instruments[i]._name_instrument + "</div></div>");
            }
            else
            {
                $(".instruments").prepend("<div id='t_"+i+"' class='onglets_pic float-right'><div class='onglets_text'>" + instruments[i]._name_instrument + "</div></div>");
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
            elapsed_time = (selected % 4) == 0 ? (speed * 0.75) : ((speed / 4) * ((selected % 4) - 1));
            line = (selected % 4 == 0) ? (((selected / 4) | 0) - 1) : ((selected / 4) | 0);
            $($("rect[id^='cursor']"), svg_inst[current_svg].root()).attr({"y": (30 + (80 * line)), transform:"translate("+ ($($("rect[id='m_"+selected+"']"), svg_inst[current_svg].root()).attr("x") - 60) +" 0)"});
        }
    });
    
    $(".onglets_pic, .onglets_pic_selected").click(function(){
        $(".onglets_pic_selected").children().attr("class", "onglets_text");
        $(".onglets_pic_selected").attr("class", "onglets_pic float-right");
        $(this).children().attr("class", "onglets_text_selected");
        $(this).attr("class", "onglets_pic_selected float-right");
        
        var id = $(this).attr("id");
        var array = id.split('_');
        $(".tab_svg #"+current_svg).css("display", "none");
        var y = $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr("y");
        var transform = $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr("transform");
        current_svg = array[1];
        $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr("y", y);
        $($("rect[id='cursor_"+current_svg+"']"), svg_inst[current_svg].root()).attr("transform", transform);
        $(".tab_svg #"+current_svg).css("display", "block");
    });
    
    /// SECTION SCROLLBARS
    
    var current_svg_advance = 100;
    if (nb_measure > 20)
    {
        $("#scroll_svg").slider({
            animate: "slow",
            value: 100,
            orientation: "vertical",
            step: ((100 / ((nb_measure / 4) - 5)) | 0),
            slide: function (event, ui){
                var step = ((100 / ((nb_measure / 4) - 5)) | 0);
                var current_step = ui.value;
                var advance = ((current_step / step) | 0);
                
                if (advance < current_svg_advance) // On va vers le bas
                {
                    for (var i = 0; i < svg_inst.length; i++)
                    {
                        svg_inst[i].root().currentTranslate.y -= 80;
                    }
                }
                else // on va vers le haut
                {
                    for (var i = 0; i < svg_inst.length; i++)
                    {
                        if (svg_inst[i].root().currentTranslate.y + 80 <= 0)
                        {
                            svg_inst[i].root().currentTranslate.y += 80;
                        }
                    }
                }
                current_svg_advance = advance;
            }
        });
    }
    
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
                }
                current_advance = advance;
            }
        });
    }
});