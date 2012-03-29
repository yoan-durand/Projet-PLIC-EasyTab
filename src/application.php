<?php
require_once 'inc/init.php';


include 'inc/head.php';
?>
<link rel="stylesheet" href="css/application.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.18.custom.css" />
<link rel="stylesheet" href="JQuery-PLUGIN/jquery.svg.css" />
<script src="js/jquery-1.7.1.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jeditable.mini.js"></script>
<script src="JQuery-PLUGIN/jquery.svg.min.js"></script>
<script src="JQuery-PLUGIN/jquery.svganim.min.js"></script>
<script src="JQuery-PLUGIN/jquery.svgdom.min.js"></script>
<script src="js/Partition.js"></script>
<script src="js/Instrument.js"></script>
<script src="js/TrackPart.js"></script>
<script src="js/Lines.js"></script>
<script src="js/Measure.js"></script>
<script src="js/Attribute.js"></script>
<script src="js/Chord.js"></script>
<script src="js/SoundParam.js"></script>
<script src="js/Note.js"></script>       
<script src="js/Parser.js"></script>
<script src="js/Event.js"></script>
<?php
include 'inc/header.php';
?>
<section class="page">
    <section class="float-left">
        <section class="header">
            <section class="player_section float-left">
                <section class="player">
                    <img id="back" src="image/playerback.png" />
                    <img id="play" src="image/playerplay.png" />
                    <img id="pause" src="image/playerpause.png" />
                    <img id="stop" src="image/playerstop.png" />
                </section>
                <section class="player_options">
                    <section class="float-left">
                        <img id="tempo" class="float-left" src="image/tempo.png" />
                        <section class="text float-left tempo"></section>
                        <section class="clear"></section>
                    </section>
                    <section class="float-left">
                        <img id="speed" class="float-left" src="image/vitesse.png" />
                        <section class="text float-left">x 1.00</section>
                        <section class="clear"></section>
                    </section>
                    <section class="clear"></section>
                </section>
            </section>
            <section class="instruments float-right">
            </section>
            <section class="clear"></section>
        </section>
        <section class="tab_svg overflow_svg">
            <!--<embed src="SVG/partition.svg" width="900" height="400" type="image/svg+xml" />-->
        </section>
        <section class="progress_bar overflow_measure">
            <table>
                <tr>
                </tr>
                <tr>
                </tr>
            </table>
        </section>
    </section>
    <section class="clear"></section>
</section>

<script type="text/javascript">
    
    var svg_inst = new Array();
    var current_svg = 0;
    
    $(document).ready (function()
    {
        $(".tab_svg").css("height", 60 + Math.ceil((80 * (nb_measure / 4))));
        $(".tempo").text(partition._instruments_list[0]._track_part._measure_list[0]._sound_params._tempo);
        
        for (var i = 0; i < partition._instruments_list.length; i++)
        {
            if (i == 0)
            {
                $(".tab_svg").append("<section id='"+i+"' style='display: block;'></section>");
            }
            else
            {
                $(".tab_svg").append("<section id='"+i+"' style='display: none;'></section>");
            }
            $(".tab_svg #"+i).svg({onLoad: function(svg) 
            {
                svg_inst.push(svg);
                var height = 60 + Math.ceil((80 * (nb_measure / 4))) < 400 ? 400 : 60 + Math.ceil((80 * (nb_measure / 4)));
                svg.rect(0, 0, 900, height, { fill: "white", stroke:"black"});
                drawLines(svg, i);
            }
        });
        }
        /*
        $('.tab_svg').svg({onLoad: function(svg) 
        {
            svg_inst = svg;
            var height = 60 + Math.ceil((80 * (nb_measure / 4))) < 400 ? 400 : 60 + Math.ceil((80 * (nb_measure / 4)));
            svg.rect(0, 0, 900, height, { fill: "white", stroke:"black"});
            drawLines(svg);
        }
        });*/

        function drawLines(svg, svg_index){
            for (var i = 0; i < nb_measure / 4; i++)
            {
                svg.text(20, 45 + (i * 80), "T");
                svg.text(20, 60 + (i * 80), "A");
                svg.text(20, 75 + (i * 80), "B");
                svg.text(60, 25 + (i * 80), ""+(1 + (4 * i))+"", {stroke: "red", "font-size": "10px"});
                svg.rect(60, 30 + (i * 80), 205, 50, {id: "m_"+ (1 + (4 * i)),fill:"white", stroke:"black"});
                svg.text(265, 25 + (i * 80), ""+(2 + (4 * i))+"", {stroke: "red", "font-size": "10px"});
                svg.rect(265, 30 + (i * 80), 205, 50, {id: "m_"+ (2 + (4 * i)),fill:"white", stroke:"black"});
                svg.text(470, 25 + (i * 80), ""+(3 + (4 * i))+"", {stroke: "red", "font-size": "10px"});
                svg.rect(470, 30 + (i * 80), 205, 50, {id: "m_"+ (3 + (4 * i)),fill:"white", stroke:"black"});
                svg.text(675, 25 + (i * 80), ""+(4 + (4 * i))+"", {stroke: "red", "font-size": "10px"});
                svg.rect(675, 30 + (i * 80), 205, 50, {id: "m_"+ (4 + (4 * i)),fill:"white", stroke:"black"});
                svg.line(60, 30 + (i * 80), 880, 30 + (i * 80), {stroke:"black"});
                svg.line(60, 40 + (i * 80), 880, 40 + (i * 80), {stroke:"black"});
                svg.line(60, 50 + (i * 80), 880, 50 + (i * 80), {stroke:"black"});
                svg.line(60, 60 + (i * 80), 880, 60 + (i * 80), {stroke:"black"});
                svg.line(60, 70 + (i * 80), 880, 70 + (i * 80), {stroke:"black"});
                svg.line(60, 80 + (i * 80), 880, 80 + (i * 80), {stroke:"black"});
                drawNotes(svg, i, svg_index);
            }
            svg.rect(60, 20, 2, 70, {id: "cursor_"+svg_index, fill:"red"});
        };
        
        var division;
        
        function drawNotes(svg, i, svg_index){
            var measures = partition._instruments_list[svg_index]._track_part._measure_list;
            
            for (var mes = (0 + i) * 4;  mes < measures.length && mes < (4 + (i * 4)); mes++)
            {
                division = measures[mes]._attributes._division == null ? division : measures[mes]._attributes._division;
                var chords = measures[mes]._chord_list;
                var interval = 0;
                for (var chord = 0; chord < chords.length; chord++)
                {
                    var notes = chords[chord]._note_list;//chords._note_list;
                    var duration;
                    for (var note = 0; note < notes.length; note++)
                    {
                        var current_mesure = (mes + 1) % 4 == 0 ? 3 : ((mes + 1) % 4) - 1;
                        var fret = notes[note]._fret_technical;
                        var string = notes[note]._string_technical;
                        duration = notes[note]._duration;
                        if (fret != null)
                        {
                            if (fret.length == 1)
                            {
                                svg.rect((current_mesure * 205) + 60 + interval, (30 + (string - 1) * 10) + (i * 80) - 5, 5.5, 11, {fill:"white"});
                            }
                            else
                            {
                                svg.rect((current_mesure * 205) + 60 + interval, (30 + (string - 1) * 10) + (i * 80) - 5, 11, 11, {fill:"white"});
                            }
                            svg.text((current_mesure * 205) + 60 + interval, (34 + (string - 1) * 10) + (i * 80), ""+ fret +"", {stroke: "blue", "font-size": "11px"});
                        }
                        else
                        {
                            svg.rect((current_mesure * 205) + 60 + interval, (30 + (string - 1) * 10) + (i * 80) - 5, 5.5, 11, {fill:"white"});
                            svg.text((current_mesure * 205) + 60 + interval, (35 + (string - 1) * 10) + (i * 80), "X", {stroke: "blue", "font-size": "11px"});
                        }
                        //interval += parseInt(duration) + 15;
                    }
                    interval += 50 * (parseInt(duration) / parseInt(division));//interval = duration;
                }
                
            }
        };

        $($("rect[id^='m_']"), svg_inst[current_svg].root()).click(function (){
            var id = $(this).attr("id");
            if ($("img[id='"+id+"']").attr("src") == "image/casegrise.png")
            {
                $("img[id='m_"+selected+"']").attr("src", "image/casegrise.png");
                $("img[id="+id+"]").attr("src", "image/casebleue.png");
                var array = id.split('_');
                selected = array[1];
                elapsed_time = (selected % 4) == 0 ? (speed * 0.75) : ((speed / 4) * ((selected % 4) - 1));
                line = (selected % 4 == 0) ? (((selected / 4) | 0) - 1) : ((selected / 4) | 0);
                $($("rect[id^='cursor']"), svg_inst[current_svg].root()).attr({"y": (20 + (80 * line)), transform:"translate("+ ($($("rect[id='m_"+selected+"']"), svg_inst[current_svg].root()).attr("x") - 60) +" 0)"});
            }
        });
        
        $(".tempo").editable(
            function(value, settings) {
                var tempo = value;
                var beat = partition._instruments_list[0]._track_part._measure_list[0]._attributes._time_beat;
                speed = (((beat * 4) * 60) / tempo) * 1000;
                
                return value;
            },
            {
                event : "dblclick",
                style : "inherit"
        });
        
        $.ajax({
            type: "POST",
            url: 'js/MIDI.php',
            data: { 'encoded' : JSON.stringify({ encoded : partition }) },
            dataType: "json",
            success: function (data, textStatus, jqXHR) {
            },
            error: function (xhr, status, err) {
                alert("fail");
            }
        });
        
    });
</script>
<?php
include 'inc/footer.php';