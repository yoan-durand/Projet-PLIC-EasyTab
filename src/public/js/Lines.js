/*  Class Lines
**  Représente une corde,
**  contient l'indice de la corde sur la guitare, le tuning correspondant et son octave
*/
function Lines(line, tuning_step, octave)
{
    this._line = line;                // int      ==> indice de la corde
    this._tuning_step = tuning_step;    // String   ==> tuning de la corde
    this._octave = octave;              // int      ==> octave de la note
}