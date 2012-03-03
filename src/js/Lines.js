/*  Class Lines
**  Représente une corde,
**  contient l'indice de la corde sur la guitare, le tuning correspondant et son octave
*/
function Lines(lines, tuning_step, octave)
{
    this._lines = lines;                // int      ==> indice de la corde
    this._tuning_step = tuning_step;    // String   ==> tuning de la corde
    this._octave = octave;              // int      ==> octave de la note
}