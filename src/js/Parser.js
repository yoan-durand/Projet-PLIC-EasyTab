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

function parse_instruments (xmlDoc)
{
    var tab_instrument = new Array ();
    var nodes_partlist = xmlDoc.getElementsByTagName ("part-list");
    var nodes_score_part = nodes_partlist[0].getElementsByTagName ("score-part");

    for (var i = 0; i < nodes_score_part.length; i++)
    {
        var nodes_part_name = nodes_score_part[i].getElementsByTagName ("part-name");
        tab_instrument[i] = nodes_part_name[0].childNodes[0].nodeValue;
    }

    return tab_instrument
}
function parse_measure (xmlDoc)
{
    var nodes_measure = xmlDoc.getElementsByTagName ("measure");
    return nodes_measure.length;
}

function parse_header (xmlDoc)
{
    var tab_assoc = new Object();

    var node_title = xmlDoc.getElementsByTagName ("work-title");
    if (node_title.length != 0)
    {
        tab_assoc["title"] = node_title[0].childNodes[0].nodeValue;
    }
    var nodes_creators = xmlDoc.getElementsByTagName ("creator");
    for (var i = 0; i < nodes_creators.length; i++)
    {
        if (nodes_creators[i].getAttribute("type") == "artist")
        {
            tab_assoc["artist"]  = nodes_creators[i].childNodes[0].nodeValue;
        }
        if (nodes_creators[i].getAttribute("type") == "composer")
        {
            tab_assoc["composer"]  = nodes_creators[i].childNodes[0].nodeValue;
        }
    }
    return tab_assoc;
}

function load_xml (path)
{
    xhr = getXMLHttpRequest();
    xhr.open ("GET", path, false);
    xhr.send ();
    xmlDoc= xhr.responseXML;


    return xmlDoc;
}

$(document).ready (function()
{
   var xmlDoc = load_xml ("../demo.xml");
   var header = parse_header (xmlDoc);
   var instrument_list = parse_instruments (xmlDoc);
   var nbr_mesure = parse_measure (xmlDoc);
   display_parsing_header (header);
   display_parsing_measures (nbr_mesure, instrument_list.length);
   display_parsing_instruments (instrument_list);
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

function display_parsing_instruments (instrument_list)
{
    for (var i = 0; i < instrument_list.length; ++i)
    {
        $("section").append("<div>Instrument : " + (i +1) + " " + instrument_list[i] + "</div>");
    }
}