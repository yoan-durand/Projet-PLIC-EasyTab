var partition;
var next_begin = 0;
function getXMLHttpRequest()
{
    var xhr = null;

    if (window.XMLHttpRequest || window.ActiveXObject)
    {
       if (window.ActiveXObject)
       {
            try
            {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e)
            {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
        else
        {
            xhr = new XMLHttpRequest();
        }
    }
    else
    {
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return null;
    }
    return xhr;
}

function get_nodeValue (node)
{
    if (node.length != 0)
    {
        return (node[0].childNodes[0].nodeValue);
    }
    return null;
}

function parse_partition (xmlDoc)
{
    var partition_obj = new Partition ();

    var node_title = xmlDoc.getElementsByTagName ("work-title");
    if (node_title.length != 0)
    {
        partition_obj._title = node_title[0].childNodes[0].nodeValue;
    }
    var nodes_miscellaneous = xmlDoc.getElementsByTagName ("miscellaneous-field");
    for (var i = 0; i < nodes_miscellaneous.length; i++)
    {
        if (nodes_miscellaneous[i].getAttribute("name") == "album")
        {
            partition_obj._album = nodes_miscellaneous[i].childNodes[0].nodeValue;
        }
    } 
    var nodes_creators = xmlDoc.getElementsByTagName ("creator");
    for (var i = 0; i < nodes_creators.length; i++)
    {
        if (nodes_creators[i].getAttribute("type") == "artist")
        {
            partition_obj._artist = nodes_creators[i].childNodes[0].nodeValue;
        }
        if (nodes_creators[i].getAttribute("type") == "composer")
        {
          partition_obj._composer = nodes_creators[i].childNodes[0].nodeValue;
        }
    }
    partition_obj._instruments_list = parse_instruments(xmlDoc);
    return partition_obj;
}

function parse_instruments (xmlDoc)
{
    var list_instruments_obj = new Array ();
    
    var nodes_partlist = xmlDoc.getElementsByTagName ("part-list");
    var nodes_score_part = nodes_partlist[0].getElementsByTagName ("score-part");
    
    for (var i = 0; i < nodes_score_part.length; i++)
    {
        var instrument_obj = new Instrument ();
        //Part-name
        var nodes_part_name = nodes_score_part[i].getElementsByTagName ("part-name");
        instrument_obj._name_instrument= nodes_part_name[0].childNodes[0].nodeValue;
        
        instrument_obj._id_instrument = nodes_score_part[i].getAttribute("id");
        
        //midi-instruments
        var nodes_midi_instrument = nodes_score_part[i].getElementsByTagName ("midi-instrument");
        instrument_obj._id_midi = nodes_midi_instrument[0].getAttribute("id");
        
        var nodes_midi_channel = nodes_midi_instrument[0].getElementsByTagName ("midi-channel");
        instrument_obj._midi_channel = nodes_midi_channel[0].childNodes[0].nodeValue;
        
        var nodes_midi_program = nodes_midi_instrument[0].getElementsByTagName ("midi-program");
        instrument_obj._gm_instrument = nodes_midi_program[0].childNodes[0].nodeValue;
        

        list_instruments_obj.push(instrument_obj);
    }
    
          
    var nodes_part = xmlDoc.getElementsByTagName ("part");  
    for (var i = 0; i < nodes_part.length; i++)  
    {
       list_instruments_obj[i]._track_part = parse_track (nodes_part[i]);
    }
    

    return list_instruments_obj;
}

function parse_track (node_part)
{
   var track_part_obj = new TrackPart ();
   
   track_part_obj._tuning = parse_tuning (node_part); //tuning = list<Lines>
   track_part_obj._measure_list = parse_list_measure (node_part);
   
   return track_part_obj;
}

function parse_tuning (node_part)
{
    var list_lines_obj = new Array (); // tuning
    var nodes_staff_tuning = node_part.getElementsByTagName ("staff-tuning");
    for (var i = 0; i < nodes_staff_tuning.length; ++i)
    {
        var lines_obj = new Lines ();
        lines_obj._line = nodes_staff_tuning[i].getAttribute("line");
        
        var nodes_tuning_step = nodes_staff_tuning[i].getElementsByTagName ("tuning-step");
        lines_obj._tuning_step = nodes_tuning_step[0].childNodes[0].nodeValue;  
        
        var nodes_tuning_octave = nodes_staff_tuning[i].getElementsByTagName ("tuning-octave");
        lines_obj._octave = nodes_tuning_octave[0].childNodes[0].nodeValue;

        list_lines_obj.push(lines_obj);
    }
    return (list_lines_obj);
}

function parse_list_measure (node_part)
{
    // Si c'est un farward on empile la mesure
    //Si c'est un backward on dépile la mesure et on ajoute toutes les mesure entre le back et le forward
    var pile_barline = new Array ();
    var list_measure_obj = new Array ();
    var nodes_measure= node_part.getElementsByTagName ("measure");
    for (var i = 0; i < nodes_measure.length; ++i)
    {
        var measure_obj = new Measure ();
        measure_obj._attributes = parse_attributes (nodes_measure[i]);
        if (measure_obj._attributes._division == null)
        {
           measure_obj._attributes._division = list_measure_obj[list_measure_obj.length - 1]._attributes._division;
        }
        measure_obj._sound_params = parse_sound_param (nodes_measure[i]);
        measure_obj._chord_list = parse_chord_list (nodes_measure[i], measure_obj._attributes._division);
        
        list_measure_obj.push (measure_obj);
                         //Si la pile n'est pas vide, j'empile les mesures.
                //Si il y a un noeud ending, je n'empile pas

        var nodes_repeat = nodes_measure[i].getElementsByTagName("repeat");
        if (nodes_repeat.length != 0)
        {
            for (var j = 0; j < nodes_repeat.length; ++j)
            {
                var direction = nodes_repeat[j].getAttribute("direction");
                if (direction == "forward")
                {
                    var pile_measure = new Array ();
                    pile_measure.push(measure_obj);
                    pile_barline.push (pile_measure);
                }
                else if (direction == "backward")
                {
                    var node_ending = nodes_measure[i].getElementsByTagName("ending");
                    if (node_ending.length != 0)
                    {
                        var pile_measures = pile_barline[pile_barline.length - 1];
                        for (var h = 0; h < pile_measures.length; ++h)
                        {
                            list_measure_obj.push (pile_measures[h]);
                        }
                    }
                    else
                    {
                        var pile_measures  = pile_barline.pop();
                        if (pile_measures != undefined)
                        {
                            for (h = 0; h < pile_measures.length; ++h)
                            {
                                list_measure_obj.push (pile_measures[h]);
                            }
                        }
                    }
                }
            }
        }
        else
        {
          if (pile_barline.length != 0)
          {
              node_ending = nodes_measure[i].getElementsByTagName("ending");
              if (node_ending.length != 0)
              {
                 pile_measures  = pile_barline.pop()
               /*  for (h = 0; h < pile_measures.length; ++h)
                 {
                    list_measure_obj.push (pile_measures[h]);
                 }*/
              }
              else
              {
                 pile_barline[pile_barline.length - 1].push(measure_obj); //Attention si repetition inclus dans repetitions, le parent ne doit pas perdre les mesures flusher par la fils
              }
          }
        }
        
        // measure_obj._direction_barline   //TODO barline
         //measure_obj._time_barline
        
    }
    return list_measure_obj;
}

function parse_attributes (node_measure)
{
    var attribute_obj = new Attribute ();
    var node_attributes = node_measure.getElementsByTagName("attributes");
    
    if (node_attributes.length != 0)
    {
        var node_division = node_attributes[0].getElementsByTagName ("divisions");
        attribute_obj._division = node_division[0].childNodes[0].nodeValue;

        var node_fifths_key = node_attributes[0].getElementsByTagName ("fifths");
        attribute_obj._fifths_key = get_nodeValue (node_fifths_key);  

        var node_mode = node_attributes[0].getElementsByTagName ("mode");
        attribute_obj._mode_key = get_nodeValue (node_mode);

        var node_beats = node_attributes[0].getElementsByTagName ("beats");
        attribute_obj._time_beat = get_nodeValue (node_beats);

        var node_beat_type = node_attributes[0].getElementsByTagName ("beat-type");
        attribute_obj._type_beat = get_nodeValue (node_beat_type);
    }
    return attribute_obj;
}

function parse_sound_param (node_measure)
{
    var sound_param_obj = new SoundParam ();
    
    var node_sound = node_measure.getElementsByTagName("sound");
    if (node_sound.length != 0)
    {
       sound_param_obj._tempo = node_sound[0].getAttribute("tempo");
       sound_param_obj._pan = node_sound[0].getAttribute("pan");
    }
    return sound_param_obj;
}

function parse_chord_list (node_measure, division)
{
    var chord_list_obj = new Array ();
    
    var node_notes = node_measure.getElementsByTagName ("note");
    var prec_chord = null;
    for (var i = 0; i < node_notes.length; ++i)
    {
        var tmp_chord = node_notes[i].getElementsByTagName ("chord");
        if (tmp_chord.length != 0)
        {
            if (prec_chord != null)
            {
               prec_chord._note_list.push (parse_note (node_notes[i], division));
            }
        }
        else
        {
            var chord_obj = new Chord ();
            chord_obj._note_list = new Array ();
            chord_obj._note_list.push (parse_note (node_notes[i], division));
            var previous_note = chord_obj._note_list[chord_obj._note_list.length - 1];
            next_begin = previous_note._begin + previous_note._duration;
            if (prec_chord != null)
            {
                chord_list_obj.push (prec_chord);
            }
            prec_chord = chord_obj;
        }
    }
    if (prec_chord != null)
    {
        chord_list_obj.push (prec_chord);
    }
    //chord_obj._strumming =  //TODO strumming
    return chord_list_obj;
}

function parse_note (node_note, division)
{
        var note_obj = new Note ();
        
        var node_pitch_step = node_note.getElementsByTagName("step");
        note_obj._step_pitch = get_nodeValue (node_pitch_step);
        
        var node_pitch_octave = node_note.getElementsByTagName("octave");
        note_obj._octave_pitch = get_nodeValue (node_pitch_octave);
        
        var node_pitch_duration = node_note.getElementsByTagName("duration");
        note_obj._duration = (get_nodeValue (node_pitch_duration) / division) * 480;

        note_obj._begin = next_begin;
        
        var node_string_technical= node_note.getElementsByTagName("string");
        note_obj._string_technical = get_nodeValue (node_string_technical);
        
        var node_fret_technical= node_note.getElementsByTagName("fret");
        note_obj._fret_technical = get_nodeValue (node_fret_technical);
 
        var node_dynamic = node_note.getElementsByTagName("dynamic"); //TODO : Convertir la dynamic (<ff></ff>)
        note_obj._dynamic = get_nodeValue (node_dynamic);
        
        var node_other_technical = node_note.getElementsByTagName("other-technical");
        note_obj._other_technical = get_nodeValue (node_other_technical);       
        
        return note_obj;
}



function load_xml (path)
{
    xhr = getXMLHttpRequest();
    xhr.open ("GET", path, false);
    xhr.send ();
    xmlDoc= xhr.responseXML;

    return xmlDoc;
}

//Fonction a appeler depuis l'exterieur, permet de récupérer un objet Partition
//ATTENTION : SCRIPT DES CLASSES A APPELER DANS LE HTML. (ex : DemoParser.html)
function parse (path)
{
   var xmlDoc = load_xml (path);
   var partition_obj = parse_partition (xmlDoc); //Return Partition 
   return partition_obj;
}

$(document).ready (function()
{
  /* var xmlDoc = load_xml ("../normal.xml");
   var header = parse_header (xmlDoc);
   var instrument_list = parse_instruments (xmlDoc);
   var nbr_mesure = parse_measure (xmlDoc);
   var nbr_notes = parse_notes (xmlDoc);
   display_parsing_header (header);
   display_parsing_measures (nbr_mesure, instrument_list.length);
   display_parsing_instruments (instrument_list);
   display_parsing_notes (nbr_notes);*/
   partition = parse ("../demo.xml");
   
});

function display_parsing_header (header)
{
    if (header["title"])
    {
        document.getElementById('title').innerHTML =  "Titre :  " + header["title"];
    }
    if (header["artist"])
    {
        document.getElementById('artist').innerHTML =  "Artiste :  " + header["artist"];
    }
    if (header["composer"])
    {
        document.getElementById('composer').innerHTML = "Compositeur : " + header["composer"];
    }
}

function display_parsing_measures (nbr_mesure, nbr_instruments)
{
    var real_nbr_mesure = nbr_mesure / nbr_instruments;
    document.getElementById('nbr_mesures').innerHTML = "Nombre mesure : " + real_nbr_mesure;
}
function display_parsing_notes(nbr_notes)
{
    document.getElementById('nbr_notes').innerHTML = "Nombre de notes : " + nbr_notes;
}
function display_parsing_instruments (instrument_list)
{
    for (var i = 0; i < instrument_list.length; ++i)
    {
        $("section").append("<div>Instrument : " + (i +1) + " " + instrument_list[i] + "</div>");
    }
}