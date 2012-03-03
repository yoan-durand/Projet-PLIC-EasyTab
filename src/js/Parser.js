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

function get_header (xmlDoc)
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
    /*var node_title = xmlDoc.getElementsByTagName ("work");
    var node_test = node_title[0].getElementsByTagName ("work-title");
    alert (node_test[0].childNodes[0].nodeValue);*/

    return xmlDoc;
}

window.onload = function()
{
   var xmlDoc = load_xml ("../Demo.xml");
   var header = get_header (xmlDoc);
   alert (header["check"]);
};