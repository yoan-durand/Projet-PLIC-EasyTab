function PosNote (x, y, fret, string, duration, measure)
{
    this._x = x;
    this._y = y;
    this._fret = fret;
    this._string = string;
    this._duration = duration;
    this._measure = measure;
}

function TimeNote (posx, posy, width, height)
{
	this._posx = posx;
	this._posy = posy;
                        this._width = width;
                        this._height = height;
}

function Draw (timenote, posnote)
{
     this._timenote = timenote;
     this._posnote = posnote;
}

