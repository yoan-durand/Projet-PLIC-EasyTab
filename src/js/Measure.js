/*  Class Measure
**  Représente une mesure
**  contient la liste des différentes notes, les différents attributs de la mesure,
**  et d'autres informations utiles
*/
function Measure(chord_list, sound_params, attributes, direction_barline, time_barline)
{
    this._chord_list = chord_list;                  // List<Chord> ==> Contient l'ensemble des chords/notes utilisées pour cette mesure
    this._sound_params = sound_params;              // SoundParam ==> Contient les informations pan et tempo
    this._attributes = attributes;                  // Attribute ==> Contient des informations spécifiques à la mesure
    this._direction_barline = direction_barline;    // String ==> Indique le début/ fin de la répétition de mesure
    this._time_barline = time_barline;              // int ==> Nbre de fois que doit être répété la mesure
}