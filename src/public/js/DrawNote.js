function PosNote (x, y, fret, string, duration, measure)
{
    this._x = x;
    this._y = y;
    this._fret = fret;
    this._string = string;
    this._duration = duration;
    this._measure = measure;
}

function TimeNote (posx, posy, width, height, listnote)
{
	this._posx = posx;
	this._posy = posy;
                        this._width = width;
                        this._height = height;
                        this._listnote = listnote;
}

