/*  Class Partition
**  Contient la liste des instruments qui composent la partition,
**  plus quelques informations supllémentaires (Titre, Artiste, Compositeur)
*/
function Partition(title, artist, composer, list)
{
    this._title = title;            // String
    this._artist = artist;          // String
    this._composer = composer;      // String
    this._instruments_list = list;  // List<Instrument>
}