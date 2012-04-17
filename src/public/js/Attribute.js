/*  Class Attribute
**
*/
function Attribute(division, fifths_key, mode_key, time_beat, type_beat)
{
    this._division = division;      // int ==> Note::Duration / Measure::Attribute::Division = 1 ==> 1 unité de temps dans la mesure
    this._fifths_key = fifths_key;  // int ==> 
    this._mode_key = mode_key;      // String ==> 
    this._time_beat = time_beat;    // int ==> 
    this._type_beat = type_beat;    // int ==> 
}