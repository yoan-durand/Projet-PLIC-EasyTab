/*  Class Chord
**  Représente une note ou un accord,
**  contient la liste des notes à jouer
*/
function Chord(list, strumming)
{
    this._note_list = list;         // List<Note>
    this._strumming = strumming;    // String ==> Direction du strumming Up/Down
}