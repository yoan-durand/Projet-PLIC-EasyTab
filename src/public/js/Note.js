/*  Class Note
**  Représente une note,
**  contient les informations nécessaires pour sa représentation graphique et MIDI
*/
function Note(step_pitch, octave_pitch, duration, string_technical, fret_technical, dynamic, other_technical)
{
    this._step_pitch = step_pitch;              // String ==> la note ex : E, F#, B
    this._octave_pitch = octave_pitch;          // int ==> Octave de la note
    this._duration = duration;                  // int ==> Durée de la note midi time
    this._string_technical = string_technical;  // int ==> Indice de la corde
    this._fret_technical = fret_technical;      // int ==> Indice de la fret
    this._dynamic = dynamic;                    // int ==> Volume de la note
    this._other_technical = other_technical;    // String ==> Autre technique ex: Palm Mute
    this._begin = 0;                                // int ==> temps midi de début de note
    this._posX = 0;                             //Position de la note en X sur le svg
    this._posY = 0;                             //POsition de la note en Y sur le svg
}