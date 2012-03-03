/*  Class Note
**  Représente une note,
**  contient les informations nécessaires pour sa représentation graphique et MIDI
*/
function Note(step_pitch, octave_pitch, duration, string_technical, fret_technical, dynamic, other_technical)
{
    this._step_pitch = step_pitch;              // String ==> la note ex : E, F#, B
    this._octave_pitch = octave_pitch;          // int ==> Octave de la note
    this._duration = duration;                  // int ==> Durée de la note
    this._string_technical = string_technical;  // int ==> Indice de la corde
    this._fret_technical = fret_technical;      // int ==> Indice de la fret
    this._dynamic = dynamic;                    // int ==> Volume de la note
    this._other_technical = other_technical;    // String ==> Autre technique ex: Palm Mute
}