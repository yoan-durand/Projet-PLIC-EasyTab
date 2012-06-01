/*
//	Beware bitches, la marge est pas good. Cya fags ;). 
*/
// Algo afin de dessiner la partition de façon lisible
function DrawPartition(mesures, svg)
{
    var MaxWidth = 820; // Taille en pixels d'une ligne de la partition
    var left_marge = 60;
    var marge_mesure = 15;
    var file_mesures = new Array (); // La file qui nous sert à stocker toutes les mesures que l'on peut mettre sur une ligne
    var Yline = 30;
    var x = left_marge; // Curseur pour la position en longueur sur la ligne;
    for (var j = 0; j < mesures.length; j++) // On itère sur les mesures
    {
                if (j==58)
            {
                writeInConsole (j); 
            }
        x += marge_mesure; // Afin de placer la première note de la mesure
        x = SetX(mesures[j], x, 0, Yline); // ERRROR :  la valeur de x modifié dans la fonction n'est pas modifié ici...
        if (x <= MaxWidth) // Encore de la place pour une mesure
        {
            file_mesures.push(j); // On enfile l'indice de la mesure qui rentre encore sur la ligne
        }
   
        else // La mesure ne rentre pas
        {
            if (mesures[j]._chord_list[0] != null) // A VERIFIER
            {
                var end_note = mesures[j]._chord_list[0]._note_list[0];
                var Xend = end_note._posX - marge_mesure; // Position de la fin de la derniere mesure qui rentre sur la ligne
                var coef = (MaxWidth  - Xend) / (Xend - left_marge ); // Permet de décaler la position de toutes les notes afin d'occuper tout l'espace restant
                Optimize(file_mesures, coef, mesures, left_marge, marge_mesure); // Permet de décaler toutes les positions en X des notes qui rentre sur une même ligne
                DrawOneLine(svg,file_mesures, mesures, Yline, marge_mesure,MaxWidth,left_marge); // On dessine la ligne entiere (rectangles de selection, lignes et mesures)
                file_mesures = []; // On nettoie la File afin de pouvoir assigner de nouvelles mesures
                j--; // Afin de repartir sur la mesure qui n'est pas traiter
                x = left_marge; // On repart sur une nouvel ligne
                Yline += 90;
            }
        }
    }
    writeInConsole ("OUUUUUUUUUUUUUUUUUUUUUUUUUUUT");
}

// Fonction qui permet de setter les X des différentes notes composant la mesure en appliquant un coef si nécessaire
function SetX(mesure, x, coef, posY)
{
    var ConvertNote = {"4" :{"4":80}, "2":{"2":60,"1":40}, "1":{"2":55, "1":45, "0.5":30}, "0.5":{"2":55, "1": 40, "0.5":25, "0.25":15}, 
        "0.25":{"2": 50, "1":40, "0.5":30, "0.25":20, "0.125":15}, "0.125":{"2":45, "1":40,"0.5":30,"0.25":20,"0.125":10, "0.0625":7},
        "0.0625":{"2":45, "1":30, "0.5":20, "0.25":15, "0.125":10, "0.0625":5, "0.03125":2 } };
    
    var deltaMin = search_min_note(mesure._chord_list); // On recherche la note qui a le temps le plus petit
    var chords = mesure._chord_list;
    for (var i = 0; i < chords.length; i++, posY)
    {
        var chord = chords[i];
        if (chord != null)
        {
            if (chord._note_list != null)
            {
                for (var j = 0; j < chord._note_list.length; j++)
                {
                    var note = chord._note_list[j];
                    note._posX = x;
                    if (posY != null)
                    {
                        note._posY = posY;
                    }
                }
                var note = chord._note_list[0];
                var first_duration = get_first_duration (note._duration/480);
                var first_distance = ConvertNote[deltaMin][first_duration];
                
              //   writeInConsole (i);
                var second_duration = get_second_duration (note._duration/480, first_duration);
                var second_distance = second_duration!=0? ConvertNote[deltaMin][second_duration]:0;
                x += (first_distance + second_distance)*(coef + 1);
            }
        }
    }
    return x;

}

// Permet de décaler toutes les positions en X des notes qui rentre sur une même ligne
function Optimize(file_mesures, coef, mesures, left_marge, marge_mesure)
{
    var x = left_marge;
    for (var i = 0; i < file_mesures.length; i++)
    {
        x += marge_mesure;
        x = SetX(mesures[file_mesures[i]], x, coef);
    }
}
function DrawOneLine (svg,file, mesures, Yline, marge_mesure,MaxWidth,left_marge)
{
  DrawSelectRect (svg, file, mesures, Yline, marge_mesure, MaxWidth, left_marge); //Dessine les rectangles de selection
  drawLines (svg, 60, Yline, MaxWidth -left_marge, 6);
  DrawNotes(svg, file, mesures, Yline, marge_mesure);
}

//Place les rectangles de selections autour de chaque note
function DrawSelectRect (svg, file, mesures, Yline, marge_mesure, MaxWidth, left_marge)
{
    var x = left_marge;
    var height = 70;
    var lastnote = null; //Derniere note d'une mesure
    for (var i = 0; i < file.length; ++i)
    {
        var mesure = mesures[file[i]]; //On recupere la mesure situé a l'index File[i]
        var chordlist = mesure._chord_list //La liste de chords dans l'objet list
        for (var j=0; j < chordlist.length ; ++j)
        {
                var cur_note = chordlist[j]._note_list[0]; //Cette note represente l'ensemble de l'accord
                if (lastnote != null) //On dessine la derniere note de la mesure precedente
                {
                        var tmpX = cur_note._posX - marge_mesure; //15 represente la marge entre la premiere note d'une mesure et la barre de celle-ci
                        svg.rect(x,Yline - 10,tmpX - x,height, {id:file[i-1]+"_"+j, fill:"white", stroke:"white"});  //file[i-1] est le numero de la mesure qui servira pour l'id du noeud
                        lastnote = null;
                        x = tmpX;
                }
                if (chordlist[j+1] != null) //Si on est pas sur la derniere note
                {
                        var next_note = chordlist[j+1]._note_list[0]; //Cette note represente l'ensemble de l'accord
                        var distance = next_note._posX - cur_note._posX;
                        svg.rect(x,Yline - 10,distance/2 + (cur_note._posX - x),height, {id:file[i]+"_"+j, fill:"white", stroke:"white"});
                        x = cur_note._posX + distance/2;
                }
                else
                {
                        lastnote = cur_note;
                }
        }
    }
    if (lastnote != null)
    {
            svg.rect(x,Yline - 10,lastnote._posX - x + MaxWidth - lastnote._posX,height, {id:file[file.length-1]+"_", fill:"white", stroke:"white"});
    }
}

function drawLines (svg, x, y, width, nb_corde)
{
    for (var j = 0; j < nb_corde; j++)
    {
        svg.line(x, y + (j*10), x+width, y + (j*10), {stroke:"black"});
    }
}
function DrawNotes(svg, file, measures, yLine,marge_mesure)
{
	for (var i = 0; i < file.length; i++)
	{
		var measure = measures[[file[i]]];
		var chords = measure._chord_list;
		if (chords != null && chords[0] != null)
		{
                    var chord = chords[0];
                    if (chord._note_list != null)
                    {
                        var note = chord._note_list[0];
                        svg.line(note._posX - marge_mesure, yLine, note._posX - marge_mesure, yLine + 50,{stroke:"black"});
                    }
		}
		for (var j = 0; j < chords.length; j++)
		{
                    var notes = chords[j]._note_list;
                    for (var k = 0; k < notes.length; k++)
                    {
                        var note2 = notes[k];
                        if (note2._fret_technical != null)
                        {
                            svg.text(note2._posX, (note2._string_technical * 10) + yLine, ""+note2._fret_technical+"", {stroke: "blue", "font-size": "11px"});
                        }
                        else
                        {
                            svg.text(note2._posX, (note2._string_technical * 10) + yLine, "X", {stroke: "blue", "font-size": "11px"});
                        }
                       // SVGText(note.X, (note.string * 10) + yLine, note.fret);
                    }
		}
	}
         svg.line(820, yLine, 820, yLine + 50, {stroke:"black"});
}



function search_min_note (chord_list)
{
    if(chord_list != null)
    {
        var chord = chord_list[0];
        var min  = chord!=null?chord._note_list[0]._duration/480:0;

        for (var i = 0; i < chord_list.length; i++)
        {
            if(chord_list[i]._note_list != null)
            {
                for (var j = 0; j < chord_list[i]._note_list.length; j++)
                {
                    if (min > chord_list[i]._note_list[j]._duration/480)
                    {
                        min =  chord_list[i]._note_list[j]._duration/480;
                    }
                }
            }
        }
        return min;
    }
    alert ("pas de chord");
    return 0;
}

function mytestduration ()
{
    var possibilities = [4,1,3, 1.5, 0.75, 0.375];
   for (var i = 0; i < 4; i++)
    {
        var firstduration = get_first_duration(possibilities[i]);
        var secondduration =  get_second_duration (possibilities[i], firstduration)
        alert ("duration " + possibilities[i] + " : " + firstduration + "+" + secondduration);
    }
}
function get_first_duration (duration) //Retourne la durée sans la valeur pointé ex: 3 => 2
{
    var possibilities = [4, 2, 1, 0.5, 0.25, 0.125, 0.0625];
    
    for (var i = 0; i < 7; i++)
    {
        if (Math.floor(duration / possibilities[i])  != 0)
        {
              return possibilities[i];
        }
    }
    alert ("wrong value");
    return null;
}
function get_second_duration (duration, first_duration) //Retourne la durée de la valeur pointé ex: 1.5 => 0.5
{
       return (duration % first_duration);
}