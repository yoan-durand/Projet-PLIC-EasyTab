/*  Class Instrument
**  Contient les informations d'un instrument,
**  la partition associée et les informations MIDI.
*/
function Instrument(id_instru, name_instru, gm_instru, midi_channel, id_midi, track_part)
{
    this._id_instrument = id_instru;        // String
    this._name_instrument = name_instru;    // String
    this._gm_instrument = gm_instru;        // int ==> Numérotation général midi de l'instrument
    this._midi_channel = midi_channel;      // int
    this._id_midi = id_midi;                // int ==> Permet de modifier l'instrument en cours de route 
    this._track_part = track_part;          // TrackPart ==> Partition correspondant à l'instrument
	this._pan = 64;                         // Valeur du pan de l'instrument 0 = gauche 64 = milieu 127 = droite
	this._volume = 100;                     // Volume de l'instrument entre 0 et 127
	this._mute = false;						//L'instrument ne sera pas générer dans le midi
}