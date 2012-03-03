/*  Class TrackPart
**  Représente une partition spécifique à un instrument,
**  contient la liste des mesures et le tuning de l'intrument
*/
function TrackPart(measure_list, tuning)
{
    this._measure_list = measure_list   // List<Measure> ==> Contient l'ensemble des mesures
    this._tuning = tuning;              // List<Lines> ==> Liste des cordes avec leurs accordages
}