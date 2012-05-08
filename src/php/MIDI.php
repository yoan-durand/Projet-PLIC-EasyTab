<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

	$note_ref = array (
		"C0" => 0, "C#0" => 1, "D0" => 2, "D#0" => 3, "E0" => 4, "F0" => 5, "F#0" => 6, "G0" => 7, "G#0" => 8, "A0" => 9, "A#0" => 10, "B0" => 11,
		"C1" => 12, "C#1" => 13, "D1" => 14, "D#1" => 15, "E1" => 16, "F1" => 17, "F#1" => 18, "G1" => 19, "G#1" => 20, "A1" => 21, "A#1" => 22, "B1" => 23,
		"C2" => 24, "C#2" => 25, "D2" => 26, "D#2" => 27, "E2" => 28, "F2" => 29, "F#2" => 30, "G2" => 31, "G#2" => 32, "A2" => 33, "A#2" => 34, "B2" => 35,
		"C3" => 36, "C#3" => 37, "D3" => 38, "D#3" => 39, "E3" => 40, "F3" => 41, "F#3" => 42, "G3" => 43, "G#3" => 44, "A3" => 45, "A#3" => 46, "B3" => 47,
		"C4" => 48, "C#4" => 49, "D4" => 50, "D#4" => 51, "E4" => 52, "F4" => 53, "F#4" => 54, "G4" => 55, "G#4" => 56, "A4" => 57, "A#4" => 58, "B4" => 59,
		"C5" => 60, "C#5" => 61, "D5" => 62, "D#5" => 63, "E5" => 64, "F5" => 65, "F#5" => 66, "G5" => 67, "G#5" => 68, "A5" => 69, "A#5" => 70, "B5" => 71,
		"C6" => 72, "C#6" => 73, "D6" => 74, "D#6" => 75, "E6" => 76, "F6" => 77, "F#6" => 78, "G6" => 79, "G#6" => 80, "A6" => 81, "A#6" => 82, "B6" => 83,
		"C7" => 84, "C#7" => 85, "D7" => 86, "D#7" => 87, "E7" => 88, "F7" => 89, "F#7" => 90, "G7" => 91, "G#7" => 92, "A7" => 93, "A#7" => 94, "B7" => 95,
		"C8" => 96, "C#8" => 97, "D8" => 98, "D#8" => 99, "E8" => 100, "F8" => 101, "F#8" => 102, "G8" => 103, "G#8" => 104, "A8" => 105, "A#8" => 106, "B8" => 107,
		"C9" => 108, "C#9" => 109, "D9" => 110, "D#9" => 111, "E9" => 112, "F9" => 113, "F#9" => 114, "G9" => 115, "G#9" => 116, "A9" => 117, "A#9" => 118, "B9" => 119
	);

    header("application/json; charset=utf-8");

    $encoded = $_POST["encoded"];

    $decoded = json_decode($encoded, true);

    $partition = $decoded["encoded"];

	$nb_tracks = count($partition["_instruments_list"]);
	$tempo = 1000000*(60 / $partition["_instruments_list"][0]["_track_part"]["_measure_list"][0]["_sound_params"]["_tempo"]);
	$time_beat = $partition["_instruments_list"][0]["_track_part"]["_measure_list"][0]["_attributes"]["_time_beat"];
	$type_beat = $partition["_instruments_list"][0]["_track_part"]["_measure_list"][0]["_attributes"]["_type_beat"];

	$txt = "MFile 1 "+($nb_tracks+1)+" 480";
	$txt += "MTrk
			0 Tempo "+$tempo+"
			0 Meta TrkName '"+$partition["_title"]+"'
			0 TimeSig "+$time_beat+"/"+$type_beat+" 9 39
			0 Meta TrkEnd
			TrkEnd";
	for (var $i = 0; $i < count($partition["_instruments_list"]); $i++)
	{
		$txt += "MTrk
				0 Meta TrkName '"+$partition["_instruments_list"][$i]["_name_instrument"]+"'";
		$measures = $partition["_instruments_list"][$i]["_track_part"]["_measure_list"];
		for (var $j = 0; $j < count($measures); $j++)
		{
			$chords = $measures[$j]["_chord_list"];
			for (var $k = 0; $k < count($chords); $k++)
			{
				$notes = $chords[$k]["_note_list"];
				for (var $h = 0; $h < count($notes); $h++)
				{
					txt += $notes[$h]["_begin"]+" On ch=1 n="+$note_ref[$notes[$h]["_step_pitch"]]+" v=95";
					txt += ($notes[$h]["_begin"]+$notes[$h]["_duration"])+" Off ch=1 n="+$note_ref[$notes[$h]["_step_pitch"]]+" v=80";
				}
			}
		}
		$txt += ($partition["_instruments_list"][$i]["_track_part"]["_measure_list"][])+" Meta TrkEnd
				TrkEnd";
	}
/*
$txt = "MFile 1 5 480
MTrk
0 Tempo 600000
0 Meta TrkName 'Sweet Home Alabama'
0 TimeSig 4/4 9 39
0 Meta TrkEnd
TrkEnd
MTrk
0 Meta TrkName 'Guitare 1'
0 Par ch=2 c=100 v=0
0 Par ch=2 c=101 v=0
0 Par ch=2 c=6 v=12
0 Pb ch=2 v=8192
0 Par ch=2 c=101 v=0
0 Par ch=2 c=100 v=1
0 Par ch=2 c=6 v=64
0 Par ch=2 c=38 v=0
0 Par ch=2 c=101 v=127
0 Par ch=2 c=100 v=127
0 Par ch=1 c=100 v=0
0 Par ch=1 c=101 v=0
0 Par ch=1 c=6 v=12
0 Pb ch=1 v=8192
0 Par ch=1 c=101 v=0
0 Par ch=1 c=100 v=1
0 Par ch=1 c=6 v=64
0 Par ch=1 c=38 v=0
0 Par ch=1 c=101 v=127
0 Par ch=1 c=100 v=127
0 Par ch=2 c=101 v=0
0 Par ch=2 c=100 v=2
0 Par ch=2 c=6 v=64
0 Par ch=2 c=101 v=127
0 Par ch=2 c=100 v=127
0 Par ch=1 c=101 v=0
0 Par ch=1 c=100 v=2
0 Par ch=1 c=6 v=64
0 Par ch=1 c=101 v=127
0 Par ch=1 c=100 v=127
0 PrCh ch=2 p=27
0 PrCh ch=1 p=27
0 Par ch=2 c=0 v=0
0 Par ch=1 c=0 v=0
0 Par ch=2 c=7 v=112
0 Par ch=1 c=7 v=112
0 Par ch=2 c=10 v=48
0 Par ch=1 c=10 v=48
0 Par ch=2 c=93 v=88
0 Par ch=1 c=93 v=88
0 Par ch=2 c=91 v=96
0 Par ch=1 c=91 v=96
0 Par ch=2 c=92 v=0
0 Par ch=1 c=92 v=0
0 Par ch=2 c=95 v=0
0 Par ch=1 c=95 v=0
1920 On ch=1 n=50 v=95
2160 Off ch=1 n=50 v=80
2160 On ch=1 n=50 v=95
2400 Off ch=1 n=50 v=80
2400 On ch=1 n=62 v=95
2520 Off ch=1 n=62 v=80
2520 On ch=1 n=57 v=95
2760 Off ch=1 n=57 v=80
2760 On ch=1 n=50 v=95
2880 Off ch=1 n=50 v=80
2880 On ch=1 n=48 v=95
3000 Off ch=1 n=48 v=80
3000 On ch=1 n=45 v=95
3120 Off ch=1 n=45 v=80
3120 On ch=1 n=48 v=95
3360 Off ch=1 n=48 v=80
3360 On ch=1 n=62 v=95
3480 Off ch=1 n=62 v=80
3480 On ch=1 n=55 v=95
3720 Off ch=1 n=55 v=80
3720 On ch=1 n=50 v=95
3840 Off ch=1 n=50 v=80
3840 On ch=1 n=43 v=95
3960 Off ch=1 n=43 v=80
3960 On ch=1 n=40 v=95
4080 Off ch=1 n=40 v=80
4080 On ch=1 n=43 v=95
4320 Off ch=1 n=43 v=80
4320 On ch=1 n=55 v=95
4680 Off ch=1 n=55 v=80
4680 On ch=1 n=55 v=95
4800 Off ch=1 n=55 v=80
4800 On ch=1 n=45 v=95
4920 Off ch=1 n=45 v=80
4920 On ch=1 n=47 v=76
5040 Off ch=1 n=47 v=80
5040 On ch=1 n=50 v=95
5160 Off ch=1 n=50 v=80
5160 On ch=1 n=52 v=95
5280 Off ch=1 n=52 v=80
5280 On ch=1 n=50 v=76
5400 Off ch=1 n=50 v=80
5400 On ch=1 n=47 v=95
5520 Off ch=1 n=47 v=80
5520 On ch=1 n=57 v=95
5640 Off ch=1 n=57 v=80
5640 On ch=1 n=55 v=76
5760 Off ch=1 n=55 v=80
5760 On ch=1 n=50 v=95
6000 Off ch=1 n=50 v=80
6000 On ch=1 n=50 v=95
6240 Off ch=1 n=50 v=80
6240 On ch=1 n=62 v=95
6360 Off ch=1 n=62 v=80
6360 On ch=1 n=57 v=95
6600 Off ch=1 n=57 v=80
6600 On ch=1 n=50 v=95
6720 Off ch=1 n=50 v=80
6720 On ch=1 n=48 v=95
6840 Off ch=1 n=48 v=80
6840 On ch=1 n=45 v=95
6960 Off ch=1 n=45 v=80
6960 On ch=1 n=48 v=95
7200 Off ch=1 n=48 v=80
7200 On ch=1 n=62 v=95
7320 Off ch=1 n=62 v=80
7320 On ch=1 n=55 v=95
7560 Off ch=1 n=55 v=80
7560 On ch=1 n=50 v=95
7680 Off ch=1 n=50 v=80
7680 On ch=1 n=43 v=95
7800 Off ch=1 n=43 v=80
7800 On ch=1 n=39 v=95
7820 Off ch=1 n=39 v=80
7920 On ch=1 n=43 v=95
8160 Off ch=1 n=43 v=80
8160 On ch=1 n=55 v=95
8520 Off ch=1 n=55 v=80
8520 On ch=1 n=55 v=95
8640 Off ch=1 n=55 v=80
8640 On ch=1 n=57 v=95
8760 Off ch=1 n=57 v=80
8760 On ch=1 n=55 v=76
8880 Off ch=1 n=55 v=80
8880 On ch=1 n=55 v=95
9000 Off ch=1 n=55 v=80
9000 On ch=1 n=59 v=95
9120 Off ch=1 n=59 v=80
9120 On ch=1 n=55 v=76
9240 Off ch=1 n=55 v=80
9240 On ch=1 n=50 v=95
9360 Off ch=1 n=50 v=80
9360 On ch=2 n=60 v=95
9360 Pb ch=2 v=8192
9392 Pb ch=2 v=8320
9408 Pb ch=2 v=8448
9424 Pb ch=2 v=8704
9440 Pb ch=2 v=8960
9456 Pb ch=2 v=9344
9472 Pb ch=2 v=9472
9488 Pb ch=2 v=9600
9600 Off ch=2 n=60 v=80
9600 Pb ch=2 v=8192
9600 On ch=1 n=50 v=95
9840 Off ch=1 n=50 v=80
9840 On ch=1 n=50 v=95
10080 Off ch=1 n=50 v=80
10080 On ch=1 n=62 v=95
10080 On ch=1 n=57 v=95
10440 Off ch=1 n=62 v=80
10440 Off ch=1 n=57 v=80
10440 On ch=1 n=49 v=95
10460 Off ch=1 n=49 v=80
10560 On ch=1 n=48 v=95
10680 Off ch=1 n=48 v=80
10680 On ch=1 n=45 v=95
10800 Off ch=1 n=45 v=80
10800 On ch=1 n=48 v=95
11040 Off ch=1 n=48 v=80
11040 On ch=1 n=62 v=95
11040 On ch=1 n=55 v=95
11400 Off ch=1 n=62 v=80
11400 Off ch=1 n=55 v=80
11400 On ch=1 n=62 v=95
11400 On ch=1 n=55 v=95
11400 On ch=1 n=52 v=95
11520 Off ch=1 n=62 v=80
11520 Off ch=1 n=55 v=80
11520 Off ch=1 n=52 v=80
11520 On ch=1 n=43 v=95
11640 Off ch=1 n=43 v=80
11640 On ch=1 n=39 v=95
11660 Off ch=1 n=39 v=80
11760 On ch=1 n=43 v=95
12000 Off ch=1 n=43 v=80
12000 On ch=1 n=62 v=95
12000 On ch=1 n=55 v=95
12000 On ch=1 n=50 v=95
12120 Off ch=1 n=62 v=80
12120 Off ch=1 n=55 v=80
12120 Off ch=1 n=50 v=80
12120 On ch=1 n=43 v=95
12360 Off ch=1 n=43 v=80
12360 On ch=1 n=62 v=95
12360 On ch=1 n=55 v=95
12360 On ch=1 n=50 v=95
12480 Off ch=1 n=62 v=80
12480 Off ch=1 n=55 v=80
12480 Off ch=1 n=50 v=80
12480 On ch=1 n=45 v=95
12600 Off ch=1 n=45 v=80
12600 On ch=1 n=47 v=76
12720 Off ch=1 n=47 v=80
12720 On ch=1 n=50 v=95
12840 Off ch=1 n=50 v=80
12840 On ch=1 n=52 v=95
12960 Off ch=1 n=52 v=80
12960 On ch=1 n=50 v=76
13080 Off ch=1 n=50 v=80
13080 On ch=1 n=47 v=95
13200 Off ch=1 n=47 v=80
13200 On ch=1 n=57 v=95
13320 Off ch=1 n=57 v=80
13320 On ch=1 n=55 v=76
13440 Off ch=1 n=55 v=80
13440 On ch=1 n=50 v=95
13680 Off ch=1 n=50 v=80
13680 On ch=1 n=50 v=95
13920 Off ch=1 n=50 v=80
13920 On ch=1 n=62 v=95
13920 On ch=1 n=57 v=95
14280 Off ch=1 n=62 v=80
14280 Off ch=1 n=57 v=80
14280 On ch=1 n=49 v=95
14300 Off ch=1 n=49 v=80
14400 On ch=1 n=48 v=95
14520 Off ch=1 n=48 v=80
14520 On ch=1 n=45 v=95
14640 Off ch=1 n=45 v=80
14640 On ch=1 n=48 v=95
14880 Off ch=1 n=48 v=80
14880 On ch=1 n=62 v=95
14880 On ch=1 n=55 v=95
15240 Off ch=1 n=62 v=80
15240 Off ch=1 n=55 v=80
15240 On ch=1 n=62 v=95
15240 On ch=1 n=55 v=95
15240 On ch=1 n=52 v=95
15360 Off ch=1 n=62 v=80
15360 Off ch=1 n=55 v=80
15360 Off ch=1 n=52 v=80
15360 On ch=1 n=43 v=95
15480 Off ch=1 n=43 v=80
15480 On ch=1 n=39 v=95
15500 Off ch=1 n=39 v=80
15600 On ch=1 n=43 v=95
15840 Off ch=1 n=43 v=80
15840 On ch=1 n=62 v=95
15840 On ch=1 n=55 v=95
15840 On ch=1 n=50 v=95
16320 Off ch=1 n=62 v=80
16320 Off ch=1 n=55 v=80
16320 Off ch=1 n=50 v=80
16320 On ch=2 n=46 v=95
16416 Pb ch=2 v=8832
16440 Off ch=2 n=46 v=80
16440 Pb ch=2 v=8192
16440 On ch=1 n=47 v=76
16560 Off ch=1 n=47 v=80
16560 On ch=1 n=50 v=95
16680 Off ch=1 n=50 v=80
16680 On ch=1 n=55 v=95
17280 Off ch=1 n=55 v=80
17280 On ch=1 n=50 v=95
17520 Off ch=1 n=50 v=80
17520 On ch=1 n=50 v=95
17760 Off ch=1 n=50 v=80
17760 On ch=1 n=62 v=95
17760 On ch=1 n=57 v=95
18120 Off ch=1 n=62 v=80
18120 Off ch=1 n=57 v=80
18120 On ch=1 n=62 v=95
18120 On ch=1 n=57 v=95
18240 Off ch=1 n=62 v=80
18240 Off ch=1 n=57 v=80
18240 On ch=1 n=48 v=95
18480 Off ch=1 n=48 v=80
18480 On ch=1 n=48 v=95
18720 Off ch=1 n=48 v=80
18720 On ch=1 n=62 v=95
18720 On ch=1 n=55 v=95
19080 Off ch=1 n=62 v=80
19080 Off ch=1 n=55 v=80
19080 On ch=1 n=62 v=95
19080 On ch=1 n=55 v=95
19080 On ch=1 n=50 v=95
19200 Off ch=1 n=62 v=80
19200 Off ch=1 n=55 v=80
19200 Off ch=1 n=50 v=80
19200 On ch=1 n=43 v=95
19440 Off ch=1 n=43 v=80
19440 On ch=1 n=43 v=95
19680 Off ch=1 n=43 v=80
19680 On ch=1 n=62 v=95
19680 On ch=1 n=55 v=95
19680 On ch=1 n=50 v=95
20160 Off ch=1 n=62 v=80
20160 Off ch=1 n=55 v=80
20160 Off ch=1 n=50 v=80
20160 On ch=1 n=45 v=95
20280 Off ch=1 n=45 v=80
20280 On ch=1 n=47 v=76
20400 Off ch=1 n=47 v=80
20400 On ch=1 n=50 v=95
20520 Off ch=1 n=50 v=80
20520 On ch=1 n=55 v=95
20760 Off ch=1 n=55 v=80
20760 On ch=1 n=55 v=95
20880 Off ch=1 n=55 v=80
20880 On ch=1 n=45 v=95
21000 Off ch=1 n=45 v=80
21000 On ch=1 n=47 v=76
21120 Off ch=1 n=47 v=80
21120 On ch=1 n=50 v=95
21360 Off ch=1 n=50 v=80
21360 On ch=1 n=50 v=95
21600 Off ch=1 n=50 v=80
21600 On ch=1 n=62 v=95
21600 On ch=1 n=57 v=95
21960 Off ch=1 n=62 v=80
21960 Off ch=1 n=57 v=80
21960 On ch=1 n=62 v=95
21960 On ch=1 n=57 v=95
22080 Off ch=1 n=62 v=80
22080 Off ch=1 n=57 v=80
22080 On ch=1 n=48 v=95
22320 Off ch=1 n=48 v=80
22320 On ch=1 n=48 v=95
22560 Off ch=1 n=48 v=80
22560 On ch=1 n=62 v=95
22560 On ch=1 n=55 v=95
22920 Off ch=1 n=62 v=80
22920 Off ch=1 n=55 v=80
22920 On ch=1 n=62 v=95
22920 On ch=1 n=55 v=95
22920 On ch=1 n=52 v=95
23040 Off ch=1 n=62 v=80
23040 Off ch=1 n=55 v=80
23040 Off ch=1 n=52 v=80
23040 On ch=1 n=43 v=95
23280 Off ch=1 n=43 v=80
23280 On ch=1 n=43 v=95
23520 Off ch=1 n=43 v=80
23520 On ch=1 n=62 v=95
23520 On ch=1 n=55 v=95
23520 On ch=1 n=50 v=95
24000 Off ch=1 n=62 v=80
24000 Off ch=1 n=55 v=80
24000 Off ch=1 n=50 v=80
24000 On ch=1 n=43 v=95
24240 Off ch=1 n=43 v=80
24240 On ch=1 n=43 v=95
24480 Off ch=1 n=43 v=80
24480 On ch=1 n=55 v=95
24480 On ch=1 n=50 v=95
24960 Off ch=1 n=55 v=80
24960 Off ch=1 n=50 v=80
24960 On ch=1 n=50 v=95
25200 Off ch=1 n=50 v=80
25200 On ch=1 n=50 v=95
25440 Off ch=1 n=50 v=80
25440 On ch=1 n=62 v=95
25440 On ch=1 n=57 v=95
25800 Off ch=1 n=62 v=80
25800 Off ch=1 n=57 v=80
25800 On ch=1 n=62 v=95
25800 On ch=1 n=57 v=95
25920 Off ch=1 n=62 v=80
25920 Off ch=1 n=57 v=80
25920 On ch=1 n=48 v=95
26160 Off ch=1 n=48 v=80
26160 On ch=1 n=48 v=95
26400 Off ch=1 n=48 v=80
26400 On ch=1 n=62 v=95
26400 On ch=1 n=55 v=95
26760 Off ch=1 n=62 v=80
26760 Off ch=1 n=55 v=80
26760 On ch=1 n=62 v=95
26760 On ch=1 n=55 v=95
26760 On ch=1 n=50 v=95
26880 Off ch=1 n=62 v=80
26880 Off ch=1 n=55 v=80
26880 Off ch=1 n=50 v=80
26880 On ch=1 n=43 v=95
27120 Off ch=1 n=43 v=80
27120 On ch=1 n=43 v=95
27360 Off ch=1 n=43 v=80
27360 On ch=1 n=62 v=95
27360 On ch=1 n=55 v=95
27360 On ch=1 n=50 v=95
27840 Off ch=1 n=62 v=80
27840 Off ch=1 n=55 v=80
27840 Off ch=1 n=50 v=80
27840 On ch=1 n=45 v=95
27960 Off ch=1 n=45 v=80
27960 On ch=1 n=47 v=76
28080 Off ch=1 n=47 v=80
28080 On ch=1 n=50 v=95
28200 Off ch=1 n=50 v=80
28200 On ch=1 n=55 v=95
28440 Off ch=1 n=55 v=80
28440 On ch=1 n=55 v=95
28560 Off ch=1 n=55 v=80
28560 On ch=1 n=45 v=95
28680 Off ch=1 n=45 v=80
28680 On ch=1 n=47 v=76
28800 Off ch=1 n=47 v=80
28800 On ch=1 n=50 v=95
29040 Off ch=1 n=50 v=80
29040 On ch=1 n=50 v=95
29280 Off ch=1 n=50 v=80
29280 On ch=1 n=62 v=95
29280 On ch=1 n=57 v=95
29640 Off ch=1 n=62 v=80
29640 Off ch=1 n=57 v=80
29640 On ch=1 n=62 v=95
29640 On ch=1 n=57 v=95
29760 Off ch=1 n=62 v=80
29760 Off ch=1 n=57 v=80
29760 On ch=1 n=48 v=95
30000 Off ch=1 n=48 v=80
30000 On ch=1 n=55 v=95
30240 Off ch=1 n=55 v=80
30240 On ch=1 n=62 v=95
30240 On ch=1 n=55 v=95
30600 Off ch=1 n=62 v=80
30600 Off ch=1 n=55 v=80
30600 On ch=1 n=62 v=95
30600 On ch=1 n=55 v=95
30600 On ch=1 n=52 v=95
30720 Off ch=1 n=62 v=80
30720 Off ch=1 n=55 v=80
30720 Off ch=1 n=52 v=80
30720 On ch=1 n=43 v=95
30960 Off ch=1 n=43 v=80
30960 On ch=1 n=43 v=95
31200 Off ch=1 n=43 v=80
31200 On ch=1 n=62 v=95
31200 On ch=1 n=55 v=95
31200 On ch=1 n=50 v=95
31560 Off ch=1 n=62 v=80
31560 Off ch=1 n=55 v=80
31560 Off ch=1 n=50 v=80
31560 On ch=1 n=62 v=95
31560 On ch=1 n=55 v=95
31560 On ch=1 n=50 v=95
31680 Off ch=1 n=62 v=80
31680 Off ch=1 n=55 v=80
31680 Off ch=1 n=50 v=80
31680 On ch=1 n=43 v=95
31920 Off ch=1 n=43 v=80
31920 On ch=1 n=43 v=95
32160 Off ch=1 n=43 v=80
32160 On ch=1 n=55 v=95
32160 On ch=1 n=50 v=95
32400 Off ch=1 n=55 v=80
32400 Off ch=1 n=50 v=80
32400 On ch=1 n=55 v=95
32400 On ch=1 n=50 v=95
32520 Off ch=1 n=55 v=80
32520 Off ch=1 n=50 v=80
32520 On ch=1 n=55 v=95
32520 On ch=1 n=50 v=95
32640 Off ch=1 n=55 v=80
32640 Off ch=1 n=50 v=80
32640 On ch=1 n=52 v=95
32760 Off ch=1 n=52 v=80
32760 On ch=1 n=53 v=95
32880 Off ch=1 n=53 v=80
32880 On ch=1 n=54 v=95
33000 Off ch=1 n=54 v=80
33000 On ch=1 n=62 v=95
33000 On ch=1 n=57 v=95
33240 Off ch=1 n=62 v=80
33240 Off ch=1 n=57 v=80
33240 On ch=1 n=62 v=95
33240 On ch=1 n=57 v=95
33360 Off ch=1 n=62 v=80
33360 Off ch=1 n=57 v=80
33360 On ch=2 n=54 v=95
33552 Pb ch=2 v=7552
33568 Pb ch=2 v=6784
33584 Pb ch=2 v=6144
33600 Off ch=2 n=54 v=80
33600 Pb ch=2 v=8192
33600 On ch=1 n=50 v=76
33720 Off ch=1 n=50 v=80
33720 On ch=1 n=52 v=95
33840 Off ch=1 n=52 v=80
33840 On ch=1 n=60 v=95
33840 On ch=1 n=55 v=95
34080 Off ch=1 n=60 v=80
34080 Off ch=1 n=55 v=80
34080 On ch=1 n=60 v=95
34080 On ch=1 n=55 v=95
34320 Off ch=1 n=60 v=80
34320 Off ch=1 n=55 v=80
34320 On ch=2 n=52 v=95
34488 Pb ch=2 v=7552
34502 Pb ch=2 v=6784
34517 Pb ch=2 v=6144
34531 Pb ch=2 v=5504
34546 Pb ch=2 v=4736
34560 Off ch=2 n=52 v=80
34560 Pb ch=2 v=8192
34560 On ch=2 n=46 v=76
34656 Pb ch=2 v=8832
34680 Off ch=2 n=46 v=80
34680 Pb ch=2 v=8192
34680 On ch=1 n=47 v=76
34800 Off ch=1 n=47 v=80
34800 On ch=1 n=50 v=95
34920 Off ch=1 n=50 v=80
34920 On ch=1 n=55 v=95
35160 Off ch=1 n=55 v=80
35160 On ch=1 n=55 v=95
35280 Off ch=1 n=55 v=80
35280 On ch=2 n=43 v=95
35280 Pb ch=2 v=8192
35285 Pb ch=2 v=8192
35289 Pb ch=2 v=8192
35294 Pb ch=2 v=8192
35299 Pb ch=2 v=8192
35303 Pb ch=2 v=8192
35308 Pb ch=2 v=8192
35313 Pb ch=2 v=8192
35318 Pb ch=2 v=8192
35322 Pb ch=2 v=8192
35327 Pb ch=2 v=8192
35332 Pb ch=2 v=8192
35336 Pb ch=2 v=8192
35341 Pb ch=2 v=8192
35346 Pb ch=2 v=8192
35350 Pb ch=2 v=8192
35355 Pb ch=2 v=8192
35360 Pb ch=2 v=8192
35364 Pb ch=2 v=8192
35369 Pb ch=2 v=8192
35374 Pb ch=2 v=8192
35378 Pb ch=2 v=8192
35383 Pb ch=2 v=8192
35388 Pb ch=2 v=8192
35392 Pb ch=2 v=8192
35397 Pb ch=2 v=8192
35402 Pb ch=2 v=8192
35407 Pb ch=2 v=8192
35411 Pb ch=2 v=8192
35416 Pb ch=2 v=8192
35421 Pb ch=2 v=8192
35425 Pb ch=2 v=8192
35430 Pb ch=2 v=8192
35435 Pb ch=2 v=8192
35439 Pb ch=2 v=8320
35444 Pb ch=2 v=8320
35449 Pb ch=2 v=8320
35453 Pb ch=2 v=8320
35458 Pb ch=2 v=8448
35463 Pb ch=2 v=8448
35468 Pb ch=2 v=8448
35472 Pb ch=2 v=8448
35477 Pb ch=2 v=8448
35482 Pb ch=2 v=8320
35486 Pb ch=2 v=8320
35491 Pb ch=2 v=8320
35496 Pb ch=2 v=8320
35500 Pb ch=2 v=8192
35505 Pb ch=2 v=8192
35510 Pb ch=2 v=8192
35514 Pb ch=2 v=8064
35519 Pb ch=2 v=8064
35524 Pb ch=2 v=8064
35528 Pb ch=2 v=8064
35533 Pb ch=2 v=7936
35538 Pb ch=2 v=7936
35542 Pb ch=2 v=7936
35547 Pb ch=2 v=7936
35552 Pb ch=2 v=7936
35557 Pb ch=2 v=8064
35561 Pb ch=2 v=8064
35566 Pb ch=2 v=8064
35571 Pb ch=2 v=8064
35575 Pb ch=2 v=8192
35580 Pb ch=2 v=8192
35585 Pb ch=2 v=8192
35589 Pb ch=2 v=8320
35594 Pb ch=2 v=8320
35599 Pb ch=2 v=8448
35603 Pb ch=2 v=8448
35608 Pb ch=2 v=8448
35613 Pb ch=2 v=8448
35618 Pb ch=2 v=8448
35622 Pb ch=2 v=8448
35627 Pb ch=2 v=8448
35632 Pb ch=2 v=8448
35636 Pb ch=2 v=8448
35641 Pb ch=2 v=8320
35646 Pb ch=2 v=8320
35650 Pb ch=2 v=8192
35655 Pb ch=2 v=8192
35660 Pb ch=2 v=8192
35664 Pb ch=2 v=8064
35669 Pb ch=2 v=8064
35674 Pb ch=2 v=7936
35678 Pb ch=2 v=7936
35683 Pb ch=2 v=7936
35688 Pb ch=2 v=7936
35692 Pb ch=2 v=7936
35697 Pb ch=2 v=7936
35702 Pb ch=2 v=7936
35707 Pb ch=2 v=7936
35711 Pb ch=2 v=7936
35716 Pb ch=2 v=8064
35721 Pb ch=2 v=8064
35725 Pb ch=2 v=8192
35730 Pb ch=2 v=8192
35735 Pb ch=2 v=8192
35739 Pb ch=2 v=8320
35744 Pb ch=2 v=8320
35749 Pb ch=2 v=8320
35753 Pb ch=2 v=8448
35758 Pb ch=2 v=8448
35763 Pb ch=2 v=8448
35768 Pb ch=2 v=8448
35772 Pb ch=2 v=8448
35777 Pb ch=2 v=8448
35782 Pb ch=2 v=8448
35786 Pb ch=2 v=8320
35791 Pb ch=2 v=8320
35796 Pb ch=2 v=8320
35800 Pb ch=2 v=8192
35805 Pb ch=2 v=8192
35810 Pb ch=2 v=8192
35814 Pb ch=2 v=8064
35819 Pb ch=2 v=8064
35824 Pb ch=2 v=8064
35828 Pb ch=2 v=7936
35833 Pb ch=2 v=7936
35838 Pb ch=2 v=7936
35842 Pb ch=2 v=7936
35847 Pb ch=2 v=7936
35852 Pb ch=2 v=7936
35857 Pb ch=2 v=7936
35861 Pb ch=2 v=8064
35866 Pb ch=2 v=8064
35871 Pb ch=2 v=8064
35875 Pb ch=2 v=8192
35880 Pb ch=2 v=8192
35885 Pb ch=2 v=8192
35889 Pb ch=2 v=8320
35894 Pb ch=2 v=8320
35899 Pb ch=2 v=8320
35903 Pb ch=2 v=8320
35908 Pb ch=2 v=8320
35913 Pb ch=2 v=8320
35918 Pb ch=2 v=8320
35922 Pb ch=2 v=8320
35927 Pb ch=2 v=8320
35932 Pb ch=2 v=8320
35936 Pb ch=2 v=8320
35941 Pb ch=2 v=8320
35946 Pb ch=2 v=8320
35950 Pb ch=2 v=8192
35955 Pb ch=2 v=8192
35960 Pb ch=2 v=8192
35964 Pb ch=2 v=8064
35969 Pb ch=2 v=8064
35974 Pb ch=2 v=8064
35978 Pb ch=2 v=8064
35983 Pb ch=2 v=8064
35988 Pb ch=2 v=8064
35992 Pb ch=2 v=8064
35997 Pb ch=2 v=8064
36002 Pb ch=2 v=8064
36007 Pb ch=2 v=8064
36011 Pb ch=2 v=8064
36016 Pb ch=2 v=8064
36021 Pb ch=2 v=8064
36025 Pb ch=2 v=8192
36030 Pb ch=2 v=8192
36035 Pb ch=2 v=8320
36039 Pb ch=2 v=8320
36044 Pb ch=2 v=8320
36049 Pb ch=2 v=8448
36053 Pb ch=2 v=8448
36058 Pb ch=2 v=8448
36063 Pb ch=2 v=8448
36068 Pb ch=2 v=8448
36072 Pb ch=2 v=8448
36077 Pb ch=2 v=8448
36082 Pb ch=2 v=8448
36086 Pb ch=2 v=8448
36091 Pb ch=2 v=8320
36096 Pb ch=2 v=8320
36100 Pb ch=2 v=8320
36105 Pb ch=2 v=8192
36110 Pb ch=2 v=8064
36114 Pb ch=2 v=8064
36119 Pb ch=2 v=8064
36124 Pb ch=2 v=7936
36128 Pb ch=2 v=7936
36133 Pb ch=2 v=7936
36138 Pb ch=2 v=7936
36142 Pb ch=2 v=7936
36147 Pb ch=2 v=7936
36152 Pb ch=2 v=7936
36157 Pb ch=2 v=7936
36161 Pb ch=2 v=7936
36166 Pb ch=2 v=8064
36171 Pb ch=2 v=8064
36175 Pb ch=2 v=8064
36180 Pb ch=2 v=8192
36185 Pb ch=2 v=8192
36189 Pb ch=2 v=8320
36194 Pb ch=2 v=8320
36199 Pb ch=2 v=8320
36203 Pb ch=2 v=8320
36208 Pb ch=2 v=8448
36213 Pb ch=2 v=8448
36218 Pb ch=2 v=8448
36222 Pb ch=2 v=8448
36227 Pb ch=2 v=8448
36232 Pb ch=2 v=8320
36236 Pb ch=2 v=8320
36241 Pb ch=2 v=8320
36246 Pb ch=2 v=8320
36250 Pb ch=2 v=8192
36255 Pb ch=2 v=8192
36260 Pb ch=2 v=8192
36264 Pb ch=2 v=8064
36269 Pb ch=2 v=8064
36274 Pb ch=2 v=8064
36278 Pb ch=2 v=8064
36283 Pb ch=2 v=7936
36288 Pb ch=2 v=7936
36292 Pb ch=2 v=7936
36297 Pb ch=2 v=7936
36302 Pb ch=2 v=7936
36307 Pb ch=2 v=8064
36311 Pb ch=2 v=8064
36316 Pb ch=2 v=8064
36321 Pb ch=2 v=8064
36325 Pb ch=2 v=8192
36330 Pb ch=2 v=8192
36335 Pb ch=2 v=8192
36339 Pb ch=2 v=8320
36344 Pb ch=2 v=8320
36349 Pb ch=2 v=8320
36353 Pb ch=2 v=8320
36358 Pb ch=2 v=8320
36363 Pb ch=2 v=8320
36368 Pb ch=2 v=8320
36372 Pb ch=2 v=8320
36377 Pb ch=2 v=8320
36382 Pb ch=2 v=8320
36386 Pb ch=2 v=8320
36391 Pb ch=2 v=8320
36396 Pb ch=2 v=8320
36400 Pb ch=2 v=8192
36405 Pb ch=2 v=8192
36410 Pb ch=2 v=8192
36414 Pb ch=2 v=8064
36419 Pb ch=2 v=8064
36424 Pb ch=2 v=8064
36428 Pb ch=2 v=8064
36433 Pb ch=2 v=8064
36438 Pb ch=2 v=8064
36442 Pb ch=2 v=8064
36447 Pb ch=2 v=8064
36452 Pb ch=2 v=8064
36457 Pb ch=2 v=8064
36461 Pb ch=2 v=8064
36466 Pb ch=2 v=8064
36471 Pb ch=2 v=8064
36475 Pb ch=2 v=8192
36480 Off ch=2 n=43 v=80
36480 Pb ch=2 v=8192
36480 On ch=1 n=52 v=95
36600 Off ch=1 n=52 v=80
36600 On ch=1 n=53 v=95
36720 Off ch=1 n=53 v=80
36720 On ch=1 n=54 v=95
36840 Off ch=1 n=54 v=80
36840 On ch=1 n=62 v=95
36840 On ch=1 n=57 v=95
37080 Off ch=1 n=62 v=80
37080 Off ch=1 n=57 v=80
37080 On ch=1 n=62 v=95
37080 On ch=1 n=57 v=95
37200 Off ch=1 n=62 v=80
37200 Off ch=1 n=57 v=80
37200 On ch=2 n=54 v=95
37392 Pb ch=2 v=7552
37408 Pb ch=2 v=6784
37424 Pb ch=2 v=6144
37440 Off ch=2 n=54 v=80
37440 Pb ch=2 v=8192
37440 On ch=1 n=50 v=76
37560 Off ch=1 n=50 v=80
37560 On ch=1 n=52 v=76
37680 Off ch=1 n=52 v=80
37680 On ch=1 n=60 v=95
37680 On ch=1 n=55 v=95
37920 Off ch=1 n=60 v=80
37920 Off ch=1 n=55 v=80
37920 On ch=1 n=60 v=95
37920 On ch=1 n=55 v=95
38160 Off ch=1 n=60 v=80
38160 Off ch=1 n=55 v=80
38160 On ch=2 n=52 v=95
38328 Pb ch=2 v=7552
38342 Pb ch=2 v=6784
38357 Pb ch=2 v=6144
38371 Pb ch=2 v=5504
38386 Pb ch=2 v=4736
38400 Off ch=2 n=52 v=80
38400 Pb ch=2 v=8192
38400 On ch=2 n=46 v=76
38496 Pb ch=2 v=8832
38520 Off ch=2 n=46 v=80
38520 Pb ch=2 v=8192
38520 On ch=1 n=47 v=76
38640 Off ch=1 n=47 v=80
38640 On ch=1 n=50 v=95
38760 Off ch=1 n=50 v=80
38760 On ch=1 n=55 v=95
38880 Off ch=1 n=55 v=80
38880 On ch=1 n=55 v=95
39120 Off ch=1 n=55 v=80
39120 On ch=2 n=43 v=95
39120 Pb ch=2 v=8192
39125 Pb ch=2 v=8192
39129 Pb ch=2 v=8192
39134 Pb ch=2 v=8192
39139 Pb ch=2 v=8192
39143 Pb ch=2 v=8192
39148 Pb ch=2 v=8192
39153 Pb ch=2 v=8192
39158 Pb ch=2 v=8192
39162 Pb ch=2 v=8192
39167 Pb ch=2 v=8192
39172 Pb ch=2 v=8192
39176 Pb ch=2 v=8192
39181 Pb ch=2 v=8192
39186 Pb ch=2 v=8192
39190 Pb ch=2 v=8192
39195 Pb ch=2 v=8192
39200 Pb ch=2 v=8192
39204 Pb ch=2 v=8192
39209 Pb ch=2 v=8192
39214 Pb ch=2 v=8192
39218 Pb ch=2 v=8192
39223 Pb ch=2 v=8192
39228 Pb ch=2 v=8192
39232 Pb ch=2 v=8192
39237 Pb ch=2 v=8192
39242 Pb ch=2 v=8192
39247 Pb ch=2 v=8192
39251 Pb ch=2 v=8192
39256 Pb ch=2 v=8192
39261 Pb ch=2 v=8192
39265 Pb ch=2 v=8192
39270 Pb ch=2 v=8192
39275 Pb ch=2 v=8192
39279 Pb ch=2 v=8320
39284 Pb ch=2 v=8320
39289 Pb ch=2 v=8320
39293 Pb ch=2 v=8320
39298 Pb ch=2 v=8320
39303 Pb ch=2 v=8320
39308 Pb ch=2 v=8320
39312 Pb ch=2 v=8320
39317 Pb ch=2 v=8320
39322 Pb ch=2 v=8320
39326 Pb ch=2 v=8320
39331 Pb ch=2 v=8320
39336 Pb ch=2 v=8320
39340 Pb ch=2 v=8192
39345 Pb ch=2 v=8192
39350 Pb ch=2 v=8192
39354 Pb ch=2 v=8064
39359 Pb ch=2 v=8064
39364 Pb ch=2 v=8064
39368 Pb ch=2 v=8064
39373 Pb ch=2 v=8064
39378 Pb ch=2 v=8064
39382 Pb ch=2 v=8064
39387 Pb ch=2 v=8064
39392 Pb ch=2 v=8064
39397 Pb ch=2 v=8064
39401 Pb ch=2 v=8064
39406 Pb ch=2 v=8064
39411 Pb ch=2 v=8064
39415 Pb ch=2 v=8192
39420 Pb ch=2 v=8192
39425 Pb ch=2 v=8192
39429 Pb ch=2 v=8320
39434 Pb ch=2 v=8320
39439 Pb ch=2 v=8448
39443 Pb ch=2 v=8448
39448 Pb ch=2 v=8448
39453 Pb ch=2 v=8448
39458 Pb ch=2 v=8448
39462 Pb ch=2 v=8448
39467 Pb ch=2 v=8448
39472 Pb ch=2 v=8448
39476 Pb ch=2 v=8448
39481 Pb ch=2 v=8320
39486 Pb ch=2 v=8320
39490 Pb ch=2 v=8192
39495 Pb ch=2 v=8192
39500 Pb ch=2 v=8192
39504 Pb ch=2 v=8064
39509 Pb ch=2 v=8064
39514 Pb ch=2 v=7936
39518 Pb ch=2 v=7936
39523 Pb ch=2 v=7936
39528 Pb ch=2 v=7936
39532 Pb ch=2 v=7936
39537 Pb ch=2 v=7936
39542 Pb ch=2 v=7936
39547 Pb ch=2 v=7936
39551 Pb ch=2 v=7936
39556 Pb ch=2 v=8064
39561 Pb ch=2 v=8064
39565 Pb ch=2 v=8192
39570 Pb ch=2 v=8192
39575 Pb ch=2 v=8192
39579 Pb ch=2 v=8320
39584 Pb ch=2 v=8320
39589 Pb ch=2 v=8320
39593 Pb ch=2 v=8320
39598 Pb ch=2 v=8448
39603 Pb ch=2 v=8448
39608 Pb ch=2 v=8448
39612 Pb ch=2 v=8448
39617 Pb ch=2 v=8448
39622 Pb ch=2 v=8320
39626 Pb ch=2 v=8320
39631 Pb ch=2 v=8320
39636 Pb ch=2 v=8320
39640 Pb ch=2 v=8192
39645 Pb ch=2 v=8192
39650 Pb ch=2 v=8192
39654 Pb ch=2 v=8064
39659 Pb ch=2 v=8064
39664 Pb ch=2 v=8064
39668 Pb ch=2 v=8064
39673 Pb ch=2 v=7936
39678 Pb ch=2 v=7936
39682 Pb ch=2 v=7936
39687 Pb ch=2 v=7936
39692 Pb ch=2 v=7936
39697 Pb ch=2 v=8064
39701 Pb ch=2 v=8064
39706 Pb ch=2 v=8064
39711 Pb ch=2 v=8064
39715 Pb ch=2 v=8192
39720 Pb ch=2 v=8192
39725 Pb ch=2 v=8192
39729 Pb ch=2 v=8320
39734 Pb ch=2 v=8320
39739 Pb ch=2 v=8448
39743 Pb ch=2 v=8448
39748 Pb ch=2 v=8448
39753 Pb ch=2 v=8448
39758 Pb ch=2 v=8448
39762 Pb ch=2 v=8448
39767 Pb ch=2 v=8448
39772 Pb ch=2 v=8448
39776 Pb ch=2 v=8448
39781 Pb ch=2 v=8320
39786 Pb ch=2 v=8320
39790 Pb ch=2 v=8192
39795 Pb ch=2 v=8192
39800 Pb ch=2 v=8192
39804 Pb ch=2 v=8064
39809 Pb ch=2 v=8064
39814 Pb ch=2 v=7936
39818 Pb ch=2 v=7936
39823 Pb ch=2 v=7936
39828 Pb ch=2 v=7936
39832 Pb ch=2 v=7936
39837 Pb ch=2 v=7936
39840 Off ch=2 n=43 v=80
39840 Pb ch=2 v=8192
39840 On ch=1 n=45 v=95
39960 Off ch=1 n=45 v=80
39960 On ch=1 n=47 v=76
40080 Off ch=1 n=47 v=80
40080 On ch=1 n=50 v=95
40320 Off ch=1 n=50 v=80
40320 On ch=1 n=50 v=95
40560 Off ch=1 n=50 v=80
40560 On ch=1 n=50 v=95
40800 Off ch=1 n=50 v=80
40800 On ch=1 n=62 v=95
40800 On ch=1 n=57 v=95
41160 Off ch=1 n=62 v=80
41160 Off ch=1 n=57 v=80
41160 On ch=1 n=62 v=95
41160 On ch=1 n=57 v=95
41280 Off ch=1 n=62 v=80
41280 Off ch=1 n=57 v=80
41280 On ch=1 n=48 v=95
41520 Off ch=1 n=48 v=80
41520 On ch=1 n=48 v=95
41760 Off ch=1 n=48 v=80
41760 On ch=1 n=62 v=95
41760 On ch=1 n=55 v=95
42120 Off ch=1 n=62 v=80
42120 Off ch=1 n=55 v=80
42120 On ch=1 n=62 v=95
42120 On ch=1 n=55 v=95
42120 On ch=1 n=50 v=95
42240 Off ch=1 n=62 v=80
42240 Off ch=1 n=55 v=80
42240 Off ch=1 n=50 v=80
42240 On ch=1 n=43 v=95
42480 Off ch=1 n=43 v=80
42480 On ch=1 n=43 v=95
42720 Off ch=1 n=43 v=80
42720 On ch=1 n=62 v=95
42720 On ch=1 n=55 v=95
42720 On ch=1 n=50 v=95
43200 Off ch=1 n=62 v=80
43200 Off ch=1 n=55 v=80
43200 Off ch=1 n=50 v=80
43200 On ch=1 n=45 v=95
43320 Off ch=1 n=45 v=80
43320 On ch=1 n=47 v=76
43440 Off ch=1 n=47 v=80
43440 On ch=1 n=50 v=95
43560 Off ch=1 n=50 v=80
43560 On ch=1 n=55 v=95
43800 Off ch=1 n=55 v=80
43800 On ch=1 n=55 v=95
43920 Off ch=1 n=55 v=80
43920 On ch=1 n=45 v=95
44040 Off ch=1 n=45 v=80
44040 On ch=1 n=47 v=76
44160 Off ch=1 n=47 v=80
44160 On ch=1 n=50 v=95
44400 Off ch=1 n=50 v=80
44400 On ch=1 n=50 v=95
44640 Off ch=1 n=50 v=80
44640 On ch=1 n=62 v=95
44640 On ch=1 n=57 v=95
45000 Off ch=1 n=62 v=80
45000 Off ch=1 n=57 v=80
45000 On ch=1 n=62 v=95
45000 On ch=1 n=57 v=95
45120 Off ch=1 n=62 v=80
45120 Off ch=1 n=57 v=80
45120 On ch=1 n=48 v=95
45360 Off ch=1 n=48 v=80
45360 On ch=1 n=48 v=95
45600 Off ch=1 n=48 v=80
45600 On ch=1 n=62 v=95
45600 On ch=1 n=55 v=95
45960 Off ch=1 n=62 v=80
45960 Off ch=1 n=55 v=80
45960 On ch=1 n=62 v=95
45960 On ch=1 n=55 v=95
45960 On ch=1 n=52 v=95
46080 Off ch=1 n=62 v=80
46080 Off ch=1 n=55 v=80
46080 Off ch=1 n=52 v=80
46080 On ch=1 n=43 v=95
46320 Off ch=1 n=43 v=80
46320 On ch=1 n=43 v=95
46560 Off ch=1 n=43 v=80
46560 On ch=1 n=62 v=95
46560 On ch=1 n=55 v=95
46560 On ch=1 n=50 v=95
47040 Off ch=1 n=62 v=80
47040 Off ch=1 n=55 v=80
47040 Off ch=1 n=50 v=80
47040 On ch=1 n=43 v=95
47280 Off ch=1 n=43 v=80
47280 On ch=1 n=43 v=95
47520 Off ch=1 n=43 v=80
47520 On ch=1 n=55 v=95
47520 On ch=1 n=50 v=95
48000 Off ch=1 n=55 v=80
48000 Off ch=1 n=50 v=80
48000 On ch=1 n=50 v=95
48240 Off ch=1 n=50 v=80
48240 On ch=1 n=50 v=95
48480 Off ch=1 n=50 v=80
48480 On ch=1 n=62 v=95
48480 On ch=1 n=57 v=95
48840 Off ch=1 n=62 v=80
48840 Off ch=1 n=57 v=80
48840 On ch=1 n=62 v=95
48840 On ch=1 n=57 v=95
48960 Off ch=1 n=62 v=80
48960 Off ch=1 n=57 v=80
48960 On ch=1 n=48 v=95
49200 Off ch=1 n=48 v=80
49200 On ch=1 n=48 v=95
49440 Off ch=1 n=48 v=80
49440 On ch=1 n=62 v=95
49440 On ch=1 n=55 v=95
49800 Off ch=1 n=62 v=80
49800 Off ch=1 n=55 v=80
49800 On ch=1 n=62 v=95
49800 On ch=1 n=55 v=95
49800 On ch=1 n=50 v=95
49920 Off ch=1 n=62 v=80
49920 Off ch=1 n=55 v=80
49920 Off ch=1 n=50 v=80
49920 On ch=1 n=43 v=95
50160 Off ch=1 n=43 v=80
50160 On ch=1 n=43 v=95
50400 Off ch=1 n=43 v=80
50400 On ch=1 n=62 v=95
50400 On ch=1 n=55 v=95
50400 On ch=1 n=50 v=95
50880 Off ch=1 n=62 v=80
50880 Off ch=1 n=55 v=80
50880 Off ch=1 n=50 v=80
50880 On ch=1 n=45 v=95
51000 Off ch=1 n=45 v=80
51000 On ch=1 n=47 v=76
51120 Off ch=1 n=47 v=80
51120 On ch=1 n=50 v=95
51240 Off ch=1 n=50 v=80
51240 On ch=1 n=55 v=95
51480 Off ch=1 n=55 v=80
51480 On ch=1 n=55 v=95
51600 Off ch=1 n=55 v=80
51600 On ch=1 n=45 v=95
51720 Off ch=1 n=45 v=80
51720 On ch=1 n=47 v=76
51840 Off ch=1 n=47 v=80
51840 On ch=1 n=50 v=95
52080 Off ch=1 n=50 v=80
52080 On ch=1 n=50 v=95
52320 Off ch=1 n=50 v=80
52320 On ch=1 n=62 v=95
52320 On ch=1 n=57 v=95
52680 Off ch=1 n=62 v=80
52680 Off ch=1 n=57 v=80
52680 On ch=1 n=62 v=95
52680 On ch=1 n=57 v=95
52800 Off ch=1 n=62 v=80
52800 Off ch=1 n=57 v=80
52800 On ch=1 n=48 v=95
53040 Off ch=1 n=48 v=80
53040 On ch=1 n=55 v=95
53280 Off ch=1 n=55 v=80
53280 On ch=1 n=62 v=95
53280 On ch=1 n=55 v=95
53640 Off ch=1 n=62 v=80
53640 Off ch=1 n=55 v=80
53640 On ch=1 n=62 v=95
53640 On ch=1 n=55 v=95
53640 On ch=1 n=52 v=95
53760 Off ch=1 n=62 v=80
53760 Off ch=1 n=55 v=80
53760 Off ch=1 n=52 v=80
53760 On ch=1 n=43 v=95
54000 Off ch=1 n=43 v=80
54000 On ch=1 n=43 v=95
54240 Off ch=1 n=43 v=80
54240 On ch=1 n=55 v=95
54240 On ch=1 n=50 v=95
54480 Off ch=1 n=55 v=80
54480 Off ch=1 n=50 v=80
54480 On ch=1 n=55 v=95
54480 On ch=1 n=50 v=95
54480 On ch=1 n=43 v=95
54600 Off ch=1 n=55 v=80
54600 Off ch=1 n=50 v=80
54600 Off ch=1 n=43 v=80
54600 On ch=1 n=55 v=95
54600 On ch=1 n=50 v=95
54600 On ch=1 n=43 v=95
54720 Off ch=1 n=55 v=80
54720 Off ch=1 n=50 v=80
54720 Off ch=1 n=43 v=80
54720 On ch=1 n=45 v=95
54840 Off ch=1 n=45 v=80
54840 On ch=1 n=47 v=76
54960 Off ch=1 n=47 v=80
54960 On ch=1 n=50 v=95
55080 Off ch=1 n=50 v=80
55080 On ch=1 n=55 v=95
55200 Off ch=1 n=55 v=80
55200 On ch=1 n=52 v=95
55320 Off ch=1 n=52 v=80
55320 On ch=1 n=50 v=76
55440 Off ch=1 n=50 v=80
55440 On ch=1 n=45 v=95
55560 Off ch=1 n=45 v=80
55560 On ch=1 n=47 v=76
55680 Off ch=1 n=47 v=80
55680 On ch=1 n=50 v=95
55920 Off ch=1 n=50 v=80
55920 On ch=1 n=57 v=95
55920 On ch=1 n=50 v=95
56160 Off ch=1 n=57 v=80
56160 Off ch=1 n=50 v=80
56160 On ch=1 n=59 v=95
56160 On ch=1 n=50 v=95
56280 Off ch=1 n=59 v=80
56280 Off ch=1 n=50 v=80
56280 On ch=1 n=57 v=95
56280 On ch=1 n=50 v=95
56640 Off ch=1 n=57 v=80
56640 Off ch=1 n=50 v=80
56640 On ch=1 n=48 v=95
56880 Off ch=1 n=48 v=80
56880 On ch=1 n=55 v=95
56880 On ch=1 n=48 v=95
57120 Off ch=1 n=55 v=80
57120 Off ch=1 n=48 v=80
57120 On ch=1 n=57 v=95
57120 On ch=1 n=48 v=95
57240 Off ch=1 n=57 v=80
57240 Off ch=1 n=48 v=80
57240 On ch=1 n=55 v=95
57240 On ch=1 n=48 v=95
57480 Off ch=1 n=55 v=80
57480 Off ch=1 n=48 v=80
57480 On ch=1 n=55 v=95
57480 On ch=1 n=48 v=95
57600 Off ch=1 n=55 v=80
57600 Off ch=1 n=48 v=80
57600 On ch=1 n=50 v=95
57600 On ch=1 n=43 v=95
57840 Off ch=1 n=50 v=80
57840 Off ch=1 n=43 v=80
57840 On ch=1 n=50 v=95
57840 On ch=1 n=43 v=95
58080 Off ch=1 n=50 v=80
58080 Off ch=1 n=43 v=80
58080 On ch=1 n=44 v=95
58080 On ch=1 n=39 v=95
58100 Off ch=1 n=44 v=80
58100 Off ch=1 n=39 v=80
58200 On ch=1 n=50 v=95
58320 Off ch=1 n=50 v=80
58320 On ch=1 n=52 v=95
58320 On ch=1 n=43 v=95
58560 Off ch=1 n=52 v=80
58560 Off ch=1 n=43 v=80
58560 On ch=1 n=45 v=95
58680 Off ch=1 n=45 v=80
58680 On ch=1 n=47 v=76
58800 Off ch=1 n=47 v=80
58800 On ch=1 n=50 v=95
58920 Off ch=1 n=50 v=80
58920 On ch=1 n=52 v=95
59040 Off ch=1 n=52 v=80
59040 On ch=1 n=50 v=76
59160 Off ch=1 n=50 v=80
59160 On ch=2 n=48 v=95
59160 Pb ch=2 v=8192
59240 Pb ch=2 v=8320
59288 Pb ch=2 v=8448
59336 Pb ch=2 v=8576
59520 Off ch=2 n=48 v=80
59520 Pb ch=2 v=8192
59520 On ch=1 n=50 v=95
59760 Off ch=1 n=50 v=80
59760 On ch=1 n=57 v=95
59760 On ch=1 n=50 v=95
60000 Off ch=1 n=57 v=80
60000 Off ch=1 n=50 v=80
60000 On ch=1 n=59 v=95
60000 On ch=1 n=50 v=95
60120 Off ch=1 n=59 v=80
60120 Off ch=1 n=50 v=80
60120 On ch=1 n=57 v=95
60120 On ch=1 n=50 v=95
60480 Off ch=1 n=57 v=80
60480 Off ch=1 n=50 v=80
60480 On ch=1 n=48 v=95
60720 Off ch=1 n=48 v=80
60720 On ch=1 n=55 v=95
60720 On ch=1 n=48 v=95
60960 Off ch=1 n=55 v=80
60960 Off ch=1 n=48 v=80
60960 On ch=1 n=57 v=95
60960 On ch=1 n=48 v=95
61080 Off ch=1 n=57 v=80
61080 Off ch=1 n=48 v=80
61080 On ch=1 n=55 v=95
61080 On ch=1 n=48 v=95
61320 Off ch=1 n=55 v=80
61320 Off ch=1 n=48 v=80
61320 On ch=1 n=55 v=95
61320 On ch=1 n=48 v=95
61440 Off ch=1 n=55 v=80
61440 Off ch=1 n=48 v=80
61440 On ch=1 n=50 v=95
61560 Off ch=1 n=50 v=80
61560 On ch=1 n=52 v=76
61680 Off ch=1 n=52 v=80
61680 On ch=1 n=55 v=95
61800 Off ch=1 n=55 v=80
61800 On ch=1 n=50 v=95
61920 Off ch=1 n=50 v=80
61920 On ch=1 n=52 v=76
62040 Off ch=1 n=52 v=80
62040 On ch=1 n=55 v=95
62160 Off ch=1 n=55 v=80
62160 On ch=1 n=50 v=95
62280 Off ch=1 n=50 v=80
62280 On ch=1 n=52 v=76
62400 Off ch=1 n=52 v=80
62400 On ch=1 n=55 v=95
62520 Off ch=1 n=55 v=80
62520 On ch=1 n=50 v=95
62640 Off ch=1 n=50 v=80
62640 On ch=1 n=52 v=76
62760 Off ch=1 n=52 v=80
62760 On ch=1 n=55 v=95
62880 Off ch=1 n=55 v=80
62880 On ch=1 n=50 v=95
63000 Off ch=1 n=50 v=80
63000 On ch=1 n=52 v=76
63120 Off ch=1 n=52 v=80
63120 On ch=1 n=55 v=95
63240 Off ch=1 n=55 v=80
63240 On ch=2 n=48 v=95
63348 Pb ch=2 v=8832
63360 Off ch=2 n=48 v=80
63360 Pb ch=2 v=8192
63360 On ch=1 n=50 v=76
63600 Off ch=1 n=50 v=80
63600 On ch=1 n=57 v=95
63600 On ch=1 n=50 v=95
63840 Off ch=1 n=57 v=80
63840 Off ch=1 n=50 v=80
63840 On ch=1 n=59 v=95
63840 On ch=1 n=50 v=95
63960 Off ch=1 n=59 v=80
63960 Off ch=1 n=50 v=80
63960 On ch=1 n=57 v=95
63960 On ch=1 n=50 v=95
64320 Off ch=1 n=57 v=80
64320 Off ch=1 n=50 v=80
64320 On ch=1 n=48 v=95
64560 Off ch=1 n=48 v=80
64560 On ch=1 n=55 v=95
64560 On ch=1 n=48 v=95
64800 Off ch=1 n=55 v=80
64800 Off ch=1 n=48 v=80
64800 On ch=1 n=57 v=95
64800 On ch=1 n=48 v=95
64920 Off ch=1 n=57 v=80
64920 Off ch=1 n=48 v=80
64920 On ch=1 n=55 v=95
64920 On ch=1 n=48 v=95
65160 Off ch=1 n=55 v=80
65160 Off ch=1 n=48 v=80
65160 On ch=1 n=55 v=95
65160 On ch=1 n=48 v=95
65280 Off ch=1 n=55 v=80
65280 Off ch=1 n=48 v=80
65280 On ch=1 n=50 v=95
65280 On ch=1 n=43 v=95
65520 Off ch=1 n=50 v=80
65520 Off ch=1 n=43 v=80
65520 On ch=1 n=50 v=95
65520 On ch=1 n=43 v=95
65760 Off ch=1 n=50 v=80
65760 Off ch=1 n=43 v=80
65760 On ch=1 n=44 v=95
65760 On ch=1 n=39 v=95
65780 Off ch=1 n=44 v=80
65780 Off ch=1 n=39 v=80
65880 On ch=1 n=50 v=95
66000 Off ch=1 n=50 v=80
66000 On ch=1 n=52 v=95
66000 On ch=1 n=43 v=95
66240 Off ch=1 n=52 v=80
66240 Off ch=1 n=43 v=80
66240 On ch=1 n=45 v=95
66360 Off ch=1 n=45 v=80
66360 On ch=1 n=47 v=76
66480 Off ch=1 n=47 v=80
66480 On ch=1 n=50 v=95
66600 Off ch=1 n=50 v=80
66600 On ch=1 n=52 v=95
66720 Off ch=1 n=52 v=80
66720 On ch=1 n=50 v=76
66840 Off ch=1 n=50 v=80
66840 On ch=1 n=48 v=95
67200 Off ch=1 n=48 v=80
67200 On ch=1 n=50 v=95
67440 Off ch=1 n=50 v=80
67440 On ch=1 n=57 v=95
67440 On ch=1 n=50 v=95
67680 Off ch=1 n=57 v=80
67680 Off ch=1 n=50 v=80
67680 On ch=1 n=59 v=95
67680 On ch=1 n=50 v=95
67800 Off ch=1 n=59 v=80
67800 Off ch=1 n=50 v=80
67800 On ch=1 n=57 v=95
67800 On ch=1 n=50 v=95
68160 Off ch=1 n=57 v=80
68160 Off ch=1 n=50 v=80
68160 On ch=1 n=48 v=95
68400 Off ch=1 n=48 v=80
68400 On ch=1 n=55 v=95
68400 On ch=1 n=48 v=95
68640 Off ch=1 n=55 v=80
68640 Off ch=1 n=48 v=80
68640 On ch=1 n=57 v=95
68640 On ch=1 n=48 v=95
68760 Off ch=1 n=57 v=80
68760 Off ch=1 n=48 v=80
68760 On ch=1 n=55 v=95
68760 On ch=1 n=48 v=95
69000 Off ch=1 n=55 v=80
69000 Off ch=1 n=48 v=80
69000 On ch=1 n=55 v=95
69000 On ch=1 n=48 v=95
69120 Off ch=1 n=55 v=80
69120 Off ch=1 n=48 v=80
69120 On ch=1 n=50 v=95
69120 On ch=1 n=43 v=95
69360 Off ch=1 n=50 v=80
69360 Off ch=1 n=43 v=80
69360 On ch=1 n=50 v=95
69360 On ch=1 n=43 v=95
69600 Off ch=1 n=50 v=80
69600 Off ch=1 n=43 v=80
69600 On ch=1 n=50 v=95
69600 On ch=1 n=43 v=95
69720 Off ch=1 n=50 v=80
69720 Off ch=1 n=43 v=80
69720 On ch=1 n=50 v=95
69720 On ch=1 n=43 v=95
69840 Off ch=1 n=50 v=80
69840 Off ch=1 n=43 v=80
69840 On ch=1 n=52 v=95
69840 On ch=1 n=43 v=95
70080 Off ch=1 n=52 v=80
70080 Off ch=1 n=43 v=80
70080 On ch=1 n=65 v=95
70080 On ch=1 n=60 v=95
70080 On ch=1 n=57 v=95
70080 On ch=1 n=53 v=95
70080 On ch=1 n=48 v=95
70560 Off ch=1 n=65 v=80
70560 Off ch=1 n=60 v=80
70560 Off ch=1 n=57 v=80
70560 Off ch=1 n=53 v=80
70560 Off ch=1 n=48 v=80
70560 On ch=1 n=64 v=95
70560 On ch=1 n=60 v=95
70560 On ch=1 n=55 v=95
70560 On ch=1 n=52 v=95
70560 On ch=1 n=48 v=95
71040 Off ch=1 n=64 v=80
71040 Off ch=1 n=60 v=80
71040 Off ch=1 n=55 v=80
71040 Off ch=1 n=52 v=80
71040 Off ch=1 n=48 v=80
71040 On ch=1 n=50 v=95
71280 Off ch=1 n=50 v=80
71280 On ch=1 n=50 v=95
71520 Off ch=1 n=50 v=80
71520 On ch=1 n=62 v=95
71520 On ch=1 n=57 v=95
71520 On ch=1 n=50 v=95
71880 Off ch=1 n=62 v=80
71880 Off ch=1 n=57 v=80
71880 Off ch=1 n=50 v=80
71880 On ch=1 n=62 v=95
71880 On ch=1 n=57 v=95
72000 Off ch=1 n=62 v=80
72000 Off ch=1 n=57 v=80
72000 On ch=1 n=48 v=95
72240 Off ch=1 n=48 v=80
72240 On ch=1 n=48 v=95
72480 Off ch=1 n=48 v=80
72480 On ch=1 n=62 v=95
72480 On ch=1 n=55 v=95
72840 Off ch=1 n=62 v=80
72840 Off ch=1 n=55 v=80
72840 On ch=1 n=62 v=95
72840 On ch=1 n=55 v=95
72840 On ch=1 n=50 v=95
72960 Off ch=1 n=62 v=80
72960 Off ch=1 n=55 v=80
72960 Off ch=1 n=50 v=80
72960 On ch=1 n=43 v=95
73200 Off ch=1 n=43 v=80
73200 On ch=1 n=43 v=95
73440 Off ch=1 n=43 v=80
73440 On ch=1 n=62 v=95
73440 On ch=1 n=55 v=95
73440 On ch=1 n=50 v=95
73800 Off ch=1 n=62 v=80
73800 Off ch=1 n=55 v=80
73800 Off ch=1 n=50 v=80
73800 On ch=1 n=62 v=95
73800 On ch=1 n=55 v=95
73800 On ch=1 n=50 v=95
73920 Off ch=1 n=62 v=80
73920 Off ch=1 n=55 v=80
73920 Off ch=1 n=50 v=80
73920 On ch=1 n=43 v=95
74160 Off ch=1 n=43 v=80
74160 On ch=1 n=43 v=95
74400 Off ch=1 n=43 v=80
74400 On ch=1 n=62 v=95
74400 On ch=1 n=55 v=95
74400 On ch=1 n=50 v=95
74880 Off ch=1 n=62 v=80
74880 Off ch=1 n=55 v=80
74880 Off ch=1 n=50 v=80
74880 On ch=1 n=50 v=95
75120 Off ch=1 n=50 v=80
75120 On ch=1 n=50 v=95
75360 Off ch=1 n=50 v=80
75360 On ch=1 n=62 v=95
75360 On ch=1 n=57 v=95
75360 On ch=1 n=50 v=95
75720 Off ch=1 n=62 v=80
75720 Off ch=1 n=57 v=80
75720 Off ch=1 n=50 v=80
75720 On ch=1 n=62 v=95
75720 On ch=1 n=57 v=95
75840 Off ch=1 n=62 v=80
75840 Off ch=1 n=57 v=80
75840 On ch=1 n=48 v=95
76080 Off ch=1 n=48 v=80
76080 On ch=1 n=48 v=95
76320 Off ch=1 n=48 v=80
76320 On ch=1 n=62 v=95
76320 On ch=1 n=55 v=95
76680 Off ch=1 n=62 v=80
76680 Off ch=1 n=55 v=80
76680 On ch=1 n=62 v=95
76680 On ch=1 n=55 v=95
76680 On ch=1 n=50 v=95
76800 Off ch=1 n=62 v=80
76800 Off ch=1 n=55 v=80
76800 Off ch=1 n=50 v=80
76800 On ch=1 n=43 v=95
77040 Off ch=1 n=43 v=80
77040 On ch=1 n=43 v=95
77280 Off ch=1 n=43 v=80
77280 On ch=1 n=62 v=95
77280 On ch=1 n=55 v=95
77280 On ch=1 n=50 v=95
77640 Off ch=1 n=62 v=80
77640 Off ch=1 n=55 v=80
77640 Off ch=1 n=50 v=80
77640 On ch=1 n=62 v=95
77640 On ch=1 n=55 v=95
77640 On ch=1 n=50 v=95
77760 Off ch=1 n=62 v=80
77760 Off ch=1 n=55 v=80
77760 Off ch=1 n=50 v=80
77760 On ch=1 n=43 v=95
78000 Off ch=1 n=43 v=80
78000 On ch=1 n=43 v=95
78240 Off ch=1 n=43 v=80
78240 On ch=1 n=62 v=95
78240 On ch=1 n=55 v=95
78240 On ch=1 n=50 v=95
78720 Off ch=1 n=62 v=80
78720 Off ch=1 n=55 v=80
78720 Off ch=1 n=50 v=80
78720 On ch=1 n=50 v=95
78960 Off ch=1 n=50 v=80
78960 On ch=1 n=50 v=95
79200 Off ch=1 n=50 v=80
79200 On ch=1 n=62 v=95
79200 On ch=1 n=57 v=95
79560 Off ch=1 n=62 v=80
79560 Off ch=1 n=57 v=80
79560 On ch=1 n=62 v=95
79560 On ch=1 n=57 v=95
79680 Off ch=1 n=62 v=80
79680 Off ch=1 n=57 v=80
79680 On ch=1 n=48 v=95
79920 Off ch=1 n=48 v=80
79920 On ch=1 n=48 v=95
80160 Off ch=1 n=48 v=80
80160 On ch=1 n=62 v=95
80160 On ch=1 n=55 v=95
80520 Off ch=1 n=62 v=80
80520 Off ch=1 n=55 v=80
80520 On ch=1 n=62 v=95
80520 On ch=1 n=55 v=95
80520 On ch=1 n=50 v=95
80640 Off ch=1 n=62 v=80
80640 Off ch=1 n=55 v=80
80640 Off ch=1 n=50 v=80
80640 On ch=1 n=43 v=95
80880 Off ch=1 n=43 v=80
80880 On ch=1 n=43 v=95
81120 Off ch=1 n=43 v=80
81120 On ch=1 n=62 v=95
81120 On ch=1 n=55 v=95
81120 On ch=1 n=50 v=95
81600 Off ch=1 n=62 v=80
81600 Off ch=1 n=55 v=80
81600 Off ch=1 n=50 v=80
81600 On ch=1 n=45 v=95
81720 Off ch=1 n=45 v=80
81720 On ch=1 n=47 v=76
81840 Off ch=1 n=47 v=80
81840 On ch=1 n=50 v=95
81960 Off ch=1 n=50 v=80
81960 On ch=1 n=55 v=95
82200 Off ch=1 n=55 v=80
82200 On ch=1 n=55 v=95
82320 Off ch=1 n=55 v=80
82320 On ch=1 n=45 v=95
82440 Off ch=1 n=45 v=80
82440 On ch=1 n=47 v=76
82560 Off ch=1 n=47 v=80
82560 On ch=1 n=50 v=95
82800 Off ch=1 n=50 v=80
82800 On ch=1 n=50 v=95
83040 Off ch=1 n=50 v=80
83040 On ch=1 n=62 v=95
83040 On ch=1 n=57 v=95
83400 Off ch=1 n=62 v=80
83400 Off ch=1 n=57 v=80
83400 On ch=1 n=62 v=95
83400 On ch=1 n=57 v=95
83520 Off ch=1 n=62 v=80
83520 Off ch=1 n=57 v=80
83520 On ch=1 n=48 v=95
83760 Off ch=1 n=48 v=80
83760 On ch=1 n=48 v=95
84000 Off ch=1 n=48 v=80
84000 On ch=1 n=62 v=95
84000 On ch=1 n=55 v=95
84360 Off ch=1 n=62 v=80
84360 Off ch=1 n=55 v=80
84360 On ch=1 n=62 v=95
84360 On ch=1 n=55 v=95
84360 On ch=1 n=52 v=95
84480 Off ch=1 n=62 v=80
84480 Off ch=1 n=55 v=80
84480 Off ch=1 n=52 v=80
84480 On ch=1 n=43 v=95
84720 Off ch=1 n=43 v=80
84720 On ch=1 n=43 v=95
84960 Off ch=1 n=43 v=80
84960 On ch=1 n=62 v=95
84960 On ch=1 n=55 v=95
84960 On ch=1 n=50 v=95
85440 Off ch=1 n=62 v=80
85440 Off ch=1 n=55 v=80
85440 Off ch=1 n=50 v=80
85440 On ch=1 n=43 v=95
85680 Off ch=1 n=43 v=80
85680 On ch=1 n=43 v=95
85920 Off ch=1 n=43 v=80
85920 On ch=1 n=55 v=95
85920 On ch=1 n=50 v=95
86400 Off ch=1 n=55 v=80
86400 Off ch=1 n=50 v=80
86400 On ch=1 n=50 v=95
86640 Off ch=1 n=50 v=80
86640 On ch=1 n=50 v=95
86880 Off ch=1 n=50 v=80
86880 On ch=1 n=62 v=95
86880 On ch=1 n=57 v=95
87240 Off ch=1 n=62 v=80
87240 Off ch=1 n=57 v=80
87240 On ch=1 n=62 v=95
87240 On ch=1 n=57 v=95
87360 Off ch=1 n=62 v=80
87360 Off ch=1 n=57 v=80
87360 On ch=1 n=48 v=95
87600 Off ch=1 n=48 v=80
87600 On ch=1 n=48 v=95
87840 Off ch=1 n=48 v=80
87840 On ch=1 n=62 v=95
87840 On ch=1 n=55 v=95
88200 Off ch=1 n=62 v=80
88200 Off ch=1 n=55 v=80
88200 On ch=1 n=62 v=95
88200 On ch=1 n=55 v=95
88200 On ch=1 n=50 v=95
88320 Off ch=1 n=62 v=80
88320 Off ch=1 n=55 v=80
88320 Off ch=1 n=50 v=80
88320 On ch=1 n=43 v=95
88560 Off ch=1 n=43 v=80
88560 On ch=1 n=43 v=95
88800 Off ch=1 n=43 v=80
88800 On ch=1 n=62 v=95
88800 On ch=1 n=55 v=95
88800 On ch=1 n=50 v=95
89280 Off ch=1 n=62 v=80
89280 Off ch=1 n=55 v=80
89280 Off ch=1 n=50 v=80
89280 On ch=1 n=45 v=95
89400 Off ch=1 n=45 v=80
89400 On ch=1 n=47 v=76
89520 Off ch=1 n=47 v=80
89520 On ch=1 n=50 v=95
89640 Off ch=1 n=50 v=80
89640 On ch=1 n=55 v=95
89880 Off ch=1 n=55 v=80
89880 On ch=1 n=55 v=95
90000 Off ch=1 n=55 v=80
90000 On ch=1 n=45 v=95
90120 Off ch=1 n=45 v=80
90120 On ch=1 n=47 v=76
90240 Off ch=1 n=47 v=80
90240 On ch=1 n=50 v=95
90480 Off ch=1 n=50 v=80
90480 On ch=1 n=50 v=95
90720 Off ch=1 n=50 v=80
90720 On ch=1 n=62 v=95
90720 On ch=1 n=57 v=95
91080 Off ch=1 n=62 v=80
91080 Off ch=1 n=57 v=80
91080 On ch=1 n=62 v=95
91080 On ch=1 n=57 v=95
91200 Off ch=1 n=62 v=80
91200 Off ch=1 n=57 v=80
91200 On ch=1 n=48 v=95
91440 Off ch=1 n=48 v=80
91440 On ch=1 n=55 v=95
91680 Off ch=1 n=55 v=80
91680 On ch=1 n=62 v=95
91680 On ch=1 n=55 v=95
92040 Off ch=1 n=62 v=80
92040 Off ch=1 n=55 v=80
92040 On ch=1 n=62 v=95
92040 On ch=1 n=55 v=95
92040 On ch=1 n=52 v=95
92160 Off ch=1 n=62 v=80
92160 Off ch=1 n=55 v=80
92160 Off ch=1 n=52 v=80
92160 On ch=1 n=43 v=95
92400 Off ch=1 n=43 v=80
92400 On ch=1 n=43 v=95
92640 Off ch=1 n=43 v=80
92640 On ch=1 n=55 v=95
92640 On ch=1 n=50 v=95
92880 Off ch=1 n=55 v=80
92880 Off ch=1 n=50 v=80
92880 On ch=1 n=55 v=95
92880 On ch=1 n=43 v=95
93000 Off ch=1 n=55 v=80
93000 Off ch=1 n=43 v=80
93000 On ch=1 n=55 v=95
93000 On ch=1 n=43 v=95
93120 Off ch=1 n=55 v=80
93120 Off ch=1 n=43 v=80
93120 On ch=1 n=45 v=95
93240 Off ch=1 n=45 v=80
93240 On ch=1 n=47 v=76
93360 Off ch=1 n=47 v=80
93360 On ch=1 n=50 v=95
93480 Off ch=1 n=50 v=80
93480 On ch=1 n=55 v=95
93600 Off ch=1 n=55 v=80
93600 On ch=1 n=52 v=95
93720 Off ch=1 n=52 v=80
93720 On ch=1 n=50 v=76
93840 Off ch=1 n=50 v=80
93840 On ch=1 n=45 v=95
93960 Off ch=1 n=45 v=80
93960 On ch=1 n=47 v=76
94080 Off ch=1 n=47 v=80
94080 On ch=1 n=50 v=95
94320 Off ch=1 n=50 v=80
94320 On ch=1 n=57 v=95
94320 On ch=1 n=50 v=95
94560 Off ch=1 n=57 v=80
94560 Off ch=1 n=50 v=80
94560 On ch=1 n=59 v=95
94560 On ch=1 n=50 v=95
94920 Off ch=1 n=59 v=80
94920 Off ch=1 n=50 v=80
94920 On ch=1 n=57 v=95
94920 On ch=1 n=50 v=95
95040 Off ch=1 n=57 v=80
95040 Off ch=1 n=50 v=80
95040 On ch=1 n=48 v=95
95280 Off ch=1 n=48 v=80
95280 On ch=1 n=55 v=95
95280 On ch=1 n=48 v=95
95520 Off ch=1 n=55 v=80
95520 Off ch=1 n=48 v=80
95520 On ch=1 n=57 v=95
95520 On ch=1 n=48 v=95
95640 Off ch=1 n=57 v=80
95640 Off ch=1 n=48 v=80
95640 On ch=1 n=55 v=95
95640 On ch=1 n=48 v=95
95880 Off ch=1 n=55 v=80
95880 Off ch=1 n=48 v=80
95880 On ch=1 n=57 v=95
95880 On ch=1 n=48 v=95
96000 Off ch=1 n=57 v=80
96000 Off ch=1 n=48 v=80
96000 On ch=1 n=50 v=95
96000 On ch=1 n=43 v=95
96240 Off ch=1 n=50 v=80
96240 Off ch=1 n=43 v=80
96240 On ch=1 n=50 v=95
96240 On ch=1 n=43 v=95
96480 Off ch=1 n=50 v=80
96480 Off ch=1 n=43 v=80
96480 On ch=1 n=44 v=95
96480 On ch=1 n=39 v=95
96500 Off ch=1 n=44 v=80
96500 Off ch=1 n=39 v=80
96600 On ch=1 n=50 v=95
96720 Off ch=1 n=50 v=80
96720 On ch=1 n=52 v=95
96720 On ch=1 n=43 v=95
96960 Off ch=1 n=52 v=80
96960 Off ch=1 n=43 v=80
96960 On ch=1 n=45 v=95
97080 Off ch=1 n=45 v=80
97080 On ch=1 n=47 v=76
97200 Off ch=1 n=47 v=80
97200 On ch=1 n=50 v=95
97320 Off ch=1 n=50 v=80
97320 On ch=1 n=52 v=95
97440 Off ch=1 n=52 v=80
97440 On ch=1 n=50 v=76
97560 Off ch=1 n=50 v=80
97560 On ch=1 n=48 v=95
97920 Off ch=1 n=48 v=80
97920 On ch=1 n=50 v=95
98160 Off ch=1 n=50 v=80
98160 On ch=1 n=57 v=95
98160 On ch=1 n=50 v=95
98400 Off ch=1 n=57 v=80
98400 Off ch=1 n=50 v=80
98400 On ch=1 n=59 v=95
98400 On ch=1 n=50 v=95
98760 Off ch=1 n=59 v=80
98760 Off ch=1 n=50 v=80
98760 On ch=1 n=57 v=95
98760 On ch=1 n=50 v=95
98880 Off ch=1 n=57 v=80
98880 Off ch=1 n=50 v=80
98880 On ch=1 n=48 v=95
99120 Off ch=1 n=48 v=80
99120 On ch=1 n=55 v=95
99120 On ch=1 n=48 v=95
99360 Off ch=1 n=55 v=80
99360 Off ch=1 n=48 v=80
99360 On ch=1 n=57 v=95
99360 On ch=1 n=48 v=95
99480 Off ch=1 n=57 v=80
99480 Off ch=1 n=48 v=80
99480 On ch=1 n=55 v=95
99480 On ch=1 n=48 v=95
99720 Off ch=1 n=55 v=80
99720 Off ch=1 n=48 v=80
99720 On ch=1 n=57 v=95
99720 On ch=1 n=48 v=95
99840 Off ch=1 n=57 v=80
99840 Off ch=1 n=48 v=80
99840 On ch=1 n=50 v=95
99960 Off ch=1 n=50 v=80
99960 On ch=1 n=52 v=76
100080 Off ch=1 n=52 v=80
100080 On ch=1 n=55 v=95
100200 Off ch=1 n=55 v=80
100200 On ch=1 n=50 v=95
100320 Off ch=1 n=50 v=80
100320 On ch=1 n=52 v=76
100440 Off ch=1 n=52 v=80
100440 On ch=1 n=55 v=95
100560 Off ch=1 n=55 v=80
100560 On ch=1 n=50 v=95
100680 Off ch=1 n=50 v=80
100680 On ch=1 n=52 v=76
100800 Off ch=1 n=52 v=80
100800 On ch=1 n=55 v=95
100920 Off ch=1 n=55 v=80
100920 On ch=1 n=50 v=95
101040 Off ch=1 n=50 v=80
101040 On ch=1 n=52 v=76
101160 Off ch=1 n=52 v=80
101160 On ch=1 n=55 v=95
101280 Off ch=1 n=55 v=80
101280 On ch=1 n=50 v=95
101400 Off ch=1 n=50 v=80
101400 On ch=1 n=52 v=76
101520 Off ch=1 n=52 v=80
101520 On ch=1 n=55 v=95
101640 Off ch=1 n=55 v=80
101640 On ch=2 n=48 v=95
101748 Pb ch=2 v=8832
101760 Off ch=2 n=48 v=80
101760 Pb ch=2 v=8192
101760 On ch=1 n=50 v=76
102000 Off ch=1 n=50 v=80
102000 On ch=1 n=57 v=95
102000 On ch=1 n=50 v=95
102240 Off ch=1 n=57 v=80
102240 Off ch=1 n=50 v=80
102240 On ch=1 n=59 v=95
102240 On ch=1 n=50 v=95
102600 Off ch=1 n=59 v=80
102600 Off ch=1 n=50 v=80
102600 On ch=1 n=57 v=95
102600 On ch=1 n=50 v=95
102720 Off ch=1 n=57 v=80
102720 Off ch=1 n=50 v=80
102720 On ch=1 n=48 v=95
102960 Off ch=1 n=48 v=80
102960 On ch=1 n=55 v=95
102960 On ch=1 n=48 v=95
103200 Off ch=1 n=55 v=80
103200 Off ch=1 n=48 v=80
103200 On ch=1 n=57 v=95
103200 On ch=1 n=48 v=95
103320 Off ch=1 n=57 v=80
103320 Off ch=1 n=48 v=80
103320 On ch=1 n=55 v=95
103320 On ch=1 n=48 v=95
103560 Off ch=1 n=55 v=80
103560 Off ch=1 n=48 v=80
103560 On ch=1 n=57 v=95
103560 On ch=1 n=48 v=95
103680 Off ch=1 n=57 v=80
103680 Off ch=1 n=48 v=80
103680 On ch=1 n=50 v=95
103680 On ch=1 n=43 v=95
103920 Off ch=1 n=50 v=80
103920 Off ch=1 n=43 v=80
103920 On ch=1 n=50 v=95
103920 On ch=1 n=43 v=95
104160 Off ch=1 n=50 v=80
104160 Off ch=1 n=43 v=80
104160 On ch=1 n=44 v=95
104160 On ch=1 n=39 v=95
104180 Off ch=1 n=44 v=80
104180 Off ch=1 n=39 v=80
104280 On ch=1 n=50 v=95
104400 Off ch=1 n=50 v=80
104400 On ch=1 n=52 v=95
104400 On ch=1 n=43 v=95
104640 Off ch=1 n=52 v=80
104640 Off ch=1 n=43 v=80
104640 On ch=1 n=45 v=95
104760 Off ch=1 n=45 v=80
104760 On ch=1 n=47 v=76
104880 Off ch=1 n=47 v=80
104880 On ch=1 n=50 v=95
105000 Off ch=1 n=50 v=80
105000 On ch=1 n=52 v=95
105120 Off ch=1 n=52 v=80
105120 On ch=1 n=50 v=76
105240 Off ch=1 n=50 v=80
105240 On ch=1 n=48 v=95
105600 Off ch=1 n=48 v=80
105600 On ch=1 n=50 v=95
105840 Off ch=1 n=50 v=80
105840 On ch=1 n=57 v=95
105840 On ch=1 n=50 v=95
106080 Off ch=1 n=57 v=80
106080 Off ch=1 n=50 v=80
106080 On ch=1 n=59 v=95
106080 On ch=1 n=50 v=95
106440 Off ch=1 n=59 v=80
106440 Off ch=1 n=50 v=80
106440 On ch=1 n=57 v=95
106440 On ch=1 n=50 v=95
106560 Off ch=1 n=57 v=80
106560 Off ch=1 n=50 v=80
106560 On ch=1 n=48 v=95
106800 Off ch=1 n=48 v=80
106800 On ch=1 n=55 v=95
106800 On ch=1 n=48 v=95
107040 Off ch=1 n=55 v=80
107040 Off ch=1 n=48 v=80
107040 On ch=1 n=57 v=95
107040 On ch=1 n=48 v=95
107160 Off ch=1 n=57 v=80
107160 Off ch=1 n=48 v=80
107160 On ch=1 n=55 v=95
107160 On ch=1 n=48 v=95
107400 Off ch=1 n=55 v=80
107400 Off ch=1 n=48 v=80
107400 On ch=1 n=57 v=95
107400 On ch=1 n=48 v=95
107520 Off ch=1 n=57 v=80
107520 Off ch=1 n=48 v=80
107520 On ch=1 n=50 v=95
107520 On ch=1 n=43 v=95
107760 Off ch=1 n=50 v=80
107760 Off ch=1 n=43 v=80
107760 On ch=1 n=50 v=95
107760 On ch=1 n=43 v=95
108000 Off ch=1 n=50 v=80
108000 Off ch=1 n=43 v=80
108000 On ch=1 n=44 v=95
108000 On ch=1 n=39 v=95
108020 Off ch=1 n=44 v=80
108020 Off ch=1 n=39 v=80
108120 On ch=1 n=44 v=95
108120 On ch=1 n=39 v=95
108140 Off ch=1 n=44 v=80
108140 Off ch=1 n=39 v=80
108240 On ch=1 n=52 v=95
108240 On ch=1 n=43 v=95
108480 Off ch=1 n=52 v=80
108480 Off ch=1 n=43 v=80
108480 On ch=1 n=44 v=95
108480 On ch=1 n=39 v=95
108500 Off ch=1 n=44 v=80
108500 Off ch=1 n=39 v=80
108600 On ch=1 n=50 v=95
108600 On ch=1 n=43 v=95
108720 Off ch=1 n=50 v=80
108720 Off ch=1 n=43 v=80
108720 On ch=1 n=50 v=95
108720 On ch=1 n=43 v=95
108840 Off ch=1 n=50 v=80
108840 Off ch=1 n=43 v=80
108840 On ch=1 n=50 v=95
108840 On ch=1 n=43 v=95
108960 Off ch=1 n=50 v=80
108960 Off ch=1 n=43 v=80
108960 On ch=1 n=44 v=95
108960 On ch=1 n=39 v=95
108980 Off ch=1 n=44 v=80
108980 Off ch=1 n=39 v=80
109080 On ch=1 n=44 v=95
109080 On ch=1 n=39 v=95
109100 Off ch=1 n=44 v=80
109100 Off ch=1 n=39 v=80
109320 On ch=1 n=50 v=95
109440 Off ch=1 n=50 v=80
109440 On ch=1 n=57 v=95
109440 On ch=1 n=50 v=95
109680 Off ch=1 n=57 v=80
109680 Off ch=1 n=50 v=80
109680 On ch=1 n=57 v=95
109680 On ch=1 n=50 v=95
109920 Off ch=1 n=57 v=80
109920 Off ch=1 n=50 v=80
109920 On ch=1 n=59 v=95
109920 On ch=1 n=50 v=95
110040 Off ch=1 n=59 v=80
110040 Off ch=1 n=50 v=80
110040 On ch=1 n=57 v=95
110040 On ch=1 n=50 v=95
110280 Off ch=1 n=57 v=80
110280 Off ch=1 n=50 v=80
110280 On ch=1 n=57 v=95
110280 On ch=1 n=50 v=95
110400 Off ch=1 n=57 v=80
110400 Off ch=1 n=50 v=80
110400 On ch=1 n=55 v=95
110400 On ch=1 n=48 v=95
110640 Off ch=1 n=55 v=80
110640 Off ch=1 n=48 v=80
110640 On ch=1 n=55 v=95
110640 On ch=1 n=48 v=95
110880 Off ch=1 n=55 v=80
110880 Off ch=1 n=48 v=80
110880 On ch=1 n=57 v=95
110880 On ch=1 n=50 v=95
111000 Off ch=1 n=57 v=80
111000 Off ch=1 n=50 v=80
111000 On ch=1 n=55 v=95
111000 On ch=1 n=48 v=95
111360 Off ch=1 n=55 v=80
111360 Off ch=1 n=48 v=80
111360 On ch=1 n=50 v=95
111360 On ch=1 n=43 v=95
111600 Off ch=1 n=50 v=80
111600 Off ch=1 n=43 v=80
111600 On ch=1 n=50 v=95
111600 On ch=1 n=43 v=95
111840 Off ch=1 n=50 v=80
111840 Off ch=1 n=43 v=80
111840 On ch=1 n=44 v=95
111840 On ch=1 n=39 v=95
111860 Off ch=1 n=44 v=80
111860 Off ch=1 n=39 v=80
111960 On ch=1 n=50 v=95
111960 On ch=1 n=43 v=95
112080 Off ch=1 n=50 v=80
112080 Off ch=1 n=43 v=80
112080 On ch=1 n=52 v=95
112080 On ch=1 n=43 v=95
112320 Off ch=1 n=52 v=80
112320 Off ch=1 n=43 v=80
112320 On ch=1 n=44 v=95
112320 On ch=1 n=39 v=95
112340 Off ch=1 n=44 v=80
112340 Off ch=1 n=39 v=80
112440 On ch=1 n=50 v=95
112440 On ch=1 n=43 v=95
112560 Off ch=1 n=50 v=80
112560 Off ch=1 n=43 v=80
112560 On ch=1 n=50 v=95
112560 On ch=1 n=43 v=95
112680 Off ch=1 n=50 v=80
112680 Off ch=1 n=43 v=80
112680 On ch=1 n=50 v=95
112680 On ch=1 n=43 v=95
112800 Off ch=1 n=50 v=80
112800 Off ch=1 n=43 v=80
112800 On ch=1 n=52 v=95
112800 On ch=1 n=43 v=95
112920 Off ch=1 n=52 v=80
112920 Off ch=1 n=43 v=80
112920 On ch=1 n=50 v=95
112920 On ch=1 n=43 v=95
113280 Off ch=1 n=50 v=80
113280 Off ch=1 n=43 v=80
113280 On ch=1 n=57 v=95
113280 On ch=1 n=50 v=95
113520 Off ch=1 n=57 v=80
113520 Off ch=1 n=50 v=80
113520 On ch=1 n=57 v=95
113520 On ch=1 n=50 v=95
113760 Off ch=1 n=57 v=80
113760 Off ch=1 n=50 v=80
113760 On ch=1 n=59 v=95
113760 On ch=1 n=50 v=95
113880 Off ch=1 n=59 v=80
113880 Off ch=1 n=50 v=80
113880 On ch=1 n=57 v=95
113880 On ch=1 n=50 v=95
114120 Off ch=1 n=57 v=80
114120 Off ch=1 n=50 v=80
114120 On ch=1 n=57 v=95
114120 On ch=1 n=50 v=95
114240 Off ch=1 n=57 v=80
114240 Off ch=1 n=50 v=80
114240 On ch=1 n=55 v=95
114240 On ch=1 n=48 v=95
114480 Off ch=1 n=55 v=80
114480 Off ch=1 n=48 v=80
114480 On ch=1 n=55 v=95
114480 On ch=1 n=48 v=95
114720 Off ch=1 n=55 v=80
114720 Off ch=1 n=48 v=80
114720 On ch=1 n=57 v=95
114720 On ch=1 n=50 v=95
114840 Off ch=1 n=57 v=80
114840 Off ch=1 n=50 v=80
114840 On ch=1 n=55 v=95
114840 On ch=1 n=48 v=95
115200 Off ch=1 n=55 v=80
115200 Off ch=1 n=48 v=80
115200 On ch=1 n=50 v=95
115200 On ch=1 n=43 v=95
115440 Off ch=1 n=50 v=80
115440 Off ch=1 n=43 v=80
115440 On ch=1 n=50 v=95
115440 On ch=1 n=43 v=95
115680 Off ch=1 n=50 v=80
115680 Off ch=1 n=43 v=80
115680 On ch=1 n=44 v=95
115680 On ch=1 n=39 v=95
115700 Off ch=1 n=44 v=80
115700 Off ch=1 n=39 v=80
115800 On ch=1 n=50 v=95
115800 On ch=1 n=43 v=95
115920 Off ch=1 n=50 v=80
115920 Off ch=1 n=43 v=80
115920 On ch=1 n=52 v=95
115920 On ch=1 n=43 v=95
116160 Off ch=1 n=52 v=80
116160 Off ch=1 n=43 v=80
116160 On ch=1 n=44 v=95
116160 On ch=1 n=39 v=95
116180 Off ch=1 n=44 v=80
116180 Off ch=1 n=39 v=80
116280 On ch=1 n=50 v=95
116280 On ch=1 n=43 v=95
116400 Off ch=1 n=50 v=80
116400 Off ch=1 n=43 v=80
116400 On ch=1 n=50 v=95
116400 On ch=1 n=43 v=95
116520 Off ch=1 n=50 v=80
116520 Off ch=1 n=43 v=80
116520 On ch=1 n=50 v=95
116520 On ch=1 n=43 v=95
116640 Off ch=1 n=50 v=80
116640 Off ch=1 n=43 v=80
116640 On ch=1 n=52 v=95
116640 On ch=1 n=43 v=95
116760 Off ch=1 n=52 v=80
116760 Off ch=1 n=43 v=80
116760 On ch=1 n=50 v=95
116760 On ch=1 n=43 v=95
117120 Off ch=1 n=50 v=80
117120 Off ch=1 n=43 v=80
117120 On ch=1 n=57 v=95
117120 On ch=1 n=50 v=95
117360 Off ch=1 n=57 v=80
117360 Off ch=1 n=50 v=80
117360 On ch=1 n=57 v=95
117360 On ch=1 n=50 v=95
117600 Off ch=1 n=57 v=80
117600 Off ch=1 n=50 v=80
117600 On ch=1 n=59 v=95
117600 On ch=1 n=50 v=95
117720 Off ch=1 n=59 v=80
117720 Off ch=1 n=50 v=80
117720 On ch=1 n=57 v=95
117720 On ch=1 n=50 v=95
117960 Off ch=1 n=57 v=80
117960 Off ch=1 n=50 v=80
117960 On ch=1 n=57 v=95
117960 On ch=1 n=50 v=95
118080 Off ch=1 n=57 v=80
118080 Off ch=1 n=50 v=80
118080 On ch=1 n=55 v=95
118080 On ch=1 n=48 v=95
118320 Off ch=1 n=55 v=80
118320 Off ch=1 n=48 v=80
118320 On ch=1 n=55 v=95
118320 On ch=1 n=48 v=95
118560 Off ch=1 n=55 v=80
118560 Off ch=1 n=48 v=80
118560 On ch=1 n=57 v=95
118560 On ch=1 n=50 v=95
118680 Off ch=1 n=57 v=80
118680 Off ch=1 n=50 v=80
118680 On ch=1 n=55 v=95
118680 On ch=1 n=48 v=95
119040 Off ch=1 n=55 v=80
119040 Off ch=1 n=48 v=80
119040 On ch=1 n=50 v=95
119040 On ch=1 n=43 v=95
119280 Off ch=1 n=50 v=80
119280 Off ch=1 n=43 v=80
119280 On ch=1 n=50 v=95
119280 On ch=1 n=43 v=95
119520 Off ch=1 n=50 v=80
119520 Off ch=1 n=43 v=80
119520 On ch=1 n=44 v=95
119520 On ch=1 n=39 v=95
119540 Off ch=1 n=44 v=80
119540 Off ch=1 n=39 v=80
119640 On ch=1 n=50 v=95
119640 On ch=1 n=43 v=95
119760 Off ch=1 n=50 v=80
119760 Off ch=1 n=43 v=80
119760 On ch=1 n=52 v=95
119760 On ch=1 n=43 v=95
120000 Off ch=1 n=52 v=80
120000 Off ch=1 n=43 v=80
120000 On ch=1 n=44 v=95
120000 On ch=1 n=39 v=95
120020 Off ch=1 n=44 v=80
120020 Off ch=1 n=39 v=80
120120 On ch=1 n=50 v=95
120120 On ch=1 n=43 v=95
120240 Off ch=1 n=50 v=80
120240 Off ch=1 n=43 v=80
120240 On ch=1 n=50 v=95
120240 On ch=1 n=43 v=95
120360 Off ch=1 n=50 v=80
120360 Off ch=1 n=43 v=80
120360 On ch=1 n=50 v=95
120360 On ch=1 n=43 v=95
120480 Off ch=1 n=50 v=80
120480 Off ch=1 n=43 v=80
120480 On ch=1 n=52 v=95
120480 On ch=1 n=43 v=95
120600 Off ch=1 n=52 v=80
120600 Off ch=1 n=43 v=80
120600 On ch=1 n=50 v=95
120600 On ch=1 n=43 v=95
120960 Off ch=1 n=50 v=80
120960 Off ch=1 n=43 v=80
120960 On ch=1 n=57 v=95
120960 On ch=1 n=50 v=95
121200 Off ch=1 n=57 v=80
121200 Off ch=1 n=50 v=80
121200 On ch=1 n=57 v=95
121200 On ch=1 n=50 v=95
121440 Off ch=1 n=57 v=80
121440 Off ch=1 n=50 v=80
121440 On ch=1 n=59 v=95
121440 On ch=1 n=50 v=95
121560 Off ch=1 n=59 v=80
121560 Off ch=1 n=50 v=80
121560 On ch=1 n=57 v=95
121560 On ch=1 n=50 v=95
121800 Off ch=1 n=57 v=80
121800 Off ch=1 n=50 v=80
121800 On ch=1 n=57 v=95
121800 On ch=1 n=50 v=95
121920 Off ch=1 n=57 v=80
121920 Off ch=1 n=50 v=80
121920 On ch=1 n=55 v=95
121920 On ch=1 n=48 v=95
122160 Off ch=1 n=55 v=80
122160 Off ch=1 n=48 v=80
122160 On ch=1 n=55 v=95
122160 On ch=1 n=48 v=95
122400 Off ch=1 n=55 v=80
122400 Off ch=1 n=48 v=80
122400 On ch=1 n=57 v=95
122400 On ch=1 n=50 v=95
122520 Off ch=1 n=57 v=80
122520 Off ch=1 n=50 v=80
122520 On ch=1 n=55 v=95
122520 On ch=1 n=48 v=95
122880 Off ch=1 n=55 v=80
122880 Off ch=1 n=48 v=80
122880 On ch=1 n=50 v=95
122880 On ch=1 n=43 v=95
123120 Off ch=1 n=50 v=80
123120 Off ch=1 n=43 v=80
123120 On ch=1 n=50 v=95
123120 On ch=1 n=43 v=95
123360 Off ch=1 n=50 v=80
123360 Off ch=1 n=43 v=80
123360 On ch=1 n=44 v=95
123360 On ch=1 n=39 v=95
123380 Off ch=1 n=44 v=80
123380 Off ch=1 n=39 v=80
123480 On ch=1 n=50 v=95
123480 On ch=1 n=43 v=95
123600 Off ch=1 n=50 v=80
123600 Off ch=1 n=43 v=80
123600 On ch=1 n=52 v=95
123600 On ch=1 n=43 v=95
123840 Off ch=1 n=52 v=80
123840 Off ch=1 n=43 v=80
123840 On ch=1 n=44 v=95
123840 On ch=1 n=39 v=95
123860 Off ch=1 n=44 v=80
123860 Off ch=1 n=39 v=80
123960 On ch=1 n=50 v=95
123960 On ch=1 n=43 v=95
124080 Off ch=1 n=50 v=80
124080 Off ch=1 n=43 v=80
124080 On ch=1 n=50 v=95
124080 On ch=1 n=43 v=95
124200 Off ch=1 n=50 v=80
124200 Off ch=1 n=43 v=80
124200 On ch=1 n=50 v=95
124200 On ch=1 n=43 v=95
124320 Off ch=1 n=50 v=80
124320 Off ch=1 n=43 v=80
124320 On ch=1 n=52 v=95
124320 On ch=1 n=43 v=95
124440 Off ch=1 n=52 v=80
124440 Off ch=1 n=43 v=80
124440 On ch=1 n=50 v=95
124440 On ch=1 n=43 v=95
124800 Off ch=1 n=50 v=80
124800 Off ch=1 n=43 v=80
124800 On ch=1 n=57 v=95
124800 On ch=1 n=50 v=95
125040 Off ch=1 n=57 v=80
125040 Off ch=1 n=50 v=80
125040 On ch=1 n=57 v=95
125040 On ch=1 n=50 v=95
125280 Off ch=1 n=57 v=80
125280 Off ch=1 n=50 v=80
125280 On ch=1 n=59 v=95
125280 On ch=1 n=50 v=95
125400 Off ch=1 n=59 v=80
125400 Off ch=1 n=50 v=80
125400 On ch=1 n=57 v=95
125400 On ch=1 n=50 v=95
125640 Off ch=1 n=57 v=80
125640 Off ch=1 n=50 v=80
125640 On ch=1 n=57 v=95
125640 On ch=1 n=50 v=95
125760 Off ch=1 n=57 v=80
125760 Off ch=1 n=50 v=80
125760 On ch=1 n=55 v=95
125760 On ch=1 n=48 v=95
126000 Off ch=1 n=55 v=80
126000 Off ch=1 n=48 v=80
126000 On ch=1 n=55 v=95
126000 On ch=1 n=48 v=95
126240 Off ch=1 n=55 v=80
126240 Off ch=1 n=48 v=80
126240 On ch=1 n=57 v=95
126240 On ch=1 n=50 v=95
126360 Off ch=1 n=57 v=80
126360 Off ch=1 n=50 v=80
126360 On ch=1 n=55 v=95
126360 On ch=1 n=48 v=95
126720 Off ch=1 n=55 v=80
126720 Off ch=1 n=48 v=80
126720 On ch=1 n=50 v=95
126720 On ch=1 n=43 v=95
126960 Off ch=1 n=50 v=80
126960 Off ch=1 n=43 v=80
126960 On ch=1 n=50 v=95
126960 On ch=1 n=43 v=95
127200 Off ch=1 n=50 v=80
127200 Off ch=1 n=43 v=80
127200 On ch=1 n=44 v=95
127200 On ch=1 n=39 v=95
127220 Off ch=1 n=44 v=80
127220 Off ch=1 n=39 v=80
127320 On ch=1 n=50 v=95
127320 On ch=1 n=43 v=95
127440 Off ch=1 n=50 v=80
127440 Off ch=1 n=43 v=80
127440 On ch=1 n=52 v=95
127440 On ch=1 n=43 v=95
127680 Off ch=1 n=52 v=80
127680 Off ch=1 n=43 v=80
127680 On ch=1 n=44 v=95
127680 On ch=1 n=39 v=95
127700 Off ch=1 n=44 v=80
127700 Off ch=1 n=39 v=80
127800 On ch=1 n=50 v=95
127800 On ch=1 n=43 v=95
127920 Off ch=1 n=50 v=80
127920 Off ch=1 n=43 v=80
127920 On ch=1 n=50 v=95
127920 On ch=1 n=43 v=95
128040 Off ch=1 n=50 v=80
128040 Off ch=1 n=43 v=80
128040 On ch=1 n=50 v=95
128040 On ch=1 n=43 v=95
128160 Off ch=1 n=50 v=80
128160 Off ch=1 n=43 v=80
128160 On ch=1 n=52 v=95
128160 On ch=1 n=43 v=95
128280 Off ch=1 n=52 v=80
128280 Off ch=1 n=43 v=80
128280 On ch=1 n=50 v=95
128280 On ch=1 n=43 v=95
128640 Off ch=1 n=50 v=80
128640 Off ch=1 n=43 v=80
128640 On ch=1 n=57 v=95
128640 On ch=1 n=50 v=95
128880 Off ch=1 n=57 v=80
128880 Off ch=1 n=50 v=80
128880 On ch=1 n=57 v=95
128880 On ch=1 n=50 v=95
129120 Off ch=1 n=57 v=80
129120 Off ch=1 n=50 v=80
129120 On ch=1 n=59 v=95
129120 On ch=1 n=50 v=95
129240 Off ch=1 n=59 v=80
129240 Off ch=1 n=50 v=80
129240 On ch=1 n=57 v=95
129240 On ch=1 n=50 v=95
129480 Off ch=1 n=57 v=80
129480 Off ch=1 n=50 v=80
129480 On ch=1 n=57 v=95
129480 On ch=1 n=50 v=95
129600 Off ch=1 n=57 v=80
129600 Off ch=1 n=50 v=80
129600 On ch=1 n=55 v=95
129600 On ch=1 n=48 v=95
129840 Off ch=1 n=55 v=80
129840 Off ch=1 n=48 v=80
129840 On ch=1 n=55 v=95
129840 On ch=1 n=48 v=95
130080 Off ch=1 n=55 v=80
130080 Off ch=1 n=48 v=80
130080 On ch=1 n=57 v=95
130080 On ch=1 n=50 v=95
130200 Off ch=1 n=57 v=80
130200 Off ch=1 n=50 v=80
130200 On ch=1 n=55 v=95
130200 On ch=1 n=48 v=95
130560 Off ch=1 n=55 v=80
130560 Off ch=1 n=48 v=80
130560 On ch=1 n=50 v=95
130560 On ch=1 n=43 v=95
130800 Off ch=1 n=50 v=80
130800 Off ch=1 n=43 v=80
130800 On ch=1 n=50 v=95
130800 On ch=1 n=43 v=95
131040 Off ch=1 n=50 v=80
131040 Off ch=1 n=43 v=80
131040 On ch=1 n=44 v=95
131040 On ch=1 n=39 v=95
131060 Off ch=1 n=44 v=80
131060 Off ch=1 n=39 v=80
131160 On ch=1 n=50 v=95
131160 On ch=1 n=43 v=95
131280 Off ch=1 n=50 v=80
131280 Off ch=1 n=43 v=80
131280 On ch=1 n=52 v=95
131280 On ch=1 n=43 v=95
131520 Off ch=1 n=52 v=80
131520 Off ch=1 n=43 v=80
131520 On ch=1 n=44 v=95
131520 On ch=1 n=39 v=95
131540 Off ch=1 n=44 v=80
131540 Off ch=1 n=39 v=80
131640 On ch=1 n=50 v=95
131640 On ch=1 n=43 v=95
131760 Off ch=1 n=50 v=80
131760 Off ch=1 n=43 v=80
131760 On ch=1 n=50 v=95
131760 On ch=1 n=43 v=95
131880 Off ch=1 n=50 v=80
131880 Off ch=1 n=43 v=80
131880 On ch=1 n=50 v=95
131880 On ch=1 n=43 v=95
132000 Off ch=1 n=50 v=80
132000 Off ch=1 n=43 v=80
132000 On ch=1 n=52 v=95
132000 On ch=1 n=43 v=95
132120 Off ch=1 n=52 v=80
132120 Off ch=1 n=43 v=80
132120 On ch=1 n=50 v=95
132120 On ch=1 n=43 v=95
132480 Off ch=1 n=50 v=80
132480 Off ch=1 n=43 v=80
132480 On ch=1 n=57 v=95
132480 On ch=1 n=50 v=95
132720 Off ch=1 n=57 v=80
132720 Off ch=1 n=50 v=80
132720 On ch=1 n=57 v=95
132720 On ch=1 n=50 v=95
132960 Off ch=1 n=57 v=80
132960 Off ch=1 n=50 v=80
132960 On ch=1 n=59 v=95
132960 On ch=1 n=50 v=95
133080 Off ch=1 n=59 v=80
133080 Off ch=1 n=50 v=80
133080 On ch=1 n=57 v=95
133080 On ch=1 n=50 v=95
133320 Off ch=1 n=57 v=80
133320 Off ch=1 n=50 v=80
133320 On ch=1 n=57 v=95
133320 On ch=1 n=50 v=95
133440 Off ch=1 n=57 v=80
133440 Off ch=1 n=50 v=80
133440 On ch=1 n=55 v=95
133440 On ch=1 n=48 v=95
133680 Off ch=1 n=55 v=80
133680 Off ch=1 n=48 v=80
133680 On ch=1 n=55 v=95
133680 On ch=1 n=48 v=95
133920 Off ch=1 n=55 v=80
133920 Off ch=1 n=48 v=80
133920 On ch=1 n=57 v=95
133920 On ch=1 n=50 v=95
134040 Off ch=1 n=57 v=80
134040 Off ch=1 n=50 v=80
134040 On ch=1 n=55 v=95
134040 On ch=1 n=48 v=95
134400 Off ch=1 n=55 v=80
134400 Off ch=1 n=48 v=80
134400 On ch=1 n=50 v=95
134400 On ch=1 n=43 v=95
134640 Off ch=1 n=50 v=80
134640 Off ch=1 n=43 v=80
134640 On ch=1 n=50 v=95
134640 On ch=1 n=43 v=95
134880 Off ch=1 n=50 v=80
134880 Off ch=1 n=43 v=80
134880 On ch=1 n=44 v=95
134880 On ch=1 n=39 v=95
134900 Off ch=1 n=44 v=80
134900 Off ch=1 n=39 v=80
135000 On ch=1 n=50 v=95
135000 On ch=1 n=43 v=95
135120 Off ch=1 n=50 v=80
135120 Off ch=1 n=43 v=80
135120 On ch=1 n=52 v=95
135120 On ch=1 n=43 v=95
135360 Off ch=1 n=52 v=80
135360 Off ch=1 n=43 v=80
135360 On ch=1 n=44 v=95
135360 On ch=1 n=39 v=95
135380 Off ch=1 n=44 v=80
135380 Off ch=1 n=39 v=80
135480 On ch=1 n=50 v=95
135480 On ch=1 n=43 v=95
135600 Off ch=1 n=50 v=80
135600 Off ch=1 n=43 v=80
135600 On ch=1 n=50 v=95
135600 On ch=1 n=43 v=95
135720 Off ch=1 n=50 v=80
135720 Off ch=1 n=43 v=80
135720 On ch=1 n=50 v=95
135720 On ch=1 n=43 v=95
135840 Off ch=1 n=50 v=80
135840 Off ch=1 n=43 v=80
135840 On ch=1 n=52 v=95
135840 On ch=1 n=43 v=95
135960 Off ch=1 n=52 v=80
135960 Off ch=1 n=43 v=80
135960 On ch=1 n=50 v=95
135960 On ch=1 n=43 v=95
136320 Off ch=1 n=50 v=80
136320 Off ch=1 n=43 v=80
136320 On ch=1 n=57 v=95
136320 On ch=1 n=50 v=95
136560 Off ch=1 n=57 v=80
136560 Off ch=1 n=50 v=80
136560 On ch=1 n=57 v=95
136560 On ch=1 n=50 v=95
136800 Off ch=1 n=57 v=80
136800 Off ch=1 n=50 v=80
136800 On ch=1 n=59 v=95
136800 On ch=1 n=50 v=95
136920 Off ch=1 n=59 v=80
136920 Off ch=1 n=50 v=80
136920 On ch=1 n=57 v=95
136920 On ch=1 n=50 v=95
137160 Off ch=1 n=57 v=80
137160 Off ch=1 n=50 v=80
137160 On ch=1 n=57 v=95
137160 On ch=1 n=50 v=95
137280 Off ch=1 n=57 v=80
137280 Off ch=1 n=50 v=80
137280 On ch=1 n=55 v=95
137280 On ch=1 n=48 v=95
137520 Off ch=1 n=55 v=80
137520 Off ch=1 n=48 v=80
137520 On ch=1 n=55 v=95
137520 On ch=1 n=48 v=95
137760 Off ch=1 n=55 v=80
137760 Off ch=1 n=48 v=80
137760 On ch=1 n=57 v=95
137760 On ch=1 n=50 v=95
137880 Off ch=1 n=57 v=80
137880 Off ch=1 n=50 v=80
137880 On ch=1 n=55 v=95
137880 On ch=1 n=48 v=95
138240 Off ch=1 n=55 v=80
138240 Off ch=1 n=48 v=80
138240 On ch=1 n=50 v=95
138240 On ch=1 n=43 v=95
138480 Off ch=1 n=50 v=80
138480 Off ch=1 n=43 v=80
138480 On ch=1 n=50 v=95
138480 On ch=1 n=43 v=95
138720 Off ch=1 n=50 v=80
138720 Off ch=1 n=43 v=80
138720 On ch=1 n=44 v=95
138720 On ch=1 n=39 v=95
138740 Off ch=1 n=44 v=80
138740 Off ch=1 n=39 v=80
138840 On ch=1 n=50 v=95
138840 On ch=1 n=43 v=95
138960 Off ch=1 n=50 v=80
138960 Off ch=1 n=43 v=80
138960 On ch=1 n=52 v=95
138960 On ch=1 n=43 v=95
139200 Off ch=1 n=52 v=80
139200 Off ch=1 n=43 v=80
139200 On ch=1 n=44 v=95
139200 On ch=1 n=39 v=95
139220 Off ch=1 n=44 v=80
139220 Off ch=1 n=39 v=80
139320 On ch=1 n=50 v=95
139320 On ch=1 n=43 v=95
139440 Off ch=1 n=50 v=80
139440 Off ch=1 n=43 v=80
139440 On ch=1 n=50 v=95
139440 On ch=1 n=43 v=95
139560 Off ch=1 n=50 v=80
139560 Off ch=1 n=43 v=80
139560 On ch=1 n=50 v=95
139560 On ch=1 n=43 v=95
139680 Off ch=1 n=50 v=80
139680 Off ch=1 n=43 v=80
139680 On ch=1 n=52 v=95
139680 On ch=1 n=43 v=95
139800 Off ch=1 n=52 v=80
139800 Off ch=1 n=43 v=80
139800 On ch=1 n=50 v=95
139800 On ch=1 n=43 v=95
140160 Off ch=1 n=50 v=80
140160 Off ch=1 n=43 v=80
140160 On ch=1 n=52 v=95
140280 Off ch=1 n=52 v=80
140280 On ch=1 n=53 v=95
140400 Off ch=1 n=53 v=80
140400 On ch=1 n=54 v=95
140520 Off ch=1 n=54 v=80
140520 On ch=1 n=62 v=95
140520 On ch=1 n=57 v=95
140760 Off ch=1 n=62 v=80
140760 Off ch=1 n=57 v=80
140760 On ch=1 n=62 v=95
140760 On ch=1 n=57 v=95
140880 Off ch=1 n=62 v=80
140880 Off ch=1 n=57 v=80
140880 On ch=2 n=54 v=95
141072 Pb ch=2 v=7552
141088 Pb ch=2 v=6784
141104 Pb ch=2 v=6144
141120 Off ch=2 n=54 v=80
141120 Pb ch=2 v=8192
141120 On ch=1 n=50 v=76
141240 Off ch=1 n=50 v=80
141240 On ch=1 n=52 v=95
141360 Off ch=1 n=52 v=80
141360 On ch=1 n=60 v=95
141360 On ch=1 n=55 v=95
141600 Off ch=1 n=60 v=80
141600 Off ch=1 n=55 v=80
141600 On ch=1 n=60 v=95
141600 On ch=1 n=55 v=95
141840 Off ch=1 n=60 v=80
141840 Off ch=1 n=55 v=80
141840 On ch=2 n=52 v=95
142008 Pb ch=2 v=7552
142022 Pb ch=2 v=6784
142037 Pb ch=2 v=6144
142051 Pb ch=2 v=5504
142066 Pb ch=2 v=4736
142080 Off ch=2 n=52 v=80
142080 Pb ch=2 v=8192
142080 On ch=2 n=46 v=76
142176 Pb ch=2 v=8832
142200 Off ch=2 n=46 v=80
142200 Pb ch=2 v=8192
142200 On ch=1 n=47 v=76
142320 Off ch=1 n=47 v=80
142320 On ch=1 n=50 v=95
142440 Off ch=1 n=50 v=80
142440 On ch=1 n=55 v=95
142680 Off ch=1 n=55 v=80
142680 On ch=1 n=55 v=95
142800 Off ch=1 n=55 v=80
142800 On ch=2 n=43 v=95
142800 Pb ch=2 v=8192
142805 Pb ch=2 v=8192
142809 Pb ch=2 v=8192
142814 Pb ch=2 v=8192
142819 Pb ch=2 v=8192
142823 Pb ch=2 v=8192
142828 Pb ch=2 v=8192
142833 Pb ch=2 v=8192
142838 Pb ch=2 v=8192
142842 Pb ch=2 v=8192
142847 Pb ch=2 v=8192
142852 Pb ch=2 v=8192
142856 Pb ch=2 v=8192
142861 Pb ch=2 v=8192
142866 Pb ch=2 v=8192
142870 Pb ch=2 v=8192
142875 Pb ch=2 v=8192
142880 Pb ch=2 v=8192
142884 Pb ch=2 v=8192
142889 Pb ch=2 v=8192
142894 Pb ch=2 v=8192
142898 Pb ch=2 v=8192
142903 Pb ch=2 v=8192
142908 Pb ch=2 v=8192
142912 Pb ch=2 v=8192
142917 Pb ch=2 v=8192
142922 Pb ch=2 v=8192
142927 Pb ch=2 v=8192
142931 Pb ch=2 v=8192
142936 Pb ch=2 v=8192
142941 Pb ch=2 v=8192
142945 Pb ch=2 v=8192
142950 Pb ch=2 v=8192
142955 Pb ch=2 v=8192
142959 Pb ch=2 v=8320
142964 Pb ch=2 v=8320
142969 Pb ch=2 v=8320
142973 Pb ch=2 v=8320
142978 Pb ch=2 v=8320
142983 Pb ch=2 v=8320
142988 Pb ch=2 v=8320
142992 Pb ch=2 v=8320
142997 Pb ch=2 v=8320
143002 Pb ch=2 v=8320
143006 Pb ch=2 v=8320
143011 Pb ch=2 v=8320
143016 Pb ch=2 v=8320
143020 Pb ch=2 v=8192
143025 Pb ch=2 v=8192
143030 Pb ch=2 v=8192
143034 Pb ch=2 v=8064
143039 Pb ch=2 v=8064
143044 Pb ch=2 v=8064
143048 Pb ch=2 v=8064
143053 Pb ch=2 v=8064
143058 Pb ch=2 v=8064
143062 Pb ch=2 v=8064
143067 Pb ch=2 v=8064
143072 Pb ch=2 v=8064
143077 Pb ch=2 v=8064
143081 Pb ch=2 v=8064
143086 Pb ch=2 v=8064
143091 Pb ch=2 v=8064
143095 Pb ch=2 v=8192
143100 Pb ch=2 v=8192
143105 Pb ch=2 v=8320
143109 Pb ch=2 v=8320
143114 Pb ch=2 v=8320
143119 Pb ch=2 v=8448
143123 Pb ch=2 v=8448
143128 Pb ch=2 v=8448
143133 Pb ch=2 v=8448
143138 Pb ch=2 v=8448
143142 Pb ch=2 v=8448
143147 Pb ch=2 v=8448
143152 Pb ch=2 v=8448
143156 Pb ch=2 v=8448
143161 Pb ch=2 v=8320
143166 Pb ch=2 v=8320
143170 Pb ch=2 v=8320
143175 Pb ch=2 v=8192
143180 Pb ch=2 v=8064
143184 Pb ch=2 v=8064
143189 Pb ch=2 v=8064
143194 Pb ch=2 v=7936
143198 Pb ch=2 v=7936
143203 Pb ch=2 v=7936
143208 Pb ch=2 v=7936
143212 Pb ch=2 v=7936
143217 Pb ch=2 v=7936
143222 Pb ch=2 v=7936
143227 Pb ch=2 v=7936
143231 Pb ch=2 v=7936
143236 Pb ch=2 v=8064
143241 Pb ch=2 v=8064
143245 Pb ch=2 v=8064
143250 Pb ch=2 v=8192
143255 Pb ch=2 v=8192
143259 Pb ch=2 v=8320
143264 Pb ch=2 v=8320
143269 Pb ch=2 v=8320
143273 Pb ch=2 v=8448
143278 Pb ch=2 v=8448
143283 Pb ch=2 v=8448
143288 Pb ch=2 v=8448
143292 Pb ch=2 v=8448
143297 Pb ch=2 v=8448
143302 Pb ch=2 v=8448
143306 Pb ch=2 v=8320
143311 Pb ch=2 v=8320
143316 Pb ch=2 v=8320
143320 Pb ch=2 v=8192
143325 Pb ch=2 v=8192
143330 Pb ch=2 v=8192
143334 Pb ch=2 v=8064
143339 Pb ch=2 v=8064
143344 Pb ch=2 v=8064
143348 Pb ch=2 v=7936
143353 Pb ch=2 v=7936
143358 Pb ch=2 v=7936
143362 Pb ch=2 v=7936
143367 Pb ch=2 v=7936
143372 Pb ch=2 v=7936
143377 Pb ch=2 v=7936
143381 Pb ch=2 v=8064
143386 Pb ch=2 v=8064
143391 Pb ch=2 v=8064
143395 Pb ch=2 v=8192
143400 Pb ch=2 v=8192
143405 Pb ch=2 v=8192
143409 Pb ch=2 v=8320
143414 Pb ch=2 v=8320
143419 Pb ch=2 v=8448
143423 Pb ch=2 v=8448
143428 Pb ch=2 v=8448
143433 Pb ch=2 v=8448
143438 Pb ch=2 v=8448
143442 Pb ch=2 v=8448
143447 Pb ch=2 v=8448
143452 Pb ch=2 v=8448
143456 Pb ch=2 v=8448
143461 Pb ch=2 v=8320
143466 Pb ch=2 v=8320
143470 Pb ch=2 v=8192
143475 Pb ch=2 v=8192
143480 Pb ch=2 v=8192
143484 Pb ch=2 v=8064
143489 Pb ch=2 v=8064
143494 Pb ch=2 v=7936
143498 Pb ch=2 v=7936
143503 Pb ch=2 v=7936
143508 Pb ch=2 v=7936
143512 Pb ch=2 v=7936
143517 Pb ch=2 v=7936
143522 Pb ch=2 v=7936
143527 Pb ch=2 v=7936
143531 Pb ch=2 v=7936
143536 Pb ch=2 v=8064
143541 Pb ch=2 v=8064
143545 Pb ch=2 v=8192
143550 Pb ch=2 v=8192
143555 Pb ch=2 v=8192
143559 Pb ch=2 v=8320
143564 Pb ch=2 v=8320
143569 Pb ch=2 v=8448
143573 Pb ch=2 v=8448
143578 Pb ch=2 v=8448
143583 Pb ch=2 v=8448
143588 Pb ch=2 v=8448
143592 Pb ch=2 v=8448
143597 Pb ch=2 v=8448
143602 Pb ch=2 v=8448
143606 Pb ch=2 v=8448
143611 Pb ch=2 v=8320
143616 Pb ch=2 v=8320
143620 Pb ch=2 v=8192
143625 Pb ch=2 v=8192
143630 Pb ch=2 v=8192
143634 Pb ch=2 v=8064
143639 Pb ch=2 v=8064
143644 Pb ch=2 v=7936
143648 Pb ch=2 v=7936
143653 Pb ch=2 v=7936
143658 Pb ch=2 v=7936
143662 Pb ch=2 v=7936
143667 Pb ch=2 v=7936
143672 Pb ch=2 v=7936
143677 Pb ch=2 v=7936
143681 Pb ch=2 v=7936
143686 Pb ch=2 v=8064
143691 Pb ch=2 v=8064
143695 Pb ch=2 v=8192
143700 Pb ch=2 v=8192
143705 Pb ch=2 v=8192
143709 Pb ch=2 v=8320
143714 Pb ch=2 v=8320
143719 Pb ch=2 v=8320
143723 Pb ch=2 v=8448
143728 Pb ch=2 v=8448
143733 Pb ch=2 v=8448
143738 Pb ch=2 v=8448
143742 Pb ch=2 v=8448
143747 Pb ch=2 v=8448
143752 Pb ch=2 v=8448
143756 Pb ch=2 v=8320
143761 Pb ch=2 v=8320
143766 Pb ch=2 v=8320
143770 Pb ch=2 v=8192
143775 Pb ch=2 v=8192
143780 Pb ch=2 v=8192
143784 Pb ch=2 v=8064
143789 Pb ch=2 v=8064
143794 Pb ch=2 v=8064
143798 Pb ch=2 v=7936
143803 Pb ch=2 v=7936
143808 Pb ch=2 v=7936
143812 Pb ch=2 v=7936
143817 Pb ch=2 v=7936
143822 Pb ch=2 v=7936
143827 Pb ch=2 v=7936
143831 Pb ch=2 v=8064
143836 Pb ch=2 v=8064
143841 Pb ch=2 v=8064
143845 Pb ch=2 v=8192
143850 Pb ch=2 v=8192
143855 Pb ch=2 v=8192
143859 Pb ch=2 v=8320
143864 Pb ch=2 v=8320
143869 Pb ch=2 v=8320
143873 Pb ch=2 v=8320
143878 Pb ch=2 v=8448
143883 Pb ch=2 v=8448
143888 Pb ch=2 v=8448
143892 Pb ch=2 v=8448
143897 Pb ch=2 v=8448
143902 Pb ch=2 v=8320
143906 Pb ch=2 v=8320
143911 Pb ch=2 v=8320
143916 Pb ch=2 v=8320
143920 Pb ch=2 v=8192
143925 Pb ch=2 v=8192
143930 Pb ch=2 v=8192
143934 Pb ch=2 v=8064
143939 Pb ch=2 v=8064
143944 Pb ch=2 v=8064
143948 Pb ch=2 v=8064
143953 Pb ch=2 v=7936
143958 Pb ch=2 v=7936
143962 Pb ch=2 v=7936
143967 Pb ch=2 v=7936
143972 Pb ch=2 v=7936
143977 Pb ch=2 v=8064
143981 Pb ch=2 v=8064
143986 Pb ch=2 v=8064
143991 Pb ch=2 v=8064
143995 Pb ch=2 v=8192
144000 Off ch=2 n=43 v=80
144000 Pb ch=2 v=8192
144000 On ch=1 n=52 v=95
144120 Off ch=1 n=52 v=80
144120 On ch=1 n=53 v=95
144240 Off ch=1 n=53 v=80
144240 On ch=1 n=54 v=95
144360 Off ch=1 n=54 v=80
144360 On ch=1 n=62 v=95
144360 On ch=1 n=57 v=95
144600 Off ch=1 n=62 v=80
144600 Off ch=1 n=57 v=80
144600 On ch=1 n=62 v=95
144600 On ch=1 n=57 v=95
144720 Off ch=1 n=62 v=80
144720 Off ch=1 n=57 v=80
144720 On ch=2 n=54 v=95
144912 Pb ch=2 v=7552
144928 Pb ch=2 v=6784
144944 Pb ch=2 v=6144
144960 Off ch=2 n=54 v=80
144960 Pb ch=2 v=8192
144960 On ch=1 n=50 v=76
145080 Off ch=1 n=50 v=80
145080 On ch=1 n=52 v=95
145200 Off ch=1 n=52 v=80
145200 On ch=1 n=60 v=95
145200 On ch=1 n=55 v=95
145440 Off ch=1 n=60 v=80
145440 Off ch=1 n=55 v=80
145440 On ch=1 n=60 v=95
145440 On ch=1 n=55 v=95
145680 Off ch=1 n=60 v=80
145680 Off ch=1 n=55 v=80
145680 On ch=2 n=52 v=95
145848 Pb ch=2 v=7552
145862 Pb ch=2 v=6784
145877 Pb ch=2 v=6144
145891 Pb ch=2 v=5504
145906 Pb ch=2 v=4736
145920 Off ch=2 n=52 v=80
145920 Pb ch=2 v=8192
145920 On ch=2 n=46 v=76
146016 Pb ch=2 v=8832
146040 Off ch=2 n=46 v=80
146040 Pb ch=2 v=8192
146040 On ch=1 n=47 v=76
146160 Off ch=1 n=47 v=80
146160 On ch=1 n=50 v=95
146280 Off ch=1 n=50 v=80
146280 On ch=1 n=55 v=95
146520 Off ch=1 n=55 v=80
146520 On ch=1 n=55 v=95
146640 Off ch=1 n=55 v=80
146640 On ch=2 n=43 v=95
146640 Pb ch=2 v=8192
146645 Pb ch=2 v=8192
146649 Pb ch=2 v=8192
146654 Pb ch=2 v=8192
146659 Pb ch=2 v=8192
146663 Pb ch=2 v=8192
146668 Pb ch=2 v=8192
146673 Pb ch=2 v=8192
146678 Pb ch=2 v=8192
146682 Pb ch=2 v=8192
146687 Pb ch=2 v=8192
146692 Pb ch=2 v=8192
146696 Pb ch=2 v=8192
146701 Pb ch=2 v=8192
146706 Pb ch=2 v=8192
146710 Pb ch=2 v=8192
146715 Pb ch=2 v=8192
146720 Pb ch=2 v=8192
146724 Pb ch=2 v=8192
146729 Pb ch=2 v=8192
146734 Pb ch=2 v=8192
146738 Pb ch=2 v=8192
146743 Pb ch=2 v=8192
146748 Pb ch=2 v=8192
146752 Pb ch=2 v=8192
146757 Pb ch=2 v=8192
146762 Pb ch=2 v=8192
146767 Pb ch=2 v=8192
146771 Pb ch=2 v=8192
146776 Pb ch=2 v=8192
146781 Pb ch=2 v=8192
146785 Pb ch=2 v=8192
146790 Pb ch=2 v=8192
146795 Pb ch=2 v=8192
146799 Pb ch=2 v=8320
146804 Pb ch=2 v=8320
146809 Pb ch=2 v=8320
146813 Pb ch=2 v=8320
146818 Pb ch=2 v=8448
146823 Pb ch=2 v=8448
146828 Pb ch=2 v=8448
146832 Pb ch=2 v=8448
146837 Pb ch=2 v=8448
146842 Pb ch=2 v=8320
146846 Pb ch=2 v=8320
146851 Pb ch=2 v=8320
146856 Pb ch=2 v=8320
146860 Pb ch=2 v=8192
146865 Pb ch=2 v=8192
146870 Pb ch=2 v=8192
146874 Pb ch=2 v=8064
146879 Pb ch=2 v=8064
146884 Pb ch=2 v=8064
146888 Pb ch=2 v=8064
146893 Pb ch=2 v=7936
146898 Pb ch=2 v=7936
146902 Pb ch=2 v=7936
146907 Pb ch=2 v=7936
146912 Pb ch=2 v=7936
146917 Pb ch=2 v=8064
146921 Pb ch=2 v=8064
146926 Pb ch=2 v=8064
146931 Pb ch=2 v=8064
146935 Pb ch=2 v=8192
146940 Pb ch=2 v=8192
146945 Pb ch=2 v=8192
146949 Pb ch=2 v=8320
146954 Pb ch=2 v=8320
146959 Pb ch=2 v=8320
146963 Pb ch=2 v=8320
146968 Pb ch=2 v=8448
146973 Pb ch=2 v=8448
146978 Pb ch=2 v=8448
146982 Pb ch=2 v=8448
146987 Pb ch=2 v=8448
146992 Pb ch=2 v=8320
146996 Pb ch=2 v=8320
147001 Pb ch=2 v=8320
147006 Pb ch=2 v=8320
147010 Pb ch=2 v=8192
147015 Pb ch=2 v=8192
147020 Pb ch=2 v=8192
147024 Pb ch=2 v=8064
147029 Pb ch=2 v=8064
147034 Pb ch=2 v=8064
147038 Pb ch=2 v=8064
147043 Pb ch=2 v=7936
147048 Pb ch=2 v=7936
147052 Pb ch=2 v=7936
147057 Pb ch=2 v=7936
147062 Pb ch=2 v=7936
147067 Pb ch=2 v=8064
147071 Pb ch=2 v=8064
147076 Pb ch=2 v=8064
147081 Pb ch=2 v=8064
147085 Pb ch=2 v=8192
147090 Pb ch=2 v=8192
147095 Pb ch=2 v=8192
147099 Pb ch=2 v=8320
147104 Pb ch=2 v=8320
147109 Pb ch=2 v=8320
147113 Pb ch=2 v=8320
147118 Pb ch=2 v=8320
147123 Pb ch=2 v=8448
147128 Pb ch=2 v=8448
147132 Pb ch=2 v=8448
147137 Pb ch=2 v=8320
147142 Pb ch=2 v=8320
147146 Pb ch=2 v=8320
147151 Pb ch=2 v=8320
147156 Pb ch=2 v=8320
147160 Pb ch=2 v=8192
147165 Pb ch=2 v=8192
147170 Pb ch=2 v=8192
147174 Pb ch=2 v=8064
147179 Pb ch=2 v=8064
147184 Pb ch=2 v=8064
147188 Pb ch=2 v=8064
147193 Pb ch=2 v=8064
147198 Pb ch=2 v=7936
147202 Pb ch=2 v=7936
147207 Pb ch=2 v=7936
147212 Pb ch=2 v=8064
147217 Pb ch=2 v=8064
147221 Pb ch=2 v=8064
147226 Pb ch=2 v=8064
147231 Pb ch=2 v=8064
147235 Pb ch=2 v=8192
147240 Pb ch=2 v=8192
147245 Pb ch=2 v=8192
147249 Pb ch=2 v=8320
147254 Pb ch=2 v=8320
147259 Pb ch=2 v=8320
147263 Pb ch=2 v=8448
147268 Pb ch=2 v=8448
147273 Pb ch=2 v=8448
147278 Pb ch=2 v=8448
147282 Pb ch=2 v=8448
147287 Pb ch=2 v=8448
147292 Pb ch=2 v=8448
147296 Pb ch=2 v=8320
147301 Pb ch=2 v=8320
147306 Pb ch=2 v=8320
147310 Pb ch=2 v=8192
147315 Pb ch=2 v=8192
147320 Pb ch=2 v=8192
147324 Pb ch=2 v=8064
147329 Pb ch=2 v=8064
147334 Pb ch=2 v=8064
147338 Pb ch=2 v=7936
147343 Pb ch=2 v=7936
147348 Pb ch=2 v=7936
147352 Pb ch=2 v=7936
147357 Pb ch=2 v=7936
147362 Pb ch=2 v=7936
147367 Pb ch=2 v=7936
147371 Pb ch=2 v=8064
147376 Pb ch=2 v=8064
147381 Pb ch=2 v=8064
147385 Pb ch=2 v=8192
147390 Pb ch=2 v=8192
147395 Pb ch=2 v=8192
147399 Pb ch=2 v=8320
147404 Pb ch=2 v=8320
147409 Pb ch=2 v=8320
147413 Pb ch=2 v=8320
147418 Pb ch=2 v=8320
147423 Pb ch=2 v=8320
147428 Pb ch=2 v=8320
147432 Pb ch=2 v=8320
147437 Pb ch=2 v=8320
147442 Pb ch=2 v=8320
147446 Pb ch=2 v=8320
147451 Pb ch=2 v=8320
147456 Pb ch=2 v=8320
147460 Pb ch=2 v=8192
147465 Pb ch=2 v=8192
147470 Pb ch=2 v=8192
147474 Pb ch=2 v=8064
147479 Pb ch=2 v=8064
147484 Pb ch=2 v=8064
147488 Pb ch=2 v=8064
147493 Pb ch=2 v=8064
147498 Pb ch=2 v=8064
147502 Pb ch=2 v=8064
147507 Pb ch=2 v=8064
147512 Pb ch=2 v=8064
147517 Pb ch=2 v=8064
147521 Pb ch=2 v=8064
147526 Pb ch=2 v=8064
147531 Pb ch=2 v=8064
147535 Pb ch=2 v=8192
147540 Pb ch=2 v=8192
147545 Pb ch=2 v=8192
147549 Pb ch=2 v=8320
147554 Pb ch=2 v=8320
147559 Pb ch=2 v=8448
147563 Pb ch=2 v=8448
147568 Pb ch=2 v=8448
147573 Pb ch=2 v=8448
147578 Pb ch=2 v=8448
147582 Pb ch=2 v=8448
147587 Pb ch=2 v=8448
147592 Pb ch=2 v=8448
147596 Pb ch=2 v=8448
147601 Pb ch=2 v=8320
147606 Pb ch=2 v=8320
147610 Pb ch=2 v=8192
147615 Pb ch=2 v=8192
147620 Pb ch=2 v=8192
147624 Pb ch=2 v=8064
147629 Pb ch=2 v=8064
147634 Pb ch=2 v=7936
147638 Pb ch=2 v=7936
147643 Pb ch=2 v=7936
147648 Pb ch=2 v=7936
147652 Pb ch=2 v=7936
147657 Pb ch=2 v=7936
147662 Pb ch=2 v=7936
147667 Pb ch=2 v=7936
147671 Pb ch=2 v=7936
147676 Pb ch=2 v=8064
147681 Pb ch=2 v=8064
147685 Pb ch=2 v=8192
147690 Pb ch=2 v=8192
147695 Pb ch=2 v=8192
147699 Pb ch=2 v=8320
147704 Pb ch=2 v=8320
147709 Pb ch=2 v=8320
147713 Pb ch=2 v=8448
147718 Pb ch=2 v=8448
147723 Pb ch=2 v=8448
147728 Pb ch=2 v=8448
147732 Pb ch=2 v=8448
147737 Pb ch=2 v=8448
147742 Pb ch=2 v=8448
147746 Pb ch=2 v=8320
147751 Pb ch=2 v=8320
147756 Pb ch=2 v=8320
147760 Pb ch=2 v=8192
147765 Pb ch=2 v=8192
147770 Pb ch=2 v=8192
147774 Pb ch=2 v=8064
147779 Pb ch=2 v=8064
147784 Pb ch=2 v=8064
147788 Pb ch=2 v=7936
147793 Pb ch=2 v=7936
147798 Pb ch=2 v=7936
147802 Pb ch=2 v=7936
147807 Pb ch=2 v=7936
147812 Pb ch=2 v=7936
147817 Pb ch=2 v=7936
147821 Pb ch=2 v=8064
147826 Pb ch=2 v=8064
147831 Pb ch=2 v=8064
147835 Pb ch=2 v=8192
147840 Off ch=2 n=43 v=80
147840 Pb ch=2 v=8192
147840 On ch=1 n=50 v=95
148080 Off ch=1 n=50 v=80
148080 On ch=1 n=50 v=95
148320 Off ch=1 n=50 v=80
148320 On ch=1 n=62 v=95
148320 On ch=1 n=57 v=95
148680 Off ch=1 n=62 v=80
148680 Off ch=1 n=57 v=80
148680 On ch=1 n=62 v=95
148680 On ch=1 n=57 v=95
148800 Off ch=1 n=62 v=80
148800 Off ch=1 n=57 v=80
148800 On ch=1 n=48 v=95
149040 Off ch=1 n=48 v=80
149040 On ch=1 n=48 v=95
149280 Off ch=1 n=48 v=80
149280 On ch=1 n=62 v=95
149280 On ch=1 n=55 v=95
149640 Off ch=1 n=62 v=80
149640 Off ch=1 n=55 v=80
149640 On ch=1 n=62 v=95
149640 On ch=1 n=55 v=95
149640 On ch=1 n=50 v=95
149760 Off ch=1 n=62 v=80
149760 Off ch=1 n=55 v=80
149760 Off ch=1 n=50 v=80
149760 On ch=1 n=43 v=95
150000 Off ch=1 n=43 v=80
150000 On ch=1 n=43 v=95
150240 Off ch=1 n=43 v=80
150240 On ch=1 n=62 v=95
150240 On ch=1 n=55 v=95
150240 On ch=1 n=50 v=95
150720 Off ch=1 n=62 v=80
150720 Off ch=1 n=55 v=80
150720 Off ch=1 n=50 v=80
150720 On ch=1 n=45 v=95
150840 Off ch=1 n=45 v=80
150840 On ch=1 n=47 v=76
150960 Off ch=1 n=47 v=80
150960 On ch=1 n=50 v=95
151080 Off ch=1 n=50 v=80
151080 On ch=1 n=55 v=95
151320 Off ch=1 n=55 v=80
151320 On ch=1 n=55 v=95
151440 Off ch=1 n=55 v=80
151440 On ch=1 n=45 v=95
151560 Off ch=1 n=45 v=80
151560 On ch=1 n=47 v=76
151680 Off ch=1 n=47 v=80
151680 On ch=1 n=50 v=95
151920 Off ch=1 n=50 v=80
151920 On ch=1 n=50 v=95
152160 Off ch=1 n=50 v=80
152160 On ch=1 n=62 v=95
152160 On ch=1 n=57 v=95
152520 Off ch=1 n=62 v=80
152520 Off ch=1 n=57 v=80
152520 On ch=1 n=62 v=95
152520 On ch=1 n=57 v=95
152640 Off ch=1 n=62 v=80
152640 Off ch=1 n=57 v=80
152640 On ch=1 n=48 v=95
152880 Off ch=1 n=48 v=80
152880 On ch=1 n=48 v=95
153120 Off ch=1 n=48 v=80
153120 On ch=1 n=62 v=95
153120 On ch=1 n=55 v=95
153480 Off ch=1 n=62 v=80
153480 Off ch=1 n=55 v=80
153480 On ch=1 n=62 v=95
153480 On ch=1 n=55 v=95
153480 On ch=1 n=52 v=95
153600 Off ch=1 n=62 v=80
153600 Off ch=1 n=55 v=80
153600 Off ch=1 n=52 v=80
153600 On ch=1 n=43 v=95
153840 Off ch=1 n=43 v=80
153840 On ch=1 n=43 v=95
154080 Off ch=1 n=43 v=80
154080 On ch=1 n=62 v=95
154080 On ch=1 n=55 v=95
154080 On ch=1 n=50 v=95
154560 Off ch=1 n=62 v=80
154560 Off ch=1 n=55 v=80
154560 Off ch=1 n=50 v=80
154560 On ch=1 n=43 v=95
154800 Off ch=1 n=43 v=80
154800 On ch=1 n=43 v=95
155040 Off ch=1 n=43 v=80
155040 On ch=1 n=55 v=95
155040 On ch=1 n=50 v=95
155520 Off ch=1 n=55 v=80
155520 Off ch=1 n=50 v=80
155520 On ch=1 n=50 v=95
155760 Off ch=1 n=50 v=80
155760 On ch=1 n=50 v=95
156000 Off ch=1 n=50 v=80
156000 On ch=1 n=62 v=95
156000 On ch=1 n=57 v=95
156360 Off ch=1 n=62 v=80
156360 Off ch=1 n=57 v=80
156360 On ch=1 n=62 v=95
156360 On ch=1 n=57 v=95
156480 Off ch=1 n=62 v=80
156480 Off ch=1 n=57 v=80
156480 On ch=1 n=48 v=95
156720 Off ch=1 n=48 v=80
156720 On ch=1 n=48 v=95
156960 Off ch=1 n=48 v=80
156960 On ch=1 n=62 v=95
156960 On ch=1 n=55 v=95
157320 Off ch=1 n=62 v=80
157320 Off ch=1 n=55 v=80
157320 On ch=1 n=62 v=95
157320 On ch=1 n=55 v=95
157320 On ch=1 n=50 v=95
157440 Off ch=1 n=62 v=80
157440 Off ch=1 n=55 v=80
157440 Off ch=1 n=50 v=80
157440 On ch=1 n=43 v=95
157680 Off ch=1 n=43 v=80
157680 On ch=1 n=43 v=95
157920 Off ch=1 n=43 v=80
157920 On ch=1 n=62 v=95
157920 On ch=1 n=55 v=95
157920 On ch=1 n=50 v=95
158400 Off ch=1 n=62 v=80
158400 Off ch=1 n=55 v=80
158400 Off ch=1 n=50 v=80
158400 On ch=1 n=45 v=95
158520 Off ch=1 n=45 v=80
158520 On ch=1 n=47 v=76
158640 Off ch=1 n=47 v=80
158640 On ch=1 n=50 v=95
158760 Off ch=1 n=50 v=80
158760 On ch=1 n=55 v=95
159000 Off ch=1 n=55 v=80
159000 On ch=1 n=55 v=95
159120 Off ch=1 n=55 v=80
159120 On ch=1 n=45 v=95
159240 Off ch=1 n=45 v=80
159240 On ch=1 n=47 v=76
159360 Off ch=1 n=47 v=80
159360 On ch=1 n=50 v=95
159600 Off ch=1 n=50 v=80
159600 On ch=1 n=50 v=95
159840 Off ch=1 n=50 v=80
159840 On ch=1 n=62 v=95
159840 On ch=1 n=57 v=95
160200 Off ch=1 n=62 v=80
160200 Off ch=1 n=57 v=80
160200 On ch=1 n=62 v=95
160200 On ch=1 n=57 v=95
160320 Off ch=1 n=62 v=80
160320 Off ch=1 n=57 v=80
160320 On ch=1 n=48 v=95
160560 Off ch=1 n=48 v=80
160560 On ch=1 n=55 v=95
160800 Off ch=1 n=55 v=80
160800 On ch=1 n=62 v=95
160800 On ch=1 n=55 v=95
161160 Off ch=1 n=62 v=80
161160 Off ch=1 n=55 v=80
161160 On ch=1 n=62 v=95
161160 On ch=1 n=55 v=95
161160 On ch=1 n=52 v=95
161280 Off ch=1 n=62 v=80
161280 Off ch=1 n=55 v=80
161280 Off ch=1 n=52 v=80
161280 On ch=1 n=43 v=95
161520 Off ch=1 n=43 v=80
161520 On ch=1 n=43 v=95
161760 Off ch=1 n=43 v=80
161760 On ch=1 n=55 v=95
161760 On ch=1 n=50 v=95
162000 Off ch=1 n=55 v=80
162000 Off ch=1 n=50 v=80
162000 On ch=1 n=55 v=95
162000 On ch=1 n=50 v=95
162000 On ch=1 n=43 v=95
162120 Off ch=1 n=55 v=80
162120 Off ch=1 n=50 v=80
162120 Off ch=1 n=43 v=80
162120 On ch=1 n=55 v=95
162120 On ch=1 n=50 v=95
162120 On ch=1 n=43 v=95
162240 Off ch=1 n=55 v=80
162240 Off ch=1 n=50 v=80
162240 Off ch=1 n=43 v=80
162240 On ch=1 n=45 v=95
162360 Off ch=1 n=45 v=80
162360 On ch=1 n=47 v=76
162480 Off ch=1 n=47 v=80
162480 On ch=1 n=50 v=95
162600 Off ch=1 n=50 v=80
162600 On ch=1 n=55 v=95
162720 Off ch=1 n=55 v=80
162720 On ch=1 n=52 v=95
162840 Off ch=1 n=52 v=80
162840 On ch=1 n=50 v=76
162960 Off ch=1 n=50 v=80
162960 On ch=1 n=45 v=95
163080 Off ch=1 n=45 v=80
163080 On ch=1 n=47 v=76
163200 Off ch=1 n=47 v=80
163200 On ch=1 n=50 v=95
163440 Off ch=1 n=50 v=80
163440 On ch=1 n=57 v=95
163440 On ch=1 n=50 v=95
163680 Off ch=1 n=57 v=80
163680 Off ch=1 n=50 v=80
163680 On ch=1 n=59 v=95
163680 On ch=1 n=50 v=95
163800 Off ch=1 n=59 v=80
163800 Off ch=1 n=50 v=80
163800 On ch=1 n=57 v=95
163800 On ch=1 n=50 v=95
164160 Off ch=1 n=57 v=80
164160 Off ch=1 n=50 v=80
164160 On ch=1 n=48 v=95
164400 Off ch=1 n=48 v=80
164400 On ch=1 n=55 v=95
164400 On ch=1 n=48 v=95
164640 Off ch=1 n=55 v=80
164640 Off ch=1 n=48 v=80
164640 On ch=1 n=57 v=95
164640 On ch=1 n=48 v=95
164760 Off ch=1 n=57 v=80
164760 Off ch=1 n=48 v=80
164760 On ch=1 n=55 v=95
164760 On ch=1 n=48 v=95
165000 Off ch=1 n=55 v=80
165000 Off ch=1 n=48 v=80
165000 On ch=1 n=55 v=95
165000 On ch=1 n=48 v=95
165120 Off ch=1 n=55 v=80
165120 Off ch=1 n=48 v=80
165120 On ch=1 n=50 v=95
165120 On ch=1 n=43 v=95
165360 Off ch=1 n=50 v=80
165360 Off ch=1 n=43 v=80
165360 On ch=1 n=50 v=95
165360 On ch=1 n=43 v=95
165600 Off ch=1 n=50 v=80
165600 Off ch=1 n=43 v=80
165600 On ch=1 n=44 v=95
165600 On ch=1 n=39 v=95
165620 Off ch=1 n=44 v=80
165620 Off ch=1 n=39 v=80
165720 On ch=1 n=50 v=95
165840 Off ch=1 n=50 v=80
165840 On ch=1 n=52 v=95
165840 On ch=1 n=43 v=95
166080 Off ch=1 n=52 v=80
166080 Off ch=1 n=43 v=80
166080 On ch=1 n=45 v=95
166200 Off ch=1 n=45 v=80
166200 On ch=1 n=47 v=76
166320 Off ch=1 n=47 v=80
166320 On ch=1 n=50 v=95
166440 Off ch=1 n=50 v=80
166440 On ch=1 n=52 v=95
166560 Off ch=1 n=52 v=80
166560 On ch=1 n=50 v=76
166680 Off ch=1 n=50 v=80
166680 On ch=2 n=48 v=95
166680 Pb ch=2 v=8192
166760 Pb ch=2 v=8320
166808 Pb ch=2 v=8448
166856 Pb ch=2 v=8576
167040 Off ch=2 n=48 v=80
167040 Pb ch=2 v=8192
167040 On ch=1 n=50 v=95
167280 Off ch=1 n=50 v=80
167280 On ch=1 n=57 v=95
167280 On ch=1 n=50 v=95
167520 Off ch=1 n=57 v=80
167520 Off ch=1 n=50 v=80
167520 On ch=1 n=59 v=95
167520 On ch=1 n=50 v=95
167640 Off ch=1 n=59 v=80
167640 Off ch=1 n=50 v=80
167640 On ch=1 n=57 v=95
167640 On ch=1 n=50 v=95
168000 Off ch=1 n=57 v=80
168000 Off ch=1 n=50 v=80
168000 On ch=1 n=48 v=95
168240 Off ch=1 n=48 v=80
168240 On ch=1 n=55 v=95
168240 On ch=1 n=48 v=95
168480 Off ch=1 n=55 v=80
168480 Off ch=1 n=48 v=80
168480 On ch=1 n=57 v=95
168480 On ch=1 n=48 v=95
168600 Off ch=1 n=57 v=80
168600 Off ch=1 n=48 v=80
168600 On ch=1 n=55 v=95
168600 On ch=1 n=48 v=95
168840 Off ch=1 n=55 v=80
168840 Off ch=1 n=48 v=80
168840 On ch=1 n=55 v=95
168840 On ch=1 n=48 v=95
168960 Off ch=1 n=55 v=80
168960 Off ch=1 n=48 v=80
168960 On ch=1 n=50 v=95
169080 Off ch=1 n=50 v=80
169080 On ch=1 n=52 v=76
169200 Off ch=1 n=52 v=80
169200 On ch=1 n=55 v=95
169320 Off ch=1 n=55 v=80
169320 On ch=1 n=50 v=95
169440 Off ch=1 n=50 v=80
169440 On ch=1 n=52 v=76
169560 Off ch=1 n=52 v=80
169560 On ch=1 n=55 v=95
169680 Off ch=1 n=55 v=80
169680 On ch=1 n=50 v=95
169800 Off ch=1 n=50 v=80
169800 On ch=1 n=52 v=76
169920 Off ch=1 n=52 v=80
169920 On ch=1 n=55 v=95
170040 Off ch=1 n=55 v=80
170040 On ch=1 n=50 v=95
170160 Off ch=1 n=50 v=80
170160 On ch=1 n=52 v=76
170280 Off ch=1 n=52 v=80
170280 On ch=1 n=55 v=95
170400 Off ch=1 n=55 v=80
170400 On ch=1 n=50 v=95
170520 Off ch=1 n=50 v=80
170520 On ch=1 n=52 v=76
170640 Off ch=1 n=52 v=80
170640 On ch=1 n=55 v=95
170760 Off ch=1 n=55 v=80
170760 On ch=2 n=48 v=95
170868 Pb ch=2 v=8832
170880 Off ch=2 n=48 v=80
170880 Pb ch=2 v=8192
170880 On ch=1 n=50 v=76
171120 Off ch=1 n=50 v=80
171120 On ch=1 n=57 v=95
171120 On ch=1 n=50 v=95
171360 Off ch=1 n=57 v=80
171360 Off ch=1 n=50 v=80
171360 On ch=1 n=59 v=95
171360 On ch=1 n=50 v=95
171480 Off ch=1 n=59 v=80
171480 Off ch=1 n=50 v=80
171480 On ch=1 n=57 v=95
171480 On ch=1 n=50 v=95
171840 Off ch=1 n=57 v=80
171840 Off ch=1 n=50 v=80
171840 On ch=1 n=48 v=95
172080 Off ch=1 n=48 v=80
172080 On ch=1 n=55 v=95
172080 On ch=1 n=48 v=95
172320 Off ch=1 n=55 v=80
172320 Off ch=1 n=48 v=80
172320 On ch=1 n=57 v=95
172320 On ch=1 n=48 v=95
172440 Off ch=1 n=57 v=80
172440 Off ch=1 n=48 v=80
172440 On ch=1 n=55 v=95
172440 On ch=1 n=48 v=95
172680 Off ch=1 n=55 v=80
172680 Off ch=1 n=48 v=80
172680 On ch=1 n=55 v=95
172680 On ch=1 n=48 v=95
172800 Off ch=1 n=55 v=80
172800 Off ch=1 n=48 v=80
172800 On ch=1 n=50 v=95
172800 On ch=1 n=43 v=95
173040 Off ch=1 n=50 v=80
173040 Off ch=1 n=43 v=80
173040 On ch=1 n=50 v=95
173040 On ch=1 n=43 v=95
173280 Off ch=1 n=50 v=80
173280 Off ch=1 n=43 v=80
173280 On ch=1 n=44 v=95
173280 On ch=1 n=39 v=95
173300 Off ch=1 n=44 v=80
173300 Off ch=1 n=39 v=80
173400 On ch=1 n=50 v=95
173520 Off ch=1 n=50 v=80
173520 On ch=1 n=52 v=95
173520 On ch=1 n=43 v=95
173760 Off ch=1 n=52 v=80
173760 Off ch=1 n=43 v=80
173760 On ch=1 n=45 v=95
173880 Off ch=1 n=45 v=80
173880 On ch=1 n=47 v=76
174000 Off ch=1 n=47 v=80
174000 On ch=1 n=50 v=95
174120 Off ch=1 n=50 v=80
174120 On ch=1 n=52 v=95
174240 Off ch=1 n=52 v=80
174240 On ch=1 n=50 v=76
174360 Off ch=1 n=50 v=80
174360 On ch=1 n=48 v=95
174720 Off ch=1 n=48 v=80
174720 On ch=1 n=50 v=95
174960 Off ch=1 n=50 v=80
174960 On ch=1 n=57 v=95
174960 On ch=1 n=50 v=95
175200 Off ch=1 n=57 v=80
175200 Off ch=1 n=50 v=80
175200 On ch=1 n=59 v=95
175200 On ch=1 n=50 v=95
175320 Off ch=1 n=59 v=80
175320 Off ch=1 n=50 v=80
175320 On ch=1 n=57 v=95
175320 On ch=1 n=50 v=95
175680 Off ch=1 n=57 v=80
175680 Off ch=1 n=50 v=80
175680 On ch=1 n=48 v=95
175920 Off ch=1 n=48 v=80
175920 On ch=1 n=55 v=95
175920 On ch=1 n=48 v=95
176160 Off ch=1 n=55 v=80
176160 Off ch=1 n=48 v=80
176160 On ch=1 n=57 v=95
176160 On ch=1 n=48 v=95
176280 Off ch=1 n=57 v=80
176280 Off ch=1 n=48 v=80
176280 On ch=1 n=55 v=95
176280 On ch=1 n=48 v=95
176520 Off ch=1 n=55 v=80
176520 Off ch=1 n=48 v=80
176520 On ch=1 n=55 v=95
176520 On ch=1 n=48 v=95
176640 Off ch=1 n=55 v=80
176640 Off ch=1 n=48 v=80
176640 On ch=1 n=43 v=95
176880 Off ch=1 n=43 v=80
176880 On ch=1 n=43 v=95
177120 Off ch=1 n=43 v=80
177120 On ch=1 n=55 v=95
177120 On ch=1 n=50 v=95
177360 Off ch=1 n=55 v=80
177360 Off ch=1 n=50 v=80
177360 On ch=1 n=55 v=95
177360 On ch=1 n=50 v=95
177360 On ch=1 n=43 v=95
177480 Off ch=1 n=55 v=80
177480 Off ch=1 n=50 v=80
177480 Off ch=1 n=43 v=80
177480 On ch=1 n=55 v=95
177480 On ch=1 n=50 v=95
177480 On ch=1 n=43 v=95
177600 Off ch=1 n=55 v=80
177600 Off ch=1 n=50 v=80
177600 Off ch=1 n=43 v=80
177600 On ch=1 n=65 v=95
177600 On ch=1 n=60 v=95
177600 On ch=1 n=57 v=95
177600 On ch=1 n=53 v=95
177600 On ch=1 n=48 v=95
178080 Off ch=1 n=65 v=80
178080 Off ch=1 n=60 v=80
178080 Off ch=1 n=57 v=80
178080 Off ch=1 n=53 v=80
178080 Off ch=1 n=48 v=80
178080 On ch=1 n=64 v=95
178080 On ch=1 n=60 v=95
178080 On ch=1 n=55 v=95
178080 On ch=1 n=52 v=95
178080 On ch=1 n=48 v=95
178560 Off ch=1 n=64 v=80
178560 Off ch=1 n=60 v=80
178560 Off ch=1 n=55 v=80
178560 Off ch=1 n=52 v=80
178560 Off ch=1 n=48 v=80
178560 On ch=1 n=50 v=95
178800 Off ch=1 n=50 v=80
178800 On ch=1 n=57 v=95
178800 On ch=1 n=50 v=95
179040 Off ch=1 n=57 v=80
179040 Off ch=1 n=50 v=80
179040 On ch=1 n=59 v=95
179040 On ch=1 n=50 v=95
179160 Off ch=1 n=59 v=80
179160 Off ch=1 n=50 v=80
179160 On ch=1 n=57 v=95
179160 On ch=1 n=50 v=95
179520 Off ch=1 n=57 v=80
179520 Off ch=1 n=50 v=80
179520 On ch=1 n=48 v=95
179760 Off ch=1 n=48 v=80
179760 On ch=1 n=55 v=95
179760 On ch=1 n=48 v=95
180000 Off ch=1 n=55 v=80
180000 Off ch=1 n=48 v=80
180000 On ch=1 n=57 v=95
180000 On ch=1 n=48 v=95
180120 Off ch=1 n=57 v=80
180120 Off ch=1 n=48 v=80
180120 On ch=1 n=55 v=95
180120 On ch=1 n=48 v=95
180360 Off ch=1 n=55 v=80
180360 Off ch=1 n=48 v=80
180360 On ch=1 n=55 v=95
180360 On ch=1 n=48 v=95
180480 Off ch=1 n=55 v=80
180480 Off ch=1 n=48 v=80
180480 On ch=1 n=50 v=95
180480 On ch=1 n=43 v=95
180720 Off ch=1 n=50 v=80
180720 Off ch=1 n=43 v=80
180720 On ch=1 n=50 v=95
180720 On ch=1 n=43 v=95
180960 Off ch=1 n=50 v=80
180960 Off ch=1 n=43 v=80
180960 On ch=1 n=44 v=95
180960 On ch=1 n=39 v=95
180980 Off ch=1 n=44 v=80
180980 Off ch=1 n=39 v=80
181080 On ch=1 n=50 v=95
181200 Off ch=1 n=50 v=80
181200 On ch=1 n=52 v=95
181200 On ch=1 n=43 v=95
181440 Off ch=1 n=52 v=80
181440 Off ch=1 n=43 v=80
181440 On ch=1 n=45 v=95
181560 Off ch=1 n=45 v=80
181560 On ch=1 n=47 v=76
181680 Off ch=1 n=47 v=80
181680 On ch=1 n=50 v=95
181800 Off ch=1 n=50 v=80
181800 On ch=1 n=52 v=95
181920 Off ch=1 n=52 v=80
181920 On ch=1 n=50 v=76
182040 Off ch=1 n=50 v=80
182040 On ch=1 n=48 v=95
182400 Off ch=1 n=48 v=80
182400 On ch=1 n=50 v=95
182640 Off ch=1 n=50 v=80
182640 On ch=1 n=57 v=95
182640 On ch=1 n=50 v=95
182880 Off ch=1 n=57 v=80
182880 Off ch=1 n=50 v=80
182880 On ch=1 n=59 v=95
182880 On ch=1 n=50 v=95
183000 Off ch=1 n=59 v=80
183000 Off ch=1 n=50 v=80
183000 On ch=1 n=57 v=95
183000 On ch=1 n=50 v=95
183360 Off ch=1 n=57 v=80
183360 Off ch=1 n=50 v=80
183360 On ch=1 n=48 v=95
183600 Off ch=1 n=48 v=80
183600 On ch=1 n=55 v=95
183600 On ch=1 n=48 v=95
183840 Off ch=1 n=55 v=80
183840 Off ch=1 n=48 v=80
183840 On ch=1 n=57 v=95
183840 On ch=1 n=48 v=95
183960 Off ch=1 n=57 v=80
183960 Off ch=1 n=48 v=80
183960 On ch=1 n=55 v=95
183960 On ch=1 n=48 v=95
184200 Off ch=1 n=55 v=80
184200 Off ch=1 n=48 v=80
184200 On ch=1 n=55 v=95
184200 On ch=1 n=48 v=95
184320 Off ch=1 n=55 v=80
184320 Off ch=1 n=48 v=80
184320 On ch=1 n=50 v=95
184440 Off ch=1 n=50 v=80
184440 On ch=1 n=52 v=76
184560 Off ch=1 n=52 v=80
184560 On ch=1 n=55 v=95
184680 Off ch=1 n=55 v=80
184680 On ch=1 n=50 v=95
184800 Off ch=1 n=50 v=80
184800 On ch=1 n=52 v=76
184920 Off ch=1 n=52 v=80
184920 On ch=1 n=55 v=95
185040 Off ch=1 n=55 v=80
185040 On ch=1 n=50 v=95
185160 Off ch=1 n=50 v=80
185160 On ch=1 n=52 v=76
185280 Off ch=1 n=52 v=80
185280 On ch=1 n=55 v=95
185400 Off ch=1 n=55 v=80
185400 On ch=1 n=50 v=95
185520 Off ch=1 n=50 v=80
185520 On ch=1 n=52 v=76
185640 Off ch=1 n=52 v=80
185640 On ch=1 n=55 v=95
185760 Off ch=1 n=55 v=80
185760 On ch=1 n=50 v=95
185880 Off ch=1 n=50 v=80
185880 On ch=1 n=52 v=76
186000 Off ch=1 n=52 v=80
186000 On ch=1 n=55 v=95
186120 Off ch=1 n=55 v=80
186120 On ch=2 n=48 v=95
186228 Pb ch=2 v=8832
186240 Off ch=2 n=48 v=80
186240 Pb ch=2 v=8192
186240 On ch=1 n=50 v=76
186480 Off ch=1 n=50 v=80
186480 On ch=1 n=57 v=95
186480 On ch=1 n=50 v=95
186720 Off ch=1 n=57 v=80
186720 Off ch=1 n=50 v=80
186720 On ch=1 n=59 v=95
186720 On ch=1 n=50 v=95
186840 Off ch=1 n=59 v=80
186840 Off ch=1 n=50 v=80
186840 On ch=1 n=57 v=95
186840 On ch=1 n=50 v=95
187200 Off ch=1 n=57 v=80
187200 Off ch=1 n=50 v=80
187200 On ch=1 n=48 v=95
187440 Off ch=1 n=48 v=80
187440 On ch=1 n=55 v=95
187440 On ch=1 n=48 v=95
187680 Off ch=1 n=55 v=80
187680 Off ch=1 n=48 v=80
187680 On ch=1 n=57 v=95
187680 On ch=1 n=48 v=95
187800 Off ch=1 n=57 v=80
187800 Off ch=1 n=48 v=80
187800 On ch=1 n=55 v=95
187800 On ch=1 n=48 v=95
188040 Off ch=1 n=55 v=80
188040 Off ch=1 n=48 v=80
188040 On ch=1 n=55 v=95
188040 On ch=1 n=48 v=95
188160 Off ch=1 n=55 v=80
188160 Off ch=1 n=48 v=80
188160 On ch=1 n=50 v=95
188160 On ch=1 n=43 v=95
188400 Off ch=1 n=50 v=80
188400 Off ch=1 n=43 v=80
188400 On ch=1 n=50 v=95
188400 On ch=1 n=43 v=95
188640 Off ch=1 n=50 v=80
188640 Off ch=1 n=43 v=80
188640 On ch=1 n=44 v=95
188640 On ch=1 n=39 v=95
188660 Off ch=1 n=44 v=80
188660 Off ch=1 n=39 v=80
188760 On ch=1 n=50 v=95
188880 Off ch=1 n=50 v=80
188880 On ch=1 n=52 v=95
188880 On ch=1 n=43 v=95
189120 Off ch=1 n=52 v=80
189120 Off ch=1 n=43 v=80
189120 On ch=1 n=45 v=95
189240 Off ch=1 n=45 v=80
189240 On ch=1 n=47 v=76
189360 Off ch=1 n=47 v=80
189360 On ch=1 n=50 v=95
189480 Off ch=1 n=50 v=80
189480 On ch=1 n=52 v=95
189600 Off ch=1 n=52 v=80
189600 On ch=1 n=50 v=76
189720 Off ch=1 n=50 v=80
189720 On ch=2 n=48 v=95
189720 Pb ch=2 v=8192
189800 Pb ch=2 v=8320
189848 Pb ch=2 v=8448
189896 Pb ch=2 v=8576
190080 Off ch=2 n=48 v=80
190080 Pb ch=2 v=8192
190080 On ch=1 n=50 v=95
190320 Off ch=1 n=50 v=80
190320 On ch=1 n=57 v=95
190320 On ch=1 n=50 v=95
190560 Off ch=1 n=57 v=80
190560 Off ch=1 n=50 v=80
190560 On ch=1 n=59 v=95
190560 On ch=1 n=50 v=95
190680 Off ch=1 n=59 v=80
190680 Off ch=1 n=50 v=80
190680 On ch=1 n=57 v=95
190680 On ch=1 n=50 v=95
191040 Off ch=1 n=57 v=80
191040 Off ch=1 n=50 v=80
191040 On ch=1 n=48 v=95
191280 Off ch=1 n=48 v=80
191280 On ch=1 n=55 v=95
191280 On ch=1 n=48 v=95
191520 Off ch=1 n=55 v=80
191520 Off ch=1 n=48 v=80
191520 On ch=1 n=57 v=95
191520 On ch=1 n=48 v=95
191640 Off ch=1 n=57 v=80
191640 Off ch=1 n=48 v=80
191640 On ch=1 n=55 v=95
191640 On ch=1 n=48 v=95
191880 Off ch=1 n=55 v=80
191880 Off ch=1 n=48 v=80
191880 On ch=1 n=55 v=95
191880 On ch=1 n=48 v=95
192000 Off ch=1 n=55 v=80
192000 Off ch=1 n=48 v=80
192000 On ch=1 n=50 v=95
192000 On ch=1 n=43 v=95
192240 Off ch=1 n=50 v=80
192240 Off ch=1 n=43 v=80
192240 On ch=1 n=50 v=95
192240 On ch=1 n=43 v=95
192480 Off ch=1 n=50 v=80
192480 Off ch=1 n=43 v=80
192480 On ch=1 n=44 v=95
192480 On ch=1 n=39 v=95
192500 Off ch=1 n=44 v=80
192500 Off ch=1 n=39 v=80
192600 On ch=1 n=44 v=95
192600 On ch=1 n=39 v=95
192620 Off ch=1 n=44 v=80
192620 Off ch=1 n=39 v=80
192720 On ch=1 n=52 v=95
192720 On ch=1 n=43 v=95
192960 Off ch=1 n=52 v=80
192960 Off ch=1 n=43 v=80
192960 On ch=1 n=44 v=95
192960 On ch=1 n=39 v=95
192980 Off ch=1 n=44 v=80
192980 Off ch=1 n=39 v=80
193080 On ch=1 n=50 v=95
193080 On ch=1 n=43 v=95
193200 Off ch=1 n=50 v=80
193200 Off ch=1 n=43 v=80
193200 On ch=1 n=50 v=95
193200 On ch=1 n=43 v=95
193320 Off ch=1 n=50 v=80
193320 Off ch=1 n=43 v=80
193320 On ch=1 n=50 v=95
193320 On ch=1 n=43 v=95
193440 Off ch=1 n=50 v=80
193440 Off ch=1 n=43 v=80
193440 On ch=1 n=44 v=95
193440 On ch=1 n=39 v=95
193460 Off ch=1 n=44 v=80
193460 Off ch=1 n=39 v=80
193560 On ch=1 n=44 v=95
193560 On ch=1 n=39 v=95
193580 Off ch=1 n=44 v=80
193580 Off ch=1 n=39 v=80
193800 On ch=1 n=50 v=95
193920 Off ch=1 n=50 v=80
193920 On ch=1 n=57 v=95
193920 On ch=1 n=50 v=95
194160 Off ch=1 n=57 v=80
194160 Off ch=1 n=50 v=80
194160 On ch=1 n=57 v=95
194160 On ch=1 n=50 v=95
194400 Off ch=1 n=57 v=80
194400 Off ch=1 n=50 v=80
194400 On ch=1 n=59 v=95
194400 On ch=1 n=50 v=95
194520 Off ch=1 n=59 v=80
194520 Off ch=1 n=50 v=80
194520 On ch=1 n=57 v=95
194520 On ch=1 n=50 v=95
194760 Off ch=1 n=57 v=80
194760 Off ch=1 n=50 v=80
194760 On ch=1 n=57 v=95
194760 On ch=1 n=50 v=95
194880 Off ch=1 n=57 v=80
194880 Off ch=1 n=50 v=80
194880 On ch=1 n=55 v=95
194880 On ch=1 n=48 v=95
195120 Off ch=1 n=55 v=80
195120 Off ch=1 n=48 v=80
195120 On ch=1 n=55 v=95
195120 On ch=1 n=48 v=95
195360 Off ch=1 n=55 v=80
195360 Off ch=1 n=48 v=80
195360 On ch=1 n=57 v=95
195360 On ch=1 n=48 v=95
195480 Off ch=1 n=57 v=80
195480 Off ch=1 n=48 v=80
195480 On ch=1 n=55 v=95
195480 On ch=1 n=48 v=95
195720 Off ch=1 n=55 v=80
195720 Off ch=1 n=48 v=80
195720 On ch=1 n=55 v=95
195720 On ch=1 n=48 v=95
195840 Off ch=1 n=55 v=80
195840 Off ch=1 n=48 v=80
195840 On ch=1 n=50 v=95
195840 On ch=1 n=43 v=95
196080 Off ch=1 n=50 v=80
196080 Off ch=1 n=43 v=80
196080 On ch=1 n=50 v=95
196080 On ch=1 n=43 v=95
196320 Off ch=1 n=50 v=80
196320 Off ch=1 n=43 v=80
196320 On ch=1 n=44 v=95
196320 On ch=1 n=39 v=95
196340 Off ch=1 n=44 v=80
196340 Off ch=1 n=39 v=80
196560 On ch=1 n=52 v=95
196560 On ch=1 n=43 v=95
196800 Off ch=1 n=52 v=80
196800 Off ch=1 n=43 v=80
196800 On ch=1 n=44 v=95
196800 On ch=1 n=39 v=95
196820 Off ch=1 n=44 v=80
196820 Off ch=1 n=39 v=80
196920 On ch=1 n=50 v=95
196920 On ch=1 n=43 v=95
197040 Off ch=1 n=50 v=80
197040 Off ch=1 n=43 v=80
197040 On ch=1 n=50 v=95
197040 On ch=1 n=43 v=95
197280 Off ch=1 n=50 v=80
197280 Off ch=1 n=43 v=80
197280 On ch=1 n=52 v=95
197280 On ch=1 n=43 v=95
197400 Off ch=1 n=52 v=80
197400 Off ch=1 n=43 v=80
197400 On ch=1 n=50 v=95
197400 On ch=1 n=43 v=95
197760 Off ch=1 n=50 v=80
197760 Off ch=1 n=43 v=80
197760 On ch=1 n=57 v=95
197760 On ch=1 n=50 v=95
198000 Off ch=1 n=57 v=80
198000 Off ch=1 n=50 v=80
198000 On ch=1 n=57 v=95
198000 On ch=1 n=50 v=95
198240 Off ch=1 n=57 v=80
198240 Off ch=1 n=50 v=80
198240 On ch=1 n=59 v=95
198240 On ch=1 n=50 v=95
198360 Off ch=1 n=59 v=80
198360 Off ch=1 n=50 v=80
198360 On ch=1 n=57 v=95
198360 On ch=1 n=50 v=95
198600 Off ch=1 n=57 v=80
198600 Off ch=1 n=50 v=80
198600 On ch=1 n=57 v=95
198600 On ch=1 n=50 v=95
198720 Off ch=1 n=57 v=80
198720 Off ch=1 n=50 v=80
198720 On ch=1 n=55 v=95
198720 On ch=1 n=48 v=95
198960 Off ch=1 n=55 v=80
198960 Off ch=1 n=48 v=80
198960 On ch=1 n=55 v=95
198960 On ch=1 n=48 v=95
199200 Off ch=1 n=55 v=80
199200 Off ch=1 n=48 v=80
199200 On ch=1 n=57 v=95
199200 On ch=1 n=48 v=95
199320 Off ch=1 n=57 v=80
199320 Off ch=1 n=48 v=80
199320 On ch=1 n=55 v=95
199320 On ch=1 n=48 v=95
199560 Off ch=1 n=55 v=80
199560 Off ch=1 n=48 v=80
199560 On ch=1 n=55 v=95
199560 On ch=1 n=48 v=95
199680 Off ch=1 n=55 v=80
199680 Off ch=1 n=48 v=80
199680 On ch=1 n=50 v=95
199680 On ch=1 n=43 v=95
199920 Off ch=1 n=50 v=80
199920 Off ch=1 n=43 v=80
199920 On ch=1 n=50 v=95
199920 On ch=1 n=43 v=95
200160 Off ch=1 n=50 v=80
200160 Off ch=1 n=43 v=80
200160 On ch=1 n=44 v=95
200160 On ch=1 n=39 v=95
200180 Off ch=1 n=44 v=80
200180 Off ch=1 n=39 v=80
200400 On ch=1 n=52 v=95
200400 On ch=1 n=43 v=95
200640 Off ch=1 n=52 v=80
200640 Off ch=1 n=43 v=80
200640 On ch=1 n=44 v=95
200640 On ch=1 n=39 v=95
200660 Off ch=1 n=44 v=80
200660 Off ch=1 n=39 v=80
200760 On ch=1 n=50 v=95
200760 On ch=1 n=43 v=95
200880 Off ch=1 n=50 v=80
200880 Off ch=1 n=43 v=80
200880 On ch=1 n=50 v=95
200880 On ch=1 n=43 v=95
201120 Off ch=1 n=50 v=80
201120 Off ch=1 n=43 v=80
201120 On ch=1 n=52 v=95
201120 On ch=1 n=43 v=95
201240 Off ch=1 n=52 v=80
201240 Off ch=1 n=43 v=80
201240 On ch=1 n=50 v=95
201240 On ch=1 n=43 v=95
201600 Off ch=1 n=50 v=80
201600 Off ch=1 n=43 v=80
201600 On ch=1 n=57 v=95
201600 On ch=1 n=50 v=95
201840 Off ch=1 n=57 v=80
201840 Off ch=1 n=50 v=80
201840 On ch=1 n=57 v=95
201840 On ch=1 n=50 v=95
202080 Off ch=1 n=57 v=80
202080 Off ch=1 n=50 v=80
202080 On ch=1 n=59 v=95
202080 On ch=1 n=50 v=95
202200 Off ch=1 n=59 v=80
202200 Off ch=1 n=50 v=80
202200 On ch=1 n=57 v=95
202200 On ch=1 n=50 v=95
202440 Off ch=1 n=57 v=80
202440 Off ch=1 n=50 v=80
202440 On ch=1 n=57 v=95
202440 On ch=1 n=50 v=95
202560 Off ch=1 n=57 v=80
202560 Off ch=1 n=50 v=80
202560 On ch=1 n=55 v=95
202560 On ch=1 n=48 v=95
202800 Off ch=1 n=55 v=80
202800 Off ch=1 n=48 v=80
202800 On ch=1 n=55 v=95
202800 On ch=1 n=48 v=95
203040 Off ch=1 n=55 v=80
203040 Off ch=1 n=48 v=80
203040 On ch=1 n=57 v=95
203040 On ch=1 n=48 v=95
203160 Off ch=1 n=57 v=80
203160 Off ch=1 n=48 v=80
203160 On ch=1 n=55 v=95
203160 On ch=1 n=48 v=95
203400 Off ch=1 n=55 v=80
203400 Off ch=1 n=48 v=80
203400 On ch=1 n=55 v=95
203400 On ch=1 n=48 v=95
203520 Off ch=1 n=55 v=80
203520 Off ch=1 n=48 v=80
203520 On ch=1 n=50 v=95
203520 On ch=1 n=43 v=95
203760 Off ch=1 n=50 v=80
203760 Off ch=1 n=43 v=80
203760 On ch=1 n=50 v=95
203760 On ch=1 n=43 v=95
204000 Off ch=1 n=50 v=80
204000 Off ch=1 n=43 v=80
204000 On ch=1 n=44 v=95
204000 On ch=1 n=39 v=95
204020 Off ch=1 n=44 v=80
204020 Off ch=1 n=39 v=80
204240 On ch=1 n=52 v=95
204240 On ch=1 n=43 v=95
204480 Off ch=1 n=52 v=80
204480 Off ch=1 n=43 v=80
204480 On ch=1 n=44 v=95
204480 On ch=1 n=39 v=95
204500 Off ch=1 n=44 v=80
204500 Off ch=1 n=39 v=80
204600 On ch=1 n=50 v=95
204600 On ch=1 n=43 v=95
204720 Off ch=1 n=50 v=80
204720 Off ch=1 n=43 v=80
204720 On ch=1 n=50 v=95
204720 On ch=1 n=43 v=95
204960 Off ch=1 n=50 v=80
204960 Off ch=1 n=43 v=80
204960 On ch=1 n=52 v=95
204960 On ch=1 n=43 v=95
205080 Off ch=1 n=52 v=80
205080 Off ch=1 n=43 v=80
205080 On ch=1 n=50 v=95
205080 On ch=1 n=43 v=95
205440 Off ch=1 n=50 v=80
205440 Off ch=1 n=43 v=80
205440 On ch=1 n=57 v=95
205440 On ch=1 n=50 v=95
205680 Off ch=1 n=57 v=80
205680 Off ch=1 n=50 v=80
205680 On ch=1 n=57 v=95
205680 On ch=1 n=50 v=95
205920 Off ch=1 n=57 v=80
205920 Off ch=1 n=50 v=80
205920 On ch=1 n=59 v=95
205920 On ch=1 n=50 v=95
206040 Off ch=1 n=59 v=80
206040 Off ch=1 n=50 v=80
206040 On ch=1 n=57 v=95
206040 On ch=1 n=50 v=95
206280 Off ch=1 n=57 v=80
206280 Off ch=1 n=50 v=80
206280 On ch=1 n=57 v=95
206280 On ch=1 n=50 v=95
206400 Off ch=1 n=57 v=80
206400 Off ch=1 n=50 v=80
206400 On ch=1 n=55 v=95
206400 On ch=1 n=48 v=95
206640 Off ch=1 n=55 v=80
206640 Off ch=1 n=48 v=80
206640 On ch=1 n=55 v=95
206640 On ch=1 n=48 v=95
206880 Off ch=1 n=55 v=80
206880 Off ch=1 n=48 v=80
206880 On ch=1 n=57 v=95
206880 On ch=1 n=48 v=95
207000 Off ch=1 n=57 v=80
207000 Off ch=1 n=48 v=80
207000 On ch=1 n=55 v=95
207000 On ch=1 n=48 v=95
207240 Off ch=1 n=55 v=80
207240 Off ch=1 n=48 v=80
207240 On ch=1 n=55 v=95
207240 On ch=1 n=48 v=95
207360 Off ch=1 n=55 v=80
207360 Off ch=1 n=48 v=80
207360 On ch=1 n=50 v=95
207360 On ch=1 n=43 v=95
207600 Off ch=1 n=50 v=80
207600 Off ch=1 n=43 v=80
207600 On ch=1 n=50 v=95
207600 On ch=1 n=43 v=95
207840 Off ch=1 n=50 v=80
207840 Off ch=1 n=43 v=80
207840 On ch=1 n=52 v=95
207840 On ch=1 n=43 v=95
208080 Off ch=1 n=52 v=80
208080 Off ch=1 n=43 v=80
208080 On ch=1 n=52 v=95
208080 On ch=1 n=43 v=95
208320 Off ch=1 n=52 v=80
208320 Off ch=1 n=43 v=80
208320 On ch=1 n=45 v=95
208440 Off ch=1 n=45 v=80
208440 On ch=1 n=47 v=76
208560 Off ch=1 n=47 v=80
208560 On ch=1 n=50 v=95
208680 Off ch=1 n=50 v=80
208680 On ch=1 n=52 v=76
208800 Off ch=1 n=52 v=80
208800 On ch=1 n=50 v=95
209040 Off ch=1 n=50 v=80
209040 On ch=2 n=48 v=95
209040 Pb ch=2 v=8192
209088 Pb ch=2 v=8320
209120 Pb ch=2 v=8448
209152 Pb ch=2 v=8576
209280 Off ch=2 n=48 v=80
209280 Pb ch=2 v=8192
209280 On ch=1 n=57 v=95
209280 On ch=1 n=50 v=95
209520 Off ch=1 n=57 v=80
209520 Off ch=1 n=50 v=80
209520 On ch=1 n=57 v=95
209520 On ch=1 n=50 v=95
209760 Off ch=1 n=57 v=80
209760 Off ch=1 n=50 v=80
209760 On ch=1 n=59 v=95
209760 On ch=1 n=50 v=95
209880 Off ch=1 n=59 v=80
209880 Off ch=1 n=50 v=80
209880 On ch=1 n=57 v=95
209880 On ch=1 n=50 v=95
210120 Off ch=1 n=57 v=80
210120 Off ch=1 n=50 v=80
210120 On ch=1 n=57 v=95
210120 On ch=1 n=50 v=95
210240 Off ch=1 n=57 v=80
210240 Off ch=1 n=50 v=80
210240 On ch=1 n=55 v=95
210240 On ch=1 n=48 v=95
210480 Off ch=1 n=55 v=80
210480 Off ch=1 n=48 v=80
210480 On ch=1 n=55 v=95
210480 On ch=1 n=48 v=95
210720 Off ch=1 n=55 v=80
210720 Off ch=1 n=48 v=80
210720 On ch=1 n=57 v=95
210720 On ch=1 n=48 v=95
210840 Off ch=1 n=57 v=80
210840 Off ch=1 n=48 v=80
210840 On ch=1 n=55 v=95
210840 On ch=1 n=48 v=95
211080 Off ch=1 n=55 v=80
211080 Off ch=1 n=48 v=80
211080 On ch=1 n=55 v=95
211080 On ch=1 n=48 v=95
211200 Off ch=1 n=55 v=80
211200 Off ch=1 n=48 v=80
211200 On ch=1 n=50 v=95
211200 On ch=1 n=43 v=95
211440 Off ch=1 n=50 v=80
211440 Off ch=1 n=43 v=80
211440 On ch=1 n=50 v=95
211440 On ch=1 n=43 v=95
211680 Off ch=1 n=50 v=80
211680 Off ch=1 n=43 v=80
211680 On ch=1 n=52 v=95
211680 On ch=1 n=43 v=95
211920 Off ch=1 n=52 v=80
211920 Off ch=1 n=43 v=80
211920 On ch=1 n=52 v=95
211920 On ch=1 n=43 v=95
212160 Off ch=1 n=52 v=80
212160 Off ch=1 n=43 v=80
212160 On ch=1 n=45 v=95
212280 Off ch=1 n=45 v=80
212280 On ch=1 n=47 v=76
212400 Off ch=1 n=47 v=80
212400 On ch=1 n=50 v=95
212520 Off ch=1 n=50 v=80
212520 On ch=1 n=52 v=76
212640 Off ch=1 n=52 v=80
212640 On ch=1 n=50 v=95
212880 Off ch=1 n=50 v=80
212880 On ch=2 n=48 v=95
212880 Pb ch=2 v=8192
212928 Pb ch=2 v=8320
212960 Pb ch=2 v=8448
212992 Pb ch=2 v=8576
213120 Off ch=2 n=48 v=80
213120 Pb ch=2 v=8192
213120 On ch=1 n=57 v=95
213120 On ch=1 n=50 v=95
213360 Off ch=1 n=57 v=80
213360 Off ch=1 n=50 v=80
213360 On ch=1 n=57 v=95
213360 On ch=1 n=50 v=95
213600 Off ch=1 n=57 v=80
213600 Off ch=1 n=50 v=80
213600 On ch=1 n=59 v=95
213600 On ch=1 n=50 v=95
213720 Off ch=1 n=59 v=80
213720 Off ch=1 n=50 v=80
213720 On ch=1 n=57 v=95
213720 On ch=1 n=50 v=95
213960 Off ch=1 n=57 v=80
213960 Off ch=1 n=50 v=80
213960 On ch=1 n=57 v=95
213960 On ch=1 n=50 v=95
214080 Off ch=1 n=57 v=80
214080 Off ch=1 n=50 v=80
214080 On ch=1 n=55 v=95
214080 On ch=1 n=48 v=95
214320 Off ch=1 n=55 v=80
214320 Off ch=1 n=48 v=80
214320 On ch=1 n=55 v=95
214320 On ch=1 n=48 v=95
214560 Off ch=1 n=55 v=80
214560 Off ch=1 n=48 v=80
214560 On ch=1 n=57 v=95
214560 On ch=1 n=48 v=95
214680 Off ch=1 n=57 v=80
214680 Off ch=1 n=48 v=80
214680 On ch=1 n=55 v=95
214680 On ch=1 n=48 v=95
214920 Off ch=1 n=55 v=80
214920 Off ch=1 n=48 v=80
214920 On ch=1 n=55 v=95
214920 On ch=1 n=48 v=95
215040 Off ch=1 n=55 v=80
215040 Off ch=1 n=48 v=80
215040 On ch=1 n=50 v=95
215040 On ch=1 n=43 v=95
215280 Off ch=1 n=50 v=80
215280 Off ch=1 n=43 v=80
215280 On ch=1 n=50 v=95
215280 On ch=1 n=43 v=95
215520 Off ch=1 n=50 v=80
215520 Off ch=1 n=43 v=80
215520 On ch=1 n=52 v=95
215520 On ch=1 n=43 v=95
215760 Off ch=1 n=52 v=80
215760 Off ch=1 n=43 v=80
215760 On ch=1 n=52 v=95
215760 On ch=1 n=43 v=95
216000 Off ch=1 n=52 v=80
216000 Off ch=1 n=43 v=80
216000 On ch=1 n=45 v=95
216120 Off ch=1 n=45 v=80
216120 On ch=1 n=47 v=76
216240 Off ch=1 n=47 v=80
216240 On ch=1 n=50 v=95
216360 Off ch=1 n=50 v=80
216360 On ch=1 n=52 v=76
216480 Off ch=1 n=52 v=80
216480 On ch=1 n=50 v=95
216720 Off ch=1 n=50 v=80
216720 On ch=2 n=48 v=95
216720 Pb ch=2 v=8192
216768 Pb ch=2 v=8320
216800 Pb ch=2 v=8448
216832 Pb ch=2 v=8576
216960 Off ch=2 n=48 v=80
216960 Pb ch=2 v=8192
216960 On ch=1 n=57 v=95
216960 On ch=1 n=50 v=95
217200 Off ch=1 n=57 v=80
217200 Off ch=1 n=50 v=80
217200 On ch=1 n=57 v=95
217200 On ch=1 n=50 v=95
217440 Off ch=1 n=57 v=80
217440 Off ch=1 n=50 v=80
217440 On ch=1 n=59 v=95
217440 On ch=1 n=50 v=95
217560 Off ch=1 n=59 v=80
217560 Off ch=1 n=50 v=80
217560 On ch=1 n=57 v=95
217560 On ch=1 n=50 v=95
217800 Off ch=1 n=57 v=80
217800 Off ch=1 n=50 v=80
217800 On ch=1 n=57 v=95
217800 On ch=1 n=50 v=95
217920 Off ch=1 n=57 v=80
217920 Off ch=1 n=50 v=80
217920 On ch=1 n=55 v=95
217920 On ch=1 n=48 v=95
218160 Off ch=1 n=55 v=80
218160 Off ch=1 n=48 v=80
218160 On ch=1 n=55 v=95
218160 On ch=1 n=48 v=95
218400 Off ch=1 n=55 v=80
218400 Off ch=1 n=48 v=80
218400 On ch=1 n=57 v=95
218400 On ch=1 n=48 v=95
218520 Off ch=1 n=57 v=80
218520 Off ch=1 n=48 v=80
218520 On ch=1 n=55 v=95
218520 On ch=1 n=48 v=95
218760 Off ch=1 n=55 v=80
218760 Off ch=1 n=48 v=80
218760 On ch=1 n=55 v=95
218760 On ch=1 n=48 v=95
218880 Off ch=1 n=55 v=80
218880 Off ch=1 n=48 v=80
218880 On ch=1 n=50 v=95
218880 On ch=1 n=43 v=95
219120 Off ch=1 n=50 v=80
219120 Off ch=1 n=43 v=80
219120 On ch=1 n=50 v=95
219120 On ch=1 n=43 v=95
219360 Off ch=1 n=50 v=80
219360 Off ch=1 n=43 v=80
219360 On ch=1 n=52 v=95
219360 On ch=1 n=43 v=95
219600 Off ch=1 n=52 v=80
219600 Off ch=1 n=43 v=80
219600 On ch=1 n=52 v=95
219600 On ch=1 n=43 v=95
219840 Off ch=1 n=52 v=80
219840 Off ch=1 n=43 v=80
219840 On ch=1 n=45 v=95
219960 Off ch=1 n=45 v=80
219960 On ch=1 n=47 v=76
220080 Off ch=1 n=47 v=80
220080 On ch=1 n=50 v=95
220200 Off ch=1 n=50 v=80
220200 On ch=1 n=52 v=76
220320 Off ch=1 n=52 v=80
220320 On ch=1 n=50 v=95
220560 Off ch=1 n=50 v=80
220560 On ch=2 n=48 v=95
220560 Pb ch=2 v=8192
220608 Pb ch=2 v=8320
220640 Pb ch=2 v=8448
220672 Pb ch=2 v=8576
220800 Off ch=2 n=48 v=80
220800 Pb ch=2 v=8192
220800 Meta TrkEnd
TrkEnd
MTrk
0 Meta TrkName 'Guitare 2'
0 Par ch=4 c=100 v=0
0 Par ch=4 c=101 v=0
0 Par ch=4 c=6 v=12
0 Pb ch=4 v=8192
0 Par ch=4 c=101 v=0
0 Par ch=4 c=100 v=1
0 Par ch=4 c=6 v=64
0 Par ch=4 c=38 v=0
0 Par ch=4 c=101 v=127
0 Par ch=4 c=100 v=127
0 Par ch=3 c=100 v=0
0 Par ch=3 c=101 v=0
0 Par ch=3 c=6 v=12
0 Pb ch=3 v=8192
0 Par ch=3 c=101 v=0
0 Par ch=3 c=100 v=1
0 Par ch=3 c=6 v=64
0 Par ch=3 c=38 v=0
0 Par ch=3 c=101 v=127
0 Par ch=3 c=100 v=127
0 Par ch=4 c=101 v=0
0 Par ch=4 c=100 v=2
0 Par ch=4 c=6 v=64
0 Par ch=4 c=101 v=127
0 Par ch=4 c=100 v=127
0 Par ch=3 c=101 v=0
0 Par ch=3 c=100 v=2
0 Par ch=3 c=6 v=64
0 Par ch=3 c=101 v=127
0 Par ch=3 c=100 v=127
0 PrCh ch=4 p=29
0 PrCh ch=3 p=29
0 Par ch=4 c=0 v=0
0 Par ch=3 c=0 v=0
0 Par ch=4 c=7 v=88
0 Par ch=3 c=7 v=88
0 Par ch=4 c=10 v=104
0 Par ch=3 c=10 v=104
0 Par ch=4 c=93 v=112
0 Par ch=3 c=93 v=112
0 Par ch=4 c=91 v=96
0 Par ch=3 c=91 v=96
0 Par ch=4 c=92 v=0
0 Par ch=3 c=92 v=0
0 Par ch=4 c=95 v=0
0 Par ch=3 c=95 v=0
5280 On ch=3 n=57 v=95
5400 Off ch=3 n=57 v=80
5400 On ch=4 n=54 v=95
5508 Pb ch=4 v=7552
5520 Off ch=4 n=54 v=80
5520 Pb ch=4 v=8192
5520 On ch=4 n=52 v=76
5628 Pb ch=4 v=8832
5640 Off ch=4 n=52 v=80
5640 Pb ch=4 v=8192
5640 On ch=3 n=54 v=76
5760 Off ch=3 n=54 v=80
5760 On ch=3 n=62 v=95
6600 Off ch=3 n=62 v=80
6600 On ch=3 n=64 v=95
6720 Off ch=3 n=64 v=80
6720 On ch=4 n=62 v=95
6720 Pb ch=4 v=8192
6725 Pb ch=4 v=8192
6729 Pb ch=4 v=8192
6734 Pb ch=4 v=8192
6739 Pb ch=4 v=8192
6743 Pb ch=4 v=8192
6748 Pb ch=4 v=8192
6753 Pb ch=4 v=8192
6758 Pb ch=4 v=8192
6762 Pb ch=4 v=8192
6767 Pb ch=4 v=8192
6772 Pb ch=4 v=8192
6776 Pb ch=4 v=8192
6781 Pb ch=4 v=8192
6786 Pb ch=4 v=8192
6790 Pb ch=4 v=8192
6795 Pb ch=4 v=8192
6800 Pb ch=4 v=8192
6804 Pb ch=4 v=8192
6809 Pb ch=4 v=8192
6814 Pb ch=4 v=8192
6818 Pb ch=4 v=8192
6823 Pb ch=4 v=8192
6828 Pb ch=4 v=8192
6832 Pb ch=4 v=8192
6837 Pb ch=4 v=8192
6842 Pb ch=4 v=8192
6847 Pb ch=4 v=8192
6851 Pb ch=4 v=8192
6856 Pb ch=4 v=8192
6861 Pb ch=4 v=8192
6865 Pb ch=4 v=8192
6870 Pb ch=4 v=8192
6875 Pb ch=4 v=8192
6879 Pb ch=4 v=8192
6884 Pb ch=4 v=8192
6889 Pb ch=4 v=8192
6893 Pb ch=4 v=8192
6898 Pb ch=4 v=8192
6903 Pb ch=4 v=8192
6908 Pb ch=4 v=8192
6912 Pb ch=4 v=8192
6917 Pb ch=4 v=8192
6922 Pb ch=4 v=8192
6926 Pb ch=4 v=8192
6931 Pb ch=4 v=8192
6936 Pb ch=4 v=8192
6940 Pb ch=4 v=8192
6945 Pb ch=4 v=8192
6950 Pb ch=4 v=8192
6954 Pb ch=4 v=8192
6959 Pb ch=4 v=8192
6964 Pb ch=4 v=8192
6968 Pb ch=4 v=8192
6973 Pb ch=4 v=8192
6978 Pb ch=4 v=8192
6982 Pb ch=4 v=8192
6987 Pb ch=4 v=8192
6992 Pb ch=4 v=8192
6997 Pb ch=4 v=8192
7001 Pb ch=4 v=8192
7006 Pb ch=4 v=8192
7011 Pb ch=4 v=8192
7015 Pb ch=4 v=8192
7020 Pb ch=4 v=8192
7025 Pb ch=4 v=8192
7029 Pb ch=4 v=8192
7034 Pb ch=4 v=8192
7039 Pb ch=4 v=8320
7043 Pb ch=4 v=8320
7048 Pb ch=4 v=8320
7053 Pb ch=4 v=8320
7058 Pb ch=4 v=8320
7062 Pb ch=4 v=8320
7067 Pb ch=4 v=8320
7072 Pb ch=4 v=8320
7076 Pb ch=4 v=8320
7081 Pb ch=4 v=8192
7086 Pb ch=4 v=8192
7090 Pb ch=4 v=8192
7095 Pb ch=4 v=8192
7100 Pb ch=4 v=8192
7104 Pb ch=4 v=8192
7109 Pb ch=4 v=8192
7114 Pb ch=4 v=8064
7118 Pb ch=4 v=8064
7123 Pb ch=4 v=8064
7128 Pb ch=4 v=8064
7132 Pb ch=4 v=8064
7137 Pb ch=4 v=8064
7142 Pb ch=4 v=8064
7147 Pb ch=4 v=8064
7151 Pb ch=4 v=8064
7156 Pb ch=4 v=8192
7161 Pb ch=4 v=8192
7165 Pb ch=4 v=8192
7170 Pb ch=4 v=8192
7175 Pb ch=4 v=8192
7179 Pb ch=4 v=8320
7184 Pb ch=4 v=8320
7189 Pb ch=4 v=8320
7193 Pb ch=4 v=8320
7198 Pb ch=4 v=8448
7203 Pb ch=4 v=8448
7208 Pb ch=4 v=8448
7212 Pb ch=4 v=8448
7217 Pb ch=4 v=8448
7222 Pb ch=4 v=8320
7226 Pb ch=4 v=8320
7231 Pb ch=4 v=8320
7236 Pb ch=4 v=8320
7240 Pb ch=4 v=8192
7245 Pb ch=4 v=8192
7250 Pb ch=4 v=8192
7254 Pb ch=4 v=8064
7259 Pb ch=4 v=8064
7264 Pb ch=4 v=8064
7268 Pb ch=4 v=8064
7273 Pb ch=4 v=7936
7278 Pb ch=4 v=7936
7282 Pb ch=4 v=7936
7287 Pb ch=4 v=7936
7292 Pb ch=4 v=7936
7297 Pb ch=4 v=8064
7301 Pb ch=4 v=8064
7306 Pb ch=4 v=8064
7311 Pb ch=4 v=8064
7315 Pb ch=4 v=8192
7320 Pb ch=4 v=8192
7325 Pb ch=4 v=8320
7329 Pb ch=4 v=8320
7334 Pb ch=4 v=8320
7339 Pb ch=4 v=8448
7343 Pb ch=4 v=8448
7348 Pb ch=4 v=8448
7353 Pb ch=4 v=8448
7358 Pb ch=4 v=8448
7362 Pb ch=4 v=8448
7367 Pb ch=4 v=8448
7372 Pb ch=4 v=8448
7376 Pb ch=4 v=8448
7381 Pb ch=4 v=8320
7386 Pb ch=4 v=8320
7390 Pb ch=4 v=8320
7395 Pb ch=4 v=8192
7400 Pb ch=4 v=8064
7404 Pb ch=4 v=8064
7409 Pb ch=4 v=8064
7414 Pb ch=4 v=7936
7418 Pb ch=4 v=7936
7423 Pb ch=4 v=7936
7428 Pb ch=4 v=7936
7432 Pb ch=4 v=7936
7437 Pb ch=4 v=7936
7442 Pb ch=4 v=7936
7447 Pb ch=4 v=7936
7451 Pb ch=4 v=7936
7456 Pb ch=4 v=8064
7461 Pb ch=4 v=8064
7465 Pb ch=4 v=8064
7470 Pb ch=4 v=8192
7475 Pb ch=4 v=8192
7479 Pb ch=4 v=8320
7484 Pb ch=4 v=8320
7489 Pb ch=4 v=8320
7493 Pb ch=4 v=8320
7498 Pb ch=4 v=8320
7503 Pb ch=4 v=8320
7508 Pb ch=4 v=8320
7512 Pb ch=4 v=8320
7517 Pb ch=4 v=8320
7522 Pb ch=4 v=8320
7526 Pb ch=4 v=8320
7531 Pb ch=4 v=8320
7536 Pb ch=4 v=8320
7540 Pb ch=4 v=8192
7545 Pb ch=4 v=8192
7550 Pb ch=4 v=8192
7554 Pb ch=4 v=8064
7559 Pb ch=4 v=8064
7564 Pb ch=4 v=8064
7568 Pb ch=4 v=8064
7573 Pb ch=4 v=8064
7578 Pb ch=4 v=8064
7582 Pb ch=4 v=8064
7587 Pb ch=4 v=8064
7592 Pb ch=4 v=8064
7597 Pb ch=4 v=8064
7601 Pb ch=4 v=8064
7606 Pb ch=4 v=8064
7611 Pb ch=4 v=8064
7615 Pb ch=4 v=8192
7620 Pb ch=4 v=8192
7625 Pb ch=4 v=8192
7629 Pb ch=4 v=8320
7634 Pb ch=4 v=8320
7639 Pb ch=4 v=8320
7643 Pb ch=4 v=8320
7648 Pb ch=4 v=8448
7653 Pb ch=4 v=8448
7658 Pb ch=4 v=8448
7662 Pb ch=4 v=8448
7667 Pb ch=4 v=8448
7672 Pb ch=4 v=8320
7676 Pb ch=4 v=8320
7681 Pb ch=4 v=8320
7686 Pb ch=4 v=8320
7690 Pb ch=4 v=8192
7695 Pb ch=4 v=8192
7700 Pb ch=4 v=8192
7704 Pb ch=4 v=8064
7709 Pb ch=4 v=8064
7714 Pb ch=4 v=8064
7718 Pb ch=4 v=8064
7723 Pb ch=4 v=7936
7728 Pb ch=4 v=7936
7732 Pb ch=4 v=7936
7737 Pb ch=4 v=7936
7742 Pb ch=4 v=7936
7747 Pb ch=4 v=8064
7751 Pb ch=4 v=8064
7756 Pb ch=4 v=8064
7761 Pb ch=4 v=8064
7765 Pb ch=4 v=8192
7770 Pb ch=4 v=8192
7775 Pb ch=4 v=8192
7779 Pb ch=4 v=8320
7784 Pb ch=4 v=8320
7789 Pb ch=4 v=8320
7793 Pb ch=4 v=8448
7798 Pb ch=4 v=8448
7803 Pb ch=4 v=8448
7808 Pb ch=4 v=8448
7812 Pb ch=4 v=8448
7817 Pb ch=4 v=8448
7822 Pb ch=4 v=8448
7826 Pb ch=4 v=8320
7831 Pb ch=4 v=8320
7836 Pb ch=4 v=8320
7840 Pb ch=4 v=8192
7845 Pb ch=4 v=8192
7850 Pb ch=4 v=8192
7854 Pb ch=4 v=8064
7859 Pb ch=4 v=8064
7864 Pb ch=4 v=8064
7868 Pb ch=4 v=7936
7873 Pb ch=4 v=7936
7878 Pb ch=4 v=7936
7882 Pb ch=4 v=7936
7887 Pb ch=4 v=7936
7892 Pb ch=4 v=7936
7897 Pb ch=4 v=7936
7901 Pb ch=4 v=8064
7906 Pb ch=4 v=8064
7911 Pb ch=4 v=8064
7915 Pb ch=4 v=8192
7920 Pb ch=4 v=8192
7925 Pb ch=4 v=8192
7929 Pb ch=4 v=8320
7934 Pb ch=4 v=8320
7939 Pb ch=4 v=8320
7943 Pb ch=4 v=8448
7948 Pb ch=4 v=8448
7953 Pb ch=4 v=8448
7958 Pb ch=4 v=8448
7962 Pb ch=4 v=8448
7967 Pb ch=4 v=8448
7972 Pb ch=4 v=8448
7976 Pb ch=4 v=8320
7981 Pb ch=4 v=8320
7986 Pb ch=4 v=8320
7990 Pb ch=4 v=8192
7995 Pb ch=4 v=8192
8000 Pb ch=4 v=8192
8004 Pb ch=4 v=8064
8009 Pb ch=4 v=8064
8014 Pb ch=4 v=8064
8018 Pb ch=4 v=7936
8023 Pb ch=4 v=7936
8028 Pb ch=4 v=7936
8032 Pb ch=4 v=7936
8037 Pb ch=4 v=7936
8042 Pb ch=4 v=7936
8047 Pb ch=4 v=7936
8051 Pb ch=4 v=8064
8056 Pb ch=4 v=8064
8061 Pb ch=4 v=8064
8065 Pb ch=4 v=8192
8070 Pb ch=4 v=8192
8075 Pb ch=4 v=8192
8079 Pb ch=4 v=8320
8084 Pb ch=4 v=8320
8089 Pb ch=4 v=8320
8093 Pb ch=4 v=8448
8098 Pb ch=4 v=8448
8103 Pb ch=4 v=8448
8108 Pb ch=4 v=8448
8112 Pb ch=4 v=8448
8117 Pb ch=4 v=8448
8122 Pb ch=4 v=8448
8126 Pb ch=4 v=8320
8131 Pb ch=4 v=8320
8136 Pb ch=4 v=8320
8140 Pb ch=4 v=8192
8145 Pb ch=4 v=8192
8150 Pb ch=4 v=8192
8154 Pb ch=4 v=8064
8159 Pb ch=4 v=8064
8164 Pb ch=4 v=8064
8168 Pb ch=4 v=7936
8173 Pb ch=4 v=7936
8178 Pb ch=4 v=7936
8182 Pb ch=4 v=7936
8187 Pb ch=4 v=7936
8192 Pb ch=4 v=7936
8197 Pb ch=4 v=7936
8201 Pb ch=4 v=8064
8206 Pb ch=4 v=8064
8211 Pb ch=4 v=8064
8215 Pb ch=4 v=8192
8220 Pb ch=4 v=8192
8225 Pb ch=4 v=8192
8229 Pb ch=4 v=8320
8234 Pb ch=4 v=8320
8239 Pb ch=4 v=8320
8243 Pb ch=4 v=8320
8248 Pb ch=4 v=8320
8253 Pb ch=4 v=8320
8258 Pb ch=4 v=8320
8262 Pb ch=4 v=8320
8267 Pb ch=4 v=8320
8272 Pb ch=4 v=8320
8276 Pb ch=4 v=8320
8281 Pb ch=4 v=8320
8286 Pb ch=4 v=8320
8290 Pb ch=4 v=8192
8295 Pb ch=4 v=8192
8300 Pb ch=4 v=8192
8304 Pb ch=4 v=8064
8309 Pb ch=4 v=8064
8314 Pb ch=4 v=8064
8318 Pb ch=4 v=8064
8323 Pb ch=4 v=8064
8328 Pb ch=4 v=8064
8332 Pb ch=4 v=8064
8337 Pb ch=4 v=8064
8342 Pb ch=4 v=8064
8347 Pb ch=4 v=8064
8351 Pb ch=4 v=8064
8356 Pb ch=4 v=8064
8361 Pb ch=4 v=8064
8365 Pb ch=4 v=8192
8370 Pb ch=4 v=8192
8375 Pb ch=4 v=8192
8379 Pb ch=4 v=8320
8384 Pb ch=4 v=8320
8389 Pb ch=4 v=8320
8393 Pb ch=4 v=8448
8398 Pb ch=4 v=8448
8403 Pb ch=4 v=8448
8408 Pb ch=4 v=8448
8412 Pb ch=4 v=8448
8417 Pb ch=4 v=8448
8422 Pb ch=4 v=8448
8426 Pb ch=4 v=8320
8431 Pb ch=4 v=8320
8436 Pb ch=4 v=8320
8440 Pb ch=4 v=8192
8445 Pb ch=4 v=8192
8450 Pb ch=4 v=8192
8454 Pb ch=4 v=8064
8459 Pb ch=4 v=8064
8464 Pb ch=4 v=8064
8468 Pb ch=4 v=7936
8473 Pb ch=4 v=7936
8478 Pb ch=4 v=7936
8482 Pb ch=4 v=7936
8487 Pb ch=4 v=7936
8492 Pb ch=4 v=7936
8497 Pb ch=4 v=7936
8501 Pb ch=4 v=8064
8506 Pb ch=4 v=8064
8511 Pb ch=4 v=8064
8515 Pb ch=4 v=8192
8520 Pb ch=4 v=8192
8525 Pb ch=4 v=8192
8529 Pb ch=4 v=8320
8534 Pb ch=4 v=8320
8539 Pb ch=4 v=8320
8543 Pb ch=4 v=8320
8548 Pb ch=4 v=8320
8553 Pb ch=4 v=8448
8558 Pb ch=4 v=8448
8562 Pb ch=4 v=8448
8567 Pb ch=4 v=8320
8572 Pb ch=4 v=8320
8576 Pb ch=4 v=8320
8581 Pb ch=4 v=8320
8586 Pb ch=4 v=8320
8590 Pb ch=4 v=8192
8595 Pb ch=4 v=8192
8600 Pb ch=4 v=8192
8604 Pb ch=4 v=8064
8609 Pb ch=4 v=8064
8614 Pb ch=4 v=8064
8618 Pb ch=4 v=8064
8623 Pb ch=4 v=8064
8628 Pb ch=4 v=7936
8632 Pb ch=4 v=7936
8637 Pb ch=4 v=7936
8642 Pb ch=4 v=8064
8647 Pb ch=4 v=8064
8651 Pb ch=4 v=8064
8656 Pb ch=4 v=8064
8661 Pb ch=4 v=8064
8665 Pb ch=4 v=8192
8670 Pb ch=4 v=8192
8675 Pb ch=4 v=8192
8679 Pb ch=4 v=8320
8684 Pb ch=4 v=8320
8689 Pb ch=4 v=8320
8693 Pb ch=4 v=8448
8698 Pb ch=4 v=8448
8703 Pb ch=4 v=8448
8708 Pb ch=4 v=8448
8712 Pb ch=4 v=8448
8717 Pb ch=4 v=8448
8722 Pb ch=4 v=8448
8726 Pb ch=4 v=8320
8731 Pb ch=4 v=8320
8736 Pb ch=4 v=8320
8740 Pb ch=4 v=8192
8745 Pb ch=4 v=8192
8750 Pb ch=4 v=8192
8754 Pb ch=4 v=8064
8759 Pb ch=4 v=8064
8764 Pb ch=4 v=8064
8768 Pb ch=4 v=7936
8773 Pb ch=4 v=7936
8778 Pb ch=4 v=7936
8782 Pb ch=4 v=7936
8787 Pb ch=4 v=7936
8792 Pb ch=4 v=7936
8797 Pb ch=4 v=7936
8801 Pb ch=4 v=8064
8806 Pb ch=4 v=8064
8811 Pb ch=4 v=8064
8815 Pb ch=4 v=8192
8820 Pb ch=4 v=8192
8825 Pb ch=4 v=8192
8829 Pb ch=4 v=8320
8834 Pb ch=4 v=8320
8839 Pb ch=4 v=8448
8843 Pb ch=4 v=8448
8848 Pb ch=4 v=8448
8853 Pb ch=4 v=8448
8858 Pb ch=4 v=8448
8862 Pb ch=4 v=8448
8867 Pb ch=4 v=8448
8872 Pb ch=4 v=8448
8876 Pb ch=4 v=8448
8881 Pb ch=4 v=8320
8886 Pb ch=4 v=8320
8890 Pb ch=4 v=8192
8895 Pb ch=4 v=8192
8900 Pb ch=4 v=8192
8904 Pb ch=4 v=8064
8909 Pb ch=4 v=8064
8914 Pb ch=4 v=7936
8918 Pb ch=4 v=7936
8923 Pb ch=4 v=7936
8928 Pb ch=4 v=7936
8932 Pb ch=4 v=7936
8937 Pb ch=4 v=7936
8942 Pb ch=4 v=7936
8947 Pb ch=4 v=7936
8951 Pb ch=4 v=7936
8956 Pb ch=4 v=8064
8961 Pb ch=4 v=8064
8965 Pb ch=4 v=8192
8970 Pb ch=4 v=8192
8975 Pb ch=4 v=8192
8979 Pb ch=4 v=8320
8984 Pb ch=4 v=8320
8989 Pb ch=4 v=8320
8993 Pb ch=4 v=8448
8998 Pb ch=4 v=8448
9003 Pb ch=4 v=8448
9008 Pb ch=4 v=8448
9012 Pb ch=4 v=8448
9017 Pb ch=4 v=8448
9022 Pb ch=4 v=8448
9026 Pb ch=4 v=8320
9031 Pb ch=4 v=8320
9036 Pb ch=4 v=8320
9040 Pb ch=4 v=8192
9045 Pb ch=4 v=8192
9050 Pb ch=4 v=8192
9054 Pb ch=4 v=8064
9059 Pb ch=4 v=8064
9064 Pb ch=4 v=8064
9068 Pb ch=4 v=7936
9073 Pb ch=4 v=7936
9078 Pb ch=4 v=7936
9082 Pb ch=4 v=7936
9087 Pb ch=4 v=7936
9092 Pb ch=4 v=7936
9097 Pb ch=4 v=7936
9101 Pb ch=4 v=8064
9106 Pb ch=4 v=8064
9111 Pb ch=4 v=8064
9115 Pb ch=4 v=8192
9120 Pb ch=4 v=8192
9125 Pb ch=4 v=8192
9129 Pb ch=4 v=8320
9134 Pb ch=4 v=8320
9139 Pb ch=4 v=8320
9143 Pb ch=4 v=8320
9148 Pb ch=4 v=8448
9153 Pb ch=4 v=8448
9158 Pb ch=4 v=8448
9162 Pb ch=4 v=8448
9167 Pb ch=4 v=8448
9172 Pb ch=4 v=8320
9176 Pb ch=4 v=8320
9181 Pb ch=4 v=8320
9186 Pb ch=4 v=8320
9190 Pb ch=4 v=8192
9195 Pb ch=4 v=8192
9200 Pb ch=4 v=8192
9204 Pb ch=4 v=8064
9209 Pb ch=4 v=8064
9214 Pb ch=4 v=8064
9218 Pb ch=4 v=8064
9223 Pb ch=4 v=7936
9228 Pb ch=4 v=7936
9232 Pb ch=4 v=7936
9237 Pb ch=4 v=7936
9242 Pb ch=4 v=7936
9247 Pb ch=4 v=8064
9251 Pb ch=4 v=8064
9256 Pb ch=4 v=8064
9261 Pb ch=4 v=8064
9265 Pb ch=4 v=8192
9270 Pb ch=4 v=8192
9275 Pb ch=4 v=8192
9279 Pb ch=4 v=8192
9284 Pb ch=4 v=8320
9289 Pb ch=4 v=8320
9293 Pb ch=4 v=8320
9298 Pb ch=4 v=8320
9303 Pb ch=4 v=8320
9308 Pb ch=4 v=8320
9312 Pb ch=4 v=8320
9317 Pb ch=4 v=8320
9322 Pb ch=4 v=8320
9326 Pb ch=4 v=8320
9331 Pb ch=4 v=8320
9336 Pb ch=4 v=8192
9340 Pb ch=4 v=8192
9345 Pb ch=4 v=8192
9350 Pb ch=4 v=8192
9354 Pb ch=4 v=8192
9359 Pb ch=4 v=8064
9364 Pb ch=4 v=8064
9368 Pb ch=4 v=8064
9373 Pb ch=4 v=8064
9378 Pb ch=4 v=8064
9382 Pb ch=4 v=8064
9387 Pb ch=4 v=8064
9392 Pb ch=4 v=8064
9397 Pb ch=4 v=8064
9401 Pb ch=4 v=8064
9406 Pb ch=4 v=8064
9411 Pb ch=4 v=8192
9415 Pb ch=4 v=8192
9420 Pb ch=4 v=8192
9425 Pb ch=4 v=8192
9429 Pb ch=4 v=8192
9434 Pb ch=4 v=8320
9439 Pb ch=4 v=8320
9443 Pb ch=4 v=8320
9448 Pb ch=4 v=8320
9453 Pb ch=4 v=8320
9458 Pb ch=4 v=8320
9462 Pb ch=4 v=8320
9467 Pb ch=4 v=8320
9472 Pb ch=4 v=8320
9476 Pb ch=4 v=8320
9481 Pb ch=4 v=8320
9486 Pb ch=4 v=8192
9490 Pb ch=4 v=8192
9495 Pb ch=4 v=8192
9500 Pb ch=4 v=8192
9504 Pb ch=4 v=8192
9509 Pb ch=4 v=8064
9514 Pb ch=4 v=8064
9518 Pb ch=4 v=8064
9523 Pb ch=4 v=8064
9528 Pb ch=4 v=8064
9532 Pb ch=4 v=8064
9537 Pb ch=4 v=8064
9542 Pb ch=4 v=8064
9547 Pb ch=4 v=8064
9551 Pb ch=4 v=8064
9556 Pb ch=4 v=8064
9561 Pb ch=4 v=8192
9565 Pb ch=4 v=8192
9570 Pb ch=4 v=8192
9575 Pb ch=4 v=8192
9579 Pb ch=4 v=8192
9584 Pb ch=4 v=8192
9589 Pb ch=4 v=8192
9593 Pb ch=4 v=8192
9598 Pb ch=4 v=8192
9600 Off ch=4 n=62 v=80
9600 Pb ch=4 v=8192
9840 On ch=3 n=64 v=95
10200 Off ch=3 n=64 v=80
10200 On ch=3 n=62 v=95
10560 Off ch=3 n=62 v=80
10800 On ch=3 n=62 v=95
11160 Off ch=3 n=62 v=80
11160 On ch=3 n=64 v=95
11400 Off ch=3 n=64 v=80
11400 On ch=3 n=62 v=95
11520 Off ch=3 n=62 v=80
11760 On ch=3 n=67 v=95
12120 Off ch=3 n=67 v=80
12120 On ch=3 n=62 v=95
12480 Off ch=3 n=62 v=80
12480 On ch=4 n=62 v=95
12588 Pb ch=4 v=8832
12600 Off ch=4 n=62 v=80
12600 Pb ch=4 v=8192
12600 On ch=3 n=64 v=76
12840 Off ch=3 n=64 v=80
12840 On ch=3 n=67 v=95
13140 Off ch=3 n=67 v=80
13140 On ch=4 n=64 v=95
13194 Pb ch=4 v=7552
13200 Off ch=4 n=64 v=80
13200 Pb ch=4 v=8192
13200 On ch=3 n=62 v=76
13440 Off ch=3 n=62 v=80
13680 On ch=3 n=64 v=95
14040 Off ch=3 n=64 v=80
14040 On ch=3 n=62 v=95
14400 Off ch=3 n=62 v=80
14640 On ch=3 n=62 v=95
15000 Off ch=3 n=62 v=80
15000 On ch=3 n=64 v=95
15240 Off ch=3 n=64 v=80
15240 On ch=3 n=62 v=95
15360 Off ch=3 n=62 v=80
15600 On ch=3 n=67 v=95
15960 Off ch=3 n=67 v=80
15960 On ch=3 n=62 v=95
16320 Off ch=3 n=62 v=80
16320 On ch=3 n=67 v=95
16680 Off ch=3 n=67 v=80
16680 On ch=3 n=62 v=95
17160 Off ch=3 n=62 v=80
17160 On ch=3 n=67 v=95
17280 Off ch=3 n=67 v=80
17520 On ch=3 n=64 v=95
17880 Off ch=3 n=64 v=80
17880 On ch=3 n=62 v=95
18240 Off ch=3 n=62 v=80
18480 On ch=3 n=62 v=95
18840 Off ch=3 n=62 v=80
18840 On ch=3 n=64 v=95
19080 Off ch=3 n=64 v=80
19080 On ch=3 n=62 v=95
19200 Off ch=3 n=62 v=80
19440 On ch=3 n=67 v=95
19800 Off ch=3 n=67 v=80
19800 On ch=3 n=62 v=95
20160 Off ch=3 n=62 v=80
20160 On ch=4 n=62 v=95
20268 Pb ch=4 v=8832
20280 Off ch=4 n=62 v=80
20280 Pb ch=4 v=8192
20280 On ch=3 n=64 v=76
20520 Off ch=3 n=64 v=80
20520 On ch=3 n=67 v=95
20820 Off ch=3 n=67 v=80
20820 On ch=4 n=64 v=95
20874 Pb ch=4 v=7552
20880 Off ch=4 n=64 v=80
20880 Pb ch=4 v=8192
20880 On ch=3 n=62 v=76
21120 Off ch=3 n=62 v=80
21360 On ch=3 n=64 v=95
21720 Off ch=3 n=64 v=80
21720 On ch=3 n=62 v=95
22080 Off ch=3 n=62 v=80
22320 On ch=3 n=62 v=95
22680 Off ch=3 n=62 v=80
22680 On ch=3 n=64 v=95
22920 Off ch=3 n=64 v=80
22920 On ch=3 n=62 v=95
23040 Off ch=3 n=62 v=80
23280 On ch=3 n=67 v=95
23640 Off ch=3 n=67 v=80
23640 On ch=3 n=62 v=95
24000 Off ch=3 n=62 v=80
24000 On ch=3 n=67 v=95
24360 Off ch=3 n=67 v=80
24360 On ch=3 n=62 v=95
24840 Off ch=3 n=62 v=80
24840 On ch=3 n=67 v=95
24960 Off ch=3 n=67 v=80
25200 On ch=3 n=64 v=95
25560 Off ch=3 n=64 v=80
25560 On ch=3 n=62 v=95
25920 Off ch=3 n=62 v=80
26160 On ch=3 n=62 v=95
26520 Off ch=3 n=62 v=80
26520 On ch=3 n=64 v=95
26760 Off ch=3 n=64 v=80
26760 On ch=3 n=62 v=95
26880 Off ch=3 n=62 v=80
27120 On ch=3 n=67 v=95
27480 Off ch=3 n=67 v=80
27480 On ch=3 n=62 v=95
27840 Off ch=3 n=62 v=80
27840 On ch=4 n=62 v=95
27948 Pb ch=4 v=8832
27960 Off ch=4 n=62 v=80
27960 Pb ch=4 v=8192
27960 On ch=3 n=64 v=76
28200 Off ch=3 n=64 v=80
28200 On ch=3 n=67 v=95
28500 Off ch=3 n=67 v=80
28500 On ch=4 n=64 v=95
28554 Pb ch=4 v=7552
28560 Off ch=4 n=64 v=80
28560 Pb ch=4 v=8192
28560 On ch=3 n=62 v=76
28800 Off ch=3 n=62 v=80
29040 On ch=3 n=64 v=95
29400 Off ch=3 n=64 v=80
29400 On ch=3 n=62 v=95
29760 Off ch=3 n=62 v=80
30000 On ch=3 n=62 v=95
30360 Off ch=3 n=62 v=80
30360 On ch=3 n=64 v=95
30600 Off ch=3 n=64 v=80
30600 On ch=3 n=62 v=95
30720 Off ch=3 n=62 v=80
30960 On ch=3 n=67 v=95
31320 Off ch=3 n=67 v=80
31320 On ch=3 n=62 v=95
31680 Off ch=3 n=62 v=80
31680 On ch=3 n=67 v=95
32040 Off ch=3 n=67 v=80
32040 On ch=3 n=62 v=95
32520 Off ch=3 n=62 v=80
32520 On ch=3 n=67 v=95
32640 Off ch=3 n=67 v=80
32640 On ch=3 n=52 v=95
32760 Off ch=3 n=52 v=80
32760 On ch=3 n=53 v=95
32880 Off ch=3 n=53 v=80
32880 On ch=3 n=54 v=95
33000 Off ch=3 n=54 v=80
33000 On ch=3 n=62 v=95
33000 On ch=3 n=57 v=95
33240 Off ch=3 n=62 v=80
33240 Off ch=3 n=57 v=80
33240 On ch=3 n=62 v=95
33240 On ch=3 n=57 v=95
33360 Off ch=3 n=62 v=80
33360 Off ch=3 n=57 v=80
33360 On ch=4 n=54 v=95
33552 Pb ch=4 v=7552
33568 Pb ch=4 v=6784
33584 Pb ch=4 v=6144
33600 Off ch=4 n=54 v=80
33600 Pb ch=4 v=8192
33600 On ch=3 n=50 v=76
33720 Off ch=3 n=50 v=80
33720 On ch=3 n=52 v=95
33840 Off ch=3 n=52 v=80
33840 On ch=3 n=60 v=95
33840 On ch=3 n=55 v=95
34080 Off ch=3 n=60 v=80
34080 Off ch=3 n=55 v=80
34080 On ch=3 n=60 v=95
34080 On ch=3 n=55 v=95
34320 Off ch=3 n=60 v=80
34320 Off ch=3 n=55 v=80
34320 On ch=4 n=52 v=95
34488 Pb ch=4 v=7552
34502 Pb ch=4 v=6784
34517 Pb ch=4 v=6144
34531 Pb ch=4 v=5504
34546 Pb ch=4 v=4736
34560 Off ch=4 n=52 v=80
34560 Pb ch=4 v=8192
34560 On ch=4 n=46 v=76
34656 Pb ch=4 v=8832
34680 Off ch=4 n=46 v=80
34680 Pb ch=4 v=8192
34680 On ch=3 n=47 v=76
34800 Off ch=3 n=47 v=80
34800 On ch=3 n=50 v=95
34920 Off ch=3 n=50 v=80
34920 On ch=3 n=55 v=95
35280 Off ch=3 n=55 v=80
35280 Pb ch=4 v=8192
35280 On ch=4 n=67 v=95
35280 On ch=4 n=62 v=95
35312 Pb ch=4 v=8064
35344 Pb ch=4 v=7936
35376 Pb ch=4 v=7808
35408 Pb ch=4 v=7680
35440 Pb ch=4 v=7552
35616 Pb ch=4 v=7680
35648 Pb ch=4 v=7808
35680 Pb ch=4 v=7936
35712 Pb ch=4 v=8064
35744 Pb ch=4 v=8192
35760 Pb ch=4 v=8192
35760 Off ch=4 n=67 v=80
35760 Off ch=4 n=62 v=80
35760 Pb ch=4 v=8192
35760 On ch=4 n=67 v=95
35760 On ch=4 n=62 v=95
35792 Pb ch=4 v=8064
35824 Pb ch=4 v=7936
35856 Pb ch=4 v=7808
35888 Pb ch=4 v=7680
35920 Pb ch=4 v=7552
36096 Pb ch=4 v=7680
36128 Pb ch=4 v=7808
36160 Pb ch=4 v=7936
36192 Pb ch=4 v=8064
36224 Pb ch=4 v=8192
36240 Pb ch=4 v=8192
36240 Off ch=4 n=67 v=80
36240 Off ch=4 n=62 v=80
36240 Pb ch=4 v=8192
36240 On ch=4 n=67 v=95
36240 On ch=4 n=62 v=95
36256 Pb ch=4 v=8064
36272 Pb ch=4 v=7936
36288 Pb ch=4 v=7808
36304 Pb ch=4 v=7680
36320 Pb ch=4 v=7552
36416 Pb ch=4 v=7680
36432 Pb ch=4 v=7808
36448 Pb ch=4 v=7936
36464 Pb ch=4 v=8064
36480 Pb ch=4 v=8192
36480 Pb ch=4 v=8192
36480 Off ch=4 n=67 v=80
36480 Off ch=4 n=62 v=80
36480 On ch=3 n=52 v=95
36600 Off ch=3 n=52 v=80
36600 On ch=3 n=53 v=95
36720 Off ch=3 n=53 v=80
36720 On ch=3 n=54 v=95
36840 Off ch=3 n=54 v=80
36840 On ch=3 n=62 v=95
36840 On ch=3 n=57 v=95
37080 Off ch=3 n=62 v=80
37080 Off ch=3 n=57 v=80
37080 On ch=3 n=62 v=95
37080 On ch=3 n=57 v=95
37200 Off ch=3 n=62 v=80
37200 Off ch=3 n=57 v=80
37200 On ch=4 n=54 v=95
37392 Pb ch=4 v=7552
37408 Pb ch=4 v=6784
37424 Pb ch=4 v=6144
37440 Off ch=4 n=54 v=80
37440 Pb ch=4 v=8192
37440 On ch=3 n=50 v=76
37560 Off ch=3 n=50 v=80
37560 On ch=3 n=52 v=76
37680 Off ch=3 n=52 v=80
37680 On ch=3 n=60 v=95
37680 On ch=3 n=55 v=95
37920 Off ch=3 n=60 v=80
37920 Off ch=3 n=55 v=80
37920 On ch=3 n=60 v=95
37920 On ch=3 n=55 v=95
38160 Off ch=3 n=60 v=80
38160 Off ch=3 n=55 v=80
38160 On ch=4 n=52 v=95
38328 Pb ch=4 v=7552
38342 Pb ch=4 v=6784
38357 Pb ch=4 v=6144
38371 Pb ch=4 v=5504
38386 Pb ch=4 v=4736
38400 Off ch=4 n=52 v=80
38400 Pb ch=4 v=8192
38400 On ch=4 n=46 v=76
38496 Pb ch=4 v=8832
38520 Off ch=4 n=46 v=80
38520 Pb ch=4 v=8192
38520 On ch=3 n=47 v=76
38640 Off ch=3 n=47 v=80
38640 On ch=3 n=50 v=95
38760 Off ch=3 n=50 v=80
38760 On ch=3 n=55 v=95
39120 Off ch=3 n=55 v=80
39120 On ch=4 n=71 v=95
39120 Pb ch=4 v=8192
39120 On ch=4 n=67 v=95
39120 Pb ch=4 v=8192
39120 On ch=4 n=62 v=95
39120 Pb ch=4 v=8192
39125 Pb ch=4 v=8192
39125 Pb ch=4 v=8192
39125 Pb ch=4 v=8192
39129 Pb ch=4 v=8192
39129 Pb ch=4 v=8192
39129 Pb ch=4 v=8192
39134 Pb ch=4 v=8192
39134 Pb ch=4 v=8192
39134 Pb ch=4 v=8192
39139 Pb ch=4 v=8192
39139 Pb ch=4 v=8192
39139 Pb ch=4 v=8192
39143 Pb ch=4 v=8192
39143 Pb ch=4 v=8192
39143 Pb ch=4 v=8192
39148 Pb ch=4 v=8192
39148 Pb ch=4 v=8192
39148 Pb ch=4 v=8192
39153 Pb ch=4 v=8192
39153 Pb ch=4 v=8192
39153 Pb ch=4 v=8192
39158 Pb ch=4 v=8192
39158 Pb ch=4 v=8192
39158 Pb ch=4 v=8192
39162 Pb ch=4 v=8192
39162 Pb ch=4 v=8192
39162 Pb ch=4 v=8192
39167 Pb ch=4 v=8192
39167 Pb ch=4 v=8192
39167 Pb ch=4 v=8192
39172 Pb ch=4 v=8192
39172 Pb ch=4 v=8192
39172 Pb ch=4 v=8192
39176 Pb ch=4 v=8192
39176 Pb ch=4 v=8192
39176 Pb ch=4 v=8192
39181 Pb ch=4 v=8192
39181 Pb ch=4 v=8192
39181 Pb ch=4 v=8192
39186 Pb ch=4 v=8192
39186 Pb ch=4 v=8192
39186 Pb ch=4 v=8192
39190 Pb ch=4 v=8192
39190 Pb ch=4 v=8192
39190 Pb ch=4 v=8192
39195 Pb ch=4 v=8192
39195 Pb ch=4 v=8192
39195 Pb ch=4 v=8192
39200 Pb ch=4 v=8192
39200 Pb ch=4 v=8192
39200 Pb ch=4 v=8192
39204 Pb ch=4 v=8192
39204 Pb ch=4 v=8192
39204 Pb ch=4 v=8192
39209 Pb ch=4 v=8192
39209 Pb ch=4 v=8192
39209 Pb ch=4 v=8192
39214 Pb ch=4 v=8192
39214 Pb ch=4 v=8192
39214 Pb ch=4 v=8192
39218 Pb ch=4 v=8192
39218 Pb ch=4 v=8192
39218 Pb ch=4 v=8192
39223 Pb ch=4 v=8192
39223 Pb ch=4 v=8192
39223 Pb ch=4 v=8192
39228 Pb ch=4 v=8192
39228 Pb ch=4 v=8192
39228 Pb ch=4 v=8192
39232 Pb ch=4 v=8192
39232 Pb ch=4 v=8192
39232 Pb ch=4 v=8192
39237 Pb ch=4 v=8192
39237 Pb ch=4 v=8192
39237 Pb ch=4 v=8192
39242 Pb ch=4 v=8192
39242 Pb ch=4 v=8192
39242 Pb ch=4 v=8192
39247 Pb ch=4 v=8192
39247 Pb ch=4 v=8192
39247 Pb ch=4 v=8192
39251 Pb ch=4 v=8192
39251 Pb ch=4 v=8192
39251 Pb ch=4 v=8192
39256 Pb ch=4 v=8192
39256 Pb ch=4 v=8192
39256 Pb ch=4 v=8192
39261 Pb ch=4 v=8192
39261 Pb ch=4 v=8192
39261 Pb ch=4 v=8192
39265 Pb ch=4 v=8192
39265 Pb ch=4 v=8192
39265 Pb ch=4 v=8192
39270 Pb ch=4 v=8192
39270 Pb ch=4 v=8192
39270 Pb ch=4 v=8192
39275 Pb ch=4 v=8192
39275 Pb ch=4 v=8192
39275 Pb ch=4 v=8192
39279 Pb ch=4 v=8320
39279 Pb ch=4 v=8192
39279 Pb ch=4 v=8192
39284 Pb ch=4 v=8320
39284 Pb ch=4 v=8320
39284 Pb ch=4 v=8320
39289 Pb ch=4 v=8320
39289 Pb ch=4 v=8320
39289 Pb ch=4 v=8320
39293 Pb ch=4 v=8320
39293 Pb ch=4 v=8320
39293 Pb ch=4 v=8320
39298 Pb ch=4 v=8320
39298 Pb ch=4 v=8320
39298 Pb ch=4 v=8320
39303 Pb ch=4 v=8320
39303 Pb ch=4 v=8320
39303 Pb ch=4 v=8320
39308 Pb ch=4 v=8320
39308 Pb ch=4 v=8320
39308 Pb ch=4 v=8320
39312 Pb ch=4 v=8320
39312 Pb ch=4 v=8320
39312 Pb ch=4 v=8320
39317 Pb ch=4 v=8320
39317 Pb ch=4 v=8320
39317 Pb ch=4 v=8320
39322 Pb ch=4 v=8320
39322 Pb ch=4 v=8320
39322 Pb ch=4 v=8320
39326 Pb ch=4 v=8320
39326 Pb ch=4 v=8320
39326 Pb ch=4 v=8320
39331 Pb ch=4 v=8320
39331 Pb ch=4 v=8320
39331 Pb ch=4 v=8320
39336 Pb ch=4 v=8320
39336 Pb ch=4 v=8192
39336 Pb ch=4 v=8192
39340 Pb ch=4 v=8192
39340 Pb ch=4 v=8192
39340 Pb ch=4 v=8192
39345 Pb ch=4 v=8192
39345 Pb ch=4 v=8192
39345 Pb ch=4 v=8192
39350 Pb ch=4 v=8192
39350 Pb ch=4 v=8192
39350 Pb ch=4 v=8192
39354 Pb ch=4 v=8064
39354 Pb ch=4 v=8192
39354 Pb ch=4 v=8192
39359 Pb ch=4 v=8064
39359 Pb ch=4 v=8064
39359 Pb ch=4 v=8064
39364 Pb ch=4 v=8064
39364 Pb ch=4 v=8064
39364 Pb ch=4 v=8064
39368 Pb ch=4 v=8064
39368 Pb ch=4 v=8064
39368 Pb ch=4 v=8064
39373 Pb ch=4 v=8064
39373 Pb ch=4 v=8064
39373 Pb ch=4 v=8064
39378 Pb ch=4 v=8064
39378 Pb ch=4 v=8064
39378 Pb ch=4 v=8064
39382 Pb ch=4 v=8064
39382 Pb ch=4 v=8064
39382 Pb ch=4 v=8064
39387 Pb ch=4 v=8064
39387 Pb ch=4 v=8064
39387 Pb ch=4 v=8064
39392 Pb ch=4 v=8064
39392 Pb ch=4 v=8064
39392 Pb ch=4 v=8064
39397 Pb ch=4 v=8064
39397 Pb ch=4 v=8064
39397 Pb ch=4 v=8064
39401 Pb ch=4 v=8064
39401 Pb ch=4 v=8064
39401 Pb ch=4 v=8064
39406 Pb ch=4 v=8064
39406 Pb ch=4 v=8064
39406 Pb ch=4 v=8064
39411 Pb ch=4 v=8064
39411 Pb ch=4 v=8192
39411 Pb ch=4 v=8192
39415 Pb ch=4 v=8192
39415 Pb ch=4 v=8192
39415 Pb ch=4 v=8192
39420 Pb ch=4 v=8192
39420 Pb ch=4 v=8192
39420 Pb ch=4 v=8192
39425 Pb ch=4 v=8192
39425 Pb ch=4 v=8320
39425 Pb ch=4 v=8192
39429 Pb ch=4 v=8320
39429 Pb ch=4 v=8320
39429 Pb ch=4 v=8320
39434 Pb ch=4 v=8320
39434 Pb ch=4 v=8320
39434 Pb ch=4 v=8320
39439 Pb ch=4 v=8320
39439 Pb ch=4 v=8448
39439 Pb ch=4 v=8320
39443 Pb ch=4 v=8320
39443 Pb ch=4 v=8448
39443 Pb ch=4 v=8448
39448 Pb ch=4 v=8448
39448 Pb ch=4 v=8448
39448 Pb ch=4 v=8448
39453 Pb ch=4 v=8448
39453 Pb ch=4 v=8448
39453 Pb ch=4 v=8448
39458 Pb ch=4 v=8448
39458 Pb ch=4 v=8448
39458 Pb ch=4 v=8448
39462 Pb ch=4 v=8448
39462 Pb ch=4 v=8448
39462 Pb ch=4 v=8448
39467 Pb ch=4 v=8448
39467 Pb ch=4 v=8448
39467 Pb ch=4 v=8448
39472 Pb ch=4 v=8320
39472 Pb ch=4 v=8448
39472 Pb ch=4 v=8448
39476 Pb ch=4 v=8320
39476 Pb ch=4 v=8448
39476 Pb ch=4 v=8320
39481 Pb ch=4 v=8320
39481 Pb ch=4 v=8320
39481 Pb ch=4 v=8320
39486 Pb ch=4 v=8320
39486 Pb ch=4 v=8320
39486 Pb ch=4 v=8320
39490 Pb ch=4 v=8192
39490 Pb ch=4 v=8320
39490 Pb ch=4 v=8192
39495 Pb ch=4 v=8192
39495 Pb ch=4 v=8192
39495 Pb ch=4 v=8192
39500 Pb ch=4 v=8192
39500 Pb ch=4 v=8064
39500 Pb ch=4 v=8192
39504 Pb ch=4 v=8064
39504 Pb ch=4 v=8064
39504 Pb ch=4 v=8064
39509 Pb ch=4 v=8064
39509 Pb ch=4 v=8064
39509 Pb ch=4 v=8064
39514 Pb ch=4 v=8064
39514 Pb ch=4 v=7936
39514 Pb ch=4 v=8064
39518 Pb ch=4 v=8064
39518 Pb ch=4 v=7936
39518 Pb ch=4 v=7936
39523 Pb ch=4 v=7936
39523 Pb ch=4 v=7936
39523 Pb ch=4 v=7936
39528 Pb ch=4 v=7936
39528 Pb ch=4 v=7936
39528 Pb ch=4 v=7936
39532 Pb ch=4 v=7936
39532 Pb ch=4 v=7936
39532 Pb ch=4 v=7936
39537 Pb ch=4 v=7936
39537 Pb ch=4 v=7936
39537 Pb ch=4 v=7936
39542 Pb ch=4 v=7936
39542 Pb ch=4 v=7936
39542 Pb ch=4 v=7936
39547 Pb ch=4 v=8064
39547 Pb ch=4 v=7936
39547 Pb ch=4 v=7936
39551 Pb ch=4 v=8064
39551 Pb ch=4 v=7936
39551 Pb ch=4 v=8064
39556 Pb ch=4 v=8064
39556 Pb ch=4 v=8064
39556 Pb ch=4 v=8064
39561 Pb ch=4 v=8064
39561 Pb ch=4 v=8064
39561 Pb ch=4 v=8064
39565 Pb ch=4 v=8192
39565 Pb ch=4 v=8064
39565 Pb ch=4 v=8192
39570 Pb ch=4 v=8192
39570 Pb ch=4 v=8192
39570 Pb ch=4 v=8192
39575 Pb ch=4 v=8192
39575 Pb ch=4 v=8192
39575 Pb ch=4 v=8192
39579 Pb ch=4 v=8320
39579 Pb ch=4 v=8320
39579 Pb ch=4 v=8320
39584 Pb ch=4 v=8320
39584 Pb ch=4 v=8320
39584 Pb ch=4 v=8320
39589 Pb ch=4 v=8320
39589 Pb ch=4 v=8320
39589 Pb ch=4 v=8320
39593 Pb ch=4 v=8448
39593 Pb ch=4 v=8448
39593 Pb ch=4 v=8448
39598 Pb ch=4 v=8448
39598 Pb ch=4 v=8448
39598 Pb ch=4 v=8448
39603 Pb ch=4 v=8448
39603 Pb ch=4 v=8448
39603 Pb ch=4 v=8448
39608 Pb ch=4 v=8448
39608 Pb ch=4 v=8448
39608 Pb ch=4 v=8448
39612 Pb ch=4 v=8448
39612 Pb ch=4 v=8448
39612 Pb ch=4 v=8448
39617 Pb ch=4 v=8448
39617 Pb ch=4 v=8448
39617 Pb ch=4 v=8448
39622 Pb ch=4 v=8448
39622 Pb ch=4 v=8448
39622 Pb ch=4 v=8448
39626 Pb ch=4 v=8320
39626 Pb ch=4 v=8320
39626 Pb ch=4 v=8320
39631 Pb ch=4 v=8320
39631 Pb ch=4 v=8320
39631 Pb ch=4 v=8320
39636 Pb ch=4 v=8320
39636 Pb ch=4 v=8320
39636 Pb ch=4 v=8320
39640 Pb ch=4 v=8192
39640 Pb ch=4 v=8192
39640 Pb ch=4 v=8192
39645 Pb ch=4 v=8192
39645 Pb ch=4 v=8192
39645 Pb ch=4 v=8192
39650 Pb ch=4 v=8192
39650 Pb ch=4 v=8192
39650 Pb ch=4 v=8192
39654 Pb ch=4 v=8064
39654 Pb ch=4 v=8064
39654 Pb ch=4 v=8064
39659 Pb ch=4 v=8064
39659 Pb ch=4 v=8064
39659 Pb ch=4 v=8064
39664 Pb ch=4 v=8064
39664 Pb ch=4 v=8064
39664 Pb ch=4 v=8064
39668 Pb ch=4 v=7936
39668 Pb ch=4 v=7936
39668 Pb ch=4 v=7936
39673 Pb ch=4 v=7936
39673 Pb ch=4 v=7936
39673 Pb ch=4 v=7936
39678 Pb ch=4 v=7936
39678 Pb ch=4 v=7936
39678 Pb ch=4 v=7936
39682 Pb ch=4 v=7936
39682 Pb ch=4 v=7936
39682 Pb ch=4 v=7936
39687 Pb ch=4 v=7936
39687 Pb ch=4 v=7936
39687 Pb ch=4 v=7936
39692 Pb ch=4 v=7936
39692 Pb ch=4 v=7936
39692 Pb ch=4 v=7936
39697 Pb ch=4 v=7936
39697 Pb ch=4 v=7936
39697 Pb ch=4 v=7936
39701 Pb ch=4 v=8064
39701 Pb ch=4 v=8064
39701 Pb ch=4 v=8064
39706 Pb ch=4 v=8064
39706 Pb ch=4 v=8064
39706 Pb ch=4 v=8064
39711 Pb ch=4 v=8064
39711 Pb ch=4 v=8064
39711 Pb ch=4 v=8064
39715 Pb ch=4 v=8192
39715 Pb ch=4 v=8192
39715 Pb ch=4 v=8192
39720 Pb ch=4 v=8192
39720 Pb ch=4 v=8192
39720 Pb ch=4 v=8192
39725 Pb ch=4 v=8192
39725 Pb ch=4 v=8192
39725 Pb ch=4 v=8192
39729 Pb ch=4 v=8320
39729 Pb ch=4 v=8320
39729 Pb ch=4 v=8320
39734 Pb ch=4 v=8320
39734 Pb ch=4 v=8320
39734 Pb ch=4 v=8320
39739 Pb ch=4 v=8448
39739 Pb ch=4 v=8320
39739 Pb ch=4 v=8320
39743 Pb ch=4 v=8448
39743 Pb ch=4 v=8448
39743 Pb ch=4 v=8448
39748 Pb ch=4 v=8448
39748 Pb ch=4 v=8448
39748 Pb ch=4 v=8448
39753 Pb ch=4 v=8448
39753 Pb ch=4 v=8448
39753 Pb ch=4 v=8448
39758 Pb ch=4 v=8448
39758 Pb ch=4 v=8448
39758 Pb ch=4 v=8448
39762 Pb ch=4 v=8448
39762 Pb ch=4 v=8448
39762 Pb ch=4 v=8448
39767 Pb ch=4 v=8448
39767 Pb ch=4 v=8448
39767 Pb ch=4 v=8448
39772 Pb ch=4 v=8448
39772 Pb ch=4 v=8448
39772 Pb ch=4 v=8448
39776 Pb ch=4 v=8448
39776 Pb ch=4 v=8320
39776 Pb ch=4 v=8320
39781 Pb ch=4 v=8320
39781 Pb ch=4 v=8320
39781 Pb ch=4 v=8320
39786 Pb ch=4 v=8320
39786 Pb ch=4 v=8320
39786 Pb ch=4 v=8320
39790 Pb ch=4 v=8192
39790 Pb ch=4 v=8192
39790 Pb ch=4 v=8192
39795 Pb ch=4 v=8192
39795 Pb ch=4 v=8192
39795 Pb ch=4 v=8192
39800 Pb ch=4 v=8192
39800 Pb ch=4 v=8192
39800 Pb ch=4 v=8192
39804 Pb ch=4 v=8064
39804 Pb ch=4 v=8064
39804 Pb ch=4 v=8064
39809 Pb ch=4 v=8064
39809 Pb ch=4 v=8064
39809 Pb ch=4 v=8064
39814 Pb ch=4 v=7936
39814 Pb ch=4 v=8064
39814 Pb ch=4 v=8064
39818 Pb ch=4 v=7936
39818 Pb ch=4 v=7936
39818 Pb ch=4 v=7936
39823 Pb ch=4 v=7936
39823 Pb ch=4 v=7936
39823 Pb ch=4 v=7936
39828 Pb ch=4 v=7936
39828 Pb ch=4 v=7936
39828 Pb ch=4 v=7936
39832 Pb ch=4 v=7936
39832 Pb ch=4 v=7936
39832 Pb ch=4 v=7936
39837 Pb ch=4 v=7936
39837 Pb ch=4 v=7936
39837 Pb ch=4 v=7936
39842 Pb ch=4 v=7936
39842 Pb ch=4 v=7936
39842 Pb ch=4 v=7936
39847 Pb ch=4 v=7936
39847 Pb ch=4 v=7936
39847 Pb ch=4 v=7936
39851 Pb ch=4 v=7936
39851 Pb ch=4 v=8064
39851 Pb ch=4 v=8064
39856 Pb ch=4 v=8064
39856 Pb ch=4 v=8064
39856 Pb ch=4 v=8064
39861 Pb ch=4 v=8064
39861 Pb ch=4 v=8064
39861 Pb ch=4 v=8064
39865 Pb ch=4 v=8192
39865 Pb ch=4 v=8192
39865 Pb ch=4 v=8192
39870 Pb ch=4 v=8192
39870 Pb ch=4 v=8192
39870 Pb ch=4 v=8192
39875 Pb ch=4 v=8192
39875 Pb ch=4 v=8192
39875 Pb ch=4 v=8192
39879 Pb ch=4 v=8320
39879 Pb ch=4 v=8320
39879 Pb ch=4 v=8320
39884 Pb ch=4 v=8320
39884 Pb ch=4 v=8320
39884 Pb ch=4 v=8320
39889 Pb ch=4 v=8320
39889 Pb ch=4 v=8320
39889 Pb ch=4 v=8320
39893 Pb ch=4 v=8320
39893 Pb ch=4 v=8448
39893 Pb ch=4 v=8320
39898 Pb ch=4 v=8320
39898 Pb ch=4 v=8448
39898 Pb ch=4 v=8448
39903 Pb ch=4 v=8320
39903 Pb ch=4 v=8448
39903 Pb ch=4 v=8448
39908 Pb ch=4 v=8320
39908 Pb ch=4 v=8448
39908 Pb ch=4 v=8448
39912 Pb ch=4 v=8320
39912 Pb ch=4 v=8448
39912 Pb ch=4 v=8448
39917 Pb ch=4 v=8320
39917 Pb ch=4 v=8448
39917 Pb ch=4 v=8448
39922 Pb ch=4 v=8320
39922 Pb ch=4 v=8448
39922 Pb ch=4 v=8320
39926 Pb ch=4 v=8320
39926 Pb ch=4 v=8320
39926 Pb ch=4 v=8320
39931 Pb ch=4 v=8320
39931 Pb ch=4 v=8320
39931 Pb ch=4 v=8320
39936 Pb ch=4 v=8320
39936 Pb ch=4 v=8320
39936 Pb ch=4 v=8320
39940 Pb ch=4 v=8192
39940 Pb ch=4 v=8192
39940 Pb ch=4 v=8192
39945 Pb ch=4 v=8192
39945 Pb ch=4 v=8192
39945 Pb ch=4 v=8192
39950 Pb ch=4 v=8192
39950 Pb ch=4 v=8192
39950 Pb ch=4 v=8192
39954 Pb ch=4 v=8064
39954 Pb ch=4 v=8064
39954 Pb ch=4 v=8064
39959 Pb ch=4 v=8064
39959 Pb ch=4 v=8064
39959 Pb ch=4 v=8064
39964 Pb ch=4 v=8064
39964 Pb ch=4 v=8064
39964 Pb ch=4 v=8064
39968 Pb ch=4 v=8064
39968 Pb ch=4 v=7936
39968 Pb ch=4 v=8064
39973 Pb ch=4 v=8064
39973 Pb ch=4 v=7936
39973 Pb ch=4 v=7936
39978 Pb ch=4 v=8064
39978 Pb ch=4 v=7936
39978 Pb ch=4 v=7936
39982 Pb ch=4 v=8064
39982 Pb ch=4 v=7936
39982 Pb ch=4 v=7936
39987 Pb ch=4 v=8064
39987 Pb ch=4 v=7936
39987 Pb ch=4 v=7936
39992 Pb ch=4 v=8064
39992 Pb ch=4 v=7936
39992 Pb ch=4 v=7936
39997 Pb ch=4 v=8064
39997 Pb ch=4 v=7936
39997 Pb ch=4 v=8064
40001 Pb ch=4 v=8064
40001 Pb ch=4 v=8064
40001 Pb ch=4 v=8064
40006 Pb ch=4 v=8064
40006 Pb ch=4 v=8064
40006 Pb ch=4 v=8064
40011 Pb ch=4 v=8064
40011 Pb ch=4 v=8064
40011 Pb ch=4 v=8064
40015 Pb ch=4 v=8192
40015 Pb ch=4 v=8192
40015 Pb ch=4 v=8192
40020 Pb ch=4 v=8192
40020 Pb ch=4 v=8192
40020 Pb ch=4 v=8192
40025 Pb ch=4 v=8192
40025 Pb ch=4 v=8192
40025 Pb ch=4 v=8192
40029 Pb ch=4 v=8320
40029 Pb ch=4 v=8320
40029 Pb ch=4 v=8320
40034 Pb ch=4 v=8320
40034 Pb ch=4 v=8320
40034 Pb ch=4 v=8320
40039 Pb ch=4 v=8320
40039 Pb ch=4 v=8320
40039 Pb ch=4 v=8320
40043 Pb ch=4 v=8320
40043 Pb ch=4 v=8320
40043 Pb ch=4 v=8320
40048 Pb ch=4 v=8320
40048 Pb ch=4 v=8320
40048 Pb ch=4 v=8320
40053 Pb ch=4 v=8320
40053 Pb ch=4 v=8320
40053 Pb ch=4 v=8320
40058 Pb ch=4 v=8320
40058 Pb ch=4 v=8320
40058 Pb ch=4 v=8320
40062 Pb ch=4 v=8320
40062 Pb ch=4 v=8320
40062 Pb ch=4 v=8320
40067 Pb ch=4 v=8320
40067 Pb ch=4 v=8320
40067 Pb ch=4 v=8320
40072 Pb ch=4 v=8320
40072 Pb ch=4 v=8320
40072 Pb ch=4 v=8320
40076 Pb ch=4 v=8320
40076 Pb ch=4 v=8320
40076 Pb ch=4 v=8320
40081 Pb ch=4 v=8320
40081 Pb ch=4 v=8320
40081 Pb ch=4 v=8320
40086 Pb ch=4 v=8320
40086 Pb ch=4 v=8320
40086 Pb ch=4 v=8320
40090 Pb ch=4 v=8192
40090 Pb ch=4 v=8192
40090 Pb ch=4 v=8192
40095 Pb ch=4 v=8192
40095 Pb ch=4 v=8192
40095 Pb ch=4 v=8192
40100 Pb ch=4 v=8192
40100 Pb ch=4 v=8192
40100 Pb ch=4 v=8192
40104 Pb ch=4 v=8064
40104 Pb ch=4 v=8064
40104 Pb ch=4 v=8064
40109 Pb ch=4 v=8064
40109 Pb ch=4 v=8064
40109 Pb ch=4 v=8064
40114 Pb ch=4 v=8064
40114 Pb ch=4 v=8064
40114 Pb ch=4 v=8064
40118 Pb ch=4 v=8064
40118 Pb ch=4 v=8064
40118 Pb ch=4 v=8064
40123 Pb ch=4 v=8064
40123 Pb ch=4 v=8064
40123 Pb ch=4 v=8064
40128 Pb ch=4 v=8064
40128 Pb ch=4 v=8064
40128 Pb ch=4 v=8064
40132 Pb ch=4 v=8064
40132 Pb ch=4 v=8064
40132 Pb ch=4 v=8064
40137 Pb ch=4 v=8064
40137 Pb ch=4 v=8064
40137 Pb ch=4 v=8064
40142 Pb ch=4 v=8064
40142 Pb ch=4 v=8064
40142 Pb ch=4 v=8064
40147 Pb ch=4 v=8064
40147 Pb ch=4 v=8064
40147 Pb ch=4 v=8064
40151 Pb ch=4 v=8064
40151 Pb ch=4 v=8064
40151 Pb ch=4 v=8064
40156 Pb ch=4 v=8064
40156 Pb ch=4 v=8064
40156 Pb ch=4 v=8064
40161 Pb ch=4 v=8064
40161 Pb ch=4 v=8064
40161 Pb ch=4 v=8064
40165 Pb ch=4 v=8192
40165 Pb ch=4 v=8192
40165 Pb ch=4 v=8192
40170 Pb ch=4 v=8192
40170 Pb ch=4 v=8192
40170 Pb ch=4 v=8192
40175 Pb ch=4 v=8192
40175 Pb ch=4 v=8192
40175 Pb ch=4 v=8192
40179 Pb ch=4 v=8192
40179 Pb ch=4 v=8320
40179 Pb ch=4 v=8320
40184 Pb ch=4 v=8320
40184 Pb ch=4 v=8320
40184 Pb ch=4 v=8320
40189 Pb ch=4 v=8320
40189 Pb ch=4 v=8320
40189 Pb ch=4 v=8320
40193 Pb ch=4 v=8320
40193 Pb ch=4 v=8320
40193 Pb ch=4 v=8448
40198 Pb ch=4 v=8320
40198 Pb ch=4 v=8448
40198 Pb ch=4 v=8448
40203 Pb ch=4 v=8320
40203 Pb ch=4 v=8448
40203 Pb ch=4 v=8448
40208 Pb ch=4 v=8320
40208 Pb ch=4 v=8448
40208 Pb ch=4 v=8448
40212 Pb ch=4 v=8320
40212 Pb ch=4 v=8448
40212 Pb ch=4 v=8448
40217 Pb ch=4 v=8320
40217 Pb ch=4 v=8448
40217 Pb ch=4 v=8448
40222 Pb ch=4 v=8320
40222 Pb ch=4 v=8320
40222 Pb ch=4 v=8448
40226 Pb ch=4 v=8320
40226 Pb ch=4 v=8320
40226 Pb ch=4 v=8320
40231 Pb ch=4 v=8320
40231 Pb ch=4 v=8320
40231 Pb ch=4 v=8320
40236 Pb ch=4 v=8192
40236 Pb ch=4 v=8320
40236 Pb ch=4 v=8320
40240 Pb ch=4 v=8192
40240 Pb ch=4 v=8192
40240 Pb ch=4 v=8192
40245 Pb ch=4 v=8192
40245 Pb ch=4 v=8192
40245 Pb ch=4 v=8192
40250 Pb ch=4 v=8192
40250 Pb ch=4 v=8192
40250 Pb ch=4 v=8192
40254 Pb ch=4 v=8192
40254 Pb ch=4 v=8064
40254 Pb ch=4 v=8064
40259 Pb ch=4 v=8064
40259 Pb ch=4 v=8064
40259 Pb ch=4 v=8064
40264 Pb ch=4 v=8064
40264 Pb ch=4 v=8064
40264 Pb ch=4 v=8064
40268 Pb ch=4 v=8064
40268 Pb ch=4 v=8064
40268 Pb ch=4 v=7936
40273 Pb ch=4 v=8064
40273 Pb ch=4 v=7936
40273 Pb ch=4 v=7936
40278 Pb ch=4 v=8064
40278 Pb ch=4 v=7936
40278 Pb ch=4 v=7936
40282 Pb ch=4 v=8064
40282 Pb ch=4 v=7936
40282 Pb ch=4 v=7936
40287 Pb ch=4 v=8064
40287 Pb ch=4 v=7936
40287 Pb ch=4 v=7936
40292 Pb ch=4 v=8064
40292 Pb ch=4 v=7936
40292 Pb ch=4 v=7936
40297 Pb ch=4 v=8064
40297 Pb ch=4 v=8064
40297 Pb ch=4 v=7936
40301 Pb ch=4 v=8064
40301 Pb ch=4 v=8064
40301 Pb ch=4 v=8064
40306 Pb ch=4 v=8064
40306 Pb ch=4 v=8064
40306 Pb ch=4 v=8064
40311 Pb ch=4 v=8192
40311 Pb ch=4 v=8064
40311 Pb ch=4 v=8064
40315 Pb ch=4 v=8192
40315 Pb ch=4 v=8192
40315 Pb ch=4 v=8192
40320 Off ch=4 n=71 v=80
40320 Pb ch=4 v=8192
40320 Off ch=4 n=67 v=80
40320 Pb ch=4 v=8192
40320 Off ch=4 n=62 v=80
40320 Pb ch=4 v=8192
40560 On ch=3 n=64 v=95
40920 Off ch=3 n=64 v=80
40920 On ch=3 n=62 v=95
41280 Off ch=3 n=62 v=80
41520 On ch=3 n=62 v=95
41880 Off ch=3 n=62 v=80
41880 On ch=3 n=64 v=95
42120 Off ch=3 n=64 v=80
42120 On ch=3 n=62 v=95
42240 Off ch=3 n=62 v=80
42480 On ch=3 n=67 v=95
42840 Off ch=3 n=67 v=80
42840 On ch=3 n=62 v=95
43200 Off ch=3 n=62 v=80
43200 On ch=4 n=62 v=95
43308 Pb ch=4 v=8832
43320 Off ch=4 n=62 v=80
43320 Pb ch=4 v=8192
43320 On ch=3 n=64 v=76
43560 Off ch=3 n=64 v=80
43560 On ch=3 n=67 v=95
43860 Off ch=3 n=67 v=80
43860 On ch=4 n=64 v=95
43914 Pb ch=4 v=7552
43920 Off ch=4 n=64 v=80
43920 Pb ch=4 v=8192
43920 On ch=3 n=62 v=76
44160 Off ch=3 n=62 v=80
44400 On ch=3 n=64 v=95
44760 Off ch=3 n=64 v=80
44760 On ch=3 n=62 v=95
45120 Off ch=3 n=62 v=80
45360 On ch=3 n=62 v=95
45720 Off ch=3 n=62 v=80
45720 On ch=3 n=64 v=95
45960 Off ch=3 n=64 v=80
45960 On ch=3 n=62 v=95
46080 Off ch=3 n=62 v=80
46320 On ch=3 n=67 v=95
46680 Off ch=3 n=67 v=80
46680 On ch=3 n=62 v=95
47040 Off ch=3 n=62 v=80
47040 On ch=3 n=67 v=95
47400 Off ch=3 n=67 v=80
47400 On ch=3 n=62 v=95
47880 Off ch=3 n=62 v=80
47880 On ch=3 n=67 v=95
48000 Off ch=3 n=67 v=80
48240 On ch=3 n=64 v=95
48600 Off ch=3 n=64 v=80
48600 On ch=3 n=62 v=95
48960 Off ch=3 n=62 v=80
49200 On ch=3 n=62 v=95
49560 Off ch=3 n=62 v=80
49560 On ch=3 n=64 v=95
49800 Off ch=3 n=64 v=80
49800 On ch=3 n=62 v=95
49920 Off ch=3 n=62 v=80
50160 On ch=3 n=67 v=95
50520 Off ch=3 n=67 v=80
50520 On ch=3 n=62 v=95
50880 Off ch=3 n=62 v=80
50880 On ch=4 n=62 v=95
50988 Pb ch=4 v=8832
51000 Off ch=4 n=62 v=80
51000 Pb ch=4 v=8192
51000 On ch=3 n=64 v=76
51240 Off ch=3 n=64 v=80
51240 On ch=3 n=67 v=95
51540 Off ch=3 n=67 v=80
51540 On ch=4 n=64 v=95
51594 Pb ch=4 v=7552
51600 Off ch=4 n=64 v=80
51600 Pb ch=4 v=8192
51600 On ch=3 n=62 v=76
51840 Off ch=3 n=62 v=80
52080 On ch=3 n=64 v=95
52440 Off ch=3 n=64 v=80
52440 On ch=3 n=62 v=95
52800 Off ch=3 n=62 v=80
53040 On ch=3 n=62 v=95
53400 Off ch=3 n=62 v=80
53400 On ch=3 n=64 v=95
53640 Off ch=3 n=64 v=80
53640 On ch=3 n=62 v=95
53760 Off ch=3 n=62 v=80
54000 On ch=3 n=67 v=95
54360 Off ch=3 n=67 v=80
54360 On ch=3 n=62 v=95
54720 Off ch=3 n=62 v=80
54720 On ch=3 n=45 v=95
54840 Off ch=3 n=45 v=80
54840 On ch=3 n=47 v=76
54960 Off ch=3 n=47 v=80
54960 On ch=3 n=50 v=95
55080 Off ch=3 n=50 v=80
55080 On ch=3 n=55 v=95
55200 Off ch=3 n=55 v=80
55200 On ch=3 n=52 v=95
55320 Off ch=3 n=52 v=80
55320 On ch=3 n=50 v=76
55440 Off ch=3 n=50 v=80
55440 On ch=3 n=45 v=95
55560 Off ch=3 n=45 v=80
55560 On ch=3 n=47 v=76
55680 Off ch=3 n=47 v=80
55680 On ch=3 n=57 v=95
55680 On ch=3 n=50 v=95
55920 Off ch=3 n=57 v=80
55920 Off ch=3 n=50 v=80
55920 On ch=3 n=57 v=95
55920 On ch=3 n=50 v=95
56160 Off ch=3 n=57 v=80
56160 Off ch=3 n=50 v=80
56160 On ch=3 n=59 v=95
56160 On ch=3 n=50 v=95
56280 Off ch=3 n=59 v=80
56280 Off ch=3 n=50 v=80
56280 On ch=3 n=57 v=95
56280 On ch=3 n=50 v=95
56640 Off ch=3 n=57 v=80
56640 Off ch=3 n=50 v=80
56640 On ch=3 n=55 v=95
56640 On ch=3 n=48 v=95
56880 Off ch=3 n=55 v=80
56880 Off ch=3 n=48 v=80
56880 On ch=3 n=55 v=95
56880 On ch=3 n=48 v=95
57120 Off ch=3 n=55 v=80
57120 Off ch=3 n=48 v=80
57120 On ch=3 n=57 v=95
57120 On ch=3 n=48 v=95
57240 Off ch=3 n=57 v=80
57240 Off ch=3 n=48 v=80
57240 On ch=3 n=55 v=95
57240 On ch=3 n=48 v=95
57480 Off ch=3 n=55 v=80
57480 Off ch=3 n=48 v=80
57480 On ch=3 n=55 v=95
57480 On ch=3 n=48 v=95
57600 Off ch=3 n=55 v=80
57600 Off ch=3 n=48 v=80
57600 On ch=3 n=50 v=95
57600 On ch=3 n=43 v=95
57840 Off ch=3 n=50 v=80
57840 Off ch=3 n=43 v=80
57840 On ch=3 n=50 v=95
57840 On ch=3 n=43 v=95
58080 Off ch=3 n=50 v=80
58080 Off ch=3 n=43 v=80
58080 On ch=3 n=44 v=95
58080 On ch=3 n=39 v=95
58100 Off ch=3 n=44 v=80
58100 Off ch=3 n=39 v=80
58200 On ch=3 n=44 v=95
58200 On ch=3 n=39 v=95
58220 Off ch=3 n=44 v=80
58220 Off ch=3 n=39 v=80
58320 On ch=3 n=52 v=95
58320 On ch=3 n=43 v=95
58560 Off ch=3 n=52 v=80
58560 Off ch=3 n=43 v=80
58560 On ch=3 n=44 v=95
58560 On ch=3 n=39 v=95
58580 Off ch=3 n=44 v=80
58580 Off ch=3 n=39 v=80
58800 On ch=3 n=50 v=95
58800 On ch=3 n=43 v=95
59040 Off ch=3 n=50 v=80
59040 Off ch=3 n=43 v=80
59040 On ch=3 n=44 v=95
59040 On ch=3 n=39 v=95
59060 Off ch=3 n=44 v=80
59060 Off ch=3 n=39 v=80
59160 On ch=3 n=62 v=95
59160 On ch=3 n=60 v=95
59160 On ch=3 n=55 v=95
59160 On ch=3 n=48 v=95
59520 Off ch=3 n=62 v=80
59520 Off ch=3 n=60 v=80
59520 Off ch=3 n=55 v=80
59520 Off ch=3 n=48 v=80
59520 On ch=3 n=57 v=95
59520 On ch=3 n=50 v=95
59760 Off ch=3 n=57 v=80
59760 Off ch=3 n=50 v=80
59760 On ch=3 n=57 v=95
59760 On ch=3 n=50 v=95
60000 Off ch=3 n=57 v=80
60000 Off ch=3 n=50 v=80
60000 On ch=3 n=59 v=95
60000 On ch=3 n=50 v=95
60120 Off ch=3 n=59 v=80
60120 Off ch=3 n=50 v=80
60120 On ch=3 n=57 v=95
60120 On ch=3 n=50 v=95
60480 Off ch=3 n=57 v=80
60480 Off ch=3 n=50 v=80
60480 On ch=3 n=55 v=95
60480 On ch=3 n=48 v=95
60720 Off ch=3 n=55 v=80
60720 Off ch=3 n=48 v=80
60720 On ch=3 n=55 v=95
60720 On ch=3 n=48 v=95
60960 Off ch=3 n=55 v=80
60960 Off ch=3 n=48 v=80
60960 On ch=3 n=57 v=95
60960 On ch=3 n=48 v=95
61080 Off ch=3 n=57 v=80
61080 Off ch=3 n=48 v=80
61080 On ch=3 n=55 v=95
61080 On ch=3 n=48 v=95
61320 Off ch=3 n=55 v=80
61320 Off ch=3 n=48 v=80
61320 On ch=3 n=55 v=95
61320 On ch=3 n=48 v=95
61440 Off ch=3 n=55 v=80
61440 Off ch=3 n=48 v=80
61440 On ch=3 n=44 v=95
61440 On ch=3 n=39 v=95
61460 Off ch=3 n=44 v=80
61460 Off ch=3 n=39 v=80
61680 On ch=3 n=50 v=95
61680 On ch=3 n=43 v=95
61920 Off ch=3 n=50 v=80
61920 Off ch=3 n=43 v=80
61920 On ch=3 n=44 v=95
61920 On ch=3 n=39 v=95
61940 Off ch=3 n=44 v=80
61940 Off ch=3 n=39 v=80
62040 On ch=3 n=50 v=95
62040 On ch=3 n=43 v=95
62160 Off ch=3 n=50 v=80
62160 Off ch=3 n=43 v=80
62160 On ch=3 n=50 v=95
62160 On ch=3 n=43 v=95
62400 Off ch=3 n=50 v=80
62400 Off ch=3 n=43 v=80
62640 On ch=3 n=55 v=95
62640 On ch=3 n=48 v=95
62880 Off ch=3 n=55 v=80
62880 Off ch=3 n=48 v=80
63120 On ch=4 n=55 v=95
63120 On ch=4 n=48 v=95
63336 Pb ch=4 v=8832
63336 Pb ch=4 v=8832
63360 Off ch=4 n=55 v=80
63360 Pb ch=4 v=8192
63360 Off ch=4 n=48 v=80
63360 Pb ch=4 v=8192
63360 On ch=3 n=57 v=76
63360 On ch=3 n=50 v=76
63600 Off ch=3 n=57 v=80
63600 Off ch=3 n=50 v=80
63600 On ch=3 n=57 v=95
63600 On ch=3 n=50 v=95
63840 Off ch=3 n=57 v=80
63840 Off ch=3 n=50 v=80
63840 On ch=3 n=59 v=95
63840 On ch=3 n=50 v=95
63960 Off ch=3 n=59 v=80
63960 Off ch=3 n=50 v=80
63960 On ch=3 n=57 v=95
63960 On ch=3 n=50 v=95
64320 Off ch=3 n=57 v=80
64320 Off ch=3 n=50 v=80
64320 On ch=3 n=55 v=95
64320 On ch=3 n=48 v=95
64560 Off ch=3 n=55 v=80
64560 Off ch=3 n=48 v=80
64560 On ch=3 n=55 v=95
64560 On ch=3 n=48 v=95
64800 Off ch=3 n=55 v=80
64800 Off ch=3 n=48 v=80
64800 On ch=3 n=57 v=95
64800 On ch=3 n=48 v=95
64920 Off ch=3 n=57 v=80
64920 Off ch=3 n=48 v=80
64920 On ch=3 n=55 v=95
64920 On ch=3 n=48 v=95
65160 Off ch=3 n=55 v=80
65160 Off ch=3 n=48 v=80
65160 On ch=3 n=55 v=95
65160 On ch=3 n=48 v=95
65280 Off ch=3 n=55 v=80
65280 Off ch=3 n=48 v=80
65280 On ch=3 n=50 v=95
65280 On ch=3 n=43 v=95
65520 Off ch=3 n=50 v=80
65520 Off ch=3 n=43 v=80
65520 On ch=3 n=50 v=95
65520 On ch=3 n=43 v=95
65760 Off ch=3 n=50 v=80
65760 Off ch=3 n=43 v=80
65760 On ch=3 n=44 v=95
65760 On ch=3 n=39 v=95
65780 Off ch=3 n=44 v=80
65780 Off ch=3 n=39 v=80
65880 On ch=3 n=44 v=95
65880 On ch=3 n=39 v=95
65900 Off ch=3 n=44 v=80
65900 Off ch=3 n=39 v=80
66000 On ch=3 n=52 v=95
66000 On ch=3 n=43 v=95
66240 Off ch=3 n=52 v=80
66240 Off ch=3 n=43 v=80
66240 On ch=3 n=44 v=95
66240 On ch=3 n=39 v=95
66260 Off ch=3 n=44 v=80
66260 Off ch=3 n=39 v=80
66480 On ch=3 n=50 v=95
66480 On ch=3 n=43 v=95
66720 Off ch=3 n=50 v=80
66720 Off ch=3 n=43 v=80
66720 On ch=3 n=49 v=95
66720 On ch=3 n=44 v=95
66740 Off ch=3 n=49 v=80
66740 Off ch=3 n=44 v=80
66840 On ch=3 n=62 v=95
66840 On ch=3 n=60 v=95
66840 On ch=3 n=55 v=95
66840 On ch=3 n=48 v=95
67200 Off ch=3 n=62 v=80
67200 Off ch=3 n=60 v=80
67200 Off ch=3 n=55 v=80
67200 Off ch=3 n=48 v=80
67200 On ch=3 n=57 v=95
67200 On ch=3 n=50 v=95
67440 Off ch=3 n=57 v=80
67440 Off ch=3 n=50 v=80
67440 On ch=3 n=57 v=95
67440 On ch=3 n=50 v=95
67680 Off ch=3 n=57 v=80
67680 Off ch=3 n=50 v=80
67680 On ch=3 n=59 v=95
67680 On ch=3 n=50 v=95
67800 Off ch=3 n=59 v=80
67800 Off ch=3 n=50 v=80
67800 On ch=3 n=57 v=95
67800 On ch=3 n=50 v=95
68160 Off ch=3 n=57 v=80
68160 Off ch=3 n=50 v=80
68160 On ch=3 n=55 v=95
68160 On ch=3 n=48 v=95
68400 Off ch=3 n=55 v=80
68400 Off ch=3 n=48 v=80
68400 On ch=3 n=55 v=95
68400 On ch=3 n=48 v=95
68640 Off ch=3 n=55 v=80
68640 Off ch=3 n=48 v=80
68640 On ch=3 n=57 v=95
68640 On ch=3 n=48 v=95
68760 Off ch=3 n=57 v=80
68760 Off ch=3 n=48 v=80
68760 On ch=3 n=55 v=95
68760 On ch=3 n=48 v=95
69000 Off ch=3 n=55 v=80
69000 Off ch=3 n=48 v=80
69000 On ch=3 n=55 v=95
69000 On ch=3 n=48 v=95
69120 Off ch=3 n=55 v=80
69120 Off ch=3 n=48 v=80
69120 On ch=3 n=50 v=95
69120 On ch=3 n=43 v=95
69360 Off ch=3 n=50 v=80
69360 Off ch=3 n=43 v=80
69360 On ch=3 n=50 v=95
69360 On ch=3 n=43 v=95
69600 Off ch=3 n=50 v=80
69600 Off ch=3 n=43 v=80
69600 On ch=3 n=44 v=95
69600 On ch=3 n=39 v=95
69620 Off ch=3 n=44 v=80
69620 Off ch=3 n=39 v=80
69720 On ch=3 n=50 v=95
69840 Off ch=3 n=50 v=80
69840 On ch=3 n=52 v=95
69840 On ch=3 n=43 v=95
70080 Off ch=3 n=52 v=80
70080 Off ch=3 n=43 v=80
70080 On ch=4 n=79 v=95
70080 Pb ch=4 v=8192
70112 Pb ch=4 v=8320
70128 Pb ch=4 v=8448
70144 Pb ch=4 v=8704
70160 Pb ch=4 v=8960
70176 Pb ch=4 v=9344
70192 Pb ch=4 v=9472
70208 Pb ch=4 v=9600
70336 Pb ch=4 v=9344
70352 Pb ch=4 v=9088
70368 Pb ch=4 v=8832
70384 Pb ch=4 v=8576
70400 Pb ch=4 v=8320
70432 Pb ch=4 v=8192
70560 Off ch=4 n=79 v=80
70560 Pb ch=4 v=8192
70560 On ch=3 n=63 v=95
70580 Off ch=3 n=63 v=80
70620 On ch=4 n=79 v=95
70620 Pb ch=4 v=8192
70625 Pb ch=4 v=8192
70629 Pb ch=4 v=8192
70634 Pb ch=4 v=8192
70639 Pb ch=4 v=8192
70643 Pb ch=4 v=8192
70648 Pb ch=4 v=8192
70653 Pb ch=4 v=8192
70658 Pb ch=4 v=8192
70662 Pb ch=4 v=8192
70667 Pb ch=4 v=8192
70672 Pb ch=4 v=8192
70676 Pb ch=4 v=8192
70681 Pb ch=4 v=8192
70686 Pb ch=4 v=8192
70690 Pb ch=4 v=8192
70695 Pb ch=4 v=8192
70700 Pb ch=4 v=8192
70704 Pb ch=4 v=8192
70709 Pb ch=4 v=8192
70714 Pb ch=4 v=8192
70718 Pb ch=4 v=8192
70723 Pb ch=4 v=8192
70728 Pb ch=4 v=8192
70732 Pb ch=4 v=8192
70737 Pb ch=4 v=8192
70742 Pb ch=4 v=8192
70747 Pb ch=4 v=8192
70751 Pb ch=4 v=8192
70756 Pb ch=4 v=8192
70761 Pb ch=4 v=8192
70765 Pb ch=4 v=8192
70770 Pb ch=4 v=8192
70775 Pb ch=4 v=8192
70779 Pb ch=4 v=8320
70784 Pb ch=4 v=8320
70789 Pb ch=4 v=8320
70793 Pb ch=4 v=8320
70798 Pb ch=4 v=8448
70803 Pb ch=4 v=8448
70808 Pb ch=4 v=8448
70812 Pb ch=4 v=8448
70817 Pb ch=4 v=8448
70822 Pb ch=4 v=8320
70826 Pb ch=4 v=8320
70831 Pb ch=4 v=8320
70836 Pb ch=4 v=8320
70840 Pb ch=4 v=8192
70845 Pb ch=4 v=8192
70850 Pb ch=4 v=8192
70854 Pb ch=4 v=8064
70859 Pb ch=4 v=8064
70864 Pb ch=4 v=8064
70868 Pb ch=4 v=8064
70873 Pb ch=4 v=7936
70878 Pb ch=4 v=7936
70882 Pb ch=4 v=7936
70887 Pb ch=4 v=7936
70892 Pb ch=4 v=7936
70897 Pb ch=4 v=8064
70901 Pb ch=4 v=8064
70906 Pb ch=4 v=8064
70911 Pb ch=4 v=8064
70915 Pb ch=4 v=8192
70920 Pb ch=4 v=8192
70925 Pb ch=4 v=8192
70929 Pb ch=4 v=8320
70934 Pb ch=4 v=8320
70939 Pb ch=4 v=8320
70943 Pb ch=4 v=8320
70948 Pb ch=4 v=8320
70953 Pb ch=4 v=8448
70958 Pb ch=4 v=8448
70962 Pb ch=4 v=8448
70967 Pb ch=4 v=8320
70972 Pb ch=4 v=8320
70976 Pb ch=4 v=8320
70981 Pb ch=4 v=8320
70986 Pb ch=4 v=8320
70990 Pb ch=4 v=8192
70995 Pb ch=4 v=8192
71000 Pb ch=4 v=8192
71004 Pb ch=4 v=8064
71009 Pb ch=4 v=8064
71014 Pb ch=4 v=8064
71018 Pb ch=4 v=8064
71023 Pb ch=4 v=8064
71028 Pb ch=4 v=7936
71032 Pb ch=4 v=7936
71037 Pb ch=4 v=7936
71040 Off ch=4 n=79 v=80
71040 Pb ch=4 v=8192
71040 On ch=3 n=78 v=95
71160 Off ch=3 n=78 v=80
71160 On ch=3 n=74 v=95
71280 Off ch=3 n=74 v=80
71280 On ch=3 n=69 v=95
71520 Off ch=3 n=69 v=80
71520 On ch=4 n=74 v=95
71520 Pb ch=4 v=8192
71525 Pb ch=4 v=8192
71529 Pb ch=4 v=8192
71534 Pb ch=4 v=8192
71539 Pb ch=4 v=8192
71543 Pb ch=4 v=8192
71548 Pb ch=4 v=8192
71553 Pb ch=4 v=8192
71558 Pb ch=4 v=8192
71562 Pb ch=4 v=8192
71567 Pb ch=4 v=8192
71572 Pb ch=4 v=8192
71576 Pb ch=4 v=8192
71581 Pb ch=4 v=8192
71586 Pb ch=4 v=8192
71590 Pb ch=4 v=8192
71595 Pb ch=4 v=8192
71600 Pb ch=4 v=8192
71604 Pb ch=4 v=8192
71609 Pb ch=4 v=8192
71614 Pb ch=4 v=8192
71618 Pb ch=4 v=8192
71623 Pb ch=4 v=8192
71628 Pb ch=4 v=8192
71632 Pb ch=4 v=8192
71637 Pb ch=4 v=8192
71642 Pb ch=4 v=8192
71647 Pb ch=4 v=8192
71651 Pb ch=4 v=8192
71656 Pb ch=4 v=8192
71661 Pb ch=4 v=8192
71665 Pb ch=4 v=8192
71670 Pb ch=4 v=8192
71675 Pb ch=4 v=8192
71679 Pb ch=4 v=8320
71684 Pb ch=4 v=8320
71689 Pb ch=4 v=8320
71693 Pb ch=4 v=8320
71698 Pb ch=4 v=8320
71703 Pb ch=4 v=8320
71708 Pb ch=4 v=8320
71712 Pb ch=4 v=8320
71717 Pb ch=4 v=8320
71722 Pb ch=4 v=8320
71726 Pb ch=4 v=8320
71731 Pb ch=4 v=8320
71736 Pb ch=4 v=8320
71740 Pb ch=4 v=8192
71745 Pb ch=4 v=8192
71750 Pb ch=4 v=8192
71754 Pb ch=4 v=8064
71759 Pb ch=4 v=8064
71764 Pb ch=4 v=8064
71768 Pb ch=4 v=8064
71773 Pb ch=4 v=8064
71778 Pb ch=4 v=8064
71782 Pb ch=4 v=8064
71787 Pb ch=4 v=8064
71792 Pb ch=4 v=8064
71797 Pb ch=4 v=8064
71801 Pb ch=4 v=8064
71806 Pb ch=4 v=8064
71811 Pb ch=4 v=8064
71815 Pb ch=4 v=8192
71820 Pb ch=4 v=8192
71825 Pb ch=4 v=8192
71829 Pb ch=4 v=8320
71834 Pb ch=4 v=8320
71839 Pb ch=4 v=8320
71843 Pb ch=4 v=8448
71848 Pb ch=4 v=8448
71853 Pb ch=4 v=8448
71858 Pb ch=4 v=8448
71862 Pb ch=4 v=8448
71867 Pb ch=4 v=8448
71872 Pb ch=4 v=8448
71876 Pb ch=4 v=8320
71881 Pb ch=4 v=8320
71886 Pb ch=4 v=8320
71890 Pb ch=4 v=8192
71895 Pb ch=4 v=8192
71900 Pb ch=4 v=8192
71904 Pb ch=4 v=8064
71909 Pb ch=4 v=8064
71914 Pb ch=4 v=8064
71918 Pb ch=4 v=7936
71923 Pb ch=4 v=7936
71928 Pb ch=4 v=7936
71932 Pb ch=4 v=7936
71937 Pb ch=4 v=7936
71942 Pb ch=4 v=7936
71947 Pb ch=4 v=7936
71951 Pb ch=4 v=8064
71956 Pb ch=4 v=8064
71961 Pb ch=4 v=8064
71965 Pb ch=4 v=8192
71970 Pb ch=4 v=8192
71975 Pb ch=4 v=8192
71979 Pb ch=4 v=8320
71984 Pb ch=4 v=8320
71989 Pb ch=4 v=8320
71993 Pb ch=4 v=8448
71998 Pb ch=4 v=8448
72003 Pb ch=4 v=8448
72008 Pb ch=4 v=8448
72012 Pb ch=4 v=8448
72017 Pb ch=4 v=8448
72022 Pb ch=4 v=8448
72026 Pb ch=4 v=8320
72031 Pb ch=4 v=8320
72036 Pb ch=4 v=8320
72040 Pb ch=4 v=8192
72045 Pb ch=4 v=8192
72050 Pb ch=4 v=8192
72054 Pb ch=4 v=8064
72059 Pb ch=4 v=8064
72064 Pb ch=4 v=8064
72068 Pb ch=4 v=7936
72073 Pb ch=4 v=7936
72078 Pb ch=4 v=7936
72082 Pb ch=4 v=7936
72087 Pb ch=4 v=7936
72092 Pb ch=4 v=7936
72097 Pb ch=4 v=7936
72101 Pb ch=4 v=8064
72106 Pb ch=4 v=8064
72111 Pb ch=4 v=8064
72115 Pb ch=4 v=8192
72120 Off ch=4 n=74 v=80
72120 Pb ch=4 v=8192
72120 On ch=3 n=69 v=95
72240 Off ch=3 n=69 v=80
72240 On ch=3 n=72 v=95
72360 Off ch=3 n=72 v=80
72360 On ch=3 n=70 v=95
72480 Off ch=3 n=70 v=80
72480 On ch=3 n=69 v=95
72600 Off ch=3 n=69 v=80
72600 On ch=3 n=67 v=95
72720 Off ch=3 n=67 v=80
72720 On ch=3 n=69 v=95
72840 Off ch=3 n=69 v=80
72840 On ch=3 n=64 v=95
72960 Off ch=3 n=64 v=80
72960 On ch=4 n=67 v=95
72960 Pb ch=4 v=8192
72965 Pb ch=4 v=8192
72969 Pb ch=4 v=8192
72974 Pb ch=4 v=8192
72979 Pb ch=4 v=8192
72983 Pb ch=4 v=8192
72988 Pb ch=4 v=8192
72993 Pb ch=4 v=8192
72998 Pb ch=4 v=8192
73002 Pb ch=4 v=8192
73007 Pb ch=4 v=8192
73012 Pb ch=4 v=8192
73016 Pb ch=4 v=8192
73021 Pb ch=4 v=8192
73026 Pb ch=4 v=8192
73030 Pb ch=4 v=8192
73035 Pb ch=4 v=8192
73040 Pb ch=4 v=8192
73044 Pb ch=4 v=8192
73049 Pb ch=4 v=8192
73054 Pb ch=4 v=8192
73058 Pb ch=4 v=8192
73063 Pb ch=4 v=8192
73068 Pb ch=4 v=8192
73072 Pb ch=4 v=8192
73077 Pb ch=4 v=8192
73082 Pb ch=4 v=8192
73087 Pb ch=4 v=8192
73091 Pb ch=4 v=8192
73096 Pb ch=4 v=8192
73101 Pb ch=4 v=8192
73105 Pb ch=4 v=8192
73110 Pb ch=4 v=8192
73115 Pb ch=4 v=8192
73119 Pb ch=4 v=8320
73124 Pb ch=4 v=8320
73129 Pb ch=4 v=8320
73133 Pb ch=4 v=8320
73138 Pb ch=4 v=8320
73143 Pb ch=4 v=8320
73148 Pb ch=4 v=8320
73152 Pb ch=4 v=8320
73157 Pb ch=4 v=8320
73162 Pb ch=4 v=8320
73166 Pb ch=4 v=8320
73171 Pb ch=4 v=8320
73176 Pb ch=4 v=8320
73180 Pb ch=4 v=8192
73185 Pb ch=4 v=8192
73190 Pb ch=4 v=8192
73194 Pb ch=4 v=8064
73199 Pb ch=4 v=8064
73204 Pb ch=4 v=8064
73208 Pb ch=4 v=8064
73213 Pb ch=4 v=8064
73218 Pb ch=4 v=8064
73222 Pb ch=4 v=8064
73227 Pb ch=4 v=8064
73232 Pb ch=4 v=8064
73237 Pb ch=4 v=8064
73241 Pb ch=4 v=8064
73246 Pb ch=4 v=8064
73251 Pb ch=4 v=8064
73255 Pb ch=4 v=8192
73260 Pb ch=4 v=8192
73265 Pb ch=4 v=8192
73269 Pb ch=4 v=8320
73274 Pb ch=4 v=8320
73279 Pb ch=4 v=8320
73283 Pb ch=4 v=8448
73288 Pb ch=4 v=8448
73293 Pb ch=4 v=8448
73298 Pb ch=4 v=8448
73302 Pb ch=4 v=8448
73307 Pb ch=4 v=8448
73312 Pb ch=4 v=8448
73316 Pb ch=4 v=8320
73320 Off ch=4 n=67 v=80
73320 Pb ch=4 v=8192
73320 On ch=3 n=67 v=95
73440 Off ch=3 n=67 v=80
73440 On ch=3 n=69 v=95
73560 Off ch=3 n=69 v=80
73560 On ch=3 n=64 v=95
73680 Off ch=3 n=64 v=80
73680 On ch=4 n=69 v=95
73680 Pb ch=4 v=8192
73712 Pb ch=4 v=8320
73728 Pb ch=4 v=8448
73744 Pb ch=4 v=8704
73760 Pb ch=4 v=8960
73776 Pb ch=4 v=9344
73792 Pb ch=4 v=9472
73808 Pb ch=4 v=9600
73920 Off ch=4 n=69 v=80
73920 Pb ch=4 v=8192
74280 On ch=3 n=62 v=95
74400 Off ch=3 n=62 v=80
74400 On ch=3 n=64 v=76
74520 Off ch=3 n=64 v=80
74520 On ch=3 n=67 v=95
74640 Off ch=3 n=67 v=80
74640 On ch=3 n=69 v=95
74760 Off ch=3 n=69 v=80
74760 On ch=3 n=67 v=95
74880 Off ch=3 n=67 v=80
74880 On ch=4 n=69 v=95
74880 Pb ch=4 v=8192
74912 Pb ch=4 v=8320
74928 Pb ch=4 v=8448
74944 Pb ch=4 v=8704
74960 Pb ch=4 v=8960
74976 Pb ch=4 v=9344
74992 Pb ch=4 v=9472
75008 Pb ch=4 v=9600
75120 Off ch=4 n=69 v=80
75120 Pb ch=4 v=8192
75240 On ch=3 n=62 v=95
75480 Off ch=3 n=62 v=80
75480 On ch=3 n=63 v=95
75600 Off ch=3 n=63 v=80
75600 On ch=3 n=64 v=95
75720 Off ch=3 n=64 v=80
75720 On ch=3 n=67 v=95
75840 Off ch=3 n=67 v=80
75840 On ch=3 n=69 v=95
75960 Off ch=3 n=69 v=80
75960 On ch=3 n=64 v=95
76080 Off ch=3 n=64 v=80
76080 On ch=3 n=67 v=95
76320 Off ch=3 n=67 v=80
76320 On ch=4 n=69 v=95
76320 Pb ch=4 v=8192
76336 Pb ch=4 v=8320
76368 Pb ch=4 v=8704
76384 Pb ch=4 v=9088
76400 Pb ch=4 v=9344
76416 Pb ch=4 v=9600
76512 Pb ch=4 v=9344
76528 Pb ch=4 v=8960
76544 Pb ch=4 v=8704
76560 Pb ch=4 v=8320
76576 Pb ch=4 v=8192
76680 Off ch=4 n=69 v=80
76680 Pb ch=4 v=8192
76680 On ch=3 n=67 v=95
76800 Off ch=3 n=67 v=80
76800 On ch=3 n=67 v=95
76920 Off ch=3 n=67 v=80
76920 On ch=4 n=64 v=95
77028 Pb ch=4 v=7552
77040 Off ch=4 n=64 v=80
77040 Pb ch=4 v=8192
77040 On ch=4 n=62 v=76
77148 Pb ch=4 v=8832
77160 Off ch=4 n=62 v=80
77160 Pb ch=4 v=8192
77160 On ch=3 n=64 v=76
77280 Off ch=3 n=64 v=80
77280 On ch=3 n=67 v=95
77400 Off ch=3 n=67 v=80
77400 On ch=3 n=62 v=95
77520 Off ch=3 n=62 v=80
77520 On ch=4 n=59 v=95
77520 Pb ch=4 v=8192
77552 Pb ch=4 v=8320
77568 Pb ch=4 v=8448
77584 Pb ch=4 v=8704
77600 Pb ch=4 v=8832
77728 Pb ch=4 v=8576
77744 Pb ch=4 v=8448
77760 Pb ch=4 v=8320
77776 Pb ch=4 v=8192
77880 Off ch=4 n=59 v=80
77880 Pb ch=4 v=8192
77880 On ch=3 n=55 v=95
78000 Off ch=3 n=55 v=80
78000 On ch=3 n=50 v=95
78360 Off ch=3 n=50 v=80
78360 Pb ch=4 v=8192
78360 On ch=4 n=50 v=95
78376 Pb ch=4 v=8064
78392 Pb ch=4 v=7936
78408 Pb ch=4 v=7808
78424 Pb ch=4 v=7552
78440 Pb ch=4 v=7424
78456 Pb ch=4 v=7168
78472 Pb ch=4 v=7040
78504 Pb ch=4 v=6912
78600 Pb ch=4 v=7040
78616 Pb ch=4 v=7168
78632 Pb ch=4 v=7296
78648 Pb ch=4 v=7424
78664 Pb ch=4 v=7680
78680 Pb ch=4 v=7808
78696 Pb ch=4 v=8064
78712 Pb ch=4 v=8192
78720 Pb ch=4 v=8192
78720 Off ch=4 n=50 v=80
78960 On ch=3 n=64 v=95
79320 Off ch=3 n=64 v=80
79320 On ch=3 n=62 v=95
79680 Off ch=3 n=62 v=80
79920 On ch=3 n=62 v=95
80280 Off ch=3 n=62 v=80
80280 On ch=3 n=64 v=95
80520 Off ch=3 n=64 v=80
80520 On ch=3 n=62 v=95
80640 Off ch=3 n=62 v=80
80880 On ch=3 n=67 v=95
81240 Off ch=3 n=67 v=80
81240 On ch=3 n=62 v=95
81600 Off ch=3 n=62 v=80
81600 On ch=4 n=62 v=95
81708 Pb ch=4 v=8832
81720 Off ch=4 n=62 v=80
81720 Pb ch=4 v=8192
81720 On ch=3 n=64 v=76
81960 Off ch=3 n=64 v=80
81960 On ch=3 n=67 v=95
82260 Off ch=3 n=67 v=80
82260 On ch=4 n=64 v=95
82314 Pb ch=4 v=7552
82320 Off ch=4 n=64 v=80
82320 Pb ch=4 v=8192
82320 On ch=3 n=62 v=76
82560 Off ch=3 n=62 v=80
82800 On ch=3 n=64 v=95
83160 Off ch=3 n=64 v=80
83160 On ch=3 n=62 v=95
83520 Off ch=3 n=62 v=80
83760 On ch=3 n=62 v=95
84120 Off ch=3 n=62 v=80
84120 On ch=3 n=64 v=95
84360 Off ch=3 n=64 v=80
84360 On ch=3 n=62 v=95
84480 Off ch=3 n=62 v=80
84720 On ch=3 n=67 v=95
85080 Off ch=3 n=67 v=80
85080 On ch=3 n=62 v=95
85440 Off ch=3 n=62 v=80
85440 On ch=3 n=67 v=95
85800 Off ch=3 n=67 v=80
85800 On ch=3 n=62 v=95
86280 Off ch=3 n=62 v=80
86280 On ch=3 n=67 v=95
86400 Off ch=3 n=67 v=80
86640 On ch=3 n=64 v=95
87000 Off ch=3 n=64 v=80
87000 On ch=3 n=62 v=95
87360 Off ch=3 n=62 v=80
87600 On ch=3 n=62 v=95
87960 Off ch=3 n=62 v=80
87960 On ch=3 n=64 v=95
88200 Off ch=3 n=64 v=80
88200 On ch=3 n=62 v=95
88320 Off ch=3 n=62 v=80
88560 On ch=3 n=67 v=95
88920 Off ch=3 n=67 v=80
88920 On ch=3 n=62 v=95
89280 Off ch=3 n=62 v=80
89280 On ch=4 n=62 v=95
89388 Pb ch=4 v=8832
89400 Off ch=4 n=62 v=80
89400 Pb ch=4 v=8192
89400 On ch=3 n=64 v=76
89640 Off ch=3 n=64 v=80
89640 On ch=3 n=67 v=95
89940 Off ch=3 n=67 v=80
89940 On ch=4 n=64 v=95
89994 Pb ch=4 v=7552
90000 Off ch=4 n=64 v=80
90000 Pb ch=4 v=8192
90000 On ch=3 n=62 v=76
90240 Off ch=3 n=62 v=80
90480 On ch=3 n=64 v=95
90840 Off ch=3 n=64 v=80
90840 On ch=3 n=62 v=95
91200 Off ch=3 n=62 v=80
91440 On ch=3 n=62 v=95
91800 Off ch=3 n=62 v=80
91800 On ch=3 n=64 v=95
92040 Off ch=3 n=64 v=80
92040 On ch=3 n=62 v=95
92160 Off ch=3 n=62 v=80
92400 On ch=3 n=67 v=95
92760 Off ch=3 n=67 v=80
92760 On ch=3 n=62 v=95
93120 Off ch=3 n=62 v=80
93120 On ch=3 n=45 v=95
93240 Off ch=3 n=45 v=80
93240 On ch=3 n=47 v=76
93360 Off ch=3 n=47 v=80
93360 On ch=3 n=50 v=95
93480 Off ch=3 n=50 v=80
93480 On ch=3 n=55 v=95
93600 Off ch=3 n=55 v=80
93600 On ch=3 n=52 v=95
93720 Off ch=3 n=52 v=80
93720 On ch=3 n=50 v=76
93840 Off ch=3 n=50 v=80
93840 On ch=3 n=45 v=95
93960 Off ch=3 n=45 v=80
93960 On ch=3 n=47 v=76
94080 Off ch=3 n=47 v=80
94080 On ch=3 n=57 v=95
94080 On ch=3 n=50 v=95
94320 Off ch=3 n=57 v=80
94320 Off ch=3 n=50 v=80
94320 On ch=3 n=57 v=95
94320 On ch=3 n=50 v=95
94560 Off ch=3 n=57 v=80
94560 Off ch=3 n=50 v=80
94560 On ch=3 n=59 v=95
94560 On ch=3 n=50 v=95
94680 Off ch=3 n=59 v=80
94680 Off ch=3 n=50 v=80
94680 On ch=3 n=57 v=95
94680 On ch=3 n=50 v=95
95040 Off ch=3 n=57 v=80
95040 Off ch=3 n=50 v=80
95040 On ch=3 n=55 v=95
95040 On ch=3 n=48 v=95
95280 Off ch=3 n=55 v=80
95280 Off ch=3 n=48 v=80
95280 On ch=3 n=55 v=95
95280 On ch=3 n=48 v=95
95520 Off ch=3 n=55 v=80
95520 Off ch=3 n=48 v=80
95520 On ch=3 n=57 v=95
95520 On ch=3 n=48 v=95
95640 Off ch=3 n=57 v=80
95640 Off ch=3 n=48 v=80
95640 On ch=3 n=55 v=95
95640 On ch=3 n=48 v=95
95880 Off ch=3 n=55 v=80
95880 Off ch=3 n=48 v=80
95880 On ch=3 n=55 v=95
95880 On ch=3 n=48 v=95
96000 Off ch=3 n=55 v=80
96000 Off ch=3 n=48 v=80
96000 On ch=3 n=50 v=95
96000 On ch=3 n=43 v=95
96240 Off ch=3 n=50 v=80
96240 Off ch=3 n=43 v=80
96240 On ch=3 n=50 v=95
96240 On ch=3 n=43 v=95
96480 Off ch=3 n=50 v=80
96480 Off ch=3 n=43 v=80
96480 On ch=3 n=44 v=95
96480 On ch=3 n=39 v=95
96500 Off ch=3 n=44 v=80
96500 Off ch=3 n=39 v=80
96600 On ch=3 n=44 v=95
96600 On ch=3 n=39 v=95
96620 Off ch=3 n=44 v=80
96620 Off ch=3 n=39 v=80
96720 On ch=3 n=52 v=95
96720 On ch=3 n=43 v=95
96960 Off ch=3 n=52 v=80
96960 Off ch=3 n=43 v=80
96960 On ch=3 n=44 v=95
96960 On ch=3 n=39 v=95
96980 Off ch=3 n=44 v=80
96980 Off ch=3 n=39 v=80
97200 On ch=3 n=50 v=95
97200 On ch=3 n=43 v=95
97440 Off ch=3 n=50 v=80
97440 Off ch=3 n=43 v=80
97440 On ch=3 n=44 v=95
97440 On ch=3 n=39 v=95
97460 Off ch=3 n=44 v=80
97460 Off ch=3 n=39 v=80
97560 On ch=3 n=62 v=95
97560 On ch=3 n=60 v=95
97560 On ch=3 n=55 v=95
97560 On ch=3 n=48 v=95
97920 Off ch=3 n=62 v=80
97920 Off ch=3 n=60 v=80
97920 Off ch=3 n=55 v=80
97920 Off ch=3 n=48 v=80
97920 On ch=3 n=57 v=95
97920 On ch=3 n=50 v=95
98160 Off ch=3 n=57 v=80
98160 Off ch=3 n=50 v=80
98160 On ch=3 n=57 v=95
98160 On ch=3 n=50 v=95
98400 Off ch=3 n=57 v=80
98400 Off ch=3 n=50 v=80
98400 On ch=3 n=59 v=95
98400 On ch=3 n=50 v=95
98520 Off ch=3 n=59 v=80
98520 Off ch=3 n=50 v=80
98520 On ch=3 n=57 v=95
98520 On ch=3 n=50 v=95
98880 Off ch=3 n=57 v=80
98880 Off ch=3 n=50 v=80
98880 On ch=3 n=55 v=95
98880 On ch=3 n=48 v=95
99120 Off ch=3 n=55 v=80
99120 Off ch=3 n=48 v=80
99120 On ch=3 n=55 v=95
99120 On ch=3 n=48 v=95
99360 Off ch=3 n=55 v=80
99360 Off ch=3 n=48 v=80
99360 On ch=3 n=57 v=95
99360 On ch=3 n=48 v=95
99480 Off ch=3 n=57 v=80
99480 Off ch=3 n=48 v=80
99480 On ch=3 n=55 v=95
99480 On ch=3 n=48 v=95
99720 Off ch=3 n=55 v=80
99720 Off ch=3 n=48 v=80
99720 On ch=3 n=55 v=95
99720 On ch=3 n=48 v=95
99840 Off ch=3 n=55 v=80
99840 Off ch=3 n=48 v=80
99840 On ch=3 n=44 v=95
99840 On ch=3 n=39 v=95
99860 Off ch=3 n=44 v=80
99860 Off ch=3 n=39 v=80
100080 On ch=3 n=50 v=95
100080 On ch=3 n=43 v=95
100320 Off ch=3 n=50 v=80
100320 Off ch=3 n=43 v=80
100320 On ch=3 n=44 v=95
100320 On ch=3 n=39 v=95
100340 Off ch=3 n=44 v=80
100340 Off ch=3 n=39 v=80
100440 On ch=3 n=50 v=95
100440 On ch=3 n=43 v=95
100560 Off ch=3 n=50 v=80
100560 Off ch=3 n=43 v=80
100560 On ch=3 n=50 v=95
100560 On ch=3 n=43 v=95
100800 Off ch=3 n=50 v=80
100800 Off ch=3 n=43 v=80
101040 On ch=3 n=55 v=95
101040 On ch=3 n=48 v=95
101280 Off ch=3 n=55 v=80
101280 Off ch=3 n=48 v=80
101520 On ch=4 n=55 v=95
101520 On ch=4 n=48 v=95
101736 Pb ch=4 v=8832
101736 Pb ch=4 v=8832
101760 Off ch=4 n=55 v=80
101760 Pb ch=4 v=8192
101760 Off ch=4 n=48 v=80
101760 Pb ch=4 v=8192
101760 On ch=3 n=57 v=76
101760 On ch=3 n=50 v=76
102000 Off ch=3 n=57 v=80
102000 Off ch=3 n=50 v=80
102000 On ch=3 n=57 v=95
102000 On ch=3 n=50 v=95
102240 Off ch=3 n=57 v=80
102240 Off ch=3 n=50 v=80
102240 On ch=3 n=59 v=95
102240 On ch=3 n=50 v=95
102360 Off ch=3 n=59 v=80
102360 Off ch=3 n=50 v=80
102360 On ch=3 n=57 v=95
102360 On ch=3 n=50 v=95
102720 Off ch=3 n=57 v=80
102720 Off ch=3 n=50 v=80
102720 On ch=3 n=55 v=95
102720 On ch=3 n=48 v=95
102960 Off ch=3 n=55 v=80
102960 Off ch=3 n=48 v=80
102960 On ch=3 n=55 v=95
102960 On ch=3 n=48 v=95
103200 Off ch=3 n=55 v=80
103200 Off ch=3 n=48 v=80
103200 On ch=3 n=57 v=95
103200 On ch=3 n=48 v=95
103320 Off ch=3 n=57 v=80
103320 Off ch=3 n=48 v=80
103320 On ch=3 n=55 v=95
103320 On ch=3 n=48 v=95
103560 Off ch=3 n=55 v=80
103560 Off ch=3 n=48 v=80
103560 On ch=3 n=55 v=95
103560 On ch=3 n=48 v=95
103680 Off ch=3 n=55 v=80
103680 Off ch=3 n=48 v=80
103680 On ch=3 n=50 v=95
103680 On ch=3 n=43 v=95
103920 Off ch=3 n=50 v=80
103920 Off ch=3 n=43 v=80
103920 On ch=3 n=50 v=95
103920 On ch=3 n=43 v=95
104160 Off ch=3 n=50 v=80
104160 Off ch=3 n=43 v=80
104160 On ch=3 n=44 v=95
104160 On ch=3 n=39 v=95
104180 Off ch=3 n=44 v=80
104180 Off ch=3 n=39 v=80
104280 On ch=3 n=44 v=95
104280 On ch=3 n=39 v=95
104300 Off ch=3 n=44 v=80
104300 Off ch=3 n=39 v=80
104400 On ch=3 n=52 v=95
104400 On ch=3 n=43 v=95
104640 Off ch=3 n=52 v=80
104640 Off ch=3 n=43 v=80
104640 On ch=3 n=44 v=95
104640 On ch=3 n=39 v=95
104660 Off ch=3 n=44 v=80
104660 Off ch=3 n=39 v=80
104880 On ch=3 n=50 v=95
104880 On ch=3 n=43 v=95
105120 Off ch=3 n=50 v=80
105120 Off ch=3 n=43 v=80
105120 On ch=3 n=49 v=95
105120 On ch=3 n=44 v=95
105140 Off ch=3 n=49 v=80
105140 Off ch=3 n=44 v=80
105240 On ch=3 n=62 v=95
105240 On ch=3 n=60 v=95
105240 On ch=3 n=55 v=95
105240 On ch=3 n=48 v=95
105600 Off ch=3 n=62 v=80
105600 Off ch=3 n=60 v=80
105600 Off ch=3 n=55 v=80
105600 Off ch=3 n=48 v=80
105600 On ch=3 n=57 v=95
105600 On ch=3 n=50 v=95
105840 Off ch=3 n=57 v=80
105840 Off ch=3 n=50 v=80
105840 On ch=3 n=57 v=95
105840 On ch=3 n=50 v=95
106080 Off ch=3 n=57 v=80
106080 Off ch=3 n=50 v=80
106080 On ch=3 n=59 v=95
106080 On ch=3 n=50 v=95
106200 Off ch=3 n=59 v=80
106200 Off ch=3 n=50 v=80
106200 On ch=3 n=57 v=95
106200 On ch=3 n=50 v=95
106560 Off ch=3 n=57 v=80
106560 Off ch=3 n=50 v=80
106560 On ch=3 n=55 v=95
106560 On ch=3 n=48 v=95
106800 Off ch=3 n=55 v=80
106800 Off ch=3 n=48 v=80
106800 On ch=3 n=55 v=95
106800 On ch=3 n=48 v=95
107040 Off ch=3 n=55 v=80
107040 Off ch=3 n=48 v=80
107040 On ch=3 n=57 v=95
107040 On ch=3 n=48 v=95
107160 Off ch=3 n=57 v=80
107160 Off ch=3 n=48 v=80
107160 On ch=3 n=55 v=95
107160 On ch=3 n=48 v=95
107400 Off ch=3 n=55 v=80
107400 Off ch=3 n=48 v=80
107400 On ch=3 n=55 v=95
107400 On ch=3 n=48 v=95
107520 Off ch=3 n=55 v=80
107520 Off ch=3 n=48 v=80
107520 On ch=3 n=50 v=95
107520 On ch=3 n=43 v=95
107760 Off ch=3 n=50 v=80
107760 Off ch=3 n=43 v=80
107760 On ch=3 n=50 v=95
107760 On ch=3 n=43 v=95
108000 Off ch=3 n=50 v=80
108000 Off ch=3 n=43 v=80
108000 On ch=3 n=50 v=95
108000 On ch=3 n=43 v=95
108120 Off ch=3 n=50 v=80
108120 Off ch=3 n=43 v=80
108120 On ch=3 n=50 v=95
108120 On ch=3 n=43 v=95
108240 Off ch=3 n=50 v=80
108240 Off ch=3 n=43 v=80
108240 On ch=3 n=52 v=95
108240 On ch=3 n=43 v=95
108480 Off ch=3 n=52 v=80
108480 Off ch=3 n=43 v=80
108480 On ch=3 n=65 v=95
108480 On ch=3 n=60 v=95
108480 On ch=3 n=57 v=95
108480 On ch=3 n=53 v=95
108480 On ch=3 n=48 v=95
108960 Off ch=3 n=65 v=80
108960 Off ch=3 n=60 v=80
108960 Off ch=3 n=57 v=80
108960 Off ch=3 n=53 v=80
108960 Off ch=3 n=48 v=80
108960 On ch=3 n=64 v=95
108960 On ch=3 n=60 v=95
108960 On ch=3 n=55 v=95
108960 On ch=3 n=52 v=95
108960 On ch=3 n=48 v=95
109440 Off ch=3 n=64 v=80
109440 Off ch=3 n=60 v=80
109440 Off ch=3 n=55 v=80
109440 Off ch=3 n=52 v=80
109440 Off ch=3 n=48 v=80
109440 On ch=3 n=50 v=95
109560 Off ch=3 n=50 v=80
109560 On ch=3 n=52 v=76
109680 Off ch=3 n=52 v=80
109680 On ch=3 n=55 v=95
109800 Off ch=3 n=55 v=80
109800 On ch=4 n=57 v=95
109908 Pb ch=4 v=8832
109920 Off ch=4 n=57 v=80
109920 Pb ch=4 v=8192
109920 On ch=3 n=59 v=76
110040 Off ch=3 n=59 v=80
110040 On ch=3 n=57 v=95
110160 Off ch=3 n=57 v=80
110160 On ch=3 n=57 v=95
110280 Off ch=3 n=57 v=80
110280 On ch=4 n=58 v=95
110280 Pb ch=4 v=8192
110312 Pb ch=4 v=8320
110328 Pb ch=4 v=8448
110344 Pb ch=4 v=8704
110360 Pb ch=4 v=8960
110376 Pb ch=4 v=9344
110392 Pb ch=4 v=9472
110408 Pb ch=4 v=9600
110520 Off ch=4 n=58 v=80
110520 Pb ch=4 v=8192
110520 On ch=4 n=57 v=95
110520 Pb ch=4 v=8192
110552 Pb ch=4 v=8320
110584 Pb ch=4 v=8448
110600 Pb ch=4 v=8576
110616 Pb ch=4 v=8704
110632 Pb ch=4 v=8832
110760 Off ch=4 n=57 v=80
110760 Pb ch=4 v=8192
110760 On ch=4 n=57 v=95
110760 Pb ch=4 v=8192
110776 Pb ch=4 v=8320
110792 Pb ch=4 v=8448
110808 Pb ch=4 v=8704
110824 Pb ch=4 v=8832
110880 Off ch=4 n=57 v=80
110880 Pb ch=4 v=8192
110880 On ch=3 n=57 v=95
111000 Off ch=3 n=57 v=80
111000 On ch=4 n=57 v=95
111000 Pb ch=4 v=8192
111032 Pb ch=4 v=8320
111064 Pb ch=4 v=8448
111080 Pb ch=4 v=8576
111096 Pb ch=4 v=8704
111112 Pb ch=4 v=8832
111272 Pb ch=4 v=8704
111288 Pb ch=4 v=8576
111304 Pb ch=4 v=8320
111336 Pb ch=4 v=8192
111480 Off ch=4 n=57 v=80
111480 Pb ch=4 v=8192
111480 On ch=4 n=57 v=95
111480 Pb ch=4 v=8192
111512 Pb ch=4 v=8320
111528 Pb ch=4 v=8448
111544 Pb ch=4 v=8704
111560 Pb ch=4 v=8832
111688 Pb ch=4 v=8576
111704 Pb ch=4 v=8448
111720 Pb ch=4 v=8320
111736 Pb ch=4 v=8192
111840 Off ch=4 n=57 v=80
111840 Pb ch=4 v=8192
111840 On ch=3 n=55 v=95
111960 Off ch=3 n=55 v=80
111960 On ch=3 n=52 v=95
112080 Off ch=3 n=52 v=80
112080 On ch=3 n=55 v=95
112200 Off ch=3 n=55 v=80
112200 Pb ch=4 v=8192
112200 On ch=4 n=55 v=95
112248 Pb ch=4 v=8064
112280 Pb ch=4 v=7936
112312 Pb ch=4 v=7808
112344 Pb ch=4 v=7680
112376 Pb ch=4 v=7552
112392 Pb ch=4 v=7424
112424 Pb ch=4 v=7296
112456 Pb ch=4 v=7168
112488 Pb ch=4 v=7040
112552 Pb ch=4 v=6912
112680 Pb ch=4 v=6784
112696 Pb ch=4 v=6912
112824 Pb ch=4 v=7040
112888 Pb ch=4 v=7168
112920 Pb ch=4 v=7296
112952 Pb ch=4 v=7424
112984 Pb ch=4 v=7552
113000 Pb ch=4 v=7680
113032 Pb ch=4 v=7808
113064 Pb ch=4 v=7936
113096 Pb ch=4 v=8064
113128 Pb ch=4 v=8192
113160 Pb ch=4 v=8192
113160 Off ch=4 n=55 v=80
113160 On ch=3 n=54 v=95
113180 Off ch=3 n=54 v=80
113250 On ch=4 n=62 v=95
113277 Pb ch=4 v=8832
113280 Off ch=4 n=62 v=80
113280 Pb ch=4 v=8192
113280 On ch=3 n=64 v=76
113520 Off ch=3 n=64 v=80
113520 On ch=3 n=67 v=95
113640 Off ch=3 n=67 v=80
113640 On ch=3 n=64 v=95
113760 Off ch=3 n=64 v=80
113760 On ch=3 n=67 v=95
113880 Off ch=3 n=67 v=80
113880 On ch=4 n=69 v=76
113880 Pb ch=4 v=8192
113912 Pb ch=4 v=8320
113960 Pb ch=4 v=8448
113976 Pb ch=4 v=8576
114008 Pb ch=4 v=8704
114024 Pb ch=4 v=8832
114040 Pb ch=4 v=9088
114056 Pb ch=4 v=9344
114072 Pb ch=4 v=9472
114088 Pb ch=4 v=9728
114104 Pb ch=4 v=9856
114120 Pb ch=4 v=10112
114136 Pb ch=4 v=10368
114168 Pb ch=4 v=10496
114184 Pb ch=4 v=10624
114480 Off ch=4 n=69 v=80
114480 Pb ch=4 v=8192
114480 On ch=4 n=69 v=95
114480 Pb ch=4 v=8192
114512 Pb ch=4 v=8320
114528 Pb ch=4 v=8448
114544 Pb ch=4 v=8704
114560 Pb ch=4 v=8960
114576 Pb ch=4 v=9344
114592 Pb ch=4 v=9472
114608 Pb ch=4 v=9600
114720 Off ch=4 n=69 v=80
114720 Pb ch=4 v=8192
114720 On ch=3 n=67 v=95
114840 Off ch=3 n=67 v=80
114840 On ch=3 n=64 v=95
114960 Off ch=3 n=64 v=80
114960 On ch=3 n=67 v=95
115080 Off ch=3 n=67 v=80
115080 On ch=4 n=64 v=95
115188 Pb ch=4 v=7552
115200 Off ch=4 n=64 v=80
115200 Pb ch=4 v=8192
115200 On ch=3 n=62 v=76
115320 Off ch=3 n=62 v=80
115320 On ch=3 n=62 v=95
115680 Off ch=3 n=62 v=80
115680 On ch=3 n=62 v=95
115800 Off ch=3 n=62 v=80
115800 On ch=3 n=62 v=95
115920 Off ch=3 n=62 v=80
115920 On ch=3 n=62 v=95
116040 Off ch=3 n=62 v=80
116040 On ch=3 n=62 v=95
116400 Off ch=3 n=62 v=80
116400 On ch=3 n=62 v=95
116520 Off ch=3 n=62 v=80
116520 On ch=3 n=59 v=95
116640 Off ch=3 n=59 v=80
116640 On ch=3 n=64 v=95
116760 Off ch=3 n=64 v=80
116760 On ch=3 n=62 v=95
117000 Off ch=3 n=62 v=80
117000 On ch=3 n=62 v=95
117120 Off ch=3 n=62 v=80
117120 On ch=4 n=59 v=95
117210 Pb ch=4 v=8832
117218 Pb ch=4 v=9600
117225 Pb ch=4 v=10240
117232 Pb ch=4 v=10880
117240 Off ch=4 n=59 v=80
117240 Pb ch=4 v=8192
117240 On ch=3 n=64 v=76
117360 Off ch=3 n=64 v=80
117360 On ch=3 n=67 v=95
117480 Off ch=3 n=67 v=80
117480 On ch=3 n=64 v=95
117600 Off ch=3 n=64 v=80
117600 On ch=3 n=67 v=95
117720 Off ch=3 n=67 v=80
117720 On ch=3 n=69 v=95
117780 Off ch=3 n=69 v=80
117780 On ch=3 n=67 v=76
117840 Off ch=3 n=67 v=80
117840 On ch=3 n=64 v=95
117960 Off ch=3 n=64 v=80
117960 On ch=3 n=67 v=95
118080 Off ch=3 n=67 v=80
118080 On ch=3 n=72 v=95
118140 Off ch=3 n=72 v=80
118140 On ch=3 n=67 v=76
118200 Off ch=3 n=67 v=80
118200 On ch=3 n=64 v=95
118320 Off ch=3 n=64 v=80
118320 On ch=3 n=67 v=95
118440 Off ch=3 n=67 v=80
118440 On ch=3 n=72 v=95
118500 Off ch=3 n=72 v=80
118500 On ch=3 n=67 v=76
118560 Off ch=3 n=67 v=80
118560 On ch=3 n=64 v=95
118680 Off ch=3 n=64 v=80
118680 On ch=3 n=67 v=95
118800 Off ch=3 n=67 v=80
118800 On ch=3 n=71 v=95
118860 Off ch=3 n=71 v=80
118860 On ch=3 n=67 v=76
118920 Off ch=3 n=67 v=80
118920 On ch=3 n=64 v=95
119040 Off ch=3 n=64 v=80
119040 On ch=3 n=67 v=95
119160 Off ch=3 n=67 v=80
119160 On ch=3 n=71 v=95
119220 Off ch=3 n=71 v=80
119220 On ch=3 n=67 v=76
119280 Off ch=3 n=67 v=80
119280 On ch=3 n=64 v=95
119400 Off ch=3 n=64 v=80
119400 On ch=3 n=67 v=95
119520 Off ch=3 n=67 v=80
119520 On ch=3 n=71 v=95
119580 Off ch=3 n=71 v=80
119580 On ch=3 n=67 v=76
119640 Off ch=3 n=67 v=80
119640 On ch=3 n=64 v=95
119760 Off ch=3 n=64 v=80
119760 On ch=3 n=67 v=95
119880 Off ch=3 n=67 v=80
119880 On ch=3 n=71 v=95
119940 Off ch=3 n=71 v=80
119940 On ch=3 n=67 v=76
120000 Off ch=3 n=67 v=80
120000 On ch=3 n=64 v=95
120120 Off ch=3 n=64 v=80
120120 On ch=3 n=67 v=95
120240 Off ch=3 n=67 v=80
120240 On ch=3 n=71 v=95
120300 Off ch=3 n=71 v=80
120300 On ch=3 n=67 v=76
120360 Off ch=3 n=67 v=80
120360 On ch=3 n=64 v=95
120480 Off ch=3 n=64 v=80
120480 On ch=3 n=67 v=95
120600 Off ch=3 n=67 v=80
120600 On ch=3 n=71 v=95
120660 Off ch=3 n=71 v=80
120660 On ch=3 n=67 v=76
120720 Off ch=3 n=67 v=80
120720 On ch=3 n=64 v=95
120840 Off ch=3 n=64 v=80
120840 On ch=3 n=67 v=95
120960 Off ch=3 n=67 v=80
120960 On ch=3 n=69 v=95
121020 Off ch=3 n=69 v=80
121020 On ch=3 n=67 v=76
121080 Off ch=3 n=67 v=80
121080 On ch=3 n=64 v=95
121200 Off ch=3 n=64 v=80
121200 On ch=3 n=67 v=95
121320 Off ch=3 n=67 v=80
121320 On ch=3 n=69 v=95
121380 Off ch=3 n=69 v=80
121380 On ch=3 n=67 v=76
121440 Off ch=3 n=67 v=80
121440 On ch=3 n=64 v=95
121560 Off ch=3 n=64 v=80
121560 On ch=3 n=67 v=95
121680 Off ch=3 n=67 v=80
121680 On ch=3 n=72 v=95
121740 Off ch=3 n=72 v=80
121740 On ch=3 n=67 v=76
121800 Off ch=3 n=67 v=80
121800 On ch=3 n=64 v=95
121920 Off ch=3 n=64 v=80
121920 On ch=3 n=67 v=95
122040 Off ch=3 n=67 v=80
122040 On ch=3 n=72 v=95
122100 Off ch=3 n=72 v=80
122100 On ch=3 n=67 v=76
122160 Off ch=3 n=67 v=80
122160 On ch=3 n=64 v=95
122280 Off ch=3 n=64 v=80
122280 On ch=3 n=67 v=95
122400 Off ch=3 n=67 v=80
122400 On ch=3 n=72 v=95
122460 Off ch=3 n=72 v=80
122460 On ch=3 n=67 v=76
122520 Off ch=3 n=67 v=80
122520 On ch=3 n=64 v=95
122640 Off ch=3 n=64 v=80
122640 On ch=3 n=67 v=95
122760 Off ch=3 n=67 v=80
122760 On ch=3 n=64 v=95
122880 Off ch=3 n=64 v=80
122880 On ch=4 n=58 v=95
122976 Pb ch=4 v=8832
123000 Off ch=4 n=58 v=80
123000 Pb ch=4 v=8192
123000 On ch=3 n=59 v=76
123120 Off ch=3 n=59 v=80
123120 On ch=3 n=67 v=95
123240 Off ch=3 n=67 v=80
123240 On ch=4 n=64 v=95
123348 Pb ch=4 v=7552
123360 Off ch=4 n=64 v=80
123360 Pb ch=4 v=8192
123360 On ch=4 n=62 v=76
123468 Pb ch=4 v=8832
123480 Off ch=4 n=62 v=80
123480 Pb ch=4 v=8192
123480 On ch=3 n=64 v=76
123600 Off ch=3 n=64 v=80
123600 On ch=3 n=67 v=95
123720 Off ch=3 n=67 v=80
123720 On ch=3 n=64 v=95
123840 Off ch=3 n=64 v=80
123840 On ch=3 n=67 v=95
123960 Off ch=3 n=67 v=80
123960 On ch=3 n=69 v=76
124080 Off ch=3 n=69 v=80
124080 On ch=3 n=71 v=95
124200 Off ch=3 n=71 v=80
124200 On ch=3 n=69 v=95
124320 Off ch=3 n=69 v=80
124320 On ch=3 n=71 v=95
124440 Off ch=3 n=71 v=80
124440 On ch=3 n=74 v=76
124560 Off ch=3 n=74 v=80
124560 On ch=3 n=76 v=95
124680 Off ch=3 n=76 v=80
124680 On ch=3 n=74 v=95
124800 Off ch=3 n=74 v=80
124800 On ch=3 n=76 v=95
124860 Off ch=3 n=76 v=80
124860 On ch=3 n=79 v=76
125040 Off ch=3 n=79 v=80
125040 On ch=4 n=79 v=95
125040 Pb ch=4 v=8192
125045 Pb ch=4 v=8192
125049 Pb ch=4 v=8192
125054 Pb ch=4 v=8192
125059 Pb ch=4 v=8192
125063 Pb ch=4 v=8192
125068 Pb ch=4 v=8192
125073 Pb ch=4 v=8192
125078 Pb ch=4 v=8192
125082 Pb ch=4 v=8192
125087 Pb ch=4 v=8192
125092 Pb ch=4 v=8192
125096 Pb ch=4 v=8192
125101 Pb ch=4 v=8192
125106 Pb ch=4 v=8192
125110 Pb ch=4 v=8192
125115 Pb ch=4 v=8192
125120 Pb ch=4 v=8192
125124 Pb ch=4 v=8192
125129 Pb ch=4 v=8192
125134 Pb ch=4 v=8192
125138 Pb ch=4 v=8192
125143 Pb ch=4 v=8192
125148 Pb ch=4 v=8192
125152 Pb ch=4 v=8192
125157 Pb ch=4 v=8192
125162 Pb ch=4 v=8192
125167 Pb ch=4 v=8192
125171 Pb ch=4 v=8192
125176 Pb ch=4 v=8192
125181 Pb ch=4 v=8192
125185 Pb ch=4 v=8192
125190 Pb ch=4 v=8192
125195 Pb ch=4 v=8192
125199 Pb ch=4 v=8320
125204 Pb ch=4 v=8320
125209 Pb ch=4 v=8320
125213 Pb ch=4 v=8448
125218 Pb ch=4 v=8448
125223 Pb ch=4 v=8448
125228 Pb ch=4 v=8448
125232 Pb ch=4 v=8448
125237 Pb ch=4 v=8448
125242 Pb ch=4 v=8448
125246 Pb ch=4 v=8320
125251 Pb ch=4 v=8320
125256 Pb ch=4 v=8320
125260 Pb ch=4 v=8192
125265 Pb ch=4 v=8192
125270 Pb ch=4 v=8192
125274 Pb ch=4 v=8064
125279 Pb ch=4 v=8064
125280 Off ch=4 n=79 v=80
125280 Pb ch=4 v=8192
125280 On ch=4 n=79 v=95
125280 Pb ch=4 v=8192
125285 Pb ch=4 v=8192
125289 Pb ch=4 v=8192
125294 Pb ch=4 v=8192
125299 Pb ch=4 v=8192
125303 Pb ch=4 v=8192
125308 Pb ch=4 v=8192
125313 Pb ch=4 v=8192
125318 Pb ch=4 v=8192
125322 Pb ch=4 v=8192
125327 Pb ch=4 v=8192
125332 Pb ch=4 v=8192
125336 Pb ch=4 v=8192
125341 Pb ch=4 v=8192
125346 Pb ch=4 v=8192
125350 Pb ch=4 v=8192
125355 Pb ch=4 v=8192
125360 Pb ch=4 v=8192
125364 Pb ch=4 v=8192
125369 Pb ch=4 v=8192
125374 Pb ch=4 v=8192
125378 Pb ch=4 v=8192
125383 Pb ch=4 v=8192
125388 Pb ch=4 v=8192
125392 Pb ch=4 v=8192
125397 Pb ch=4 v=8192
125402 Pb ch=4 v=8192
125407 Pb ch=4 v=8192
125411 Pb ch=4 v=8192
125416 Pb ch=4 v=8192
125421 Pb ch=4 v=8192
125425 Pb ch=4 v=8192
125430 Pb ch=4 v=8192
125435 Pb ch=4 v=8192
125439 Pb ch=4 v=8320
125444 Pb ch=4 v=8320
125449 Pb ch=4 v=8320
125453 Pb ch=4 v=8320
125458 Pb ch=4 v=8320
125463 Pb ch=4 v=8320
125468 Pb ch=4 v=8320
125472 Pb ch=4 v=8320
125477 Pb ch=4 v=8320
125482 Pb ch=4 v=8320
125486 Pb ch=4 v=8320
125491 Pb ch=4 v=8320
125496 Pb ch=4 v=8320
125500 Pb ch=4 v=8192
125505 Pb ch=4 v=8192
125510 Pb ch=4 v=8192
125514 Pb ch=4 v=8064
125519 Pb ch=4 v=8064
125520 Off ch=4 n=79 v=80
125520 Pb ch=4 v=8192
125520 On ch=4 n=79 v=95
125520 Pb ch=4 v=8192
125525 Pb ch=4 v=8192
125529 Pb ch=4 v=8192
125534 Pb ch=4 v=8192
125539 Pb ch=4 v=8192
125543 Pb ch=4 v=8192
125548 Pb ch=4 v=8192
125553 Pb ch=4 v=8192
125558 Pb ch=4 v=8192
125562 Pb ch=4 v=8192
125567 Pb ch=4 v=8192
125572 Pb ch=4 v=8192
125576 Pb ch=4 v=8192
125581 Pb ch=4 v=8192
125586 Pb ch=4 v=8192
125590 Pb ch=4 v=8192
125595 Pb ch=4 v=8192
125600 Pb ch=4 v=8192
125604 Pb ch=4 v=8192
125609 Pb ch=4 v=8192
125614 Pb ch=4 v=8192
125618 Pb ch=4 v=8192
125623 Pb ch=4 v=8192
125628 Pb ch=4 v=8192
125632 Pb ch=4 v=8192
125637 Pb ch=4 v=8192
125640 Off ch=4 n=79 v=80
125640 Pb ch=4 v=8192
125640 On ch=4 n=79 v=95
125640 Pb ch=4 v=8192
125645 Pb ch=4 v=8192
125649 Pb ch=4 v=8192
125654 Pb ch=4 v=8192
125659 Pb ch=4 v=8192
125663 Pb ch=4 v=8192
125668 Pb ch=4 v=8192
125673 Pb ch=4 v=8192
125678 Pb ch=4 v=8192
125682 Pb ch=4 v=8192
125687 Pb ch=4 v=8192
125692 Pb ch=4 v=8192
125696 Pb ch=4 v=8192
125701 Pb ch=4 v=8192
125706 Pb ch=4 v=8192
125710 Pb ch=4 v=8192
125715 Pb ch=4 v=8192
125720 Pb ch=4 v=8192
125724 Pb ch=4 v=8192
125729 Pb ch=4 v=8192
125734 Pb ch=4 v=8192
125738 Pb ch=4 v=8192
125743 Pb ch=4 v=8192
125748 Pb ch=4 v=8192
125752 Pb ch=4 v=8192
125757 Pb ch=4 v=8192
125760 Off ch=4 n=79 v=80
125760 Pb ch=4 v=8192
125760 On ch=4 n=79 v=95
125760 Pb ch=4 v=8192
125765 Pb ch=4 v=8192
125769 Pb ch=4 v=8192
125774 Pb ch=4 v=8192
125779 Pb ch=4 v=8192
125783 Pb ch=4 v=8192
125788 Pb ch=4 v=8192
125793 Pb ch=4 v=8192
125798 Pb ch=4 v=8192
125802 Pb ch=4 v=8192
125807 Pb ch=4 v=8192
125812 Pb ch=4 v=8192
125816 Pb ch=4 v=8192
125821 Pb ch=4 v=8192
125826 Pb ch=4 v=8192
125830 Pb ch=4 v=8192
125835 Pb ch=4 v=8192
125840 Pb ch=4 v=8192
125844 Pb ch=4 v=8192
125849 Pb ch=4 v=8192
125854 Pb ch=4 v=8192
125858 Pb ch=4 v=8192
125863 Pb ch=4 v=8192
125868 Pb ch=4 v=8192
125872 Pb ch=4 v=8192
125877 Pb ch=4 v=8192
125882 Pb ch=4 v=8192
125887 Pb ch=4 v=8192
125891 Pb ch=4 v=8192
125896 Pb ch=4 v=8192
125901 Pb ch=4 v=8192
125905 Pb ch=4 v=8192
125910 Pb ch=4 v=8192
125915 Pb ch=4 v=8192
125919 Pb ch=4 v=8320
125924 Pb ch=4 v=8320
125929 Pb ch=4 v=8320
125933 Pb ch=4 v=8320
125938 Pb ch=4 v=8320
125943 Pb ch=4 v=8320
125948 Pb ch=4 v=8320
125952 Pb ch=4 v=8320
125957 Pb ch=4 v=8320
125962 Pb ch=4 v=8320
125966 Pb ch=4 v=8320
125971 Pb ch=4 v=8320
125976 Pb ch=4 v=8320
125980 Pb ch=4 v=8192
125985 Pb ch=4 v=8192
125990 Pb ch=4 v=8192
125994 Pb ch=4 v=8064
125999 Pb ch=4 v=8064
126000 Off ch=4 n=79 v=80
126000 Pb ch=4 v=8192
126000 On ch=4 n=79 v=95
126000 Pb ch=4 v=8192
126005 Pb ch=4 v=8192
126009 Pb ch=4 v=8192
126014 Pb ch=4 v=8192
126019 Pb ch=4 v=8192
126023 Pb ch=4 v=8192
126028 Pb ch=4 v=8192
126033 Pb ch=4 v=8192
126038 Pb ch=4 v=8192
126042 Pb ch=4 v=8192
126047 Pb ch=4 v=8192
126052 Pb ch=4 v=8192
126056 Pb ch=4 v=8192
126061 Pb ch=4 v=8192
126066 Pb ch=4 v=8192
126070 Pb ch=4 v=8192
126075 Pb ch=4 v=8192
126080 Pb ch=4 v=8192
126084 Pb ch=4 v=8192
126089 Pb ch=4 v=8192
126094 Pb ch=4 v=8192
126098 Pb ch=4 v=8192
126103 Pb ch=4 v=8192
126108 Pb ch=4 v=8192
126112 Pb ch=4 v=8192
126117 Pb ch=4 v=8192
126120 Off ch=4 n=79 v=80
126120 Pb ch=4 v=8192
126120 On ch=4 n=79 v=95
126120 Pb ch=4 v=8192
126125 Pb ch=4 v=8192
126129 Pb ch=4 v=8192
126134 Pb ch=4 v=8192
126139 Pb ch=4 v=8192
126143 Pb ch=4 v=8192
126148 Pb ch=4 v=8192
126153 Pb ch=4 v=8192
126158 Pb ch=4 v=8192
126162 Pb ch=4 v=8192
126167 Pb ch=4 v=8192
126172 Pb ch=4 v=8192
126176 Pb ch=4 v=8192
126181 Pb ch=4 v=8192
126186 Pb ch=4 v=8192
126190 Pb ch=4 v=8192
126195 Pb ch=4 v=8192
126200 Pb ch=4 v=8192
126204 Pb ch=4 v=8192
126209 Pb ch=4 v=8192
126214 Pb ch=4 v=8192
126218 Pb ch=4 v=8192
126223 Pb ch=4 v=8192
126228 Pb ch=4 v=8192
126232 Pb ch=4 v=8192
126237 Pb ch=4 v=8192
126240 Off ch=4 n=79 v=80
126240 Pb ch=4 v=8192
126240 On ch=4 n=79 v=95
126240 Pb ch=4 v=8192
126245 Pb ch=4 v=8192
126249 Pb ch=4 v=8192
126254 Pb ch=4 v=8192
126259 Pb ch=4 v=8192
126263 Pb ch=4 v=8192
126268 Pb ch=4 v=8192
126273 Pb ch=4 v=8192
126278 Pb ch=4 v=8192
126282 Pb ch=4 v=8192
126287 Pb ch=4 v=8192
126292 Pb ch=4 v=8192
126296 Pb ch=4 v=8192
126301 Pb ch=4 v=8192
126306 Pb ch=4 v=8192
126310 Pb ch=4 v=8192
126315 Pb ch=4 v=8192
126320 Pb ch=4 v=8192
126324 Pb ch=4 v=8192
126329 Pb ch=4 v=8192
126334 Pb ch=4 v=8192
126338 Pb ch=4 v=8192
126343 Pb ch=4 v=8192
126348 Pb ch=4 v=8192
126352 Pb ch=4 v=8192
126357 Pb ch=4 v=8192
126362 Pb ch=4 v=8192
126367 Pb ch=4 v=8192
126371 Pb ch=4 v=8192
126376 Pb ch=4 v=8192
126381 Pb ch=4 v=8192
126385 Pb ch=4 v=8192
126390 Pb ch=4 v=8192
126395 Pb ch=4 v=8192
126399 Pb ch=4 v=8320
126404 Pb ch=4 v=8320
126409 Pb ch=4 v=8320
126413 Pb ch=4 v=8320
126418 Pb ch=4 v=8320
126423 Pb ch=4 v=8320
126428 Pb ch=4 v=8320
126432 Pb ch=4 v=8320
126437 Pb ch=4 v=8320
126442 Pb ch=4 v=8320
126446 Pb ch=4 v=8320
126451 Pb ch=4 v=8320
126456 Pb ch=4 v=8320
126460 Pb ch=4 v=8192
126465 Pb ch=4 v=8192
126470 Pb ch=4 v=8192
126474 Pb ch=4 v=8064
126479 Pb ch=4 v=8064
126480 Off ch=4 n=79 v=80
126480 Pb ch=4 v=8192
126480 On ch=4 n=79 v=95
126480 Pb ch=4 v=8192
126485 Pb ch=4 v=8192
126489 Pb ch=4 v=8192
126494 Pb ch=4 v=8192
126499 Pb ch=4 v=8192
126503 Pb ch=4 v=8192
126508 Pb ch=4 v=8192
126513 Pb ch=4 v=8192
126518 Pb ch=4 v=8192
126522 Pb ch=4 v=8192
126527 Pb ch=4 v=8192
126532 Pb ch=4 v=8192
126536 Pb ch=4 v=8192
126541 Pb ch=4 v=8192
126546 Pb ch=4 v=8192
126550 Pb ch=4 v=8192
126555 Pb ch=4 v=8192
126560 Pb ch=4 v=8192
126564 Pb ch=4 v=8192
126569 Pb ch=4 v=8192
126574 Pb ch=4 v=8192
126578 Pb ch=4 v=8192
126583 Pb ch=4 v=8192
126588 Pb ch=4 v=8192
126592 Pb ch=4 v=8192
126597 Pb ch=4 v=8192
126600 Off ch=4 n=79 v=80
126600 Pb ch=4 v=8192
126600 On ch=4 n=79 v=95
126600 Pb ch=4 v=8192
126605 Pb ch=4 v=8192
126609 Pb ch=4 v=8192
126614 Pb ch=4 v=8192
126619 Pb ch=4 v=8192
126623 Pb ch=4 v=8192
126628 Pb ch=4 v=8192
126633 Pb ch=4 v=8192
126638 Pb ch=4 v=8192
126642 Pb ch=4 v=8192
126647 Pb ch=4 v=8192
126652 Pb ch=4 v=8192
126656 Pb ch=4 v=8192
126661 Pb ch=4 v=8192
126666 Pb ch=4 v=8192
126670 Pb ch=4 v=8192
126675 Pb ch=4 v=8192
126680 Pb ch=4 v=8192
126684 Pb ch=4 v=8192
126689 Pb ch=4 v=8192
126694 Pb ch=4 v=8192
126698 Pb ch=4 v=8192
126703 Pb ch=4 v=8192
126708 Pb ch=4 v=8192
126712 Pb ch=4 v=8192
126717 Pb ch=4 v=8192
126720 Off ch=4 n=79 v=80
126720 Pb ch=4 v=8192
126720 On ch=4 n=79 v=95
126720 Pb ch=4 v=8192
126725 Pb ch=4 v=8192
126729 Pb ch=4 v=8192
126734 Pb ch=4 v=8192
126739 Pb ch=4 v=8192
126743 Pb ch=4 v=8192
126748 Pb ch=4 v=8192
126753 Pb ch=4 v=8192
126758 Pb ch=4 v=8192
126762 Pb ch=4 v=8192
126767 Pb ch=4 v=8192
126772 Pb ch=4 v=8192
126776 Pb ch=4 v=8192
126781 Pb ch=4 v=8192
126786 Pb ch=4 v=8192
126790 Pb ch=4 v=8192
126795 Pb ch=4 v=8192
126800 Pb ch=4 v=8192
126804 Pb ch=4 v=8192
126809 Pb ch=4 v=8192
126814 Pb ch=4 v=8192
126818 Pb ch=4 v=8192
126823 Pb ch=4 v=8192
126828 Pb ch=4 v=8192
126832 Pb ch=4 v=8192
126837 Pb ch=4 v=8192
126842 Pb ch=4 v=8192
126847 Pb ch=4 v=8192
126851 Pb ch=4 v=8192
126856 Pb ch=4 v=8192
126861 Pb ch=4 v=8192
126865 Pb ch=4 v=8192
126870 Pb ch=4 v=8192
126875 Pb ch=4 v=8192
126879 Pb ch=4 v=8320
126884 Pb ch=4 v=8320
126889 Pb ch=4 v=8320
126893 Pb ch=4 v=8320
126898 Pb ch=4 v=8320
126903 Pb ch=4 v=8320
126908 Pb ch=4 v=8320
126912 Pb ch=4 v=8320
126917 Pb ch=4 v=8320
126922 Pb ch=4 v=8320
126926 Pb ch=4 v=8320
126931 Pb ch=4 v=8320
126936 Pb ch=4 v=8320
126940 Pb ch=4 v=8192
126945 Pb ch=4 v=8192
126950 Pb ch=4 v=8192
126954 Pb ch=4 v=8064
126959 Pb ch=4 v=8064
126960 Off ch=4 n=79 v=80
126960 Pb ch=4 v=8192
126960 On ch=4 n=79 v=95
126960 Pb ch=4 v=8192
126965 Pb ch=4 v=8192
126969 Pb ch=4 v=8192
126974 Pb ch=4 v=8192
126979 Pb ch=4 v=8192
126983 Pb ch=4 v=8192
126988 Pb ch=4 v=8192
126993 Pb ch=4 v=8192
126998 Pb ch=4 v=8192
127002 Pb ch=4 v=8192
127007 Pb ch=4 v=8192
127012 Pb ch=4 v=8192
127016 Pb ch=4 v=8192
127021 Pb ch=4 v=8192
127026 Pb ch=4 v=8192
127030 Pb ch=4 v=8192
127035 Pb ch=4 v=8192
127040 Pb ch=4 v=8192
127044 Pb ch=4 v=8192
127049 Pb ch=4 v=8192
127054 Pb ch=4 v=8192
127058 Pb ch=4 v=8192
127063 Pb ch=4 v=8192
127068 Pb ch=4 v=8192
127072 Pb ch=4 v=8192
127077 Pb ch=4 v=8192
127080 Off ch=4 n=79 v=80
127080 Pb ch=4 v=8192
127080 On ch=4 n=79 v=95
127080 Pb ch=4 v=8192
127085 Pb ch=4 v=8192
127089 Pb ch=4 v=8192
127094 Pb ch=4 v=8192
127099 Pb ch=4 v=8192
127103 Pb ch=4 v=8192
127108 Pb ch=4 v=8192
127113 Pb ch=4 v=8192
127118 Pb ch=4 v=8192
127122 Pb ch=4 v=8192
127127 Pb ch=4 v=8192
127132 Pb ch=4 v=8192
127136 Pb ch=4 v=8192
127141 Pb ch=4 v=8192
127146 Pb ch=4 v=8192
127150 Pb ch=4 v=8192
127155 Pb ch=4 v=8192
127160 Pb ch=4 v=8192
127164 Pb ch=4 v=8192
127169 Pb ch=4 v=8192
127174 Pb ch=4 v=8192
127178 Pb ch=4 v=8192
127183 Pb ch=4 v=8192
127188 Pb ch=4 v=8192
127192 Pb ch=4 v=8192
127197 Pb ch=4 v=8192
127200 Off ch=4 n=79 v=80
127200 Pb ch=4 v=8192
127200 On ch=4 n=79 v=95
127200 Pb ch=4 v=8192
127205 Pb ch=4 v=8192
127209 Pb ch=4 v=8192
127214 Pb ch=4 v=8192
127219 Pb ch=4 v=8192
127223 Pb ch=4 v=8192
127228 Pb ch=4 v=8192
127233 Pb ch=4 v=8192
127238 Pb ch=4 v=8192
127242 Pb ch=4 v=8192
127247 Pb ch=4 v=8192
127252 Pb ch=4 v=8192
127256 Pb ch=4 v=8192
127261 Pb ch=4 v=8192
127266 Pb ch=4 v=8192
127270 Pb ch=4 v=8192
127275 Pb ch=4 v=8192
127280 Pb ch=4 v=8192
127284 Pb ch=4 v=8192
127289 Pb ch=4 v=8192
127294 Pb ch=4 v=8192
127298 Pb ch=4 v=8192
127303 Pb ch=4 v=8192
127308 Pb ch=4 v=8192
127312 Pb ch=4 v=8192
127317 Pb ch=4 v=8192
127322 Pb ch=4 v=8192
127327 Pb ch=4 v=8192
127331 Pb ch=4 v=8192
127336 Pb ch=4 v=8192
127341 Pb ch=4 v=8192
127345 Pb ch=4 v=8192
127350 Pb ch=4 v=8192
127355 Pb ch=4 v=8192
127359 Pb ch=4 v=8320
127364 Pb ch=4 v=8320
127369 Pb ch=4 v=8320
127373 Pb ch=4 v=8448
127378 Pb ch=4 v=8448
127383 Pb ch=4 v=8448
127388 Pb ch=4 v=8448
127392 Pb ch=4 v=8448
127397 Pb ch=4 v=8448
127402 Pb ch=4 v=8448
127406 Pb ch=4 v=8320
127411 Pb ch=4 v=8320
127416 Pb ch=4 v=8320
127420 Pb ch=4 v=8192
127425 Pb ch=4 v=8192
127430 Pb ch=4 v=8192
127434 Pb ch=4 v=8064
127439 Pb ch=4 v=8064
127440 Off ch=4 n=79 v=80
127440 Pb ch=4 v=8192
127440 On ch=4 n=79 v=95
127440 Pb ch=4 v=8192
127445 Pb ch=4 v=8192
127449 Pb ch=4 v=8192
127454 Pb ch=4 v=8192
127459 Pb ch=4 v=8192
127463 Pb ch=4 v=8192
127468 Pb ch=4 v=8192
127473 Pb ch=4 v=8192
127478 Pb ch=4 v=8192
127482 Pb ch=4 v=8192
127487 Pb ch=4 v=8192
127492 Pb ch=4 v=8192
127496 Pb ch=4 v=8192
127501 Pb ch=4 v=8192
127506 Pb ch=4 v=8192
127510 Pb ch=4 v=8192
127515 Pb ch=4 v=8192
127520 Pb ch=4 v=8192
127524 Pb ch=4 v=8192
127529 Pb ch=4 v=8192
127534 Pb ch=4 v=8192
127538 Pb ch=4 v=8192
127543 Pb ch=4 v=8192
127548 Pb ch=4 v=8192
127552 Pb ch=4 v=8192
127557 Pb ch=4 v=8192
127560 Off ch=4 n=79 v=80
127560 Pb ch=4 v=8192
127560 On ch=4 n=79 v=95
127560 Pb ch=4 v=8192
127565 Pb ch=4 v=8192
127569 Pb ch=4 v=8192
127574 Pb ch=4 v=8192
127579 Pb ch=4 v=8192
127583 Pb ch=4 v=8192
127588 Pb ch=4 v=8192
127593 Pb ch=4 v=8192
127598 Pb ch=4 v=8192
127602 Pb ch=4 v=8192
127607 Pb ch=4 v=8192
127612 Pb ch=4 v=8192
127616 Pb ch=4 v=8192
127621 Pb ch=4 v=8192
127626 Pb ch=4 v=8192
127630 Pb ch=4 v=8192
127635 Pb ch=4 v=8192
127640 Pb ch=4 v=8192
127644 Pb ch=4 v=8192
127649 Pb ch=4 v=8192
127654 Pb ch=4 v=8192
127658 Pb ch=4 v=8192
127663 Pb ch=4 v=8192
127668 Pb ch=4 v=8192
127672 Pb ch=4 v=8192
127677 Pb ch=4 v=8192
127680 Off ch=4 n=79 v=80
127680 Pb ch=4 v=8192
127680 On ch=4 n=79 v=95
127680 Pb ch=4 v=8192
127685 Pb ch=4 v=8192
127689 Pb ch=4 v=8192
127694 Pb ch=4 v=8192
127699 Pb ch=4 v=8192
127703 Pb ch=4 v=8192
127708 Pb ch=4 v=8192
127713 Pb ch=4 v=8192
127718 Pb ch=4 v=8192
127722 Pb ch=4 v=8192
127727 Pb ch=4 v=8192
127732 Pb ch=4 v=8192
127736 Pb ch=4 v=8192
127741 Pb ch=4 v=8192
127746 Pb ch=4 v=8192
127750 Pb ch=4 v=8192
127755 Pb ch=4 v=8192
127760 Pb ch=4 v=8192
127764 Pb ch=4 v=8192
127769 Pb ch=4 v=8192
127774 Pb ch=4 v=8192
127778 Pb ch=4 v=8192
127783 Pb ch=4 v=8192
127788 Pb ch=4 v=8192
127792 Pb ch=4 v=8192
127797 Pb ch=4 v=8192
127800 Off ch=4 n=79 v=80
127800 Pb ch=4 v=8192
127800 On ch=4 n=77 v=95
127800 Pb ch=4 v=8192
127880 Pb ch=4 v=8320
127928 Pb ch=4 v=8448
127976 Pb ch=4 v=8576
128160 Off ch=4 n=77 v=80
128160 Pb ch=4 v=8192
128160 On ch=3 n=76 v=95
128280 Off ch=3 n=76 v=80
128280 On ch=3 n=74 v=95
128400 Off ch=3 n=74 v=80
128400 On ch=3 n=76 v=95
128520 Off ch=3 n=76 v=80
128520 On ch=4 n=74 v=95
128520 Pb ch=4 v=8192
128552 Pb ch=4 v=8320
128568 Pb ch=4 v=8448
128584 Pb ch=4 v=8704
128600 Pb ch=4 v=8960
128616 Pb ch=4 v=9344
128632 Pb ch=4 v=9472
128648 Pb ch=4 v=9600
128760 Off ch=4 n=74 v=80
128760 Pb ch=4 v=8192
128760 On ch=3 n=76 v=95
128880 Off ch=3 n=76 v=80
128880 On ch=4 n=74 v=95
128880 Pb ch=4 v=8192
128912 Pb ch=4 v=8320
128928 Pb ch=4 v=8448
128944 Pb ch=4 v=8704
128960 Pb ch=4 v=8960
128976 Pb ch=4 v=9344
128992 Pb ch=4 v=9472
129008 Pb ch=4 v=9600
129120 Off ch=4 n=74 v=80
129120 Pb ch=4 v=8192
129120 On ch=3 n=76 v=95
129240 Off ch=3 n=76 v=80
129240 On ch=4 n=74 v=95
129240 Pb ch=4 v=8192
129272 Pb ch=4 v=8320
129288 Pb ch=4 v=8448
129304 Pb ch=4 v=8704
129320 Pb ch=4 v=8960
129336 Pb ch=4 v=9344
129352 Pb ch=4 v=9472
129368 Pb ch=4 v=9600
129480 Off ch=4 n=74 v=80
129480 Pb ch=4 v=8192
129480 On ch=3 n=76 v=95
129600 Off ch=3 n=76 v=80
129600 On ch=4 n=74 v=95
129600 Pb ch=4 v=8192
129632 Pb ch=4 v=8320
129648 Pb ch=4 v=8448
129664 Pb ch=4 v=8704
129680 Pb ch=4 v=8960
129696 Pb ch=4 v=9344
129712 Pb ch=4 v=9472
129728 Pb ch=4 v=9600
129840 Off ch=4 n=74 v=80
129840 Pb ch=4 v=8192
129840 On ch=3 n=76 v=95
129960 Off ch=3 n=76 v=80
129960 On ch=3 n=72 v=95
130080 Off ch=3 n=72 v=80
130080 On ch=4 n=69 v=95
130080 Pb ch=4 v=8192
130096 Pb ch=4 v=8320
130112 Pb ch=4 v=8704
130128 Pb ch=4 v=9344
130144 Pb ch=4 v=9600
130200 Off ch=4 n=69 v=80
130200 Pb ch=4 v=8192
130200 On ch=3 n=76 v=95
130320 Off ch=3 n=76 v=80
130320 On ch=4 n=74 v=95
130320 Pb ch=4 v=8192
130352 Pb ch=4 v=8320
130368 Pb ch=4 v=8448
130384 Pb ch=4 v=8704
130400 Pb ch=4 v=8960
130416 Pb ch=4 v=9344
130432 Pb ch=4 v=9472
130448 Pb ch=4 v=9600
130560 Off ch=4 n=74 v=80
130560 Pb ch=4 v=8192
130560 On ch=3 n=76 v=95
130680 Off ch=3 n=76 v=80
130680 On ch=3 n=74 v=95
130800 Off ch=3 n=74 v=80
130800 On ch=3 n=71 v=76
130920 Off ch=3 n=71 v=80
130920 On ch=3 n=70 v=95
131040 Off ch=3 n=70 v=80
131040 On ch=3 n=71 v=95
131160 Off ch=3 n=71 v=80
131160 On ch=3 n=70 v=95
131280 Off ch=3 n=70 v=80
131280 On ch=3 n=69 v=76
131400 Off ch=3 n=69 v=80
131400 On ch=3 n=67 v=95
131520 Off ch=3 n=67 v=80
131520 On ch=3 n=64 v=95
131640 Off ch=3 n=64 v=80
131640 On ch=3 n=62 v=95
131730 Off ch=3 n=62 v=80
131730 On ch=4 n=59 v=95
131757 Pb ch=4 v=7552
131760 Off ch=4 n=59 v=80
131760 Pb ch=4 v=8192
131760 On ch=3 n=57 v=76
131880 Off ch=3 n=57 v=80
131880 On ch=3 n=55 v=95
132000 Off ch=3 n=55 v=80
132000 On ch=3 n=55 v=95
132120 Off ch=3 n=55 v=80
132120 On ch=3 n=55 v=95
132240 Off ch=3 n=55 v=80
132240 On ch=3 n=57 v=95
132360 Off ch=3 n=57 v=80
132360 On ch=3 n=52 v=95
132480 Off ch=3 n=52 v=80
132480 On ch=3 n=55 v=95
132540 Off ch=3 n=55 v=80
132540 On ch=3 n=57 v=76
132720 Off ch=3 n=57 v=80
132720 On ch=3 n=57 v=95
132960 Off ch=3 n=57 v=80
132960 On ch=3 n=55 v=95
133080 Off ch=3 n=55 v=80
133080 On ch=3 n=52 v=95
133200 Off ch=3 n=52 v=80
133200 On ch=3 n=55 v=95
133320 Off ch=3 n=55 v=80
133320 On ch=3 n=55 v=95
133560 Off ch=3 n=55 v=80
133560 On ch=3 n=55 v=95
133680 Off ch=3 n=55 v=80
133680 On ch=3 n=57 v=95
133800 Off ch=3 n=57 v=80
133800 On ch=3 n=52 v=95
133920 Off ch=3 n=52 v=80
133920 On ch=3 n=55 v=95
134040 Off ch=3 n=55 v=80
134040 On ch=4 n=57 v=95
134148 Pb ch=4 v=8832
134160 Off ch=4 n=57 v=80
134160 Pb ch=4 v=8192
134160 On ch=3 n=59 v=76
134280 Off ch=3 n=59 v=80
134280 On ch=3 n=62 v=95
134400 Off ch=3 n=62 v=80
134400 On ch=3 n=64 v=95
134520 Off ch=3 n=64 v=80
134520 On ch=3 n=59 v=95
134640 Off ch=3 n=59 v=80
134640 On ch=3 n=62 v=95
134760 Off ch=3 n=62 v=80
134760 On ch=3 n=62 v=95
134880 Off ch=3 n=62 v=80
134880 On ch=3 n=64 v=76
135000 Off ch=3 n=64 v=80
135000 On ch=3 n=67 v=95
135120 Off ch=3 n=67 v=80
135120 On ch=3 n=64 v=95
135240 Off ch=3 n=64 v=80
135240 On ch=3 n=67 v=95
135360 Off ch=3 n=67 v=80
135360 On ch=3 n=69 v=95
135480 Off ch=3 n=69 v=80
135480 On ch=3 n=64 v=95
135600 Off ch=3 n=64 v=80
135600 On ch=3 n=67 v=95
135720 Off ch=3 n=67 v=80
135720 On ch=4 n=69 v=95
135720 Pb ch=4 v=8192
135752 Pb ch=4 v=8320
135768 Pb ch=4 v=8448
135784 Pb ch=4 v=8704
135800 Pb ch=4 v=8960
135816 Pb ch=4 v=9344
135832 Pb ch=4 v=9472
135848 Pb ch=4 v=9600
135960 Off ch=4 n=69 v=80
135960 Pb ch=4 v=8192
135960 On ch=3 n=71 v=95
136080 Off ch=3 n=71 v=80
136080 On ch=3 n=74 v=95
136200 Off ch=3 n=74 v=80
136200 On ch=3 n=71 v=76
136320 Off ch=3 n=71 v=80
136320 On ch=3 n=74 v=95
136440 Off ch=3 n=74 v=80
136440 On ch=3 n=71 v=76
136560 Off ch=3 n=71 v=80
136560 On ch=4 n=69 v=95
136668 Pb ch=4 v=8832
136680 Off ch=4 n=69 v=80
136680 Pb ch=4 v=8192
136680 On ch=3 n=71 v=76
136800 Off ch=3 n=71 v=80
136800 On ch=3 n=74 v=95
136920 Off ch=3 n=74 v=80
136920 On ch=3 n=76 v=76
137040 Off ch=3 n=76 v=80
137040 On ch=3 n=79 v=95
137160 Off ch=3 n=79 v=80
137160 On ch=4 n=81 v=95
137160 Pb ch=4 v=8192
137176 Pb ch=4 v=8320
137192 Pb ch=4 v=8704
137208 Pb ch=4 v=9344
137224 Pb ch=4 v=9600
137280 Off ch=4 n=81 v=80
137280 Pb ch=4 v=8192
137400 On ch=4 n=81 v=95
137400 Pb ch=4 v=8192
137416 Pb ch=4 v=8320
137432 Pb ch=4 v=8704
137448 Pb ch=4 v=9344
137464 Pb ch=4 v=9600
137520 Off ch=4 n=81 v=80
137520 Pb ch=4 v=8192
137640 On ch=4 n=81 v=95
137640 Pb ch=4 v=8192
137672 Pb ch=4 v=8320
137688 Pb ch=4 v=8448
137704 Pb ch=4 v=8704
137720 Pb ch=4 v=8960
137736 Pb ch=4 v=9344
137752 Pb ch=4 v=9472
137768 Pb ch=4 v=9600
137880 Off ch=4 n=81 v=80
137880 Pb ch=4 v=8192
137880 On ch=3 n=81 v=95
138000 Off ch=3 n=81 v=80
138000 On ch=3 n=79 v=95
138120 Off ch=3 n=79 v=80
138120 On ch=3 n=76 v=95
138240 Off ch=3 n=76 v=80
138240 On ch=3 n=79 v=95
138360 Off ch=3 n=79 v=80
138360 On ch=3 n=76 v=95
138480 Off ch=3 n=76 v=80
138480 On ch=3 n=74 v=95
138720 Off ch=3 n=74 v=80
138720 On ch=3 n=71 v=95
138840 Off ch=3 n=71 v=80
138840 On ch=3 n=70 v=95
138960 Off ch=3 n=70 v=80
138960 On ch=3 n=71 v=95
139080 Off ch=3 n=71 v=80
139080 On ch=3 n=70 v=95
139200 Off ch=3 n=70 v=80
139200 On ch=3 n=69 v=76
139320 Off ch=3 n=69 v=80
139320 On ch=3 n=67 v=95
139440 Off ch=3 n=67 v=80
139440 On ch=3 n=64 v=95
139560 Off ch=3 n=64 v=80
139560 On ch=3 n=62 v=95
139680 Off ch=3 n=62 v=80
139680 On ch=4 n=67 v=95
139680 Pb ch=4 v=8192
139685 Pb ch=4 v=8192
139689 Pb ch=4 v=8192
139694 Pb ch=4 v=8192
139699 Pb ch=4 v=8192
139703 Pb ch=4 v=8192
139708 Pb ch=4 v=8192
139713 Pb ch=4 v=8192
139718 Pb ch=4 v=8192
139722 Pb ch=4 v=8192
139727 Pb ch=4 v=8192
139732 Pb ch=4 v=8192
139736 Pb ch=4 v=8192
139741 Pb ch=4 v=8192
139746 Pb ch=4 v=8192
139750 Pb ch=4 v=8192
139755 Pb ch=4 v=8192
139760 Pb ch=4 v=8192
139764 Pb ch=4 v=8192
139769 Pb ch=4 v=8192
139774 Pb ch=4 v=8192
139778 Pb ch=4 v=8192
139783 Pb ch=4 v=8192
139788 Pb ch=4 v=8192
139792 Pb ch=4 v=8192
139797 Pb ch=4 v=8192
139802 Pb ch=4 v=8192
139807 Pb ch=4 v=8192
139811 Pb ch=4 v=8192
139816 Pb ch=4 v=8192
139821 Pb ch=4 v=8192
139825 Pb ch=4 v=8192
139830 Pb ch=4 v=8192
139835 Pb ch=4 v=8320
139839 Pb ch=4 v=8320
139844 Pb ch=4 v=8320
139849 Pb ch=4 v=8448
139853 Pb ch=4 v=8448
139858 Pb ch=4 v=8448
139863 Pb ch=4 v=8448
139868 Pb ch=4 v=8448
139872 Pb ch=4 v=8448
139877 Pb ch=4 v=8448
139882 Pb ch=4 v=8448
139886 Pb ch=4 v=8448
139891 Pb ch=4 v=8320
139896 Pb ch=4 v=8320
139900 Pb ch=4 v=8320
139905 Pb ch=4 v=8192
139910 Pb ch=4 v=8064
139914 Pb ch=4 v=8064
139919 Pb ch=4 v=8064
139924 Pb ch=4 v=7936
139928 Pb ch=4 v=7936
139933 Pb ch=4 v=7936
139938 Pb ch=4 v=7936
139942 Pb ch=4 v=7936
139947 Pb ch=4 v=7936
139952 Pb ch=4 v=7936
139957 Pb ch=4 v=7936
139961 Pb ch=4 v=7936
139966 Pb ch=4 v=8064
139971 Pb ch=4 v=8064
139975 Pb ch=4 v=8064
139980 Pb ch=4 v=8192
139985 Pb ch=4 v=8192
139989 Pb ch=4 v=8320
139994 Pb ch=4 v=8320
139999 Pb ch=4 v=8320
140003 Pb ch=4 v=8448
140008 Pb ch=4 v=8448
140013 Pb ch=4 v=8448
140018 Pb ch=4 v=8448
140022 Pb ch=4 v=8448
140027 Pb ch=4 v=8448
140032 Pb ch=4 v=8448
140036 Pb ch=4 v=8320
140040 Off ch=4 n=67 v=80
140040 Pb ch=4 v=8192
140040 On ch=3 n=67 v=95
140160 Off ch=3 n=67 v=80
143160 On ch=3 n=62 v=95
143280 Off ch=3 n=62 v=80
143280 On ch=4 n=69 v=95
143280 Pb ch=4 v=8192
143312 Pb ch=4 v=8320
143328 Pb ch=4 v=8448
143344 Pb ch=4 v=8704
143360 Pb ch=4 v=8960
143376 Pb ch=4 v=9344
143392 Pb ch=4 v=9472
143408 Pb ch=4 v=9600
143536 Pb ch=4 v=9344
143552 Pb ch=4 v=9088
143568 Pb ch=4 v=8832
143584 Pb ch=4 v=8576
143600 Pb ch=4 v=8320
143632 Pb ch=4 v=8192
143760 Off ch=4 n=69 v=80
143760 Pb ch=4 v=8192
143760 On ch=3 n=67 v=95
144000 Off ch=3 n=67 v=80
144000 On ch=3 n=69 v=95
144120 Off ch=3 n=69 v=80
144120 On ch=4 n=65 v=95
144216 Pb ch=4 v=8832
144240 Off ch=4 n=65 v=80
144240 Pb ch=4 v=8192
144240 On ch=3 n=66 v=76
144360 Off ch=3 n=66 v=80
144360 On ch=3 n=62 v=95
144480 Off ch=3 n=62 v=80
144480 On ch=3 n=57 v=95
144960 Off ch=3 n=57 v=80
144960 On ch=3 n=57 v=95
145080 Off ch=3 n=57 v=80
145080 On ch=3 n=55 v=76
145920 Off ch=3 n=55 v=80
146880 On ch=3 n=69 v=95
147000 Off ch=3 n=69 v=80
147000 On ch=3 n=71 v=95
147240 Off ch=3 n=71 v=80
147240 On ch=3 n=79 v=95
147840 Off ch=3 n=79 v=80
148080 On ch=3 n=64 v=95
148440 Off ch=3 n=64 v=80
148440 On ch=3 n=62 v=95
148800 Off ch=3 n=62 v=80
149040 On ch=3 n=62 v=95
149400 Off ch=3 n=62 v=80
149400 On ch=3 n=64 v=95
149640 Off ch=3 n=64 v=80
149640 On ch=3 n=62 v=95
149760 Off ch=3 n=62 v=80
150000 On ch=3 n=67 v=95
150360 Off ch=3 n=67 v=80
150360 On ch=3 n=62 v=95
150720 Off ch=3 n=62 v=80
150720 On ch=4 n=62 v=95
150828 Pb ch=4 v=8832
150840 Off ch=4 n=62 v=80
150840 Pb ch=4 v=8192
150840 On ch=3 n=64 v=76
151080 Off ch=3 n=64 v=80
151080 On ch=3 n=67 v=95
151380 Off ch=3 n=67 v=80
151380 On ch=4 n=64 v=95
151434 Pb ch=4 v=7552
151440 Off ch=4 n=64 v=80
151440 Pb ch=4 v=8192
151440 On ch=3 n=62 v=76
151680 Off ch=3 n=62 v=80
151920 On ch=3 n=64 v=95
152280 Off ch=3 n=64 v=80
152280 On ch=3 n=62 v=95
152640 Off ch=3 n=62 v=80
152880 On ch=3 n=62 v=95
153240 Off ch=3 n=62 v=80
153240 On ch=3 n=64 v=95
153480 Off ch=3 n=64 v=80
153480 On ch=3 n=62 v=95
153600 Off ch=3 n=62 v=80
153840 On ch=3 n=67 v=95
154200 Off ch=3 n=67 v=80
154200 On ch=3 n=62 v=95
154560 Off ch=3 n=62 v=80
154560 On ch=3 n=67 v=95
154920 Off ch=3 n=67 v=80
154920 On ch=3 n=62 v=95
155400 Off ch=3 n=62 v=80
155400 On ch=3 n=67 v=95
155520 Off ch=3 n=67 v=80
155760 On ch=3 n=64 v=95
156120 Off ch=3 n=64 v=80
156120 On ch=3 n=62 v=95
156480 Off ch=3 n=62 v=80
156720 On ch=3 n=62 v=95
157080 Off ch=3 n=62 v=80
157080 On ch=3 n=64 v=95
157320 Off ch=3 n=64 v=80
157320 On ch=3 n=62 v=95
157440 Off ch=3 n=62 v=80
157680 On ch=3 n=67 v=95
158040 Off ch=3 n=67 v=80
158040 On ch=3 n=62 v=95
158400 Off ch=3 n=62 v=80
158400 On ch=4 n=62 v=95
158508 Pb ch=4 v=8832
158520 Off ch=4 n=62 v=80
158520 Pb ch=4 v=8192
158520 On ch=3 n=64 v=76
158760 Off ch=3 n=64 v=80
158760 On ch=3 n=67 v=95
159060 Off ch=3 n=67 v=80
159060 On ch=4 n=64 v=95
159114 Pb ch=4 v=7552
159120 Off ch=4 n=64 v=80
159120 Pb ch=4 v=8192
159120 On ch=3 n=62 v=76
159360 Off ch=3 n=62 v=80
159600 On ch=3 n=64 v=95
159960 Off ch=3 n=64 v=80
159960 On ch=3 n=62 v=95
160320 Off ch=3 n=62 v=80
160560 On ch=3 n=62 v=95
160920 Off ch=3 n=62 v=80
160920 On ch=3 n=64 v=95
161160 Off ch=3 n=64 v=80
161160 On ch=3 n=62 v=95
161280 Off ch=3 n=62 v=80
161520 On ch=3 n=67 v=95
161880 Off ch=3 n=67 v=80
161880 On ch=3 n=62 v=95
162240 Off ch=3 n=62 v=80
162240 On ch=3 n=45 v=95
162360 Off ch=3 n=45 v=80
162360 On ch=3 n=47 v=76
162480 Off ch=3 n=47 v=80
162480 On ch=3 n=50 v=95
162600 Off ch=3 n=50 v=80
162600 On ch=3 n=55 v=95
162720 Off ch=3 n=55 v=80
162720 On ch=3 n=52 v=95
162840 Off ch=3 n=52 v=80
162840 On ch=3 n=50 v=76
162960 Off ch=3 n=50 v=80
162960 On ch=3 n=45 v=95
163080 Off ch=3 n=45 v=80
163080 On ch=3 n=47 v=76
163200 Off ch=3 n=47 v=80
163200 On ch=3 n=57 v=95
163200 On ch=3 n=50 v=95
163440 Off ch=3 n=57 v=80
163440 Off ch=3 n=50 v=80
163440 On ch=3 n=57 v=95
163440 On ch=3 n=50 v=95
163680 Off ch=3 n=57 v=80
163680 Off ch=3 n=50 v=80
163680 On ch=3 n=59 v=95
163680 On ch=3 n=50 v=95
163800 Off ch=3 n=59 v=80
163800 Off ch=3 n=50 v=80
163800 On ch=3 n=57 v=95
163800 On ch=3 n=50 v=95
164160 Off ch=3 n=57 v=80
164160 Off ch=3 n=50 v=80
164160 On ch=3 n=55 v=95
164160 On ch=3 n=48 v=95
164400 Off ch=3 n=55 v=80
164400 Off ch=3 n=48 v=80
164400 On ch=3 n=55 v=95
164400 On ch=3 n=48 v=95
164640 Off ch=3 n=55 v=80
164640 Off ch=3 n=48 v=80
164640 On ch=3 n=57 v=95
164640 On ch=3 n=48 v=95
164760 Off ch=3 n=57 v=80
164760 Off ch=3 n=48 v=80
164760 On ch=3 n=55 v=95
164760 On ch=3 n=48 v=95
165000 Off ch=3 n=55 v=80
165000 Off ch=3 n=48 v=80
165000 On ch=3 n=55 v=95
165000 On ch=3 n=48 v=95
165120 Off ch=3 n=55 v=80
165120 Off ch=3 n=48 v=80
165120 On ch=3 n=50 v=95
165120 On ch=3 n=43 v=95
165360 Off ch=3 n=50 v=80
165360 Off ch=3 n=43 v=80
165360 On ch=3 n=50 v=95
165360 On ch=3 n=43 v=95
165600 Off ch=3 n=50 v=80
165600 Off ch=3 n=43 v=80
165600 On ch=3 n=44 v=95
165600 On ch=3 n=39 v=95
165620 Off ch=3 n=44 v=80
165620 Off ch=3 n=39 v=80
165720 On ch=3 n=44 v=95
165720 On ch=3 n=39 v=95
165740 Off ch=3 n=44 v=80
165740 Off ch=3 n=39 v=80
165840 On ch=3 n=52 v=95
165840 On ch=3 n=43 v=95
166080 Off ch=3 n=52 v=80
166080 Off ch=3 n=43 v=80
166080 On ch=3 n=44 v=95
166080 On ch=3 n=39 v=95
166100 Off ch=3 n=44 v=80
166100 Off ch=3 n=39 v=80
166320 On ch=3 n=50 v=95
166320 On ch=3 n=43 v=95
166560 Off ch=3 n=50 v=80
166560 Off ch=3 n=43 v=80
166560 On ch=3 n=44 v=95
166560 On ch=3 n=39 v=95
166580 Off ch=3 n=44 v=80
166580 Off ch=3 n=39 v=80
166680 On ch=3 n=62 v=95
166680 On ch=3 n=60 v=95
166680 On ch=3 n=55 v=95
166680 On ch=3 n=48 v=95
167040 Off ch=3 n=62 v=80
167040 Off ch=3 n=60 v=80
167040 Off ch=3 n=55 v=80
167040 Off ch=3 n=48 v=80
167040 On ch=3 n=57 v=95
167040 On ch=3 n=50 v=95
167280 Off ch=3 n=57 v=80
167280 Off ch=3 n=50 v=80
167280 On ch=3 n=57 v=95
167280 On ch=3 n=50 v=95
167520 Off ch=3 n=57 v=80
167520 Off ch=3 n=50 v=80
167520 On ch=3 n=59 v=95
167520 On ch=3 n=50 v=95
167640 Off ch=3 n=59 v=80
167640 Off ch=3 n=50 v=80
167640 On ch=3 n=57 v=95
167640 On ch=3 n=50 v=95
168000 Off ch=3 n=57 v=80
168000 Off ch=3 n=50 v=80
168000 On ch=3 n=55 v=95
168000 On ch=3 n=48 v=95
168240 Off ch=3 n=55 v=80
168240 Off ch=3 n=48 v=80
168240 On ch=3 n=55 v=95
168240 On ch=3 n=48 v=95
168480 Off ch=3 n=55 v=80
168480 Off ch=3 n=48 v=80
168480 On ch=3 n=57 v=95
168480 On ch=3 n=48 v=95
168600 Off ch=3 n=57 v=80
168600 Off ch=3 n=48 v=80
168600 On ch=3 n=55 v=95
168600 On ch=3 n=48 v=95
168840 Off ch=3 n=55 v=80
168840 Off ch=3 n=48 v=80
168840 On ch=3 n=55 v=95
168840 On ch=3 n=48 v=95
168960 Off ch=3 n=55 v=80
168960 Off ch=3 n=48 v=80
168960 On ch=3 n=44 v=95
168960 On ch=3 n=39 v=95
168980 Off ch=3 n=44 v=80
168980 Off ch=3 n=39 v=80
169200 On ch=3 n=50 v=95
169200 On ch=3 n=43 v=95
169440 Off ch=3 n=50 v=80
169440 Off ch=3 n=43 v=80
169440 On ch=3 n=44 v=95
169440 On ch=3 n=39 v=95
169460 Off ch=3 n=44 v=80
169460 Off ch=3 n=39 v=80
169560 On ch=3 n=50 v=95
169560 On ch=3 n=43 v=95
169680 Off ch=3 n=50 v=80
169680 Off ch=3 n=43 v=80
169680 On ch=3 n=50 v=95
169680 On ch=3 n=43 v=95
169920 Off ch=3 n=50 v=80
169920 Off ch=3 n=43 v=80
170160 On ch=3 n=55 v=95
170160 On ch=3 n=48 v=95
170400 Off ch=3 n=55 v=80
170400 Off ch=3 n=48 v=80
170640 On ch=4 n=55 v=95
170640 On ch=4 n=48 v=95
170856 Pb ch=4 v=8832
170856 Pb ch=4 v=8832
170880 Off ch=4 n=55 v=80
170880 Pb ch=4 v=8192
170880 Off ch=4 n=48 v=80
170880 Pb ch=4 v=8192
170880 On ch=3 n=57 v=76
170880 On ch=3 n=50 v=76
171120 Off ch=3 n=57 v=80
171120 Off ch=3 n=50 v=80
171120 On ch=3 n=57 v=95
171120 On ch=3 n=50 v=95
171360 Off ch=3 n=57 v=80
171360 Off ch=3 n=50 v=80
171360 On ch=3 n=59 v=95
171360 On ch=3 n=50 v=95
171480 Off ch=3 n=59 v=80
171480 Off ch=3 n=50 v=80
171480 On ch=3 n=57 v=95
171480 On ch=3 n=50 v=95
171840 Off ch=3 n=57 v=80
171840 Off ch=3 n=50 v=80
171840 On ch=3 n=55 v=95
171840 On ch=3 n=48 v=95
172080 Off ch=3 n=55 v=80
172080 Off ch=3 n=48 v=80
172080 On ch=3 n=55 v=95
172080 On ch=3 n=48 v=95
172320 Off ch=3 n=55 v=80
172320 Off ch=3 n=48 v=80
172320 On ch=3 n=57 v=95
172320 On ch=3 n=48 v=95
172440 Off ch=3 n=57 v=80
172440 Off ch=3 n=48 v=80
172440 On ch=3 n=55 v=95
172440 On ch=3 n=48 v=95
172680 Off ch=3 n=55 v=80
172680 Off ch=3 n=48 v=80
172680 On ch=3 n=55 v=95
172680 On ch=3 n=48 v=95
172800 Off ch=3 n=55 v=80
172800 Off ch=3 n=48 v=80
172800 On ch=3 n=50 v=95
172800 On ch=3 n=43 v=95
173040 Off ch=3 n=50 v=80
173040 Off ch=3 n=43 v=80
173040 On ch=3 n=50 v=95
173040 On ch=3 n=43 v=95
173280 Off ch=3 n=50 v=80
173280 Off ch=3 n=43 v=80
173280 On ch=3 n=44 v=95
173280 On ch=3 n=39 v=95
173300 Off ch=3 n=44 v=80
173300 Off ch=3 n=39 v=80
173400 On ch=3 n=44 v=95
173400 On ch=3 n=39 v=95
173420 Off ch=3 n=44 v=80
173420 Off ch=3 n=39 v=80
173520 On ch=3 n=52 v=95
173520 On ch=3 n=43 v=95
173760 Off ch=3 n=52 v=80
173760 Off ch=3 n=43 v=80
173760 On ch=3 n=44 v=95
173760 On ch=3 n=39 v=95
173780 Off ch=3 n=44 v=80
173780 Off ch=3 n=39 v=80
174000 On ch=3 n=50 v=95
174000 On ch=3 n=43 v=95
174240 Off ch=3 n=50 v=80
174240 Off ch=3 n=43 v=80
174240 On ch=3 n=49 v=95
174240 On ch=3 n=44 v=95
174260 Off ch=3 n=49 v=80
174260 Off ch=3 n=44 v=80
174360 On ch=3 n=62 v=95
174360 On ch=3 n=60 v=95
174360 On ch=3 n=55 v=95
174360 On ch=3 n=48 v=95
174720 Off ch=3 n=62 v=80
174720 Off ch=3 n=60 v=80
174720 Off ch=3 n=55 v=80
174720 Off ch=3 n=48 v=80
174720 On ch=3 n=57 v=95
174720 On ch=3 n=50 v=95
174960 Off ch=3 n=57 v=80
174960 Off ch=3 n=50 v=80
174960 On ch=3 n=57 v=95
174960 On ch=3 n=50 v=95
175200 Off ch=3 n=57 v=80
175200 Off ch=3 n=50 v=80
175200 On ch=3 n=59 v=95
175200 On ch=3 n=50 v=95
175320 Off ch=3 n=59 v=80
175320 Off ch=3 n=50 v=80
175320 On ch=3 n=57 v=95
175320 On ch=3 n=50 v=95
175680 Off ch=3 n=57 v=80
175680 Off ch=3 n=50 v=80
175680 On ch=3 n=55 v=95
175680 On ch=3 n=48 v=95
175920 Off ch=3 n=55 v=80
175920 Off ch=3 n=48 v=80
175920 On ch=3 n=55 v=95
175920 On ch=3 n=48 v=95
176160 Off ch=3 n=55 v=80
176160 Off ch=3 n=48 v=80
176160 On ch=3 n=57 v=95
176160 On ch=3 n=48 v=95
176280 Off ch=3 n=57 v=80
176280 Off ch=3 n=48 v=80
176280 On ch=3 n=55 v=95
176280 On ch=3 n=48 v=95
176520 Off ch=3 n=55 v=80
176520 Off ch=3 n=48 v=80
176520 On ch=3 n=55 v=95
176520 On ch=3 n=48 v=95
176640 Off ch=3 n=55 v=80
176640 Off ch=3 n=48 v=80
176640 On ch=3 n=43 v=95
176880 Off ch=3 n=43 v=80
176880 On ch=3 n=43 v=95
177120 Off ch=3 n=43 v=80
177120 On ch=3 n=55 v=95
177120 On ch=3 n=50 v=95
177360 Off ch=3 n=55 v=80
177360 Off ch=3 n=50 v=80
177360 On ch=3 n=55 v=95
177360 On ch=3 n=50 v=95
177360 On ch=3 n=43 v=95
177480 Off ch=3 n=55 v=80
177480 Off ch=3 n=50 v=80
177480 Off ch=3 n=43 v=80
177480 On ch=3 n=55 v=95
177480 On ch=3 n=50 v=95
177480 On ch=3 n=43 v=95
177600 Off ch=3 n=55 v=80
177600 Off ch=3 n=50 v=80
177600 Off ch=3 n=43 v=80
177600 On ch=3 n=65 v=95
177600 On ch=3 n=60 v=95
177600 On ch=3 n=57 v=95
177600 On ch=3 n=53 v=95
177600 On ch=3 n=48 v=95
178080 Off ch=3 n=65 v=80
178080 Off ch=3 n=60 v=80
178080 Off ch=3 n=57 v=80
178080 Off ch=3 n=53 v=80
178080 Off ch=3 n=48 v=80
178080 On ch=3 n=64 v=95
178080 On ch=3 n=60 v=95
178080 On ch=3 n=55 v=95
178080 On ch=3 n=52 v=95
178080 On ch=3 n=48 v=95
178560 Off ch=3 n=64 v=80
178560 Off ch=3 n=60 v=80
178560 Off ch=3 n=55 v=80
178560 Off ch=3 n=52 v=80
178560 Off ch=3 n=48 v=80
178560 On ch=3 n=57 v=95
178560 On ch=3 n=50 v=95
178800 Off ch=3 n=57 v=80
178800 Off ch=3 n=50 v=80
178800 On ch=3 n=57 v=95
178800 On ch=3 n=50 v=95
179040 Off ch=3 n=57 v=80
179040 Off ch=3 n=50 v=80
179040 On ch=3 n=59 v=95
179040 On ch=3 n=50 v=95
179160 Off ch=3 n=59 v=80
179160 Off ch=3 n=50 v=80
179160 On ch=3 n=57 v=95
179160 On ch=3 n=50 v=95
179520 Off ch=3 n=57 v=80
179520 Off ch=3 n=50 v=80
179520 On ch=3 n=55 v=95
179520 On ch=3 n=48 v=95
179760 Off ch=3 n=55 v=80
179760 Off ch=3 n=48 v=80
179760 On ch=3 n=55 v=95
179760 On ch=3 n=48 v=95
180000 Off ch=3 n=55 v=80
180000 Off ch=3 n=48 v=80
180000 On ch=3 n=57 v=95
180000 On ch=3 n=48 v=95
180120 Off ch=3 n=57 v=80
180120 Off ch=3 n=48 v=80
180120 On ch=3 n=55 v=95
180120 On ch=3 n=48 v=95
180360 Off ch=3 n=55 v=80
180360 Off ch=3 n=48 v=80
180360 On ch=3 n=55 v=95
180360 On ch=3 n=48 v=95
180480 Off ch=3 n=55 v=80
180480 Off ch=3 n=48 v=80
180480 On ch=3 n=50 v=95
180480 On ch=3 n=43 v=95
180720 Off ch=3 n=50 v=80
180720 Off ch=3 n=43 v=80
180720 On ch=3 n=50 v=95
180720 On ch=3 n=43 v=95
180960 Off ch=3 n=50 v=80
180960 Off ch=3 n=43 v=80
180960 On ch=3 n=44 v=95
180960 On ch=3 n=39 v=95
180980 Off ch=3 n=44 v=80
180980 Off ch=3 n=39 v=80
181080 On ch=3 n=44 v=95
181080 On ch=3 n=39 v=95
181100 Off ch=3 n=44 v=80
181100 Off ch=3 n=39 v=80
181200 On ch=3 n=52 v=95
181200 On ch=3 n=43 v=95
181440 Off ch=3 n=52 v=80
181440 Off ch=3 n=43 v=80
181440 On ch=3 n=44 v=95
181440 On ch=3 n=39 v=95
181460 Off ch=3 n=44 v=80
181460 Off ch=3 n=39 v=80
181680 On ch=3 n=50 v=95
181680 On ch=3 n=43 v=95
181920 Off ch=3 n=50 v=80
181920 Off ch=3 n=43 v=80
181920 On ch=3 n=44 v=95
181920 On ch=3 n=39 v=95
181940 Off ch=3 n=44 v=80
181940 Off ch=3 n=39 v=80
182040 On ch=3 n=62 v=95
182040 On ch=3 n=60 v=95
182040 On ch=3 n=55 v=95
182040 On ch=3 n=48 v=95
182400 Off ch=3 n=62 v=80
182400 Off ch=3 n=60 v=80
182400 Off ch=3 n=55 v=80
182400 Off ch=3 n=48 v=80
182400 On ch=3 n=57 v=95
182400 On ch=3 n=50 v=95
182640 Off ch=3 n=57 v=80
182640 Off ch=3 n=50 v=80
182640 On ch=3 n=57 v=95
182640 On ch=3 n=50 v=95
182880 Off ch=3 n=57 v=80
182880 Off ch=3 n=50 v=80
182880 On ch=3 n=59 v=95
182880 On ch=3 n=50 v=95
183000 Off ch=3 n=59 v=80
183000 Off ch=3 n=50 v=80
183000 On ch=3 n=57 v=95
183000 On ch=3 n=50 v=95
183360 Off ch=3 n=57 v=80
183360 Off ch=3 n=50 v=80
183360 On ch=3 n=55 v=95
183360 On ch=3 n=48 v=95
183600 Off ch=3 n=55 v=80
183600 Off ch=3 n=48 v=80
183600 On ch=3 n=55 v=95
183600 On ch=3 n=48 v=95
183840 Off ch=3 n=55 v=80
183840 Off ch=3 n=48 v=80
183840 On ch=3 n=57 v=95
183840 On ch=3 n=48 v=95
183960 Off ch=3 n=57 v=80
183960 Off ch=3 n=48 v=80
183960 On ch=3 n=55 v=95
183960 On ch=3 n=48 v=95
184200 Off ch=3 n=55 v=80
184200 Off ch=3 n=48 v=80
184200 On ch=3 n=55 v=95
184200 On ch=3 n=48 v=95
184320 Off ch=3 n=55 v=80
184320 Off ch=3 n=48 v=80
184320 On ch=3 n=44 v=95
184320 On ch=3 n=39 v=95
184340 Off ch=3 n=44 v=80
184340 Off ch=3 n=39 v=80
184560 On ch=3 n=50 v=95
184560 On ch=3 n=43 v=95
184800 Off ch=3 n=50 v=80
184800 Off ch=3 n=43 v=80
184800 On ch=3 n=44 v=95
184800 On ch=3 n=39 v=95
184820 Off ch=3 n=44 v=80
184820 Off ch=3 n=39 v=80
184920 On ch=3 n=50 v=95
184920 On ch=3 n=43 v=95
185040 Off ch=3 n=50 v=80
185040 Off ch=3 n=43 v=80
185040 On ch=3 n=50 v=95
185040 On ch=3 n=43 v=95
185280 Off ch=3 n=50 v=80
185280 Off ch=3 n=43 v=80
185520 On ch=3 n=55 v=95
185520 On ch=3 n=48 v=95
185760 Off ch=3 n=55 v=80
185760 Off ch=3 n=48 v=80
186000 On ch=4 n=55 v=95
186000 On ch=4 n=48 v=95
186216 Pb ch=4 v=8832
186216 Pb ch=4 v=8832
186240 Off ch=4 n=55 v=80
186240 Pb ch=4 v=8192
186240 Off ch=4 n=48 v=80
186240 Pb ch=4 v=8192
186240 On ch=3 n=57 v=76
186240 On ch=3 n=50 v=76
186480 Off ch=3 n=57 v=80
186480 Off ch=3 n=50 v=80
186480 On ch=3 n=57 v=95
186480 On ch=3 n=50 v=95
186720 Off ch=3 n=57 v=80
186720 Off ch=3 n=50 v=80
186720 On ch=3 n=59 v=95
186720 On ch=3 n=50 v=95
186840 Off ch=3 n=59 v=80
186840 Off ch=3 n=50 v=80
186840 On ch=3 n=57 v=95
186840 On ch=3 n=50 v=95
187200 Off ch=3 n=57 v=80
187200 Off ch=3 n=50 v=80
187200 On ch=3 n=55 v=95
187200 On ch=3 n=48 v=95
187440 Off ch=3 n=55 v=80
187440 Off ch=3 n=48 v=80
187440 On ch=3 n=55 v=95
187440 On ch=3 n=48 v=95
187680 Off ch=3 n=55 v=80
187680 Off ch=3 n=48 v=80
187680 On ch=3 n=57 v=95
187680 On ch=3 n=48 v=95
187800 Off ch=3 n=57 v=80
187800 Off ch=3 n=48 v=80
187800 On ch=3 n=55 v=95
187800 On ch=3 n=48 v=95
188040 Off ch=3 n=55 v=80
188040 Off ch=3 n=48 v=80
188040 On ch=3 n=55 v=95
188040 On ch=3 n=48 v=95
188160 Off ch=3 n=55 v=80
188160 Off ch=3 n=48 v=80
188160 On ch=3 n=50 v=95
188160 On ch=3 n=43 v=95
188400 Off ch=3 n=50 v=80
188400 Off ch=3 n=43 v=80
188400 On ch=3 n=50 v=95
188400 On ch=3 n=43 v=95
188640 Off ch=3 n=50 v=80
188640 Off ch=3 n=43 v=80
188640 On ch=3 n=44 v=95
188640 On ch=3 n=39 v=95
188660 Off ch=3 n=44 v=80
188660 Off ch=3 n=39 v=80
188760 On ch=3 n=44 v=95
188760 On ch=3 n=39 v=95
188780 Off ch=3 n=44 v=80
188780 Off ch=3 n=39 v=80
188880 On ch=3 n=52 v=95
188880 On ch=3 n=43 v=95
189120 Off ch=3 n=52 v=80
189120 Off ch=3 n=43 v=80
189120 On ch=3 n=44 v=95
189120 On ch=3 n=39 v=95
189140 Off ch=3 n=44 v=80
189140 Off ch=3 n=39 v=80
189360 On ch=3 n=50 v=95
189360 On ch=3 n=43 v=95
189600 Off ch=3 n=50 v=80
189600 Off ch=3 n=43 v=80
189600 On ch=3 n=49 v=95
189600 On ch=3 n=44 v=95
189620 Off ch=3 n=49 v=80
189620 Off ch=3 n=44 v=80
189720 On ch=3 n=62 v=95
189720 On ch=3 n=60 v=95
189720 On ch=3 n=55 v=95
189720 On ch=3 n=48 v=95
190080 Off ch=3 n=62 v=80
190080 Off ch=3 n=60 v=80
190080 Off ch=3 n=55 v=80
190080 Off ch=3 n=48 v=80
190080 On ch=3 n=57 v=95
190080 On ch=3 n=50 v=95
190320 Off ch=3 n=57 v=80
190320 Off ch=3 n=50 v=80
190320 On ch=3 n=57 v=95
190320 On ch=3 n=50 v=95
190560 Off ch=3 n=57 v=80
190560 Off ch=3 n=50 v=80
190560 On ch=3 n=59 v=95
190560 On ch=3 n=50 v=95
190680 Off ch=3 n=59 v=80
190680 Off ch=3 n=50 v=80
190680 On ch=3 n=57 v=95
190680 On ch=3 n=50 v=95
191040 Off ch=3 n=57 v=80
191040 Off ch=3 n=50 v=80
191040 On ch=3 n=55 v=95
191040 On ch=3 n=48 v=95
191280 Off ch=3 n=55 v=80
191280 Off ch=3 n=48 v=80
191280 On ch=3 n=55 v=95
191280 On ch=3 n=48 v=95
191520 Off ch=3 n=55 v=80
191520 Off ch=3 n=48 v=80
191520 On ch=3 n=57 v=95
191520 On ch=3 n=48 v=95
191640 Off ch=3 n=57 v=80
191640 Off ch=3 n=48 v=80
191640 On ch=3 n=55 v=95
191640 On ch=3 n=48 v=95
191880 Off ch=3 n=55 v=80
191880 Off ch=3 n=48 v=80
191880 On ch=3 n=55 v=95
191880 On ch=3 n=48 v=95
192000 Off ch=3 n=55 v=80
192000 Off ch=3 n=48 v=80
192000 On ch=3 n=50 v=95
192000 On ch=3 n=43 v=95
192240 Off ch=3 n=50 v=80
192240 Off ch=3 n=43 v=80
192240 On ch=3 n=50 v=95
192240 On ch=3 n=43 v=95
192480 Off ch=3 n=50 v=80
192480 Off ch=3 n=43 v=80
192480 On ch=3 n=44 v=95
192480 On ch=3 n=39 v=95
192500 Off ch=3 n=44 v=80
192500 Off ch=3 n=39 v=80
192600 On ch=3 n=44 v=95
192600 On ch=3 n=39 v=95
192620 Off ch=3 n=44 v=80
192620 Off ch=3 n=39 v=80
192720 On ch=3 n=52 v=95
192720 On ch=3 n=43 v=95
192960 Off ch=3 n=52 v=80
192960 Off ch=3 n=43 v=80
192960 On ch=3 n=44 v=95
192960 On ch=3 n=39 v=95
192980 Off ch=3 n=44 v=80
192980 Off ch=3 n=39 v=80
193080 On ch=3 n=50 v=95
193080 On ch=3 n=43 v=95
193200 Off ch=3 n=50 v=80
193200 Off ch=3 n=43 v=80
193200 On ch=3 n=50 v=95
193200 On ch=3 n=43 v=95
193320 Off ch=3 n=50 v=80
193320 Off ch=3 n=43 v=80
193320 On ch=3 n=50 v=95
193320 On ch=3 n=43 v=95
193440 Off ch=3 n=50 v=80
193440 Off ch=3 n=43 v=80
193440 On ch=3 n=44 v=95
193440 On ch=3 n=39 v=95
193460 Off ch=3 n=44 v=80
193460 Off ch=3 n=39 v=80
193560 On ch=3 n=44 v=95
193560 On ch=3 n=39 v=95
193580 Off ch=3 n=44 v=80
193580 Off ch=3 n=39 v=80
193800 On ch=3 n=50 v=95
193920 Off ch=3 n=50 v=80
193920 On ch=3 n=57 v=95
193920 On ch=3 n=50 v=95
194160 Off ch=3 n=57 v=80
194160 Off ch=3 n=50 v=80
194160 On ch=3 n=57 v=95
194160 On ch=3 n=50 v=95
194400 Off ch=3 n=57 v=80
194400 Off ch=3 n=50 v=80
194400 On ch=3 n=59 v=95
194400 On ch=3 n=50 v=95
194520 Off ch=3 n=59 v=80
194520 Off ch=3 n=50 v=80
194520 On ch=3 n=57 v=95
194520 On ch=3 n=50 v=95
194760 Off ch=3 n=57 v=80
194760 Off ch=3 n=50 v=80
194760 On ch=3 n=57 v=95
194760 On ch=3 n=50 v=95
194880 Off ch=3 n=57 v=80
194880 Off ch=3 n=50 v=80
194880 On ch=3 n=55 v=95
194880 On ch=3 n=48 v=95
195120 Off ch=3 n=55 v=80
195120 Off ch=3 n=48 v=80
195120 On ch=3 n=55 v=95
195120 On ch=3 n=48 v=95
195360 Off ch=3 n=55 v=80
195360 Off ch=3 n=48 v=80
195360 On ch=3 n=57 v=95
195360 On ch=3 n=48 v=95
195480 Off ch=3 n=57 v=80
195480 Off ch=3 n=48 v=80
195480 On ch=3 n=55 v=95
195480 On ch=3 n=48 v=95
195720 Off ch=3 n=55 v=80
195720 Off ch=3 n=48 v=80
195720 On ch=3 n=55 v=95
195720 On ch=3 n=48 v=95
195840 Off ch=3 n=55 v=80
195840 Off ch=3 n=48 v=80
195840 On ch=3 n=50 v=95
195840 On ch=3 n=43 v=95
196080 Off ch=3 n=50 v=80
196080 Off ch=3 n=43 v=80
196080 On ch=3 n=50 v=95
196080 On ch=3 n=43 v=95
196320 Off ch=3 n=50 v=80
196320 Off ch=3 n=43 v=80
196320 On ch=3 n=44 v=95
196320 On ch=3 n=39 v=95
196340 Off ch=3 n=44 v=80
196340 Off ch=3 n=39 v=80
196560 On ch=3 n=52 v=95
196560 On ch=3 n=43 v=95
196800 Off ch=3 n=52 v=80
196800 Off ch=3 n=43 v=80
196800 On ch=3 n=44 v=95
196800 On ch=3 n=39 v=95
196820 Off ch=3 n=44 v=80
196820 Off ch=3 n=39 v=80
196920 On ch=3 n=50 v=95
196920 On ch=3 n=43 v=95
197040 Off ch=3 n=50 v=80
197040 Off ch=3 n=43 v=80
197040 On ch=3 n=50 v=95
197040 On ch=3 n=43 v=95
197280 Off ch=3 n=50 v=80
197280 Off ch=3 n=43 v=80
197280 On ch=3 n=52 v=95
197280 On ch=3 n=43 v=95
197400 Off ch=3 n=52 v=80
197400 Off ch=3 n=43 v=80
197400 On ch=3 n=50 v=95
197400 On ch=3 n=43 v=95
197760 Off ch=3 n=50 v=80
197760 Off ch=3 n=43 v=80
197760 On ch=3 n=57 v=95
197760 On ch=3 n=50 v=95
198000 Off ch=3 n=57 v=80
198000 Off ch=3 n=50 v=80
198000 On ch=3 n=57 v=95
198000 On ch=3 n=50 v=95
198240 Off ch=3 n=57 v=80
198240 Off ch=3 n=50 v=80
198240 On ch=3 n=59 v=95
198240 On ch=3 n=50 v=95
198360 Off ch=3 n=59 v=80
198360 Off ch=3 n=50 v=80
198360 On ch=3 n=57 v=95
198360 On ch=3 n=50 v=95
198600 Off ch=3 n=57 v=80
198600 Off ch=3 n=50 v=80
198600 On ch=3 n=57 v=95
198600 On ch=3 n=50 v=95
198720 Off ch=3 n=57 v=80
198720 Off ch=3 n=50 v=80
198720 On ch=3 n=55 v=95
198720 On ch=3 n=48 v=95
198960 Off ch=3 n=55 v=80
198960 Off ch=3 n=48 v=80
198960 On ch=3 n=55 v=95
198960 On ch=3 n=48 v=95
199200 Off ch=3 n=55 v=80
199200 Off ch=3 n=48 v=80
199200 On ch=3 n=57 v=95
199200 On ch=3 n=48 v=95
199320 Off ch=3 n=57 v=80
199320 Off ch=3 n=48 v=80
199320 On ch=3 n=55 v=95
199320 On ch=3 n=48 v=95
199560 Off ch=3 n=55 v=80
199560 Off ch=3 n=48 v=80
199560 On ch=3 n=55 v=95
199560 On ch=3 n=48 v=95
199680 Off ch=3 n=55 v=80
199680 Off ch=3 n=48 v=80
199680 On ch=3 n=50 v=95
199680 On ch=3 n=43 v=95
199920 Off ch=3 n=50 v=80
199920 Off ch=3 n=43 v=80
199920 On ch=3 n=50 v=95
199920 On ch=3 n=43 v=95
200160 Off ch=3 n=50 v=80
200160 Off ch=3 n=43 v=80
200160 On ch=3 n=44 v=95
200160 On ch=3 n=39 v=95
200180 Off ch=3 n=44 v=80
200180 Off ch=3 n=39 v=80
200400 On ch=3 n=52 v=95
200400 On ch=3 n=43 v=95
200640 Off ch=3 n=52 v=80
200640 Off ch=3 n=43 v=80
200640 On ch=3 n=44 v=95
200640 On ch=3 n=39 v=95
200660 Off ch=3 n=44 v=80
200660 Off ch=3 n=39 v=80
200760 On ch=3 n=50 v=95
200760 On ch=3 n=43 v=95
200880 Off ch=3 n=50 v=80
200880 Off ch=3 n=43 v=80
200880 On ch=3 n=50 v=95
200880 On ch=3 n=43 v=95
201120 Off ch=3 n=50 v=80
201120 Off ch=3 n=43 v=80
201120 On ch=3 n=52 v=95
201120 On ch=3 n=43 v=95
201240 Off ch=3 n=52 v=80
201240 Off ch=3 n=43 v=80
201240 On ch=3 n=50 v=95
201240 On ch=3 n=43 v=95
201600 Off ch=3 n=50 v=80
201600 Off ch=3 n=43 v=80
201600 On ch=3 n=57 v=95
201600 On ch=3 n=50 v=95
201840 Off ch=3 n=57 v=80
201840 Off ch=3 n=50 v=80
201840 On ch=3 n=57 v=95
201840 On ch=3 n=50 v=95
202080 Off ch=3 n=57 v=80
202080 Off ch=3 n=50 v=80
202080 On ch=3 n=59 v=95
202080 On ch=3 n=50 v=95
202200 Off ch=3 n=59 v=80
202200 Off ch=3 n=50 v=80
202200 On ch=3 n=57 v=95
202200 On ch=3 n=50 v=95
202440 Off ch=3 n=57 v=80
202440 Off ch=3 n=50 v=80
202440 On ch=3 n=57 v=95
202440 On ch=3 n=50 v=95
202560 Off ch=3 n=57 v=80
202560 Off ch=3 n=50 v=80
202560 On ch=3 n=55 v=95
202560 On ch=3 n=48 v=95
202800 Off ch=3 n=55 v=80
202800 Off ch=3 n=48 v=80
202800 On ch=3 n=55 v=95
202800 On ch=3 n=48 v=95
203040 Off ch=3 n=55 v=80
203040 Off ch=3 n=48 v=80
203040 On ch=3 n=57 v=95
203040 On ch=3 n=48 v=95
203160 Off ch=3 n=57 v=80
203160 Off ch=3 n=48 v=80
203160 On ch=3 n=55 v=95
203160 On ch=3 n=48 v=95
203400 Off ch=3 n=55 v=80
203400 Off ch=3 n=48 v=80
203400 On ch=3 n=55 v=95
203400 On ch=3 n=48 v=95
203520 Off ch=3 n=55 v=80
203520 Off ch=3 n=48 v=80
203520 On ch=3 n=50 v=95
203520 On ch=3 n=43 v=95
203760 Off ch=3 n=50 v=80
203760 Off ch=3 n=43 v=80
203760 On ch=3 n=50 v=95
203760 On ch=3 n=43 v=95
204000 Off ch=3 n=50 v=80
204000 Off ch=3 n=43 v=80
204000 On ch=3 n=44 v=95
204000 On ch=3 n=39 v=95
204020 Off ch=3 n=44 v=80
204020 Off ch=3 n=39 v=80
204240 On ch=3 n=52 v=95
204240 On ch=3 n=43 v=95
204480 Off ch=3 n=52 v=80
204480 Off ch=3 n=43 v=80
204480 On ch=3 n=44 v=95
204480 On ch=3 n=39 v=95
204500 Off ch=3 n=44 v=80
204500 Off ch=3 n=39 v=80
204600 On ch=3 n=50 v=95
204600 On ch=3 n=43 v=95
204720 Off ch=3 n=50 v=80
204720 Off ch=3 n=43 v=80
204720 On ch=3 n=50 v=95
204720 On ch=3 n=43 v=95
204960 Off ch=3 n=50 v=80
204960 Off ch=3 n=43 v=80
204960 On ch=3 n=52 v=95
204960 On ch=3 n=43 v=95
205080 Off ch=3 n=52 v=80
205080 Off ch=3 n=43 v=80
205080 On ch=3 n=50 v=95
205080 On ch=3 n=43 v=95
205440 Off ch=3 n=50 v=80
205440 Off ch=3 n=43 v=80
205440 On ch=3 n=57 v=95
205440 On ch=3 n=50 v=95
205680 Off ch=3 n=57 v=80
205680 Off ch=3 n=50 v=80
205680 On ch=3 n=57 v=95
205680 On ch=3 n=50 v=95
205920 Off ch=3 n=57 v=80
205920 Off ch=3 n=50 v=80
205920 On ch=3 n=59 v=95
205920 On ch=3 n=50 v=95
206040 Off ch=3 n=59 v=80
206040 Off ch=3 n=50 v=80
206040 On ch=3 n=57 v=95
206040 On ch=3 n=50 v=95
206280 Off ch=3 n=57 v=80
206280 Off ch=3 n=50 v=80
206280 On ch=3 n=57 v=95
206280 On ch=3 n=50 v=95
206400 Off ch=3 n=57 v=80
206400 Off ch=3 n=50 v=80
206400 On ch=3 n=55 v=95
206400 On ch=3 n=48 v=95
206640 Off ch=3 n=55 v=80
206640 Off ch=3 n=48 v=80
206640 On ch=3 n=55 v=95
206640 On ch=3 n=48 v=95
206880 Off ch=3 n=55 v=80
206880 Off ch=3 n=48 v=80
206880 On ch=3 n=57 v=95
206880 On ch=3 n=48 v=95
207000 Off ch=3 n=57 v=80
207000 Off ch=3 n=48 v=80
207000 On ch=3 n=55 v=95
207000 On ch=3 n=48 v=95
207240 Off ch=3 n=55 v=80
207240 Off ch=3 n=48 v=80
207240 On ch=3 n=55 v=95
207240 On ch=3 n=48 v=95
207360 Off ch=3 n=55 v=80
207360 Off ch=3 n=48 v=80
207360 On ch=3 n=50 v=95
207360 On ch=3 n=43 v=95
207600 Off ch=3 n=50 v=80
207600 Off ch=3 n=43 v=80
207600 On ch=3 n=50 v=95
207600 On ch=3 n=43 v=95
207840 Off ch=3 n=50 v=80
207840 Off ch=3 n=43 v=80
207840 On ch=3 n=52 v=95
207840 On ch=3 n=43 v=95
208080 Off ch=3 n=52 v=80
208080 Off ch=3 n=43 v=80
208080 On ch=3 n=52 v=95
208080 On ch=3 n=43 v=95
208320 Off ch=3 n=52 v=80
208320 Off ch=3 n=43 v=80
208320 On ch=3 n=45 v=95
208440 Off ch=3 n=45 v=80
208440 On ch=3 n=47 v=76
208560 Off ch=3 n=47 v=80
208560 On ch=3 n=50 v=95
208680 Off ch=3 n=50 v=80
208680 On ch=3 n=52 v=76
208800 Off ch=3 n=52 v=80
208800 On ch=3 n=50 v=95
209040 Off ch=3 n=50 v=80
209040 On ch=4 n=48 v=95
209040 Pb ch=4 v=8192
209088 Pb ch=4 v=8320
209120 Pb ch=4 v=8448
209152 Pb ch=4 v=8576
209280 Off ch=4 n=48 v=80
209280 Pb ch=4 v=8192
209280 On ch=3 n=57 v=95
209280 On ch=3 n=50 v=95
209520 Off ch=3 n=57 v=80
209520 Off ch=3 n=50 v=80
209520 On ch=3 n=57 v=95
209520 On ch=3 n=50 v=95
209760 Off ch=3 n=57 v=80
209760 Off ch=3 n=50 v=80
209760 On ch=3 n=59 v=95
209760 On ch=3 n=50 v=95
209880 Off ch=3 n=59 v=80
209880 Off ch=3 n=50 v=80
209880 On ch=3 n=57 v=95
209880 On ch=3 n=50 v=95
210120 Off ch=3 n=57 v=80
210120 Off ch=3 n=50 v=80
210120 On ch=3 n=57 v=95
210120 On ch=3 n=50 v=95
210240 Off ch=3 n=57 v=80
210240 Off ch=3 n=50 v=80
210240 On ch=3 n=55 v=95
210240 On ch=3 n=48 v=95
210480 Off ch=3 n=55 v=80
210480 Off ch=3 n=48 v=80
210480 On ch=3 n=55 v=95
210480 On ch=3 n=48 v=95
210720 Off ch=3 n=55 v=80
210720 Off ch=3 n=48 v=80
210720 On ch=3 n=57 v=95
210720 On ch=3 n=48 v=95
210840 Off ch=3 n=57 v=80
210840 Off ch=3 n=48 v=80
210840 On ch=3 n=55 v=95
210840 On ch=3 n=48 v=95
211080 Off ch=3 n=55 v=80
211080 Off ch=3 n=48 v=80
211080 On ch=3 n=55 v=95
211080 On ch=3 n=48 v=95
211200 Off ch=3 n=55 v=80
211200 Off ch=3 n=48 v=80
211200 On ch=3 n=50 v=95
211200 On ch=3 n=43 v=95
211440 Off ch=3 n=50 v=80
211440 Off ch=3 n=43 v=80
211440 On ch=3 n=50 v=95
211440 On ch=3 n=43 v=95
211680 Off ch=3 n=50 v=80
211680 Off ch=3 n=43 v=80
211680 On ch=3 n=52 v=95
211680 On ch=3 n=43 v=95
211920 Off ch=3 n=52 v=80
211920 Off ch=3 n=43 v=80
211920 On ch=3 n=52 v=95
211920 On ch=3 n=43 v=95
212160 Off ch=3 n=52 v=80
212160 Off ch=3 n=43 v=80
212160 On ch=3 n=45 v=95
212280 Off ch=3 n=45 v=80
212280 On ch=3 n=47 v=76
212400 Off ch=3 n=47 v=80
212400 On ch=3 n=50 v=95
212520 Off ch=3 n=50 v=80
212520 On ch=3 n=52 v=76
212640 Off ch=3 n=52 v=80
212640 On ch=3 n=50 v=95
212880 Off ch=3 n=50 v=80
212880 On ch=4 n=48 v=95
212880 Pb ch=4 v=8192
212928 Pb ch=4 v=8320
212960 Pb ch=4 v=8448
212992 Pb ch=4 v=8576
213120 Off ch=4 n=48 v=80
213120 Pb ch=4 v=8192
213120 On ch=3 n=57 v=95
213120 On ch=3 n=50 v=95
213360 Off ch=3 n=57 v=80
213360 Off ch=3 n=50 v=80
213360 On ch=3 n=57 v=95
213360 On ch=3 n=50 v=95
213600 Off ch=3 n=57 v=80
213600 Off ch=3 n=50 v=80
213600 On ch=3 n=59 v=95
213600 On ch=3 n=50 v=95
213720 Off ch=3 n=59 v=80
213720 Off ch=3 n=50 v=80
213720 On ch=3 n=57 v=95
213720 On ch=3 n=50 v=95
213960 Off ch=3 n=57 v=80
213960 Off ch=3 n=50 v=80
213960 On ch=3 n=57 v=95
213960 On ch=3 n=50 v=95
214080 Off ch=3 n=57 v=80
214080 Off ch=3 n=50 v=80
214080 On ch=3 n=55 v=95
214080 On ch=3 n=48 v=95
214320 Off ch=3 n=55 v=80
214320 Off ch=3 n=48 v=80
214320 On ch=3 n=55 v=95
214320 On ch=3 n=48 v=95
214560 Off ch=3 n=55 v=80
214560 Off ch=3 n=48 v=80
214560 On ch=3 n=57 v=95
214560 On ch=3 n=48 v=95
214680 Off ch=3 n=57 v=80
214680 Off ch=3 n=48 v=80
214680 On ch=3 n=55 v=95
214680 On ch=3 n=48 v=95
214920 Off ch=3 n=55 v=80
214920 Off ch=3 n=48 v=80
214920 On ch=3 n=55 v=95
214920 On ch=3 n=48 v=95
215040 Off ch=3 n=55 v=80
215040 Off ch=3 n=48 v=80
215040 On ch=3 n=50 v=95
215040 On ch=3 n=43 v=95
215280 Off ch=3 n=50 v=80
215280 Off ch=3 n=43 v=80
215280 On ch=3 n=50 v=95
215280 On ch=3 n=43 v=95
215520 Off ch=3 n=50 v=80
215520 Off ch=3 n=43 v=80
215520 On ch=3 n=52 v=95
215520 On ch=3 n=43 v=95
215760 Off ch=3 n=52 v=80
215760 Off ch=3 n=43 v=80
215760 On ch=3 n=52 v=95
215760 On ch=3 n=43 v=95
216000 Off ch=3 n=52 v=80
216000 Off ch=3 n=43 v=80
216000 On ch=3 n=45 v=95
216120 Off ch=3 n=45 v=80
216120 On ch=3 n=47 v=76
216240 Off ch=3 n=47 v=80
216240 On ch=3 n=50 v=95
216360 Off ch=3 n=50 v=80
216360 On ch=3 n=52 v=76
216480 Off ch=3 n=52 v=80
216480 On ch=3 n=50 v=95
216720 Off ch=3 n=50 v=80
216720 On ch=4 n=48 v=95
216720 Pb ch=4 v=8192
216768 Pb ch=4 v=8320
216800 Pb ch=4 v=8448
216832 Pb ch=4 v=8576
216960 Off ch=4 n=48 v=80
216960 Pb ch=4 v=8192
216960 On ch=3 n=57 v=95
216960 On ch=3 n=50 v=95
217200 Off ch=3 n=57 v=80
217200 Off ch=3 n=50 v=80
217200 On ch=3 n=57 v=95
217200 On ch=3 n=50 v=95
217440 Off ch=3 n=57 v=80
217440 Off ch=3 n=50 v=80
217440 On ch=3 n=59 v=95
217440 On ch=3 n=50 v=95
217560 Off ch=3 n=59 v=80
217560 Off ch=3 n=50 v=80
217560 On ch=3 n=57 v=95
217560 On ch=3 n=50 v=95
217800 Off ch=3 n=57 v=80
217800 Off ch=3 n=50 v=80
217800 On ch=3 n=57 v=95
217800 On ch=3 n=50 v=95
217920 Off ch=3 n=57 v=80
217920 Off ch=3 n=50 v=80
217920 On ch=3 n=55 v=95
217920 On ch=3 n=48 v=95
218160 Off ch=3 n=55 v=80
218160 Off ch=3 n=48 v=80
218160 On ch=3 n=55 v=95
218160 On ch=3 n=48 v=95
218400 Off ch=3 n=55 v=80
218400 Off ch=3 n=48 v=80
218400 On ch=3 n=57 v=95
218400 On ch=3 n=48 v=95
218520 Off ch=3 n=57 v=80
218520 Off ch=3 n=48 v=80
218520 On ch=3 n=55 v=95
218520 On ch=3 n=48 v=95
218760 Off ch=3 n=55 v=80
218760 Off ch=3 n=48 v=80
218760 On ch=3 n=55 v=95
218760 On ch=3 n=48 v=95
218880 Off ch=3 n=55 v=80
218880 Off ch=3 n=48 v=80
218880 On ch=3 n=50 v=95
218880 On ch=3 n=43 v=95
219120 Off ch=3 n=50 v=80
219120 Off ch=3 n=43 v=80
219120 On ch=3 n=50 v=95
219120 On ch=3 n=43 v=95
219360 Off ch=3 n=50 v=80
219360 Off ch=3 n=43 v=80
219360 On ch=3 n=52 v=95
219360 On ch=3 n=43 v=95
219600 Off ch=3 n=52 v=80
219600 Off ch=3 n=43 v=80
219600 On ch=3 n=52 v=95
219600 On ch=3 n=43 v=95
219840 Off ch=3 n=52 v=80
219840 Off ch=3 n=43 v=80
219840 On ch=3 n=45 v=95
219960 Off ch=3 n=45 v=80
219960 On ch=3 n=47 v=76
220080 Off ch=3 n=47 v=80
220080 On ch=3 n=50 v=95
220200 Off ch=3 n=50 v=80
220200 On ch=3 n=52 v=76
220320 Off ch=3 n=52 v=80
220320 On ch=3 n=50 v=95
220560 Off ch=3 n=50 v=80
220560 On ch=4 n=48 v=95
220560 Pb ch=4 v=8192
220608 Pb ch=4 v=8320
220640 Pb ch=4 v=8448
220672 Pb ch=4 v=8576
220800 Off ch=4 n=48 v=80
220800 Pb ch=4 v=8192
220800 Meta TrkEnd
TrkEnd
MTrk
0 Meta TrkName 'Bass'
0 Par ch=16 c=100 v=0
0 Par ch=16 c=101 v=0
0 Par ch=16 c=6 v=12
0 Pb ch=16 v=8192
0 Par ch=16 c=101 v=0
0 Par ch=16 c=100 v=1
0 Par ch=16 c=6 v=64
0 Par ch=16 c=38 v=0
0 Par ch=16 c=101 v=127
0 Par ch=16 c=100 v=127
0 Par ch=15 c=100 v=0
0 Par ch=15 c=101 v=0
0 Par ch=15 c=6 v=12
0 Pb ch=15 v=8192
0 Par ch=15 c=101 v=0
0 Par ch=15 c=100 v=1
0 Par ch=15 c=6 v=64
0 Par ch=15 c=38 v=0
0 Par ch=15 c=101 v=127
0 Par ch=15 c=100 v=127
0 Par ch=16 c=101 v=0
0 Par ch=16 c=100 v=2
0 Par ch=16 c=6 v=64
0 Par ch=16 c=101 v=127
0 Par ch=16 c=100 v=127
0 Par ch=15 c=101 v=0
0 Par ch=15 c=100 v=2
0 Par ch=15 c=6 v=64
0 Par ch=15 c=101 v=127
0 Par ch=15 c=100 v=127
0 PrCh ch=16 p=33
0 PrCh ch=15 p=33
0 Par ch=16 c=0 v=0
0 Par ch=15 c=0 v=0
0 Par ch=16 c=7 v=127
0 Par ch=15 c=7 v=127
0 Par ch=16 c=10 v=64
0 Par ch=15 c=10 v=64
0 Par ch=16 c=93 v=0
0 Par ch=15 c=93 v=0
0 Par ch=16 c=91 v=96
0 Par ch=15 c=91 v=96
0 Par ch=16 c=92 v=0
0 Par ch=15 c=92 v=0
0 Par ch=16 c=95 v=0
0 Par ch=15 c=95 v=0
9600 On ch=15 n=38 v=95
9840 Off ch=15 n=38 v=80
9840 On ch=15 n=38 v=95
10080 Off ch=15 n=38 v=80
10440 On ch=15 n=32 v=95
10460 Off ch=15 n=32 v=80
10560 On ch=15 n=36 v=95
10800 Off ch=15 n=36 v=80
10800 On ch=15 n=36 v=95
11040 Off ch=15 n=36 v=80
11520 On ch=15 n=31 v=95
11760 Off ch=15 n=31 v=80
11760 On ch=15 n=31 v=95
12240 Off ch=15 n=31 v=80
12480 On ch=15 n=31 v=95
12720 Off ch=15 n=31 v=80
12720 On ch=15 n=31 v=95
12960 Off ch=15 n=31 v=80
13440 On ch=15 n=38 v=95
13680 Off ch=15 n=38 v=80
13680 On ch=15 n=38 v=95
13920 Off ch=15 n=38 v=80
14280 On ch=15 n=32 v=95
14300 Off ch=15 n=32 v=80
14400 On ch=15 n=36 v=95
14640 Off ch=15 n=36 v=80
14640 On ch=15 n=36 v=95
14880 Off ch=15 n=36 v=80
15360 On ch=15 n=31 v=95
15600 Off ch=15 n=31 v=80
15600 On ch=15 n=31 v=95
15840 Off ch=15 n=31 v=80
16320 On ch=15 n=31 v=95
16560 Off ch=15 n=31 v=80
16560 On ch=15 n=31 v=95
17040 Off ch=15 n=31 v=80
17040 On ch=16 n=33 v=95
17148 Pb ch=16 v=8832
17160 Off ch=16 n=33 v=80
17160 Pb ch=16 v=8192
17160 On ch=15 n=35 v=76
17280 Off ch=15 n=35 v=80
17280 On ch=15 n=38 v=95
17520 Off ch=15 n=38 v=80
17520 On ch=15 n=38 v=95
18000 Off ch=15 n=38 v=80
18120 On ch=15 n=33 v=95
18240 Off ch=15 n=33 v=80
18240 On ch=15 n=36 v=95
18480 Off ch=15 n=36 v=80
18480 On ch=15 n=36 v=95
18960 Off ch=15 n=36 v=80
19080 On ch=15 n=28 v=95
19200 Off ch=15 n=28 v=80
21120 On ch=15 n=38 v=95
21360 Off ch=15 n=38 v=80
21360 On ch=15 n=38 v=95
21840 Off ch=15 n=38 v=80
21960 On ch=15 n=33 v=95
22080 Off ch=15 n=33 v=80
22080 On ch=15 n=36 v=95
22320 Off ch=15 n=36 v=80
22320 On ch=15 n=36 v=95
22800 Off ch=15 n=36 v=80
22920 On ch=15 n=28 v=95
23040 Off ch=15 n=28 v=80
24960 On ch=15 n=38 v=95
25200 Off ch=15 n=38 v=80
25200 On ch=15 n=38 v=95
25680 Off ch=15 n=38 v=80
25800 On ch=15 n=33 v=95
25920 Off ch=15 n=33 v=80
25920 On ch=15 n=36 v=95
26160 Off ch=15 n=36 v=80
26160 On ch=15 n=36 v=95
26400 Off ch=15 n=36 v=80
26400 On ch=15 n=36 v=95
26760 Off ch=15 n=36 v=80
26760 On ch=15 n=28 v=95
26880 Off ch=15 n=28 v=80
26880 On ch=15 n=31 v=95
27120 Off ch=15 n=31 v=80
27120 On ch=15 n=31 v=95
27600 Off ch=15 n=31 v=80
27720 On ch=15 n=28 v=95
27840 Off ch=15 n=28 v=80
27840 On ch=15 n=31 v=95
28080 Off ch=15 n=31 v=80
28080 On ch=15 n=31 v=95
28560 Off ch=15 n=31 v=80
28560 On ch=16 n=33 v=95
28668 Pb ch=16 v=8832
28680 Off ch=16 n=33 v=80
28680 Pb ch=16 v=8192
28680 On ch=15 n=35 v=76
28800 Off ch=15 n=35 v=80
28800 On ch=15 n=38 v=95
29040 Off ch=15 n=38 v=80
29040 On ch=15 n=38 v=95
29280 Off ch=15 n=38 v=80
29280 On ch=15 n=38 v=95
29640 Off ch=15 n=38 v=80
29640 On ch=15 n=33 v=95
29760 Off ch=15 n=33 v=80
29760 On ch=15 n=36 v=95
30000 Off ch=15 n=36 v=80
30000 On ch=15 n=36 v=95
30480 Off ch=15 n=36 v=80
30600 On ch=15 n=28 v=95
30720 Off ch=15 n=28 v=80
30720 On ch=15 n=31 v=95
30960 Off ch=15 n=31 v=80
30960 On ch=15 n=31 v=95
31440 Off ch=15 n=31 v=80
31560 On ch=15 n=28 v=95
31680 Off ch=15 n=28 v=80
31680 On ch=15 n=31 v=95
31920 Off ch=15 n=31 v=80
31920 On ch=15 n=31 v=95
32400 Off ch=15 n=31 v=80
32400 On ch=16 n=33 v=95
32508 Pb ch=16 v=8832
32520 Off ch=16 n=33 v=80
32520 Pb ch=16 v=8192
32520 On ch=15 n=35 v=76
32640 Off ch=15 n=35 v=80
32640 On ch=15 n=38 v=95
32880 Off ch=15 n=38 v=80
32880 On ch=15 n=38 v=95
33120 Off ch=15 n=38 v=80
33120 On ch=15 n=33 v=95
33240 Off ch=15 n=33 v=80
33240 On ch=15 n=35 v=95
33600 Off ch=15 n=35 v=80
33600 On ch=15 n=43 v=95
33840 Off ch=15 n=43 v=80
33840 On ch=15 n=48 v=95
34080 Off ch=15 n=48 v=80
34080 On ch=15 n=48 v=95
34320 Off ch=15 n=48 v=80
34320 On ch=16 n=43 v=95
34464 Pb ch=16 v=7552
34478 Pb ch=16 v=6784
34491 Pb ch=16 v=6144
34505 Pb ch=16 v=5504
34519 Pb ch=16 v=4736
34533 Pb ch=16 v=4096
34546 Pb ch=16 v=3456
34560 Off ch=16 n=43 v=80
34560 Pb ch=16 v=8192
34560 On ch=15 n=31 v=95
34800 Off ch=15 n=31 v=80
34800 On ch=15 n=31 v=95
35040 Off ch=15 n=31 v=80
35040 On ch=15 n=43 v=95
35280 Off ch=15 n=43 v=80
35400 On ch=15 n=28 v=95
35520 Off ch=15 n=28 v=80
35520 On ch=15 n=31 v=95
35760 Off ch=15 n=31 v=80
35760 On ch=15 n=31 v=95
36000 Off ch=15 n=31 v=80
36000 On ch=15 n=43 v=95
36240 Off ch=15 n=43 v=80
36240 On ch=16 n=35 v=76
36444 Pb ch=16 v=8832
36462 Pb ch=16 v=9600
36480 Off ch=16 n=35 v=80
36480 Pb ch=16 v=8192
36480 On ch=15 n=38 v=76
36720 Off ch=15 n=38 v=80
36720 On ch=15 n=38 v=95
36960 Off ch=15 n=38 v=80
36960 On ch=15 n=33 v=95
37080 Off ch=15 n=33 v=80
37080 On ch=15 n=35 v=95
37440 Off ch=15 n=35 v=80
37440 On ch=15 n=43 v=95
37680 Off ch=15 n=43 v=80
37680 On ch=15 n=48 v=95
37920 Off ch=15 n=48 v=80
37920 On ch=15 n=48 v=95
38160 Off ch=15 n=48 v=80
38160 On ch=16 n=43 v=95
38304 Pb ch=16 v=7552
38318 Pb ch=16 v=6784
38331 Pb ch=16 v=6144
38345 Pb ch=16 v=5504
38359 Pb ch=16 v=4736
38373 Pb ch=16 v=4096
38386 Pb ch=16 v=3456
38400 Off ch=16 n=43 v=80
38400 Pb ch=16 v=8192
38400 On ch=15 n=31 v=95
38640 Off ch=15 n=31 v=80
38640 On ch=15 n=31 v=95
38880 Off ch=15 n=31 v=80
38880 On ch=15 n=43 v=95
39120 Off ch=15 n=43 v=80
39240 On ch=15 n=28 v=95
39360 Off ch=15 n=28 v=80
39360 On ch=15 n=31 v=95
39600 Off ch=15 n=31 v=80
39600 On ch=15 n=31 v=95
39840 Off ch=15 n=31 v=80
39840 On ch=15 n=43 v=95
40080 Off ch=15 n=43 v=80
40080 On ch=16 n=35 v=76
40284 Pb ch=16 v=8832
40302 Pb ch=16 v=9600
40320 Off ch=16 n=35 v=80
40320 Pb ch=16 v=8192
40320 On ch=15 n=38 v=76
40560 Off ch=15 n=38 v=80
40560 On ch=15 n=38 v=95
41040 Off ch=15 n=38 v=80
41160 On ch=15 n=33 v=95
41280 Off ch=15 n=33 v=80
41280 On ch=15 n=36 v=95
41520 Off ch=15 n=36 v=80
41520 On ch=15 n=36 v=95
42000 Off ch=15 n=36 v=80
42120 On ch=15 n=28 v=95
42240 Off ch=15 n=28 v=80
42240 On ch=15 n=31 v=95
42480 Off ch=15 n=31 v=80
42480 On ch=15 n=31 v=95
43080 Off ch=15 n=31 v=80
43080 On ch=15 n=27 v=95
43100 Off ch=15 n=27 v=80
43200 On ch=15 n=31 v=95
43440 Off ch=15 n=31 v=80
43440 On ch=15 n=31 v=95
43680 Off ch=15 n=31 v=80
43920 On ch=16 n=33 v=95
44028 Pb ch=16 v=8832
44040 Off ch=16 n=33 v=80
44040 Pb ch=16 v=8192
44040 On ch=15 n=35 v=76
44160 Off ch=15 n=35 v=80
44160 On ch=15 n=38 v=95
44400 Off ch=15 n=38 v=80
44400 On ch=15 n=38 v=95
44880 Off ch=15 n=38 v=80
45000 On ch=15 n=33 v=95
45120 Off ch=15 n=33 v=80
45120 On ch=15 n=36 v=95
45360 Off ch=15 n=36 v=80
45360 On ch=15 n=36 v=95
45840 Off ch=15 n=36 v=80
45960 On ch=15 n=28 v=95
46080 Off ch=15 n=28 v=80
46080 On ch=15 n=31 v=95
46320 Off ch=15 n=31 v=80
46320 On ch=15 n=31 v=95
46800 Off ch=15 n=31 v=80
46920 On ch=15 n=27 v=95
46940 Off ch=15 n=27 v=80
47040 On ch=15 n=31 v=95
47280 Off ch=15 n=31 v=80
47280 On ch=15 n=31 v=95
47520 Off ch=15 n=31 v=80
47520 On ch=15 n=43 v=95
47640 Off ch=15 n=43 v=80
47760 On ch=16 n=33 v=95
47868 Pb ch=16 v=8832
47880 Off ch=16 n=33 v=80
47880 Pb ch=16 v=8192
47880 On ch=15 n=35 v=76
48000 Off ch=15 n=35 v=80
48000 On ch=15 n=38 v=95
48240 Off ch=15 n=38 v=80
48240 On ch=15 n=38 v=95
48720 Off ch=15 n=38 v=80
48840 On ch=15 n=33 v=95
48960 Off ch=15 n=33 v=80
48960 On ch=15 n=36 v=95
49200 Off ch=15 n=36 v=80
49200 On ch=15 n=36 v=95
49680 Off ch=15 n=36 v=80
49800 On ch=15 n=28 v=95
49920 Off ch=15 n=28 v=80
49920 On ch=15 n=31 v=95
50160 Off ch=15 n=31 v=80
50160 On ch=15 n=31 v=95
50760 Off ch=15 n=31 v=80
50760 On ch=15 n=27 v=95
50780 Off ch=15 n=27 v=80
50880 On ch=15 n=31 v=95
51120 Off ch=15 n=31 v=80
51120 On ch=15 n=31 v=95
51360 Off ch=15 n=31 v=80
51600 On ch=16 n=33 v=95
51708 Pb ch=16 v=8832
51720 Off ch=16 n=33 v=80
51720 Pb ch=16 v=8192
51720 On ch=15 n=35 v=76
51840 Off ch=15 n=35 v=80
51840 On ch=15 n=38 v=95
52080 Off ch=15 n=38 v=80
52080 On ch=15 n=38 v=95
52560 Off ch=15 n=38 v=80
52680 On ch=15 n=33 v=95
52800 Off ch=15 n=33 v=80
52800 On ch=15 n=36 v=95
53040 Off ch=15 n=36 v=80
53040 On ch=15 n=36 v=95
53520 Off ch=15 n=36 v=80
53640 On ch=15 n=28 v=95
53760 Off ch=15 n=28 v=80
53760 On ch=15 n=31 v=95
54000 Off ch=15 n=31 v=80
54000 On ch=15 n=31 v=95
54720 Off ch=15 n=31 v=80
54720 On ch=15 n=33 v=95
54840 Off ch=15 n=33 v=80
54840 On ch=15 n=35 v=76
54960 Off ch=15 n=35 v=80
54960 On ch=15 n=38 v=95
55080 Off ch=15 n=38 v=80
55080 On ch=15 n=43 v=95
55200 Off ch=15 n=43 v=80
55200 On ch=15 n=40 v=95
55320 Off ch=15 n=40 v=80
55320 On ch=15 n=38 v=95
55440 Off ch=15 n=38 v=80
55440 On ch=15 n=33 v=95
55560 Off ch=15 n=33 v=80
55560 On ch=15 n=35 v=76
55680 Off ch=15 n=35 v=80
55680 On ch=15 n=38 v=95
55920 Off ch=15 n=38 v=80
55920 On ch=15 n=38 v=95
56160 Off ch=15 n=38 v=80
56160 On ch=16 n=38 v=95
56592 Pb ch=16 v=7552
56640 Off ch=16 n=38 v=80
56640 Pb ch=16 v=8192
56640 On ch=15 n=36 v=76
56880 Off ch=15 n=36 v=80
56880 On ch=15 n=36 v=95
57120 Off ch=15 n=36 v=80
57120 On ch=15 n=36 v=95
57480 Off ch=15 n=36 v=80
57480 On ch=15 n=36 v=95
57600 Off ch=15 n=36 v=80
57600 On ch=15 n=31 v=95
57840 Off ch=15 n=31 v=80
57840 On ch=15 n=31 v=95
58080 Off ch=15 n=31 v=80
58320 On ch=15 n=31 v=95
58560 Off ch=15 n=31 v=80
58800 On ch=15 n=31 v=95
59040 Off ch=15 n=31 v=80
59280 On ch=15 n=36 v=95
59520 Off ch=15 n=36 v=80
59520 On ch=15 n=38 v=95
59760 Off ch=15 n=38 v=80
59760 On ch=15 n=38 v=95
60000 Off ch=15 n=38 v=80
60000 On ch=16 n=38 v=95
60432 Pb ch=16 v=7552
60480 Off ch=16 n=38 v=80
60480 Pb ch=16 v=8192
60480 On ch=15 n=36 v=76
60720 Off ch=15 n=36 v=80
60720 On ch=15 n=36 v=95
60960 Off ch=15 n=36 v=80
60960 On ch=15 n=36 v=95
61440 Off ch=15 n=36 v=80
61440 On ch=15 n=31 v=95
61680 Off ch=15 n=31 v=80
61680 On ch=15 n=31 v=95
61920 Off ch=15 n=31 v=80
62040 On ch=15 n=31 v=95
62160 Off ch=15 n=31 v=80
62160 On ch=15 n=31 v=95
62400 Off ch=15 n=31 v=80
62640 On ch=15 n=36 v=95
62880 Off ch=15 n=36 v=80
63120 On ch=15 n=36 v=95
63360 Off ch=15 n=36 v=80
63360 On ch=15 n=38 v=95
63600 Off ch=15 n=38 v=80
63600 On ch=15 n=38 v=95
63840 Off ch=15 n=38 v=80
63840 On ch=16 n=38 v=95
64272 Pb ch=16 v=7552
64320 Off ch=16 n=38 v=80
64320 Pb ch=16 v=8192
64320 On ch=15 n=36 v=76
64560 Off ch=15 n=36 v=80
64560 On ch=15 n=36 v=95
64800 Off ch=15 n=36 v=80
64800 On ch=15 n=36 v=95
65160 Off ch=15 n=36 v=80
65160 On ch=15 n=36 v=95
65280 Off ch=15 n=36 v=80
65280 On ch=15 n=31 v=95
65520 Off ch=15 n=31 v=80
65520 On ch=15 n=31 v=95
65760 Off ch=15 n=31 v=80
66000 On ch=15 n=31 v=95
66240 Off ch=15 n=31 v=80
66480 On ch=15 n=31 v=95
66720 Off ch=15 n=31 v=80
66960 On ch=15 n=36 v=95
67200 Off ch=15 n=36 v=80
67200 On ch=15 n=38 v=95
67440 Off ch=15 n=38 v=80
67440 On ch=15 n=38 v=95
67680 Off ch=15 n=38 v=80
67680 On ch=16 n=38 v=95
68112 Pb ch=16 v=7552
68160 Off ch=16 n=38 v=80
68160 Pb ch=16 v=8192
68160 On ch=15 n=36 v=76
68400 Off ch=15 n=36 v=80
68400 On ch=15 n=36 v=95
68640 Off ch=15 n=36 v=80
68640 On ch=15 n=36 v=95
69120 Off ch=15 n=36 v=80
69120 On ch=15 n=31 v=95
69360 Off ch=15 n=31 v=80
69360 On ch=15 n=31 v=95
69600 Off ch=15 n=31 v=80
69840 On ch=15 n=31 v=95
70080 Off ch=15 n=31 v=80
70080 On ch=15 n=41 v=95
70560 Off ch=15 n=41 v=80
70560 On ch=15 n=40 v=95
71040 Off ch=15 n=40 v=80
71040 On ch=15 n=38 v=95
71280 Off ch=15 n=38 v=80
71280 On ch=15 n=38 v=95
71880 Off ch=15 n=38 v=80
71880 On ch=15 n=33 v=95
72000 Off ch=15 n=33 v=80
72000 On ch=15 n=36 v=95
72240 Off ch=15 n=36 v=80
72240 On ch=15 n=36 v=95
72480 Off ch=15 n=36 v=80
72840 On ch=15 n=28 v=95
72960 Off ch=15 n=28 v=80
72960 On ch=15 n=31 v=95
73200 Off ch=15 n=31 v=80
73200 On ch=15 n=31 v=95
73800 Off ch=15 n=31 v=80
73800 On ch=15 n=28 v=95
73920 Off ch=15 n=28 v=80
73920 On ch=15 n=31 v=95
74160 Off ch=15 n=31 v=80
74160 On ch=15 n=31 v=95
74400 Off ch=15 n=31 v=80
74640 On ch=16 n=33 v=95
74748 Pb ch=16 v=8832
74760 Off ch=16 n=33 v=80
74760 Pb ch=16 v=8192
74760 On ch=15 n=35 v=76
74880 Off ch=15 n=35 v=80
74880 On ch=15 n=38 v=95
75120 Off ch=15 n=38 v=80
75120 On ch=15 n=38 v=95
75720 Off ch=15 n=38 v=80
75720 On ch=15 n=32 v=95
75740 Off ch=15 n=32 v=80
75840 On ch=15 n=36 v=95
76080 Off ch=15 n=36 v=80
76080 On ch=15 n=36 v=95
76680 Off ch=15 n=36 v=80
76680 On ch=15 n=28 v=95
76800 Off ch=15 n=28 v=80
76800 On ch=15 n=31 v=95
77040 Off ch=15 n=31 v=80
77040 On ch=15 n=31 v=95
77280 Off ch=15 n=31 v=80
77280 On ch=15 n=31 v=95
77640 Off ch=15 n=31 v=80
77640 On ch=15 n=28 v=95
77760 Off ch=15 n=28 v=80
77760 On ch=15 n=31 v=95
78000 Off ch=15 n=31 v=80
78000 On ch=15 n=31 v=95
78240 Off ch=15 n=31 v=80
78480 On ch=15 n=33 v=95
78600 Off ch=15 n=33 v=80
78600 On ch=15 n=35 v=76
78720 Off ch=15 n=35 v=80
78720 On ch=15 n=38 v=95
78960 Off ch=15 n=38 v=80
78960 On ch=15 n=38 v=95
79440 Off ch=15 n=38 v=80
79560 On ch=15 n=33 v=95
79680 Off ch=15 n=33 v=80
79680 On ch=15 n=36 v=95
79920 Off ch=15 n=36 v=80
79920 On ch=15 n=36 v=95
80520 Off ch=15 n=36 v=80
80520 On ch=15 n=28 v=95
80640 Off ch=15 n=28 v=80
80640 On ch=15 n=31 v=95
80880 Off ch=15 n=31 v=80
80880 On ch=15 n=31 v=95
81600 Off ch=15 n=31 v=80
81600 On ch=15 n=41 v=95
82080 Off ch=15 n=41 v=80
82080 On ch=15 n=36 v=95
82560 Off ch=15 n=36 v=80
82560 On ch=15 n=38 v=95
82800 Off ch=15 n=38 v=80
82800 On ch=15 n=38 v=95
83400 Off ch=15 n=38 v=80
83400 On ch=15 n=33 v=95
83520 Off ch=15 n=33 v=80
83520 On ch=15 n=36 v=95
83760 Off ch=15 n=36 v=80
83760 On ch=15 n=36 v=95
84360 Off ch=15 n=36 v=80
84360 On ch=15 n=28 v=95
84480 Off ch=15 n=28 v=80
84480 On ch=15 n=31 v=95
84720 Off ch=15 n=31 v=80
84720 On ch=15 n=31 v=95
85320 Off ch=15 n=31 v=80
85320 On ch=15 n=28 v=95
85440 Off ch=15 n=28 v=80
85440 On ch=15 n=31 v=95
85680 Off ch=15 n=31 v=80
85680 On ch=15 n=31 v=95
85920 Off ch=15 n=31 v=80
86160 On ch=16 n=33 v=95
86268 Pb ch=16 v=8832
86280 Off ch=16 n=33 v=80
86280 Pb ch=16 v=8192
86280 On ch=15 n=35 v=76
86400 Off ch=15 n=35 v=80
86400 On ch=15 n=38 v=95
86640 Off ch=15 n=38 v=80
86640 On ch=15 n=38 v=95
87240 Off ch=15 n=38 v=80
87240 On ch=15 n=33 v=95
87360 Off ch=15 n=33 v=80
87360 On ch=15 n=36 v=95
87600 Off ch=15 n=36 v=80
87600 On ch=15 n=36 v=95
88200 Off ch=15 n=36 v=80
88200 On ch=15 n=28 v=95
88320 Off ch=15 n=28 v=80
88320 On ch=15 n=31 v=95
88560 Off ch=15 n=31 v=80
88560 On ch=15 n=43 v=95
89160 Off ch=15 n=43 v=80
89160 On ch=15 n=28 v=95
89280 Off ch=15 n=28 v=80
89280 On ch=15 n=31 v=95
89520 Off ch=15 n=31 v=80
89520 On ch=15 n=31 v=95
89760 Off ch=15 n=31 v=80
90000 On ch=16 n=33 v=95
90108 Pb ch=16 v=8832
90120 Off ch=16 n=33 v=80
90120 Pb ch=16 v=8192
90120 On ch=15 n=35 v=76
90240 Off ch=15 n=35 v=80
90240 On ch=15 n=38 v=95
90480 Off ch=15 n=38 v=80
90480 On ch=15 n=38 v=95
91080 Off ch=15 n=38 v=80
91080 On ch=15 n=33 v=95
91200 Off ch=15 n=33 v=80
91200 On ch=15 n=36 v=95
91440 Off ch=15 n=36 v=80
91440 On ch=15 n=36 v=95
92040 Off ch=15 n=36 v=80
92040 On ch=15 n=28 v=95
92160 Off ch=15 n=28 v=80
92160 On ch=15 n=31 v=95
92400 Off ch=15 n=31 v=80
92400 On ch=15 n=43 v=95
93120 Off ch=15 n=43 v=80
93120 On ch=15 n=33 v=95
93240 Off ch=15 n=33 v=80
93240 On ch=15 n=35 v=76
93360 Off ch=15 n=35 v=80
93360 On ch=15 n=38 v=95
93480 Off ch=15 n=38 v=80
93480 On ch=15 n=43 v=95
93600 Off ch=15 n=43 v=80
93600 On ch=15 n=40 v=95
93720 Off ch=15 n=40 v=80
93720 On ch=15 n=38 v=95
93840 Off ch=15 n=38 v=80
93840 On ch=15 n=33 v=95
93960 Off ch=15 n=33 v=80
93960 On ch=15 n=35 v=76
94080 Off ch=15 n=35 v=80
94080 On ch=15 n=38 v=95
94320 Off ch=15 n=38 v=80
94320 On ch=15 n=38 v=95
94560 Off ch=15 n=38 v=80
94560 On ch=15 n=38 v=95
94680 Off ch=15 n=38 v=80
94680 On ch=16 n=38 v=95
95004 Pb ch=16 v=7552
95040 Off ch=16 n=38 v=80
95040 Pb ch=16 v=8192
95040 On ch=15 n=36 v=76
95280 Off ch=15 n=36 v=80
95280 On ch=15 n=36 v=95
95520 Off ch=15 n=36 v=80
95520 On ch=15 n=36 v=95
95640 Off ch=15 n=36 v=80
95640 On ch=15 n=36 v=95
96000 Off ch=15 n=36 v=80
96000 On ch=15 n=31 v=95
96240 Off ch=15 n=31 v=80
96240 On ch=15 n=31 v=95
96480 Off ch=15 n=31 v=80
96720 On ch=15 n=31 v=95
96960 Off ch=15 n=31 v=80
97200 On ch=15 n=31 v=95
97440 Off ch=15 n=31 v=80
97560 On ch=15 n=36 v=95
97920 Off ch=15 n=36 v=80
97920 On ch=15 n=38 v=95
98160 Off ch=15 n=38 v=80
98160 On ch=15 n=38 v=95
98400 Off ch=15 n=38 v=80
98400 On ch=15 n=38 v=95
98520 Off ch=15 n=38 v=80
98520 On ch=16 n=38 v=95
98844 Pb ch=16 v=7552
98880 Off ch=16 n=38 v=80
98880 Pb ch=16 v=8192
98880 On ch=15 n=36 v=76
99120 Off ch=15 n=36 v=80
99120 On ch=15 n=36 v=95
99360 Off ch=15 n=36 v=80
99360 On ch=15 n=36 v=95
99480 Off ch=15 n=36 v=80
99480 On ch=15 n=36 v=95
99840 Off ch=15 n=36 v=80
99840 On ch=15 n=31 v=95
100080 Off ch=15 n=31 v=80
100080 On ch=15 n=31 v=95
100320 Off ch=15 n=31 v=80
100560 On ch=15 n=31 v=95
100800 Off ch=15 n=31 v=80
101040 On ch=15 n=36 v=95
101280 Off ch=15 n=36 v=80
101520 On ch=15 n=36 v=95
101760 Off ch=15 n=36 v=80
101760 On ch=15 n=38 v=95
102000 Off ch=15 n=38 v=80
102000 On ch=15 n=38 v=95
102240 Off ch=15 n=38 v=80
102240 On ch=15 n=38 v=95
102360 Off ch=15 n=38 v=80
102360 On ch=16 n=38 v=95
102684 Pb ch=16 v=7552
102720 Off ch=16 n=38 v=80
102720 Pb ch=16 v=8192
102720 On ch=15 n=36 v=76
102960 Off ch=15 n=36 v=80
102960 On ch=15 n=36 v=95
103200 Off ch=15 n=36 v=80
103200 On ch=15 n=36 v=95
103320 Off ch=15 n=36 v=80
103320 On ch=15 n=36 v=95
103680 Off ch=15 n=36 v=80
103680 On ch=15 n=31 v=95
103920 Off ch=15 n=31 v=80
103920 On ch=15 n=31 v=95
104160 Off ch=15 n=31 v=80
104400 On ch=15 n=31 v=95
104640 Off ch=15 n=31 v=80
104880 On ch=15 n=31 v=95
105120 Off ch=15 n=31 v=80
105240 On ch=15 n=36 v=95
105600 Off ch=15 n=36 v=80
105600 On ch=15 n=38 v=95
105840 Off ch=15 n=38 v=80
105840 On ch=15 n=38 v=95
106080 Off ch=15 n=38 v=80
106080 On ch=15 n=38 v=95
106200 Off ch=15 n=38 v=80
106200 On ch=16 n=38 v=95
106524 Pb ch=16 v=7552
106560 Off ch=16 n=38 v=80
106560 Pb ch=16 v=8192
106560 On ch=15 n=36 v=76
106800 Off ch=15 n=36 v=80
106800 On ch=15 n=36 v=95
107040 Off ch=15 n=36 v=80
107040 On ch=15 n=36 v=95
107160 Off ch=15 n=36 v=80
107160 On ch=15 n=36 v=95
107520 Off ch=15 n=36 v=80
107520 On ch=15 n=31 v=95
107760 Off ch=15 n=31 v=80
107760 On ch=15 n=31 v=95
108000 Off ch=15 n=31 v=80
108240 On ch=16 n=31 v=95
108432 Pb ch=16 v=7552
108448 Pb ch=16 v=6784
108464 Pb ch=16 v=6144
108480 Off ch=16 n=31 v=80
108480 Pb ch=16 v=8192
108600 On ch=15 n=27 v=95
108620 Off ch=15 n=27 v=80
108720 On ch=15 n=31 v=76
108840 Off ch=15 n=31 v=80
108960 On ch=15 n=33 v=95
109080 Off ch=15 n=33 v=80
109080 On ch=15 n=31 v=95
109440 Off ch=15 n=31 v=80
109440 On ch=15 n=38 v=95
109680 Off ch=15 n=38 v=80
109680 On ch=15 n=38 v=95
109920 Off ch=15 n=38 v=80
109920 On ch=15 n=33 v=95
110040 Off ch=15 n=33 v=80
110040 On ch=15 n=35 v=95
110400 Off ch=15 n=35 v=80
110400 On ch=15 n=36 v=95
110640 Off ch=15 n=36 v=80
110640 On ch=15 n=36 v=95
110880 Off ch=15 n=36 v=80
110880 On ch=15 n=28 v=95
111000 Off ch=15 n=28 v=80
111000 On ch=15 n=30 v=95
111360 Off ch=15 n=30 v=80
111360 On ch=15 n=31 v=95
111600 Off ch=15 n=31 v=80
111600 On ch=15 n=31 v=95
111840 Off ch=15 n=31 v=80
111840 On ch=15 n=40 v=95
112080 Off ch=15 n=40 v=80
112080 On ch=15 n=40 v=95
112200 Off ch=15 n=40 v=80
112200 On ch=15 n=40 v=95
112320 Off ch=15 n=40 v=80
112320 On ch=15 n=41 v=95
112560 Off ch=15 n=41 v=80
112560 On ch=15 n=41 v=95
112680 Off ch=15 n=41 v=80
112680 On ch=15 n=41 v=95
112800 Off ch=15 n=41 v=80
112800 On ch=15 n=40 v=95
112920 Off ch=15 n=40 v=80
112920 On ch=16 n=38 v=95
113208 Pb ch=16 v=8832
113280 Off ch=16 n=38 v=80
113280 Pb ch=16 v=8192
113280 On ch=15 n=38 v=76
113520 Off ch=15 n=38 v=80
113520 On ch=15 n=38 v=95
113760 Off ch=15 n=38 v=80
113760 On ch=15 n=33 v=95
113880 Off ch=15 n=33 v=80
113880 On ch=15 n=35 v=95
114240 Off ch=15 n=35 v=80
114240 On ch=15 n=36 v=95
114480 Off ch=15 n=36 v=80
114480 On ch=15 n=36 v=95
114720 Off ch=15 n=36 v=80
114720 On ch=15 n=28 v=95
114840 Off ch=15 n=28 v=80
114840 On ch=15 n=28 v=95
114960 Off ch=15 n=28 v=80
114960 On ch=15 n=30 v=95
115080 Off ch=15 n=30 v=80
115080 On ch=15 n=30 v=95
115200 Off ch=15 n=30 v=80
115200 On ch=15 n=31 v=95
115440 Off ch=15 n=31 v=80
115440 On ch=15 n=31 v=95
115680 Off ch=15 n=31 v=80
115680 On ch=15 n=40 v=95
115920 Off ch=15 n=40 v=80
115920 On ch=15 n=40 v=95
116160 Off ch=15 n=40 v=80
116160 On ch=15 n=41 v=95
116400 Off ch=15 n=41 v=80
116400 On ch=15 n=41 v=95
116640 Off ch=15 n=41 v=80
116640 On ch=15 n=40 v=95
116760 Off ch=15 n=40 v=80
116760 On ch=16 n=38 v=95
117048 Pb ch=16 v=8832
117120 Off ch=16 n=38 v=80
117120 Pb ch=16 v=8192
117120 On ch=15 n=38 v=76
117360 Off ch=15 n=38 v=80
117360 On ch=15 n=38 v=95
117600 Off ch=15 n=38 v=80
117600 On ch=15 n=33 v=95
117720 Off ch=15 n=33 v=80
117720 On ch=15 n=35 v=95
118080 Off ch=15 n=35 v=80
118080 On ch=15 n=36 v=95
118320 Off ch=15 n=36 v=80
118320 On ch=15 n=36 v=95
118560 Off ch=15 n=36 v=80
118560 On ch=15 n=28 v=95
118680 Off ch=15 n=28 v=80
118680 On ch=15 n=28 v=95
118800 Off ch=15 n=28 v=80
118800 On ch=15 n=30 v=95
118920 Off ch=15 n=30 v=80
118920 On ch=15 n=30 v=95
119040 Off ch=15 n=30 v=80
119040 On ch=15 n=31 v=95
119280 Off ch=15 n=31 v=80
119280 On ch=15 n=31 v=95
119520 Off ch=15 n=31 v=80
119520 On ch=15 n=40 v=95
119760 Off ch=15 n=40 v=80
119760 On ch=15 n=40 v=95
120000 Off ch=15 n=40 v=80
120000 On ch=15 n=41 v=95
120240 Off ch=15 n=41 v=80
120240 On ch=15 n=41 v=95
120360 Off ch=15 n=41 v=80
120360 On ch=15 n=41 v=95
120480 Off ch=15 n=41 v=80
120480 On ch=15 n=40 v=95
120600 Off ch=15 n=40 v=80
120600 On ch=16 n=38 v=95
120888 Pb ch=16 v=8832
120960 Off ch=16 n=38 v=80
120960 Pb ch=16 v=8192
120960 On ch=15 n=38 v=76
121200 Off ch=15 n=38 v=80
121200 On ch=15 n=38 v=95
121440 Off ch=15 n=38 v=80
121440 On ch=15 n=33 v=95
121560 Off ch=15 n=33 v=80
121560 On ch=15 n=35 v=95
121920 Off ch=15 n=35 v=80
121920 On ch=15 n=36 v=95
122160 Off ch=15 n=36 v=80
122160 On ch=15 n=36 v=95
122400 Off ch=15 n=36 v=80
122400 On ch=15 n=28 v=95
122520 Off ch=15 n=28 v=80
122520 On ch=15 n=30 v=95
122760 Off ch=15 n=30 v=80
122760 On ch=16 n=30 v=95
122856 Pb ch=16 v=8832
122880 Off ch=16 n=30 v=80
122880 Pb ch=16 v=8192
122880 On ch=15 n=31 v=76
123120 Off ch=15 n=31 v=80
123120 On ch=15 n=31 v=95
123360 Off ch=15 n=31 v=80
123360 On ch=15 n=40 v=95
123600 Off ch=15 n=40 v=80
123600 On ch=15 n=40 v=95
123720 Off ch=15 n=40 v=80
123720 On ch=15 n=40 v=95
123840 Off ch=15 n=40 v=80
123840 On ch=15 n=41 v=95
124080 Off ch=15 n=41 v=80
124080 On ch=15 n=41 v=95
124200 Off ch=15 n=41 v=80
124200 On ch=15 n=41 v=95
124320 Off ch=15 n=41 v=80
124320 On ch=15 n=40 v=95
124440 Off ch=15 n=40 v=80
124440 On ch=16 n=38 v=95
124728 Pb ch=16 v=8832
124800 Off ch=16 n=38 v=80
124800 Pb ch=16 v=8192
124800 On ch=15 n=38 v=76
125040 Off ch=15 n=38 v=80
125040 On ch=15 n=38 v=95
125280 Off ch=15 n=38 v=80
125280 On ch=15 n=33 v=95
125400 Off ch=15 n=33 v=80
125400 On ch=15 n=35 v=95
125760 Off ch=15 n=35 v=80
125760 On ch=15 n=36 v=95
126000 Off ch=15 n=36 v=80
126000 On ch=15 n=36 v=95
126240 Off ch=15 n=36 v=80
126240 On ch=15 n=28 v=95
126360 Off ch=15 n=28 v=80
126360 On ch=15 n=30 v=95
126720 Off ch=15 n=30 v=80
126720 On ch=15 n=31 v=95
126960 Off ch=15 n=31 v=80
126960 On ch=15 n=31 v=95
127200 Off ch=15 n=31 v=80
127200 On ch=15 n=40 v=95
127440 Off ch=15 n=40 v=80
127440 On ch=15 n=40 v=95
127680 Off ch=15 n=40 v=80
127680 On ch=15 n=41 v=95
127920 Off ch=15 n=41 v=80
127920 On ch=15 n=41 v=95
128040 Off ch=15 n=41 v=80
128040 On ch=15 n=41 v=95
128160 Off ch=15 n=41 v=80
128160 On ch=15 n=40 v=95
128280 Off ch=15 n=40 v=80
128280 On ch=16 n=38 v=95
128568 Pb ch=16 v=8832
128640 Off ch=16 n=38 v=80
128640 Pb ch=16 v=8192
128640 On ch=15 n=38 v=76
128880 Off ch=15 n=38 v=80
128880 On ch=15 n=38 v=95
129120 Off ch=15 n=38 v=80
129120 On ch=15 n=33 v=95
129240 Off ch=15 n=33 v=80
129240 On ch=15 n=35 v=95
129600 Off ch=15 n=35 v=80
129600 On ch=15 n=36 v=95
129840 Off ch=15 n=36 v=80
129840 On ch=15 n=36 v=95
130080 Off ch=15 n=36 v=80
130080 On ch=15 n=28 v=95
130200 Off ch=15 n=28 v=80
130200 On ch=15 n=30 v=95
130560 Off ch=15 n=30 v=80
130560 On ch=15 n=31 v=95
130800 Off ch=15 n=31 v=80
130800 On ch=15 n=31 v=95
131040 Off ch=15 n=31 v=80
131040 On ch=15 n=40 v=95
131280 Off ch=15 n=40 v=80
131280 On ch=15 n=40 v=95
131520 Off ch=15 n=40 v=80
131520 On ch=15 n=41 v=95
131760 Off ch=15 n=41 v=80
131760 On ch=15 n=41 v=95
131880 Off ch=15 n=41 v=80
131880 On ch=15 n=41 v=95
132000 Off ch=15 n=41 v=80
132000 On ch=15 n=40 v=95
132120 Off ch=15 n=40 v=80
132120 On ch=16 n=38 v=95
132408 Pb ch=16 v=8832
132480 Off ch=16 n=38 v=80
132480 Pb ch=16 v=8192
132480 On ch=15 n=38 v=76
132720 Off ch=15 n=38 v=80
132720 On ch=15 n=38 v=95
132960 Off ch=15 n=38 v=80
132960 On ch=15 n=33 v=95
133080 Off ch=15 n=33 v=80
133080 On ch=15 n=35 v=95
133440 Off ch=15 n=35 v=80
133440 On ch=15 n=36 v=95
133680 Off ch=15 n=36 v=80
133680 On ch=15 n=36 v=95
133920 Off ch=15 n=36 v=80
133920 On ch=15 n=28 v=95
134040 Off ch=15 n=28 v=80
134040 On ch=15 n=30 v=95
134400 Off ch=15 n=30 v=80
134400 On ch=15 n=31 v=95
134640 Off ch=15 n=31 v=80
134640 On ch=15 n=31 v=95
134880 Off ch=15 n=31 v=80
134880 On ch=15 n=40 v=95
135120 Off ch=15 n=40 v=80
135120 On ch=15 n=40 v=95
135360 Off ch=15 n=40 v=80
135360 On ch=15 n=41 v=95
135600 Off ch=15 n=41 v=80
135600 On ch=15 n=41 v=95
135720 Off ch=15 n=41 v=80
135720 On ch=15 n=41 v=95
135840 Off ch=15 n=41 v=80
135840 On ch=15 n=40 v=95
135960 Off ch=15 n=40 v=80
135960 On ch=16 n=38 v=95
136248 Pb ch=16 v=8832
136320 Off ch=16 n=38 v=80
136320 Pb ch=16 v=8192
136320 On ch=15 n=38 v=76
136560 Off ch=15 n=38 v=80
136560 On ch=15 n=38 v=95
136800 Off ch=15 n=38 v=80
136800 On ch=15 n=33 v=95
136920 Off ch=15 n=33 v=80
136920 On ch=15 n=35 v=95
137280 Off ch=15 n=35 v=80
137280 On ch=15 n=36 v=95
137520 Off ch=15 n=36 v=80
137520 On ch=15 n=36 v=95
137760 Off ch=15 n=36 v=80
137760 On ch=15 n=28 v=95
137880 Off ch=15 n=28 v=80
137880 On ch=15 n=30 v=95
138240 Off ch=15 n=30 v=80
138240 On ch=15 n=31 v=95
138480 Off ch=15 n=31 v=80
138480 On ch=15 n=31 v=95
138720 Off ch=15 n=31 v=80
138720 On ch=15 n=40 v=95
138960 Off ch=15 n=40 v=80
138960 On ch=15 n=40 v=95
139200 Off ch=15 n=40 v=80
139200 On ch=15 n=41 v=95
139440 Off ch=15 n=41 v=80
139440 On ch=15 n=41 v=95
139560 Off ch=15 n=41 v=80
139560 On ch=15 n=41 v=95
139680 Off ch=15 n=41 v=80
139680 On ch=15 n=40 v=95
139800 Off ch=15 n=40 v=80
139800 On ch=16 n=38 v=95
140088 Pb ch=16 v=8832
140160 Off ch=16 n=38 v=80
140160 Pb ch=16 v=8192
140160 On ch=15 n=38 v=76
140400 Off ch=15 n=38 v=80
140400 On ch=15 n=38 v=95
140640 Off ch=15 n=38 v=80
140640 On ch=15 n=33 v=95
140760 Off ch=15 n=33 v=80
140760 On ch=15 n=35 v=76
141120 Off ch=15 n=35 v=80
141120 On ch=15 n=43 v=95
141360 Off ch=15 n=43 v=80
141360 On ch=15 n=48 v=95
141600 Off ch=15 n=48 v=80
141600 On ch=15 n=48 v=95
141840 Off ch=15 n=48 v=80
141840 On ch=16 n=43 v=95
141948 Pb ch=16 v=7552
141961 Pb ch=16 v=6784
141974 Pb ch=16 v=6144
141988 Pb ch=16 v=5504
142001 Pb ch=16 v=4736
142014 Pb ch=16 v=4096
142027 Pb ch=16 v=3456
142040 Pb ch=16 v=2688
142054 Pb ch=16 v=2048
142067 Pb ch=16 v=1408
142080 Off ch=16 n=43 v=80
142080 Pb ch=16 v=640
142080 Pb ch=16 v=8192
142080 On ch=15 n=31 v=95
142320 Off ch=15 n=31 v=80
142320 On ch=15 n=31 v=95
142560 Off ch=15 n=31 v=80
142560 On ch=15 n=43 v=95
142920 Off ch=15 n=43 v=80
142920 On ch=15 n=32 v=95
142940 Off ch=15 n=32 v=80
143040 On ch=15 n=31 v=95
143280 Off ch=15 n=31 v=80
143280 On ch=15 n=31 v=95
143520 Off ch=15 n=31 v=80
143520 On ch=15 n=43 v=95
143760 Off ch=15 n=43 v=80
143760 On ch=15 n=35 v=76
144000 Off ch=15 n=35 v=80
144000 On ch=15 n=38 v=95
144240 Off ch=15 n=38 v=80
144240 On ch=15 n=38 v=95
144480 Off ch=15 n=38 v=80
144480 On ch=15 n=33 v=95
144600 Off ch=15 n=33 v=80
144600 On ch=15 n=35 v=76
144960 Off ch=15 n=35 v=80
144960 On ch=15 n=43 v=95
145200 Off ch=15 n=43 v=80
145200 On ch=15 n=48 v=95
145440 Off ch=15 n=48 v=80
145440 On ch=15 n=48 v=95
145680 Off ch=15 n=48 v=80
145680 On ch=16 n=43 v=95
145788 Pb ch=16 v=7552
145801 Pb ch=16 v=6784
145814 Pb ch=16 v=6144
145828 Pb ch=16 v=5504
145841 Pb ch=16 v=4736
145854 Pb ch=16 v=4096
145867 Pb ch=16 v=3456
145880 Pb ch=16 v=2688
145894 Pb ch=16 v=2048
145907 Pb ch=16 v=1408
145920 Off ch=16 n=43 v=80
145920 Pb ch=16 v=640
145920 Pb ch=16 v=8192
145920 On ch=15 n=31 v=95
146160 Off ch=15 n=31 v=80
146160 On ch=15 n=31 v=95
146400 Off ch=15 n=31 v=80
146400 On ch=15 n=43 v=95
146760 Off ch=15 n=43 v=80
146760 On ch=15 n=32 v=95
146780 Off ch=15 n=32 v=80
146880 On ch=15 n=31 v=95
147120 Off ch=15 n=31 v=80
147120 On ch=15 n=31 v=95
147360 Off ch=15 n=31 v=80
147360 On ch=15 n=43 v=95
147600 Off ch=15 n=43 v=80
147600 On ch=15 n=35 v=76
147840 Off ch=15 n=35 v=80
147840 On ch=15 n=38 v=95
148080 Off ch=15 n=38 v=80
148080 On ch=15 n=38 v=95
148680 Off ch=15 n=38 v=80
148680 On ch=15 n=33 v=95
148800 Off ch=15 n=33 v=80
148800 On ch=15 n=36 v=95
149040 Off ch=15 n=36 v=80
149040 On ch=15 n=36 v=95
149640 Off ch=15 n=36 v=80
149640 On ch=15 n=28 v=95
149760 Off ch=15 n=28 v=80
149760 On ch=15 n=31 v=95
150000 Off ch=15 n=31 v=80
150000 On ch=15 n=31 v=95
150240 Off ch=15 n=31 v=80
150240 On ch=15 n=43 v=95
150600 Off ch=15 n=43 v=80
150600 On ch=15 n=28 v=95
150720 Off ch=15 n=28 v=80
150720 On ch=15 n=31 v=95
150960 Off ch=15 n=31 v=80
150960 On ch=15 n=31 v=95
151200 Off ch=15 n=31 v=80
151440 On ch=15 n=33 v=95
151560 Off ch=15 n=33 v=80
151560 On ch=15 n=35 v=76
151680 Off ch=15 n=35 v=80
151680 On ch=15 n=38 v=95
151920 Off ch=15 n=38 v=80
151920 On ch=15 n=38 v=95
152520 Off ch=15 n=38 v=80
152520 On ch=15 n=33 v=95
152640 Off ch=15 n=33 v=80
152640 On ch=15 n=36 v=95
152880 Off ch=15 n=36 v=80
152880 On ch=15 n=36 v=95
153480 Off ch=15 n=36 v=80
153480 On ch=15 n=28 v=95
153600 Off ch=15 n=28 v=80
153600 On ch=15 n=31 v=95
153840 Off ch=15 n=31 v=80
153840 On ch=15 n=31 v=95
154440 Off ch=15 n=31 v=80
154440 On ch=15 n=28 v=95
154560 Off ch=15 n=28 v=80
154560 On ch=15 n=31 v=95
154800 Off ch=15 n=31 v=80
154800 On ch=15 n=43 v=95
155040 Off ch=15 n=43 v=80
155280 On ch=15 n=33 v=95
155400 Off ch=15 n=33 v=80
155400 On ch=15 n=35 v=76
155520 Off ch=15 n=35 v=80
155520 On ch=15 n=38 v=95
155760 Off ch=15 n=38 v=80
155760 On ch=15 n=38 v=95
156360 Off ch=15 n=38 v=80
156360 On ch=15 n=33 v=95
156480 Off ch=15 n=33 v=80
156480 On ch=15 n=36 v=95
156720 Off ch=15 n=36 v=80
156720 On ch=15 n=36 v=95
157320 Off ch=15 n=36 v=80
157320 On ch=15 n=28 v=95
157440 Off ch=15 n=28 v=80
157440 On ch=15 n=31 v=95
157680 Off ch=15 n=31 v=80
157680 On ch=15 n=31 v=95
157920 Off ch=15 n=31 v=80
157920 On ch=15 n=43 v=95
158280 Off ch=15 n=43 v=80
158280 On ch=15 n=28 v=95
158400 Off ch=15 n=28 v=80
158400 On ch=15 n=31 v=95
158640 Off ch=15 n=31 v=80
158640 On ch=15 n=31 v=95
158880 Off ch=15 n=31 v=80
159120 On ch=15 n=33 v=95
159240 Off ch=15 n=33 v=80
159240 On ch=15 n=35 v=76
159360 Off ch=15 n=35 v=80
159360 On ch=15 n=38 v=95
159600 Off ch=15 n=38 v=80
159600 On ch=15 n=38 v=95
160200 Off ch=15 n=38 v=80
160200 On ch=15 n=33 v=95
160320 Off ch=15 n=33 v=80
160320 On ch=15 n=36 v=95
160560 Off ch=15 n=36 v=80
160560 On ch=15 n=36 v=95
161160 Off ch=15 n=36 v=80
161160 On ch=15 n=28 v=95
161280 Off ch=15 n=28 v=80
161280 On ch=15 n=31 v=95
161520 Off ch=15 n=31 v=80
161520 On ch=15 n=43 v=95
162240 Off ch=15 n=43 v=80
162240 On ch=15 n=33 v=95
162360 Off ch=15 n=33 v=80
162360 On ch=15 n=35 v=76
162480 Off ch=15 n=35 v=80
162480 On ch=15 n=38 v=95
162600 Off ch=15 n=38 v=80
162600 On ch=15 n=43 v=95
162720 Off ch=15 n=43 v=80
162720 On ch=15 n=40 v=95
162840 Off ch=15 n=40 v=80
162840 On ch=15 n=38 v=95
162960 Off ch=15 n=38 v=80
162960 On ch=15 n=33 v=95
163080 Off ch=15 n=33 v=80
163080 On ch=16 n=35 v=76
163176 Pb ch=16 v=7552
163184 Pb ch=16 v=6784
163192 Pb ch=16 v=6144
163200 Off ch=16 n=35 v=80
163200 Pb ch=16 v=8192
163200 On ch=15 n=38 v=95
163440 Off ch=15 n=38 v=80
163440 On ch=15 n=38 v=95
163680 Off ch=15 n=38 v=80
163680 On ch=15 n=38 v=95
163800 Off ch=15 n=38 v=80
163800 On ch=16 n=38 v=95
164124 Pb ch=16 v=7552
164160 Off ch=16 n=38 v=80
164160 Pb ch=16 v=8192
164160 On ch=15 n=36 v=76
164400 Off ch=15 n=36 v=80
164400 On ch=15 n=36 v=95
164640 Off ch=15 n=36 v=80
164640 On ch=15 n=36 v=95
164760 Off ch=15 n=36 v=80
164760 On ch=15 n=36 v=95
165120 Off ch=15 n=36 v=80
165120 On ch=15 n=31 v=76
165360 Off ch=15 n=31 v=80
165360 On ch=15 n=31 v=95
165600 Off ch=15 n=31 v=80
165840 On ch=15 n=31 v=95
166080 Off ch=15 n=31 v=80
166320 On ch=15 n=31 v=95
166560 Off ch=15 n=31 v=80
166680 On ch=15 n=36 v=95
167040 Off ch=15 n=36 v=80
167040 On ch=15 n=38 v=95
167280 Off ch=15 n=38 v=80
167280 On ch=15 n=38 v=95
167520 Off ch=15 n=38 v=80
167520 On ch=15 n=38 v=95
167640 Off ch=15 n=38 v=80
167640 On ch=16 n=38 v=95
167964 Pb ch=16 v=7552
168000 Off ch=16 n=38 v=80
168000 Pb ch=16 v=8192
168000 On ch=15 n=36 v=76
168240 Off ch=15 n=36 v=80
168240 On ch=15 n=36 v=95
168480 Off ch=15 n=36 v=80
168480 On ch=15 n=36 v=95
168960 Off ch=15 n=36 v=80
168960 On ch=15 n=31 v=95
169200 Off ch=15 n=31 v=80
169200 On ch=15 n=31 v=95
169440 Off ch=15 n=31 v=80
169680 On ch=15 n=31 v=95
169920 Off ch=15 n=31 v=80
170160 On ch=15 n=36 v=95
170400 Off ch=15 n=36 v=80
170640 On ch=15 n=36 v=95
170880 Off ch=15 n=36 v=80
170880 On ch=15 n=38 v=95
171120 Off ch=15 n=38 v=80
171120 On ch=15 n=38 v=95
171360 Off ch=15 n=38 v=80
171360 On ch=15 n=38 v=95
171480 Off ch=15 n=38 v=80
171480 On ch=16 n=38 v=95
171804 Pb ch=16 v=7552
171840 Off ch=16 n=38 v=80
171840 Pb ch=16 v=8192
171840 On ch=15 n=36 v=76
172080 Off ch=15 n=36 v=80
172080 On ch=15 n=36 v=95
172320 Off ch=15 n=36 v=80
172320 On ch=15 n=36 v=95
172440 Off ch=15 n=36 v=80
172440 On ch=15 n=36 v=95
172800 Off ch=15 n=36 v=80
172800 On ch=15 n=31 v=95
173040 Off ch=15 n=31 v=80
173040 On ch=15 n=31 v=95
173280 Off ch=15 n=31 v=80
173520 On ch=15 n=31 v=95
173760 Off ch=15 n=31 v=80
174000 On ch=15 n=31 v=95
174240 Off ch=15 n=31 v=80
174360 On ch=15 n=36 v=95
174720 Off ch=15 n=36 v=80
174720 On ch=15 n=38 v=95
174960 Off ch=15 n=38 v=80
174960 On ch=15 n=38 v=95
175200 Off ch=15 n=38 v=80
175200 On ch=15 n=38 v=95
175320 Off ch=15 n=38 v=80
175320 On ch=16 n=38 v=95
175644 Pb ch=16 v=7552
175680 Off ch=16 n=38 v=80
175680 Pb ch=16 v=8192
175680 On ch=15 n=36 v=76
175920 Off ch=15 n=36 v=80
175920 On ch=15 n=36 v=95
176160 Off ch=15 n=36 v=80
176160 On ch=15 n=36 v=95
176640 Off ch=15 n=36 v=80
176640 On ch=15 n=31 v=95
176880 Off ch=15 n=31 v=80
176880 On ch=15 n=31 v=95
177120 Off ch=15 n=31 v=80
177360 On ch=15 n=31 v=95
177600 Off ch=15 n=31 v=80
177600 On ch=15 n=41 v=95
178080 Off ch=15 n=41 v=80
178080 On ch=15 n=40 v=95
178560 Off ch=15 n=40 v=80
178560 On ch=15 n=38 v=95
178800 Off ch=15 n=38 v=80
178800 On ch=15 n=38 v=95
179040 Off ch=15 n=38 v=80
179040 On ch=15 n=38 v=95
179160 Off ch=15 n=38 v=80
179160 On ch=16 n=38 v=95
179484 Pb ch=16 v=7552
179520 Off ch=16 n=38 v=80
179520 Pb ch=16 v=8192
179520 On ch=15 n=36 v=76
179760 Off ch=15 n=36 v=80
179760 On ch=15 n=36 v=95
180000 Off ch=15 n=36 v=80
180000 On ch=15 n=36 v=95
180120 Off ch=15 n=36 v=80
180120 On ch=15 n=36 v=95
180480 Off ch=15 n=36 v=80
180480 On ch=15 n=31 v=95
180720 Off ch=15 n=31 v=80
180720 On ch=15 n=31 v=95
180960 Off ch=15 n=31 v=80
181200 On ch=15 n=31 v=95
181440 Off ch=15 n=31 v=80
181680 On ch=15 n=31 v=95
181920 Off ch=15 n=31 v=80
182040 On ch=15 n=36 v=95
182400 Off ch=15 n=36 v=80
182400 On ch=15 n=38 v=95
182640 Off ch=15 n=38 v=80
182640 On ch=15 n=38 v=95
182880 Off ch=15 n=38 v=80
182880 On ch=15 n=38 v=95
183000 Off ch=15 n=38 v=80
183000 On ch=16 n=38 v=95
183324 Pb ch=16 v=7552
183360 Off ch=16 n=38 v=80
183360 Pb ch=16 v=8192
183360 On ch=15 n=36 v=76
183600 Off ch=15 n=36 v=80
183600 On ch=15 n=36 v=95
183840 Off ch=15 n=36 v=80
183840 On ch=15 n=36 v=95
183960 Off ch=15 n=36 v=80
183960 On ch=15 n=36 v=95
184320 Off ch=15 n=36 v=80
184320 On ch=15 n=31 v=95
184560 Off ch=15 n=31 v=80
184560 On ch=15 n=31 v=95
184800 Off ch=15 n=31 v=80
185040 On ch=15 n=31 v=95
185280 Off ch=15 n=31 v=80
185520 On ch=15 n=36 v=95
185760 Off ch=15 n=36 v=80
186000 On ch=15 n=36 v=95
186120 Off ch=15 n=36 v=80
186120 On ch=15 n=36 v=95
186240 Off ch=15 n=36 v=80
186240 On ch=15 n=38 v=95
186480 Off ch=15 n=38 v=80
186480 On ch=15 n=38 v=95
186720 Off ch=15 n=38 v=80
186720 On ch=15 n=38 v=95
186840 Off ch=15 n=38 v=80
186840 On ch=16 n=38 v=95
187164 Pb ch=16 v=7552
187200 Off ch=16 n=38 v=80
187200 Pb ch=16 v=8192
187200 On ch=15 n=36 v=76
187440 Off ch=15 n=36 v=80
187440 On ch=15 n=36 v=95
187680 Off ch=15 n=36 v=80
187680 On ch=15 n=36 v=95
187800 Off ch=15 n=36 v=80
187800 On ch=15 n=36 v=95
188160 Off ch=15 n=36 v=80
188160 On ch=15 n=31 v=95
188400 Off ch=15 n=31 v=80
188400 On ch=15 n=31 v=95
188640 Off ch=15 n=31 v=80
188880 On ch=15 n=31 v=95
189120 Off ch=15 n=31 v=80
189360 On ch=15 n=31 v=95
189600 Off ch=15 n=31 v=80
189720 On ch=15 n=36 v=95
190080 Off ch=15 n=36 v=80
190080 On ch=15 n=38 v=95
190320 Off ch=15 n=38 v=80
190320 On ch=15 n=38 v=95
190560 Off ch=15 n=38 v=80
190560 On ch=15 n=38 v=95
190680 Off ch=15 n=38 v=80
190680 On ch=16 n=38 v=95
191004 Pb ch=16 v=7552
191040 Off ch=16 n=38 v=80
191040 Pb ch=16 v=8192
191040 On ch=15 n=36 v=76
191280 Off ch=15 n=36 v=80
191280 On ch=15 n=36 v=95
191520 Off ch=15 n=36 v=80
191520 On ch=15 n=36 v=95
191640 Off ch=15 n=36 v=80
191640 On ch=15 n=36 v=95
192000 Off ch=15 n=36 v=80
192000 On ch=15 n=31 v=95
192240 Off ch=15 n=31 v=80
192240 On ch=15 n=31 v=95
192480 Off ch=15 n=31 v=80
192720 On ch=15 n=31 v=95
192960 Off ch=15 n=31 v=80
193080 On ch=15 n=28 v=95
193200 Off ch=15 n=28 v=80
193200 On ch=15 n=31 v=95
193320 Off ch=15 n=31 v=80
193320 On ch=15 n=28 v=95
193440 Off ch=15 n=28 v=80
193440 On ch=15 n=31 v=95
193560 Off ch=15 n=31 v=80
193560 On ch=15 n=31 v=95
193680 Off ch=15 n=31 v=80
193680 On ch=15 n=28 v=95
193800 Off ch=15 n=28 v=80
193800 On ch=15 n=31 v=95
193920 Off ch=15 n=31 v=80
193920 On ch=15 n=38 v=95
194160 Off ch=15 n=38 v=80
194160 On ch=15 n=38 v=95
194400 Off ch=15 n=38 v=80
194400 On ch=15 n=33 v=95
194520 Off ch=15 n=33 v=80
194520 On ch=15 n=35 v=95
194880 Off ch=15 n=35 v=80
194880 On ch=15 n=36 v=95
195120 Off ch=15 n=36 v=80
195120 On ch=15 n=36 v=95
195360 Off ch=15 n=36 v=80
195360 On ch=15 n=28 v=95
195480 Off ch=15 n=28 v=80
195480 On ch=15 n=30 v=95
195600 Off ch=15 n=30 v=80
195600 On ch=15 n=30 v=95
195840 Off ch=15 n=30 v=80
195840 On ch=15 n=31 v=95
196080 Off ch=15 n=31 v=80
196080 On ch=15 n=31 v=95
196320 Off ch=15 n=31 v=80
196320 On ch=15 n=40 v=95
196560 Off ch=15 n=40 v=80
196560 On ch=15 n=40 v=95
196800 Off ch=15 n=40 v=80
196800 On ch=15 n=41 v=95
197040 Off ch=15 n=41 v=80
197040 On ch=15 n=41 v=95
197280 Off ch=15 n=41 v=80
197280 On ch=15 n=40 v=95
197400 Off ch=15 n=40 v=80
197400 On ch=16 n=38 v=95
197688 Pb ch=16 v=8832
197760 Off ch=16 n=38 v=80
197760 Pb ch=16 v=8192
197760 On ch=15 n=38 v=76
198000 Off ch=15 n=38 v=80
198000 On ch=15 n=38 v=95
198240 Off ch=15 n=38 v=80
198240 On ch=15 n=33 v=95
198360 Off ch=15 n=33 v=80
198360 On ch=15 n=35 v=95
198720 Off ch=15 n=35 v=80
198720 On ch=15 n=36 v=95
198960 Off ch=15 n=36 v=80
198960 On ch=15 n=36 v=95
199200 Off ch=15 n=36 v=80
199200 On ch=15 n=28 v=95
199320 Off ch=15 n=28 v=80
199320 On ch=15 n=28 v=95
199440 Off ch=15 n=28 v=80
199440 On ch=15 n=30 v=95
199560 Off ch=15 n=30 v=80
199560 On ch=15 n=30 v=95
199680 Off ch=15 n=30 v=80
199680 On ch=15 n=31 v=95
199920 Off ch=15 n=31 v=80
199920 On ch=15 n=31 v=95
200160 Off ch=15 n=31 v=80
200160 On ch=15 n=40 v=95
200400 Off ch=15 n=40 v=80
200400 On ch=15 n=40 v=95
200640 Off ch=15 n=40 v=80
200640 On ch=15 n=41 v=95
200880 Off ch=15 n=41 v=80
200880 On ch=15 n=41 v=95
201120 Off ch=15 n=41 v=80
201120 On ch=15 n=40 v=95
201240 Off ch=15 n=40 v=80
201240 On ch=15 n=38 v=95
201600 Off ch=15 n=38 v=80
201600 On ch=15 n=38 v=95
201840 Off ch=15 n=38 v=80
201840 On ch=15 n=38 v=95
202080 Off ch=15 n=38 v=80
202080 On ch=15 n=33 v=95
202200 Off ch=15 n=33 v=80
202200 On ch=15 n=35 v=95
202440 Off ch=15 n=35 v=80
202440 On ch=15 n=35 v=95
202560 Off ch=15 n=35 v=80
202560 On ch=15 n=36 v=95
202800 Off ch=15 n=36 v=80
202800 On ch=15 n=36 v=95
203040 Off ch=15 n=36 v=80
203040 On ch=15 n=28 v=95
203160 Off ch=15 n=28 v=80
203160 On ch=15 n=30 v=95
203520 Off ch=15 n=30 v=80
203520 On ch=15 n=31 v=95
203760 Off ch=15 n=31 v=80
203760 On ch=15 n=31 v=95
204000 Off ch=15 n=31 v=80
204000 On ch=15 n=40 v=95
204240 Off ch=15 n=40 v=80
204240 On ch=15 n=40 v=95
204480 Off ch=15 n=40 v=80
204480 On ch=15 n=41 v=95
204720 Off ch=15 n=41 v=80
204720 On ch=15 n=41 v=95
204960 Off ch=15 n=41 v=80
204960 On ch=15 n=40 v=95
205080 Off ch=15 n=40 v=80
205080 On ch=16 n=38 v=95
205368 Pb ch=16 v=8832
205440 Off ch=16 n=38 v=80
205440 Pb ch=16 v=8192
205440 On ch=15 n=38 v=76
205680 Off ch=15 n=38 v=80
205680 On ch=15 n=38 v=95
205920 Off ch=15 n=38 v=80
205920 On ch=15 n=33 v=95
206040 Off ch=15 n=33 v=80
206040 On ch=15 n=35 v=95
206400 Off ch=15 n=35 v=80
206400 On ch=15 n=36 v=95
206640 Off ch=15 n=36 v=80
206640 On ch=15 n=36 v=95
206880 Off ch=15 n=36 v=80
206880 On ch=15 n=28 v=95
207000 Off ch=15 n=28 v=80
207000 On ch=15 n=30 v=95
207360 Off ch=15 n=30 v=80
207360 On ch=15 n=31 v=95
207600 Off ch=15 n=31 v=80
207600 On ch=15 n=31 v=95
207840 Off ch=15 n=31 v=80
207840 On ch=15 n=40 v=95
208080 Off ch=15 n=40 v=80
208080 On ch=15 n=40 v=95
208320 Off ch=15 n=40 v=80
208320 On ch=15 n=33 v=95
208440 Off ch=15 n=33 v=80
208440 On ch=15 n=35 v=76
208560 Off ch=15 n=35 v=80
208560 On ch=15 n=38 v=95
208680 Off ch=15 n=38 v=80
208680 On ch=15 n=40 v=76
208800 Off ch=15 n=40 v=80
208800 On ch=15 n=38 v=76
208920 Off ch=15 n=38 v=80
208920 On ch=15 n=36 v=95
209280 Off ch=15 n=36 v=80
209280 On ch=15 n=38 v=95
209520 Off ch=15 n=38 v=80
209520 On ch=15 n=38 v=95
209760 Off ch=15 n=38 v=80
209760 On ch=15 n=33 v=95
209880 Off ch=15 n=33 v=80
209880 On ch=15 n=35 v=95
210240 Off ch=15 n=35 v=80
210240 On ch=15 n=36 v=95
210480 Off ch=15 n=36 v=80
210480 On ch=15 n=36 v=95
210720 Off ch=15 n=36 v=80
210720 On ch=15 n=28 v=95
210840 Off ch=15 n=28 v=80
210840 On ch=15 n=30 v=95
211200 Off ch=15 n=30 v=80
211200 On ch=15 n=31 v=95
211440 Off ch=15 n=31 v=80
211440 On ch=15 n=31 v=95
211680 Off ch=15 n=31 v=80
211680 On ch=15 n=40 v=95
211920 Off ch=15 n=40 v=80
211920 On ch=15 n=40 v=95
212160 Off ch=15 n=40 v=80
212160 On ch=15 n=33 v=95
212280 Off ch=15 n=33 v=80
212280 On ch=15 n=35 v=76
212400 Off ch=15 n=35 v=80
212400 On ch=15 n=38 v=95
212520 Off ch=15 n=38 v=80
212520 On ch=15 n=40 v=76
212640 Off ch=15 n=40 v=80
212640 On ch=15 n=38 v=76
212760 Off ch=15 n=38 v=80
212760 On ch=15 n=36 v=95
213120 Off ch=15 n=36 v=80
213120 On ch=15 n=38 v=95
213360 Off ch=15 n=38 v=80
213360 On ch=15 n=38 v=95
213600 Off ch=15 n=38 v=80
213600 On ch=15 n=33 v=95
213720 Off ch=15 n=33 v=80
213720 On ch=15 n=35 v=95
214080 Off ch=15 n=35 v=80
214080 On ch=15 n=36 v=95
214320 Off ch=15 n=36 v=80
214320 On ch=15 n=36 v=95
214560 Off ch=15 n=36 v=80
214560 On ch=15 n=28 v=95
214680 Off ch=15 n=28 v=80
214680 On ch=15 n=30 v=95
215040 Off ch=15 n=30 v=80
215040 On ch=15 n=31 v=95
215280 Off ch=15 n=31 v=80
215280 On ch=15 n=31 v=95
215520 Off ch=15 n=31 v=80
215520 On ch=15 n=40 v=95
215760 Off ch=15 n=40 v=80
215760 On ch=15 n=40 v=95
216000 Off ch=15 n=40 v=80
216000 On ch=15 n=33 v=95
216120 Off ch=15 n=33 v=80
216120 On ch=15 n=35 v=76
216240 Off ch=15 n=35 v=80
216240 On ch=15 n=38 v=95
216360 Off ch=15 n=38 v=80
216360 On ch=15 n=40 v=76
216480 Off ch=15 n=40 v=80
216480 On ch=15 n=38 v=76
216600 Off ch=15 n=38 v=80
216600 On ch=15 n=36 v=95
216960 Off ch=15 n=36 v=80
216960 On ch=15 n=38 v=95
217200 Off ch=15 n=38 v=80
217200 On ch=15 n=38 v=95
217440 Off ch=15 n=38 v=80
217440 On ch=15 n=33 v=95
217560 Off ch=15 n=33 v=80
217560 On ch=15 n=35 v=95
217920 Off ch=15 n=35 v=80
217920 On ch=15 n=36 v=95
218160 Off ch=15 n=36 v=80
218160 On ch=15 n=36 v=95
218400 Off ch=15 n=36 v=80
218400 On ch=15 n=28 v=95
218520 Off ch=15 n=28 v=80
218520 On ch=15 n=30 v=95
218880 Off ch=15 n=30 v=80
218880 On ch=15 n=31 v=95
219120 Off ch=15 n=31 v=80
219120 On ch=15 n=31 v=95
219360 Off ch=15 n=31 v=80
219360 On ch=15 n=40 v=95
219600 Off ch=15 n=40 v=80
219600 On ch=15 n=40 v=95
219840 Off ch=15 n=40 v=80
219840 On ch=15 n=33 v=95
219960 Off ch=15 n=33 v=80
219960 On ch=15 n=35 v=76
220080 Off ch=15 n=35 v=80
220080 On ch=15 n=38 v=95
220200 Off ch=15 n=38 v=80
220200 On ch=15 n=40 v=76
220320 Off ch=15 n=40 v=80
220320 On ch=15 n=38 v=76
220440 Off ch=15 n=38 v=80
220440 On ch=15 n=36 v=95
220800 Off ch=15 n=36 v=80
220800 Meta TrkEnd
TrkEnd
MTrk
0 Meta TrkName 'Drums'
0 Par ch=10 c=100 v=0
0 Par ch=10 c=101 v=0
0 Par ch=10 c=6 v=12
0 Pb ch=10 v=8192
0 Par ch=10 c=101 v=0
0 Par ch=10 c=100 v=1
0 Par ch=10 c=6 v=64
0 Par ch=10 c=38 v=0
0 Par ch=10 c=101 v=127
0 Par ch=10 c=100 v=127
0 PrCh ch=10 p=24
0 Par ch=10 c=0 v=0
0 Par ch=10 c=7 v=127
0 Par ch=10 c=10 v=64
0 Par ch=10 c=93 v=0
0 Par ch=10 c=91 v=0
0 Par ch=10 c=92 v=0
0 Par ch=10 c=95 v=0
0 On ch=10 n=42 v=95
120 Off ch=10 n=42 v=80
480 On ch=10 n=42 v=95
600 Off ch=10 n=42 v=80
960 On ch=10 n=42 v=95
1080 Off ch=10 n=42 v=80
1440 On ch=10 n=42 v=95
1560 Off ch=10 n=42 v=80
1920 On ch=10 n=42 v=95
2040 Off ch=10 n=42 v=80
2880 On ch=10 n=42 v=95
3000 Off ch=10 n=42 v=80
3840 On ch=10 n=42 v=95
3960 Off ch=10 n=42 v=80
4800 On ch=10 n=42 v=95
4920 Off ch=10 n=42 v=80
5760 On ch=10 n=36 v=95
5760 On ch=10 n=42 v=95
5880 Off ch=10 n=36 v=80
5880 Off ch=10 n=42 v=80
6000 On ch=10 n=36 v=95
6120 Off ch=10 n=36 v=80
6240 On ch=10 n=38 v=95
6360 Off ch=10 n=38 v=80
6720 On ch=10 n=36 v=95
6720 On ch=10 n=42 v=95
6840 Off ch=10 n=36 v=80
6840 Off ch=10 n=42 v=80
6960 On ch=10 n=36 v=95
7080 Off ch=10 n=36 v=80
7200 On ch=10 n=38 v=95
7320 Off ch=10 n=38 v=80
7680 On ch=10 n=36 v=95
7680 On ch=10 n=42 v=95
7800 Off ch=10 n=36 v=80
7800 Off ch=10 n=42 v=80
7920 On ch=10 n=36 v=95
8040 Off ch=10 n=36 v=80
8160 On ch=10 n=38 v=95
8280 Off ch=10 n=38 v=80
8640 On ch=10 n=36 v=95
8760 Off ch=10 n=36 v=80
8880 On ch=10 n=36 v=95
9000 Off ch=10 n=36 v=80
9120 On ch=10 n=38 v=95
9240 Off ch=10 n=38 v=80
9600 On ch=10 n=36 v=95
9600 On ch=10 n=42 v=95
9600 On ch=10 n=49 v=95
9720 Off ch=10 n=36 v=80
9720 Off ch=10 n=42 v=80
9720 Off ch=10 n=49 v=80
9840 On ch=10 n=36 v=95
9840 On ch=10 n=42 v=95
9960 Off ch=10 n=36 v=80
9960 Off ch=10 n=42 v=80
10080 On ch=10 n=38 v=95
10080 On ch=10 n=42 v=95
10200 Off ch=10 n=38 v=80
10200 Off ch=10 n=42 v=80
10320 On ch=10 n=42 v=95
10440 Off ch=10 n=42 v=80
10560 On ch=10 n=36 v=95
10560 On ch=10 n=42 v=95
10680 Off ch=10 n=36 v=80
10680 Off ch=10 n=42 v=80
10800 On ch=10 n=36 v=95
10800 On ch=10 n=42 v=95
10920 Off ch=10 n=36 v=80
10920 Off ch=10 n=42 v=80
11040 On ch=10 n=38 v=95
11040 On ch=10 n=42 v=95
11160 Off ch=10 n=38 v=80
11160 Off ch=10 n=42 v=80
11280 On ch=10 n=42 v=95
11400 Off ch=10 n=42 v=80
11520 On ch=10 n=36 v=95
11520 On ch=10 n=42 v=95
11640 Off ch=10 n=36 v=80
11640 Off ch=10 n=42 v=80
11760 On ch=10 n=36 v=95
11880 Off ch=10 n=36 v=80
12000 On ch=10 n=38 v=95
12000 On ch=10 n=42 v=95
12120 Off ch=10 n=38 v=80
12120 Off ch=10 n=42 v=80
12240 On ch=10 n=42 v=95
12360 Off ch=10 n=42 v=80
12480 On ch=10 n=36 v=95
12480 On ch=10 n=42 v=95
12600 Off ch=10 n=36 v=80
12600 Off ch=10 n=42 v=80
12720 On ch=10 n=36 v=95
12840 Off ch=10 n=36 v=80
12960 On ch=10 n=38 v=95
12960 On ch=10 n=42 v=95
13080 Off ch=10 n=38 v=80
13080 Off ch=10 n=42 v=80
13200 On ch=10 n=46 v=95
13320 Off ch=10 n=46 v=80
13440 On ch=10 n=36 v=95
13440 On ch=10 n=42 v=95
13560 Off ch=10 n=36 v=80
13560 Off ch=10 n=42 v=80
13680 On ch=10 n=36 v=95
13680 On ch=10 n=42 v=95
13800 Off ch=10 n=36 v=80
13800 Off ch=10 n=42 v=80
13920 On ch=10 n=38 v=95
13920 On ch=10 n=42 v=95
14040 Off ch=10 n=38 v=80
14040 Off ch=10 n=42 v=80
14160 On ch=10 n=42 v=95
14280 Off ch=10 n=42 v=80
14400 On ch=10 n=36 v=95
14400 On ch=10 n=42 v=95
14520 Off ch=10 n=36 v=80
14520 Off ch=10 n=42 v=80
14640 On ch=10 n=36 v=95
14760 Off ch=10 n=36 v=80
14880 On ch=10 n=38 v=95
14880 On ch=10 n=42 v=95
15000 Off ch=10 n=38 v=80
15000 Off ch=10 n=42 v=80
15120 On ch=10 n=42 v=95
15240 Off ch=10 n=42 v=80
15360 On ch=10 n=36 v=95
15360 On ch=10 n=42 v=95
15480 Off ch=10 n=36 v=80
15480 Off ch=10 n=42 v=80
15600 On ch=10 n=36 v=95
15600 On ch=10 n=42 v=95
15720 Off ch=10 n=36 v=80
15720 Off ch=10 n=42 v=80
15840 On ch=10 n=38 v=95
15840 On ch=10 n=42 v=95
15960 Off ch=10 n=38 v=80
15960 Off ch=10 n=42 v=80
16080 On ch=10 n=42 v=95
16200 Off ch=10 n=42 v=80
16320 On ch=10 n=36 v=95
16440 Off ch=10 n=36 v=80
16560 On ch=10 n=36 v=95
16560 On ch=10 n=42 v=95
16680 Off ch=10 n=36 v=80
16680 Off ch=10 n=42 v=80
16800 On ch=10 n=38 v=95
16920 Off ch=10 n=38 v=80
17040 On ch=10 n=46 v=95
17160 Off ch=10 n=46 v=80
17280 On ch=10 n=36 v=95
17280 On ch=10 n=42 v=95
17400 Off ch=10 n=36 v=80
17400 Off ch=10 n=42 v=80
17520 On ch=10 n=36 v=95
17520 On ch=10 n=42 v=95
17640 Off ch=10 n=36 v=80
17640 Off ch=10 n=42 v=80
17760 On ch=10 n=38 v=95
17760 On ch=10 n=42 v=95
17880 Off ch=10 n=38 v=80
17880 Off ch=10 n=42 v=80
18000 On ch=10 n=42 v=95
18120 Off ch=10 n=42 v=80
18240 On ch=10 n=36 v=95
18240 On ch=10 n=42 v=95
18360 Off ch=10 n=36 v=80
18360 Off ch=10 n=42 v=80
18480 On ch=10 n=36 v=95
18600 Off ch=10 n=36 v=80
18720 On ch=10 n=38 v=95
18720 On ch=10 n=42 v=95
18840 Off ch=10 n=38 v=80
18840 Off ch=10 n=42 v=80
18960 On ch=10 n=42 v=95
19080 Off ch=10 n=42 v=80
19200 On ch=10 n=36 v=95
19200 On ch=10 n=42 v=95
19320 Off ch=10 n=36 v=80
19320 Off ch=10 n=42 v=80
19440 On ch=10 n=36 v=95
19440 On ch=10 n=42 v=95
19560 Off ch=10 n=36 v=80
19560 Off ch=10 n=42 v=80
19680 On ch=10 n=38 v=95
19800 Off ch=10 n=38 v=80
19920 On ch=10 n=42 v=95
20040 Off ch=10 n=42 v=80
20160 On ch=10 n=36 v=95
20160 On ch=10 n=42 v=95
20280 Off ch=10 n=36 v=80
20280 Off ch=10 n=42 v=80
20400 On ch=10 n=36 v=95
20520 Off ch=10 n=36 v=80
20640 On ch=10 n=38 v=95
20640 On ch=10 n=42 v=95
20760 Off ch=10 n=38 v=80
20760 Off ch=10 n=42 v=80
20880 On ch=10 n=42 v=95
21000 Off ch=10 n=42 v=80
21120 On ch=10 n=36 v=95
21120 On ch=10 n=42 v=95
21240 Off ch=10 n=36 v=80
21240 Off ch=10 n=42 v=80
21360 On ch=10 n=36 v=95
21360 On ch=10 n=42 v=95
21480 Off ch=10 n=36 v=80
21480 Off ch=10 n=42 v=80
21600 On ch=10 n=38 v=95
21600 On ch=10 n=42 v=95
21720 Off ch=10 n=38 v=80
21720 Off ch=10 n=42 v=80
21840 On ch=10 n=42 v=95
21960 Off ch=10 n=42 v=80
22080 On ch=10 n=36 v=95
22200 Off ch=10 n=36 v=80
22320 On ch=10 n=36 v=95
22440 Off ch=10 n=36 v=80
22560 On ch=10 n=38 v=95
22680 Off ch=10 n=38 v=80
22800 On ch=10 n=42 v=95
22920 Off ch=10 n=42 v=80
23040 On ch=10 n=36 v=95
23040 On ch=10 n=42 v=95
23160 Off ch=10 n=36 v=80
23160 Off ch=10 n=42 v=80
23280 On ch=10 n=36 v=95
23280 On ch=10 n=42 v=95
23400 Off ch=10 n=36 v=80
23400 Off ch=10 n=42 v=80
23520 On ch=10 n=38 v=95
23520 On ch=10 n=42 v=95
23640 Off ch=10 n=38 v=80
23640 Off ch=10 n=42 v=80
23760 On ch=10 n=42 v=95
23880 Off ch=10 n=42 v=80
24000 On ch=10 n=36 v=95
24000 On ch=10 n=42 v=95
24120 Off ch=10 n=36 v=80
24120 Off ch=10 n=42 v=80
24240 On ch=10 n=36 v=95
24360 Off ch=10 n=36 v=80
24480 On ch=10 n=38 v=95
24480 On ch=10 n=42 v=95
24600 Off ch=10 n=38 v=80
24600 Off ch=10 n=42 v=80
24720 On ch=10 n=46 v=95
24840 Off ch=10 n=46 v=80
24960 On ch=10 n=36 v=95
24960 On ch=10 n=42 v=95
25080 Off ch=10 n=36 v=80
25080 Off ch=10 n=42 v=80
25200 On ch=10 n=36 v=95
25200 On ch=10 n=42 v=95
25320 Off ch=10 n=36 v=80
25320 Off ch=10 n=42 v=80
25440 On ch=10 n=38 v=95
25560 Off ch=10 n=38 v=80
25680 On ch=10 n=42 v=95
25800 Off ch=10 n=42 v=80
25920 On ch=10 n=36 v=95
25920 On ch=10 n=42 v=95
26040 Off ch=10 n=36 v=80
26040 Off ch=10 n=42 v=80
26160 On ch=10 n=36 v=95
26160 On ch=10 n=42 v=95
26280 Off ch=10 n=36 v=80
26280 Off ch=10 n=42 v=80
26400 On ch=10 n=38 v=95
26400 On ch=10 n=42 v=95
26520 Off ch=10 n=38 v=80
26520 Off ch=10 n=42 v=80
26640 On ch=10 n=42 v=95
26760 Off ch=10 n=42 v=80
26880 On ch=10 n=36 v=95
26880 On ch=10 n=42 v=95
27000 Off ch=10 n=36 v=80
27000 Off ch=10 n=42 v=80
27120 On ch=10 n=36 v=95
27240 Off ch=10 n=36 v=80
27360 On ch=10 n=38 v=95
27360 On ch=10 n=42 v=95
27480 Off ch=10 n=38 v=80
27480 Off ch=10 n=42 v=80
27600 On ch=10 n=42 v=95
27720 Off ch=10 n=42 v=80
27840 On ch=10 n=36 v=95
27840 On ch=10 n=42 v=95
27960 Off ch=10 n=36 v=80
27960 Off ch=10 n=42 v=80
28080 On ch=10 n=36 v=95
28200 Off ch=10 n=36 v=80
28320 On ch=10 n=38 v=95
28440 Off ch=10 n=38 v=80
28560 On ch=10 n=42 v=95
28680 Off ch=10 n=42 v=80
28800 On ch=10 n=36 v=95
28800 On ch=10 n=42 v=95
28920 Off ch=10 n=36 v=80
28920 Off ch=10 n=42 v=80
29040 On ch=10 n=36 v=95
29040 On ch=10 n=42 v=95
29160 Off ch=10 n=36 v=80
29160 Off ch=10 n=42 v=80
29280 On ch=10 n=38 v=95
29400 Off ch=10 n=38 v=80
29520 On ch=10 n=42 v=95
29640 Off ch=10 n=42 v=80
29760 On ch=10 n=36 v=95
29760 On ch=10 n=42 v=95
29880 Off ch=10 n=36 v=80
29880 Off ch=10 n=42 v=80
30000 On ch=10 n=36 v=95
30000 On ch=10 n=42 v=95
30120 Off ch=10 n=36 v=80
30120 Off ch=10 n=42 v=80
30240 On ch=10 n=38 v=95
30240 On ch=10 n=42 v=95
30360 Off ch=10 n=38 v=80
30360 Off ch=10 n=42 v=80
30480 On ch=10 n=42 v=95
30600 Off ch=10 n=42 v=80
30720 On ch=10 n=36 v=95
30720 On ch=10 n=42 v=95
30840 Off ch=10 n=36 v=80
30840 Off ch=10 n=42 v=80
30960 On ch=10 n=36 v=95
31080 Off ch=10 n=36 v=80
31200 On ch=10 n=38 v=95
31320 Off ch=10 n=38 v=80
31440 On ch=10 n=42 v=95
31560 Off ch=10 n=42 v=80
31680 On ch=10 n=36 v=95
31800 Off ch=10 n=36 v=80
31920 On ch=10 n=36 v=95
31920 On ch=10 n=42 v=95
32040 Off ch=10 n=36 v=80
32040 Off ch=10 n=42 v=80
32160 On ch=10 n=38 v=95
32160 On ch=10 n=42 v=95
32280 Off ch=10 n=38 v=80
32280 Off ch=10 n=42 v=80
32400 On ch=10 n=42 v=95
32520 Off ch=10 n=42 v=80
32640 On ch=10 n=36 v=95
32640 On ch=10 n=42 v=95
32640 On ch=10 n=49 v=95
32760 Off ch=10 n=36 v=80
32760 Off ch=10 n=42 v=80
32760 Off ch=10 n=49 v=80
32880 On ch=10 n=36 v=95
32880 On ch=10 n=42 v=95
33000 Off ch=10 n=36 v=80
33000 Off ch=10 n=42 v=80
33120 On ch=10 n=38 v=95
33120 On ch=10 n=42 v=95
33240 Off ch=10 n=38 v=80
33240 Off ch=10 n=42 v=80
33360 On ch=10 n=42 v=95
33480 Off ch=10 n=42 v=80
33600 On ch=10 n=36 v=95
33600 On ch=10 n=42 v=95
33720 Off ch=10 n=36 v=80
33720 Off ch=10 n=42 v=80
33840 On ch=10 n=36 v=95
33840 On ch=10 n=42 v=95
33960 Off ch=10 n=36 v=80
33960 Off ch=10 n=42 v=80
34080 On ch=10 n=38 v=95
34080 On ch=10 n=42 v=95
34200 Off ch=10 n=38 v=80
34200 Off ch=10 n=42 v=80
34320 On ch=10 n=42 v=95
34440 Off ch=10 n=42 v=80
34560 On ch=10 n=36 v=95
34560 On ch=10 n=42 v=95
34680 Off ch=10 n=36 v=80
34680 Off ch=10 n=42 v=80
34800 On ch=10 n=36 v=95
34800 On ch=10 n=42 v=95
34920 Off ch=10 n=36 v=80
34920 Off ch=10 n=42 v=80
35040 On ch=10 n=38 v=95
35160 Off ch=10 n=38 v=80
35280 On ch=10 n=42 v=95
35400 Off ch=10 n=42 v=80
35520 On ch=10 n=36 v=95
35640 Off ch=10 n=36 v=80
35760 On ch=10 n=36 v=95
35760 On ch=10 n=42 v=95
35880 Off ch=10 n=36 v=80
35880 Off ch=10 n=42 v=80
36000 On ch=10 n=38 v=95
36120 Off ch=10 n=38 v=80
36240 On ch=10 n=46 v=95
36360 Off ch=10 n=46 v=80
36480 On ch=10 n=36 v=95
36480 On ch=10 n=42 v=95
36600 Off ch=10 n=36 v=80
36600 Off ch=10 n=42 v=80
36720 On ch=10 n=36 v=95
36840 Off ch=10 n=36 v=80
36960 On ch=10 n=38 v=95
36960 On ch=10 n=42 v=95
37080 Off ch=10 n=38 v=80
37080 Off ch=10 n=42 v=80
37200 On ch=10 n=42 v=95
37320 Off ch=10 n=42 v=80
37440 On ch=10 n=36 v=95
37440 On ch=10 n=42 v=95
37560 Off ch=10 n=36 v=80
37560 Off ch=10 n=42 v=80
37680 On ch=10 n=36 v=95
37680 On ch=10 n=42 v=95
37800 Off ch=10 n=36 v=80
37800 Off ch=10 n=42 v=80
37920 On ch=10 n=38 v=95
38040 Off ch=10 n=38 v=80
38160 On ch=10 n=42 v=95
38280 Off ch=10 n=42 v=80
38400 On ch=10 n=36 v=95
38400 On ch=10 n=42 v=95
38520 Off ch=10 n=36 v=80
38520 Off ch=10 n=42 v=80
38640 On ch=10 n=36 v=95
38640 On ch=10 n=42 v=95
38760 Off ch=10 n=36 v=80
38760 Off ch=10 n=42 v=80
38880 On ch=10 n=38 v=95
38880 On ch=10 n=42 v=95
39000 Off ch=10 n=38 v=80
39000 Off ch=10 n=42 v=80
39120 On ch=10 n=42 v=95
39240 Off ch=10 n=42 v=80
39360 On ch=10 n=36 v=95
39480 Off ch=10 n=36 v=80
39600 On ch=10 n=36 v=95
39600 On ch=10 n=42 v=95
39720 Off ch=10 n=36 v=80
39720 Off ch=10 n=42 v=80
39840 On ch=10 n=38 v=95
39840 On ch=10 n=42 v=95
39960 Off ch=10 n=38 v=80
39960 Off ch=10 n=42 v=80
40080 On ch=10 n=46 v=95
40200 Off ch=10 n=46 v=80
40320 On ch=10 n=36 v=95
40320 On ch=10 n=42 v=95
40440 Off ch=10 n=36 v=80
40440 Off ch=10 n=42 v=80
40560 On ch=10 n=36 v=95
40680 Off ch=10 n=36 v=80
40800 On ch=10 n=38 v=95
40800 On ch=10 n=42 v=95
40920 Off ch=10 n=38 v=80
40920 Off ch=10 n=42 v=80
41040 On ch=10 n=42 v=95
41160 Off ch=10 n=42 v=80
41280 On ch=10 n=36 v=95
41280 On ch=10 n=42 v=95
41400 Off ch=10 n=36 v=80
41400 Off ch=10 n=42 v=80
41520 On ch=10 n=36 v=95
41520 On ch=10 n=42 v=95
41640 Off ch=10 n=36 v=80
41640 Off ch=10 n=42 v=80
41760 On ch=10 n=38 v=95
41760 On ch=10 n=42 v=95
41880 Off ch=10 n=38 v=80
41880 Off ch=10 n=42 v=80
42000 On ch=10 n=42 v=95
42120 Off ch=10 n=42 v=80
42240 On ch=10 n=36 v=95
42240 On ch=10 n=42 v=95
42360 Off ch=10 n=36 v=80
42360 Off ch=10 n=42 v=80
42480 On ch=10 n=36 v=95
42480 On ch=10 n=42 v=95
42600 Off ch=10 n=36 v=80
42600 Off ch=10 n=42 v=80
42720 On ch=10 n=38 v=95
42720 On ch=10 n=42 v=95
42840 Off ch=10 n=38 v=80
42840 Off ch=10 n=42 v=80
42960 On ch=10 n=42 v=95
43080 Off ch=10 n=42 v=80
43200 On ch=10 n=36 v=95
43200 On ch=10 n=42 v=95
43320 Off ch=10 n=36 v=80
43320 Off ch=10 n=42 v=80
43440 On ch=10 n=36 v=95
43440 On ch=10 n=42 v=95
43560 Off ch=10 n=36 v=80
43560 Off ch=10 n=42 v=80
43680 On ch=10 n=38 v=95
43680 On ch=10 n=42 v=95
43800 Off ch=10 n=38 v=80
43800 Off ch=10 n=42 v=80
43920 On ch=10 n=42 v=95
44040 Off ch=10 n=42 v=80
44160 On ch=10 n=36 v=95
44160 On ch=10 n=42 v=95
44280 Off ch=10 n=36 v=80
44280 Off ch=10 n=42 v=80
44400 On ch=10 n=36 v=95
44400 On ch=10 n=42 v=95
44520 Off ch=10 n=36 v=80
44520 Off ch=10 n=42 v=80
44640 On ch=10 n=38 v=95
44640 On ch=10 n=42 v=95
44760 Off ch=10 n=38 v=80
44760 Off ch=10 n=42 v=80
44880 On ch=10 n=42 v=95
45000 Off ch=10 n=42 v=80
45120 On ch=10 n=36 v=95
45240 Off ch=10 n=36 v=80
45360 On ch=10 n=36 v=95
45360 On ch=10 n=42 v=95
45480 Off ch=10 n=36 v=80
45480 Off ch=10 n=42 v=80
45600 On ch=10 n=38 v=95
45600 On ch=10 n=42 v=95
45720 Off ch=10 n=38 v=80
45720 Off ch=10 n=42 v=80
45840 On ch=10 n=42 v=95
45960 Off ch=10 n=42 v=80
46080 On ch=10 n=36 v=95
46080 On ch=10 n=42 v=95
46200 Off ch=10 n=36 v=80
46200 Off ch=10 n=42 v=80
46320 On ch=10 n=36 v=95
46440 Off ch=10 n=36 v=80
46560 On ch=10 n=38 v=95
46560 On ch=10 n=42 v=95
46680 Off ch=10 n=38 v=80
46680 Off ch=10 n=42 v=80
46800 On ch=10 n=42 v=95
46920 Off ch=10 n=42 v=80
47040 On ch=10 n=36 v=95
47040 On ch=10 n=42 v=95
47160 Off ch=10 n=36 v=80
47160 Off ch=10 n=42 v=80
47280 On ch=10 n=36 v=95
47280 On ch=10 n=42 v=95
47400 Off ch=10 n=36 v=80
47400 Off ch=10 n=42 v=80
47520 On ch=10 n=38 v=95
47640 Off ch=10 n=38 v=80
47760 On ch=10 n=46 v=95
47880 Off ch=10 n=46 v=80
48000 On ch=10 n=36 v=95
48000 On ch=10 n=42 v=95
48120 Off ch=10 n=36 v=80
48120 Off ch=10 n=42 v=80
48240 On ch=10 n=36 v=95
48240 On ch=10 n=42 v=95
48360 Off ch=10 n=36 v=80
48360 Off ch=10 n=42 v=80
48480 On ch=10 n=38 v=95
48480 On ch=10 n=42 v=95
48600 Off ch=10 n=38 v=80
48600 Off ch=10 n=42 v=80
48720 On ch=10 n=42 v=95
48840 Off ch=10 n=42 v=80
48960 On ch=10 n=36 v=95
48960 On ch=10 n=42 v=95
49080 Off ch=10 n=36 v=80
49080 Off ch=10 n=42 v=80
49200 On ch=10 n=36 v=95
49200 On ch=10 n=42 v=95
49320 Off ch=10 n=36 v=80
49320 Off ch=10 n=42 v=80
49440 On ch=10 n=38 v=95
49440 On ch=10 n=42 v=95
49560 Off ch=10 n=38 v=80
49560 Off ch=10 n=42 v=80
49680 On ch=10 n=42 v=95
49800 Off ch=10 n=42 v=80
49920 On ch=10 n=36 v=95
49920 On ch=10 n=42 v=95
50040 Off ch=10 n=36 v=80
50040 Off ch=10 n=42 v=80
50160 On ch=10 n=36 v=95
50280 Off ch=10 n=36 v=80
50400 On ch=10 n=38 v=95
50400 On ch=10 n=42 v=95
50520 Off ch=10 n=38 v=80
50520 Off ch=10 n=42 v=80
50640 On ch=10 n=42 v=95
50760 Off ch=10 n=42 v=80
50880 On ch=10 n=36 v=95
50880 On ch=10 n=42 v=95
51000 Off ch=10 n=36 v=80
51000 Off ch=10 n=42 v=80
51120 On ch=10 n=36 v=95
51120 On ch=10 n=42 v=95
51240 Off ch=10 n=36 v=80
51240 Off ch=10 n=42 v=80
51360 On ch=10 n=38 v=95
51360 On ch=10 n=42 v=95
51480 Off ch=10 n=38 v=80
51480 Off ch=10 n=42 v=80
51600 On ch=10 n=42 v=95
51720 Off ch=10 n=42 v=80
51840 On ch=10 n=36 v=95
51840 On ch=10 n=42 v=95
51960 Off ch=10 n=36 v=80
51960 Off ch=10 n=42 v=80
52080 On ch=10 n=36 v=95
52080 On ch=10 n=42 v=95
52200 Off ch=10 n=36 v=80
52200 Off ch=10 n=42 v=80
52320 On ch=10 n=38 v=95
52320 On ch=10 n=42 v=95
52440 Off ch=10 n=38 v=80
52440 Off ch=10 n=42 v=80
52560 On ch=10 n=42 v=95
52680 Off ch=10 n=42 v=80
52800 On ch=10 n=36 v=95
52800 On ch=10 n=42 v=95
52920 Off ch=10 n=36 v=80
52920 Off ch=10 n=42 v=80
53040 On ch=10 n=36 v=95
53040 On ch=10 n=42 v=95
53160 Off ch=10 n=36 v=80
53160 Off ch=10 n=42 v=80
53280 On ch=10 n=38 v=95
53280 On ch=10 n=42 v=95
53400 Off ch=10 n=38 v=80
53400 Off ch=10 n=42 v=80
53520 On ch=10 n=42 v=95
53640 Off ch=10 n=42 v=80
53760 On ch=10 n=36 v=95
53760 On ch=10 n=42 v=95
53880 Off ch=10 n=36 v=80
53880 Off ch=10 n=42 v=80
54000 On ch=10 n=36 v=95
54000 On ch=10 n=42 v=95
54120 Off ch=10 n=36 v=80
54120 Off ch=10 n=42 v=80
54240 On ch=10 n=38 v=95
54240 On ch=10 n=42 v=95
54360 Off ch=10 n=38 v=80
54360 Off ch=10 n=42 v=80
54480 On ch=10 n=42 v=95
54600 Off ch=10 n=42 v=80
54720 On ch=10 n=38 v=95
54720 On ch=10 n=42 v=95
54840 Off ch=10 n=38 v=80
54840 Off ch=10 n=42 v=80
54840 On ch=10 n=38 v=95
54960 Off ch=10 n=38 v=80
54960 On ch=10 n=38 v=95
54960 On ch=10 n=42 v=95
55080 Off ch=10 n=38 v=80
55080 Off ch=10 n=42 v=80
55080 On ch=10 n=38 v=95
55200 Off ch=10 n=38 v=80
55200 On ch=10 n=38 v=95
55200 On ch=10 n=42 v=95
55320 Off ch=10 n=38 v=80
55320 Off ch=10 n=42 v=80
55440 On ch=10 n=38 v=95
55440 On ch=10 n=42 v=95
55440 On ch=10 n=43 v=95
55560 Off ch=10 n=38 v=80
55560 Off ch=10 n=42 v=80
55560 Off ch=10 n=43 v=80
55680 On ch=10 n=36 v=95
55680 On ch=10 n=42 v=95
55800 Off ch=10 n=36 v=80
55800 Off ch=10 n=42 v=80
55920 On ch=10 n=36 v=95
55920 On ch=10 n=42 v=95
56040 Off ch=10 n=36 v=80
56040 Off ch=10 n=42 v=80
56160 On ch=10 n=38 v=95
56160 On ch=10 n=42 v=95
56160 On ch=10 n=49 v=95
56280 Off ch=10 n=38 v=80
56280 Off ch=10 n=42 v=80
56280 Off ch=10 n=49 v=80
56400 On ch=10 n=42 v=95
56520 Off ch=10 n=42 v=80
56640 On ch=10 n=36 v=95
56640 On ch=10 n=42 v=95
56760 Off ch=10 n=36 v=80
56760 Off ch=10 n=42 v=80
56880 On ch=10 n=36 v=95
56880 On ch=10 n=42 v=95
57000 Off ch=10 n=36 v=80
57000 Off ch=10 n=42 v=80
57120 On ch=10 n=38 v=95
57120 On ch=10 n=42 v=95
57240 Off ch=10 n=38 v=80
57240 Off ch=10 n=42 v=80
57360 On ch=10 n=42 v=95
57480 Off ch=10 n=42 v=80
57600 On ch=10 n=36 v=95
57600 On ch=10 n=42 v=95
57720 Off ch=10 n=36 v=80
57720 Off ch=10 n=42 v=80
57840 On ch=10 n=36 v=95
57840 On ch=10 n=42 v=95
57960 Off ch=10 n=36 v=80
57960 Off ch=10 n=42 v=80
58080 On ch=10 n=38 v=95
58080 On ch=10 n=42 v=95
58200 Off ch=10 n=38 v=80
58200 Off ch=10 n=42 v=80
58320 On ch=10 n=36 v=95
58320 On ch=10 n=42 v=95
58440 Off ch=10 n=36 v=80
58440 Off ch=10 n=42 v=80
58560 On ch=10 n=42 v=95
58680 Off ch=10 n=42 v=80
58800 On ch=10 n=36 v=95
58800 On ch=10 n=42 v=95
58920 Off ch=10 n=36 v=80
58920 Off ch=10 n=42 v=80
59040 On ch=10 n=38 v=95
59040 On ch=10 n=42 v=95
59160 Off ch=10 n=38 v=80
59160 Off ch=10 n=42 v=80
59160 On ch=10 n=36 v=95
59160 On ch=10 n=49 v=95
59280 Off ch=10 n=36 v=80
59280 Off ch=10 n=49 v=80
59520 On ch=10 n=36 v=95
59520 On ch=10 n=42 v=95
59640 Off ch=10 n=36 v=80
59640 Off ch=10 n=42 v=80
59760 On ch=10 n=36 v=95
59760 On ch=10 n=42 v=95
59880 Off ch=10 n=36 v=80
59880 Off ch=10 n=42 v=80
60000 On ch=10 n=38 v=95
60000 On ch=10 n=42 v=95
60120 Off ch=10 n=38 v=80
60120 Off ch=10 n=42 v=80
60240 On ch=10 n=42 v=95
60360 Off ch=10 n=42 v=80
60480 On ch=10 n=36 v=95
60480 On ch=10 n=42 v=95
60600 Off ch=10 n=36 v=80
60600 Off ch=10 n=42 v=80
60720 On ch=10 n=36 v=95
60720 On ch=10 n=42 v=95
60840 Off ch=10 n=36 v=80
60840 Off ch=10 n=42 v=80
60960 On ch=10 n=38 v=95
60960 On ch=10 n=42 v=95
61080 Off ch=10 n=38 v=80
61080 Off ch=10 n=42 v=80
61200 On ch=10 n=42 v=95
61320 Off ch=10 n=42 v=80
61440 On ch=10 n=36 v=95
61440 On ch=10 n=42 v=95
61560 Off ch=10 n=36 v=80
61560 Off ch=10 n=42 v=80
61680 On ch=10 n=36 v=95
61680 On ch=10 n=42 v=95
61800 Off ch=10 n=36 v=80
61800 Off ch=10 n=42 v=80
61920 On ch=10 n=38 v=95
61920 On ch=10 n=42 v=95
62040 Off ch=10 n=38 v=80
62040 Off ch=10 n=42 v=80
62160 On ch=10 n=36 v=95
62160 On ch=10 n=42 v=95
62280 Off ch=10 n=36 v=80
62280 Off ch=10 n=42 v=80
62400 On ch=10 n=42 v=95
62520 Off ch=10 n=42 v=80
62640 On ch=10 n=36 v=95
62640 On ch=10 n=42 v=95
62760 Off ch=10 n=36 v=80
62760 Off ch=10 n=42 v=80
62880 On ch=10 n=38 v=95
62880 On ch=10 n=42 v=95
63000 Off ch=10 n=38 v=80
63000 Off ch=10 n=42 v=80
63120 On ch=10 n=36 v=95
63120 On ch=10 n=42 v=95
63240 Off ch=10 n=36 v=80
63240 Off ch=10 n=42 v=80
63360 On ch=10 n=36 v=95
63360 On ch=10 n=42 v=95
63480 Off ch=10 n=36 v=80
63480 Off ch=10 n=42 v=80
63600 On ch=10 n=36 v=95
63600 On ch=10 n=42 v=95
63720 Off ch=10 n=36 v=80
63720 Off ch=10 n=42 v=80
63840 On ch=10 n=38 v=95
63840 On ch=10 n=42 v=95
63960 Off ch=10 n=38 v=80
63960 Off ch=10 n=42 v=80
64080 On ch=10 n=42 v=95
64200 Off ch=10 n=42 v=80
64320 On ch=10 n=36 v=95
64320 On ch=10 n=42 v=95
64440 Off ch=10 n=36 v=80
64440 Off ch=10 n=42 v=80
64560 On ch=10 n=36 v=95
64680 Off ch=10 n=36 v=80
64800 On ch=10 n=38 v=95
64800 On ch=10 n=42 v=95
64920 Off ch=10 n=38 v=80
64920 Off ch=10 n=42 v=80
65040 On ch=10 n=42 v=95
65160 Off ch=10 n=42 v=80
65280 On ch=10 n=36 v=95
65280 On ch=10 n=42 v=95
65400 Off ch=10 n=36 v=80
65400 Off ch=10 n=42 v=80
65520 On ch=10 n=36 v=95
65520 On ch=10 n=42 v=95
65640 Off ch=10 n=36 v=80
65640 Off ch=10 n=42 v=80
65760 On ch=10 n=38 v=95
65760 On ch=10 n=42 v=95
65880 Off ch=10 n=38 v=80
65880 Off ch=10 n=42 v=80
66000 On ch=10 n=36 v=95
66000 On ch=10 n=42 v=95
66120 Off ch=10 n=36 v=80
66120 Off ch=10 n=42 v=80
66240 On ch=10 n=42 v=95
66360 Off ch=10 n=42 v=80
66480 On ch=10 n=42 v=95
66600 Off ch=10 n=42 v=80
66720 On ch=10 n=38 v=95
66720 On ch=10 n=42 v=95
66840 Off ch=10 n=38 v=80
66840 Off ch=10 n=42 v=80
66840 On ch=10 n=36 v=95
66960 Off ch=10 n=36 v=80
67200 On ch=10 n=36 v=95
67200 On ch=10 n=42 v=95
67320 Off ch=10 n=36 v=80
67320 Off ch=10 n=42 v=80
67440 On ch=10 n=36 v=95
67560 Off ch=10 n=36 v=80
67680 On ch=10 n=38 v=95
67800 Off ch=10 n=38 v=80
67920 On ch=10 n=42 v=95
68040 Off ch=10 n=42 v=80
68160 On ch=10 n=36 v=95
68280 Off ch=10 n=36 v=80
68400 On ch=10 n=36 v=95
68400 On ch=10 n=42 v=95
68520 Off ch=10 n=36 v=80
68520 Off ch=10 n=42 v=80
68640 On ch=10 n=38 v=95
68640 On ch=10 n=42 v=95
68760 Off ch=10 n=38 v=80
68760 Off ch=10 n=42 v=80
68880 On ch=10 n=42 v=95
69000 Off ch=10 n=42 v=80
69120 On ch=10 n=36 v=95
69120 On ch=10 n=42 v=95
69240 Off ch=10 n=36 v=80
69240 Off ch=10 n=42 v=80
69360 On ch=10 n=36 v=95
69480 Off ch=10 n=36 v=80
69600 On ch=10 n=38 v=95
69600 On ch=10 n=42 v=95
69720 Off ch=10 n=38 v=80
69720 Off ch=10 n=42 v=80
69840 On ch=10 n=36 v=95
69960 Off ch=10 n=36 v=80
70080 On ch=10 n=49 v=95
70200 Off ch=10 n=49 v=80
70560 On ch=10 n=49 v=95
70680 Off ch=10 n=49 v=80
71040 On ch=10 n=36 v=95
71040 On ch=10 n=42 v=95
71040 On ch=10 n=49 v=95
71160 Off ch=10 n=36 v=80
71160 Off ch=10 n=42 v=80
71160 Off ch=10 n=49 v=80
71280 On ch=10 n=36 v=95
71280 On ch=10 n=42 v=95
71400 Off ch=10 n=36 v=80
71400 Off ch=10 n=42 v=80
71520 On ch=10 n=38 v=95
71520 On ch=10 n=42 v=95
71640 Off ch=10 n=38 v=80
71640 Off ch=10 n=42 v=80
71760 On ch=10 n=42 v=95
71880 Off ch=10 n=42 v=80
72000 On ch=10 n=36 v=95
72000 On ch=10 n=42 v=95
72120 Off ch=10 n=36 v=80
72120 Off ch=10 n=42 v=80
72240 On ch=10 n=36 v=95
72240 On ch=10 n=42 v=95
72360 Off ch=10 n=36 v=80
72360 Off ch=10 n=42 v=80
72480 On ch=10 n=38 v=95
72480 On ch=10 n=42 v=95
72600 Off ch=10 n=38 v=80
72600 Off ch=10 n=42 v=80
72720 On ch=10 n=42 v=95
72840 Off ch=10 n=42 v=80
72960 On ch=10 n=36 v=95
72960 On ch=10 n=42 v=95
73080 Off ch=10 n=36 v=80
73080 Off ch=10 n=42 v=80
73200 On ch=10 n=36 v=95
73320 Off ch=10 n=36 v=80
73440 On ch=10 n=38 v=95
73440 On ch=10 n=42 v=95
73560 Off ch=10 n=38 v=80
73560 Off ch=10 n=42 v=80
73680 On ch=10 n=42 v=95
73800 Off ch=10 n=42 v=80
73920 On ch=10 n=36 v=95
74040 Off ch=10 n=36 v=80
74160 On ch=10 n=36 v=95
74280 Off ch=10 n=36 v=80
74400 On ch=10 n=38 v=95
74400 On ch=10 n=42 v=95
74520 Off ch=10 n=38 v=80
74520 Off ch=10 n=42 v=80
74640 On ch=10 n=46 v=95
74760 Off ch=10 n=46 v=80
74880 On ch=10 n=36 v=95
74880 On ch=10 n=42 v=95
75000 Off ch=10 n=36 v=80
75000 Off ch=10 n=42 v=80
75120 On ch=10 n=36 v=95
75120 On ch=10 n=42 v=95
75240 Off ch=10 n=36 v=80
75240 Off ch=10 n=42 v=80
75360 On ch=10 n=38 v=95
75360 On ch=10 n=42 v=95
75480 Off ch=10 n=38 v=80
75480 Off ch=10 n=42 v=80
75600 On ch=10 n=42 v=95
75720 Off ch=10 n=42 v=80
75840 On ch=10 n=36 v=95
75840 On ch=10 n=42 v=95
75960 Off ch=10 n=36 v=80
75960 Off ch=10 n=42 v=80
76080 On ch=10 n=36 v=95
76200 Off ch=10 n=36 v=80
76320 On ch=10 n=38 v=95
76440 Off ch=10 n=38 v=80
76560 On ch=10 n=42 v=95
76680 Off ch=10 n=42 v=80
76800 On ch=10 n=36 v=95
76800 On ch=10 n=42 v=95
76920 Off ch=10 n=36 v=80
76920 Off ch=10 n=42 v=80
77040 On ch=10 n=36 v=95
77040 On ch=10 n=42 v=95
77160 Off ch=10 n=36 v=80
77160 Off ch=10 n=42 v=80
77280 On ch=10 n=38 v=95
77400 Off ch=10 n=38 v=80
77520 On ch=10 n=42 v=95
77640 Off ch=10 n=42 v=80
77760 On ch=10 n=36 v=95
77880 Off ch=10 n=36 v=80
78000 On ch=10 n=36 v=95
78000 On ch=10 n=42 v=95
78120 Off ch=10 n=36 v=80
78120 Off ch=10 n=42 v=80
78240 On ch=10 n=38 v=95
78240 On ch=10 n=42 v=95
78360 Off ch=10 n=38 v=80
78360 Off ch=10 n=42 v=80
78480 On ch=10 n=46 v=95
78600 Off ch=10 n=46 v=80
78720 On ch=10 n=36 v=95
78720 On ch=10 n=42 v=95
78840 Off ch=10 n=36 v=80
78840 Off ch=10 n=42 v=80
78960 On ch=10 n=36 v=95
78960 On ch=10 n=42 v=95
79080 Off ch=10 n=36 v=80
79080 Off ch=10 n=42 v=80
79200 On ch=10 n=38 v=95
79200 On ch=10 n=42 v=95
79320 Off ch=10 n=38 v=80
79320 Off ch=10 n=42 v=80
79440 On ch=10 n=42 v=95
79560 Off ch=10 n=42 v=80
79680 On ch=10 n=36 v=95
79680 On ch=10 n=42 v=95
79800 Off ch=10 n=36 v=80
79800 Off ch=10 n=42 v=80
79920 On ch=10 n=36 v=95
80040 Off ch=10 n=36 v=80
80160 On ch=10 n=38 v=95
80280 Off ch=10 n=38 v=80
80400 On ch=10 n=42 v=95
80520 Off ch=10 n=42 v=80
80640 On ch=10 n=36 v=95
80640 On ch=10 n=42 v=95
80760 Off ch=10 n=36 v=80
80760 Off ch=10 n=42 v=80
80880 On ch=10 n=36 v=95
80880 On ch=10 n=42 v=95
81000 Off ch=10 n=36 v=80
81000 Off ch=10 n=42 v=80
81120 On ch=10 n=38 v=95
81120 On ch=10 n=42 v=95
81240 Off ch=10 n=38 v=80
81240 Off ch=10 n=42 v=80
81360 On ch=10 n=36 v=95
81360 On ch=10 n=42 v=95
81480 Off ch=10 n=36 v=80
81480 Off ch=10 n=42 v=80
81600 On ch=10 n=36 v=95
81600 On ch=10 n=49 v=95
81720 Off ch=10 n=36 v=80
81720 Off ch=10 n=49 v=80
82080 On ch=10 n=36 v=95
82080 On ch=10 n=49 v=95
82200 Off ch=10 n=36 v=80
82200 Off ch=10 n=49 v=80
82560 On ch=10 n=36 v=95
82560 On ch=10 n=42 v=95
82560 On ch=10 n=49 v=95
82680 Off ch=10 n=36 v=80
82680 Off ch=10 n=42 v=80
82680 Off ch=10 n=49 v=80
82800 On ch=10 n=36 v=95
82920 Off ch=10 n=36 v=80
83040 On ch=10 n=38 v=95
83040 On ch=10 n=42 v=95
83160 Off ch=10 n=38 v=80
83160 Off ch=10 n=42 v=80
83280 On ch=10 n=42 v=95
83400 Off ch=10 n=42 v=80
83520 On ch=10 n=36 v=95
83640 Off ch=10 n=36 v=80
83760 On ch=10 n=36 v=95
83880 Off ch=10 n=36 v=80
84000 On ch=10 n=38 v=95
84120 Off ch=10 n=38 v=80
84240 On ch=10 n=42 v=95
84360 Off ch=10 n=42 v=80
84480 On ch=10 n=36 v=95
84480 On ch=10 n=42 v=95
84600 Off ch=10 n=36 v=80
84600 Off ch=10 n=42 v=80
84720 On ch=10 n=36 v=95
84720 On ch=10 n=42 v=95
84840 Off ch=10 n=36 v=80
84840 Off ch=10 n=42 v=80
84960 On ch=10 n=38 v=95
84960 On ch=10 n=42 v=95
85080 Off ch=10 n=38 v=80
85080 Off ch=10 n=42 v=80
85200 On ch=10 n=42 v=95
85320 Off ch=10 n=42 v=80
85440 On ch=10 n=36 v=95
85440 On ch=10 n=42 v=95
85560 Off ch=10 n=36 v=80
85560 Off ch=10 n=42 v=80
85680 On ch=10 n=36 v=95
85800 Off ch=10 n=36 v=80
85920 On ch=10 n=38 v=95
85920 On ch=10 n=42 v=95
86040 Off ch=10 n=38 v=80
86040 Off ch=10 n=42 v=80
86160 On ch=10 n=46 v=95
86280 Off ch=10 n=46 v=80
86400 On ch=10 n=36 v=95
86400 On ch=10 n=42 v=95
86520 Off ch=10 n=36 v=80
86520 Off ch=10 n=42 v=80
86640 On ch=10 n=36 v=95
86640 On ch=10 n=42 v=95
86760 Off ch=10 n=36 v=80
86760 Off ch=10 n=42 v=80
86880 On ch=10 n=38 v=95
87000 Off ch=10 n=38 v=80
87120 On ch=10 n=42 v=95
87240 Off ch=10 n=42 v=80
87360 On ch=10 n=36 v=95
87480 Off ch=10 n=36 v=80
87600 On ch=10 n=36 v=95
87720 Off ch=10 n=36 v=80
87840 On ch=10 n=38 v=95
87840 On ch=10 n=42 v=95
87960 Off ch=10 n=38 v=80
87960 Off ch=10 n=42 v=80
88080 On ch=10 n=42 v=95
88200 Off ch=10 n=42 v=80
88320 On ch=10 n=36 v=95
88320 On ch=10 n=42 v=95
88440 Off ch=10 n=36 v=80
88440 Off ch=10 n=42 v=80
88560 On ch=10 n=36 v=95
88560 On ch=10 n=42 v=95
88680 Off ch=10 n=36 v=80
88680 Off ch=10 n=42 v=80
88800 On ch=10 n=38 v=95
88800 On ch=10 n=42 v=95
88920 Off ch=10 n=38 v=80
88920 Off ch=10 n=42 v=80
89040 On ch=10 n=42 v=95
89160 Off ch=10 n=42 v=80
89280 On ch=10 n=36 v=95
89280 On ch=10 n=42 v=95
89400 Off ch=10 n=36 v=80
89400 Off ch=10 n=42 v=80
89520 On ch=10 n=36 v=95
89640 Off ch=10 n=36 v=80
89760 On ch=10 n=38 v=95
89880 Off ch=10 n=38 v=80
90000 On ch=10 n=42 v=95
90120 Off ch=10 n=42 v=80
90240 On ch=10 n=36 v=95
90240 On ch=10 n=42 v=95
90360 Off ch=10 n=36 v=80
90360 Off ch=10 n=42 v=80
90480 On ch=10 n=36 v=95
90480 On ch=10 n=42 v=95
90600 Off ch=10 n=36 v=80
90600 Off ch=10 n=42 v=80
90720 On ch=10 n=38 v=95
90840 Off ch=10 n=38 v=80
90960 On ch=10 n=42 v=95
91080 Off ch=10 n=42 v=80
91200 On ch=10 n=36 v=95
91200 On ch=10 n=42 v=95
91320 Off ch=10 n=36 v=80
91320 Off ch=10 n=42 v=80
91440 On ch=10 n=36 v=95
91440 On ch=10 n=42 v=95
91560 Off ch=10 n=36 v=80
91560 Off ch=10 n=42 v=80
91680 On ch=10 n=38 v=95
91680 On ch=10 n=42 v=95
91800 Off ch=10 n=38 v=80
91800 Off ch=10 n=42 v=80
91920 On ch=10 n=42 v=95
92040 Off ch=10 n=42 v=80
92160 On ch=10 n=36 v=95
92160 On ch=10 n=42 v=95
92280 Off ch=10 n=36 v=80
92280 Off ch=10 n=42 v=80
92400 On ch=10 n=36 v=95
92400 On ch=10 n=42 v=95
92520 Off ch=10 n=36 v=80
92520 Off ch=10 n=42 v=80
92640 On ch=10 n=38 v=95
92640 On ch=10 n=42 v=95
92760 Off ch=10 n=38 v=80
92760 Off ch=10 n=42 v=80
92880 On ch=10 n=42 v=95
93000 Off ch=10 n=42 v=80
93120 On ch=10 n=38 v=95
93240 Off ch=10 n=38 v=80
93360 On ch=10 n=38 v=95
93480 Off ch=10 n=38 v=80
93600 On ch=10 n=38 v=95
93720 Off ch=10 n=38 v=80
93840 On ch=10 n=38 v=95
93840 On ch=10 n=43 v=95
93960 Off ch=10 n=38 v=80
93960 Off ch=10 n=43 v=80
94080 On ch=10 n=36 v=95
94080 On ch=10 n=42 v=95
94200 Off ch=10 n=36 v=80
94200 Off ch=10 n=42 v=80
94320 On ch=10 n=36 v=95
94320 On ch=10 n=42 v=95
94440 Off ch=10 n=36 v=80
94440 Off ch=10 n=42 v=80
94560 On ch=10 n=38 v=95
94560 On ch=10 n=42 v=95
94560 On ch=10 n=49 v=95
94680 Off ch=10 n=38 v=80
94680 Off ch=10 n=42 v=80
94680 Off ch=10 n=49 v=80
94800 On ch=10 n=42 v=95
94920 Off ch=10 n=42 v=80
95040 On ch=10 n=36 v=95
95040 On ch=10 n=42 v=95
95160 Off ch=10 n=36 v=80
95160 Off ch=10 n=42 v=80
95280 On ch=10 n=36 v=95
95280 On ch=10 n=42 v=95
95400 Off ch=10 n=36 v=80
95400 Off ch=10 n=42 v=80
95520 On ch=10 n=38 v=95
95520 On ch=10 n=42 v=95
95640 Off ch=10 n=38 v=80
95640 Off ch=10 n=42 v=80
95760 On ch=10 n=42 v=95
95880 Off ch=10 n=42 v=80
96000 On ch=10 n=36 v=95
96000 On ch=10 n=42 v=95
96120 Off ch=10 n=36 v=80
96120 Off ch=10 n=42 v=80
96240 On ch=10 n=42 v=95
96360 Off ch=10 n=42 v=80
96480 On ch=10 n=38 v=95
96480 On ch=10 n=42 v=95
96600 Off ch=10 n=38 v=80
96600 Off ch=10 n=42 v=80
96720 On ch=10 n=36 v=95
96720 On ch=10 n=42 v=95
96840 Off ch=10 n=36 v=80
96840 Off ch=10 n=42 v=80
96960 On ch=10 n=42 v=95
97080 Off ch=10 n=42 v=80
97200 On ch=10 n=36 v=95
97200 On ch=10 n=42 v=95
97320 Off ch=10 n=36 v=80
97320 Off ch=10 n=42 v=80
97440 On ch=10 n=38 v=95
97440 On ch=10 n=42 v=95
97560 Off ch=10 n=38 v=80
97560 Off ch=10 n=42 v=80
97560 On ch=10 n=36 v=95
97560 On ch=10 n=49 v=95
97680 Off ch=10 n=36 v=80
97680 Off ch=10 n=49 v=80
97920 On ch=10 n=36 v=95
97920 On ch=10 n=42 v=95
98040 Off ch=10 n=36 v=80
98040 Off ch=10 n=42 v=80
98160 On ch=10 n=36 v=95
98160 On ch=10 n=42 v=95
98280 Off ch=10 n=36 v=80
98280 Off ch=10 n=42 v=80
98400 On ch=10 n=38 v=95
98400 On ch=10 n=42 v=95
98520 Off ch=10 n=38 v=80
98520 Off ch=10 n=42 v=80
98640 On ch=10 n=42 v=95
98760 Off ch=10 n=42 v=80
98880 On ch=10 n=36 v=95
98880 On ch=10 n=42 v=95
99000 Off ch=10 n=36 v=80
99000 Off ch=10 n=42 v=80
99120 On ch=10 n=36 v=95
99120 On ch=10 n=42 v=95
99240 Off ch=10 n=36 v=80
99240 Off ch=10 n=42 v=80
99360 On ch=10 n=38 v=95
99360 On ch=10 n=42 v=95
99480 Off ch=10 n=38 v=80
99480 Off ch=10 n=42 v=80
99600 On ch=10 n=42 v=95
99720 Off ch=10 n=42 v=80
99840 On ch=10 n=36 v=95
99840 On ch=10 n=42 v=95
99960 Off ch=10 n=36 v=80
99960 Off ch=10 n=42 v=80
100080 On ch=10 n=36 v=95
100200 Off ch=10 n=36 v=80
100320 On ch=10 n=38 v=95
100320 On ch=10 n=42 v=95
100440 Off ch=10 n=38 v=80
100440 Off ch=10 n=42 v=80
100560 On ch=10 n=36 v=95
100680 Off ch=10 n=36 v=80
100800 On ch=10 n=42 v=95
100920 Off ch=10 n=42 v=80
101040 On ch=10 n=36 v=95
101040 On ch=10 n=42 v=95
101160 Off ch=10 n=36 v=80
101160 Off ch=10 n=42 v=80
101280 On ch=10 n=38 v=95
101400 Off ch=10 n=38 v=80
101520 On ch=10 n=36 v=95
101640 Off ch=10 n=36 v=80
101760 On ch=10 n=36 v=95
101760 On ch=10 n=42 v=95
101880 Off ch=10 n=36 v=80
101880 Off ch=10 n=42 v=80
102000 On ch=10 n=36 v=95
102000 On ch=10 n=42 v=95
102120 Off ch=10 n=36 v=80
102120 Off ch=10 n=42 v=80
102240 On ch=10 n=38 v=95
102240 On ch=10 n=42 v=95
102360 Off ch=10 n=38 v=80
102360 Off ch=10 n=42 v=80
102480 On ch=10 n=42 v=95
102600 Off ch=10 n=42 v=80
102720 On ch=10 n=36 v=95
102720 On ch=10 n=42 v=95
102840 Off ch=10 n=36 v=80
102840 Off ch=10 n=42 v=80
102960 On ch=10 n=36 v=95
103080 Off ch=10 n=36 v=80
103200 On ch=10 n=38 v=95
103200 On ch=10 n=42 v=95
103320 Off ch=10 n=38 v=80
103320 Off ch=10 n=42 v=80
103440 On ch=10 n=42 v=95
103560 Off ch=10 n=42 v=80
103680 On ch=10 n=36 v=95
103680 On ch=10 n=42 v=95
103800 Off ch=10 n=36 v=80
103800 Off ch=10 n=42 v=80
103920 On ch=10 n=42 v=95
104040 Off ch=10 n=42 v=80
104160 On ch=10 n=42 v=95
104280 Off ch=10 n=42 v=80
104400 On ch=10 n=36 v=95
104400 On ch=10 n=42 v=95
104520 Off ch=10 n=36 v=80
104520 Off ch=10 n=42 v=80
104640 On ch=10 n=42 v=95
104760 Off ch=10 n=42 v=80
104880 On ch=10 n=36 v=95
104880 On ch=10 n=42 v=95
105000 Off ch=10 n=36 v=80
105000 Off ch=10 n=42 v=80
105120 On ch=10 n=38 v=95
105120 On ch=10 n=42 v=95
105240 Off ch=10 n=38 v=80
105240 Off ch=10 n=42 v=80
105240 On ch=10 n=36 v=95
105240 On ch=10 n=49 v=95
105360 Off ch=10 n=36 v=80
105360 Off ch=10 n=49 v=80
105600 On ch=10 n=36 v=95
105600 On ch=10 n=42 v=95
105720 Off ch=10 n=36 v=80
105720 Off ch=10 n=42 v=80
105840 On ch=10 n=36 v=95
105840 On ch=10 n=42 v=95
105960 Off ch=10 n=36 v=80
105960 Off ch=10 n=42 v=80
106080 On ch=10 n=38 v=95
106080 On ch=10 n=42 v=95
106200 Off ch=10 n=38 v=80
106200 Off ch=10 n=42 v=80
106320 On ch=10 n=42 v=95
106440 Off ch=10 n=42 v=80
106560 On ch=10 n=36 v=95
106560 On ch=10 n=42 v=95
106680 Off ch=10 n=36 v=80
106680 Off ch=10 n=42 v=80
106800 On ch=10 n=36 v=95
106920 Off ch=10 n=36 v=80
107040 On ch=10 n=38 v=95
107040 On ch=10 n=42 v=95
107160 Off ch=10 n=38 v=80
107160 Off ch=10 n=42 v=80
107280 On ch=10 n=42 v=95
107400 Off ch=10 n=42 v=80
107520 On ch=10 n=36 v=95
107520 On ch=10 n=42 v=95
107640 Off ch=10 n=36 v=80
107640 Off ch=10 n=42 v=80
107760 On ch=10 n=36 v=95
107880 Off ch=10 n=36 v=80
108000 On ch=10 n=38 v=95
108120 Off ch=10 n=38 v=80
108240 On ch=10 n=36 v=95
108240 On ch=10 n=42 v=95
108360 Off ch=10 n=36 v=80
108360 Off ch=10 n=42 v=80
108480 On ch=10 n=36 v=95
108480 On ch=10 n=49 v=95
108600 Off ch=10 n=36 v=80
108600 Off ch=10 n=49 v=80
108960 On ch=10 n=36 v=95
108960 On ch=10 n=49 v=95
109080 Off ch=10 n=36 v=80
109080 Off ch=10 n=49 v=80
109440 On ch=10 n=36 v=95
109440 On ch=10 n=42 v=95
109560 Off ch=10 n=36 v=80
109560 Off ch=10 n=42 v=80
109680 On ch=10 n=36 v=95
109680 On ch=10 n=42 v=95
109800 Off ch=10 n=36 v=80
109800 Off ch=10 n=42 v=80
109920 On ch=10 n=38 v=95
109920 On ch=10 n=42 v=95
109920 On ch=10 n=49 v=95
110040 Off ch=10 n=38 v=80
110040 Off ch=10 n=42 v=80
110040 Off ch=10 n=49 v=80
110160 On ch=10 n=42 v=95
110280 Off ch=10 n=42 v=80
110400 On ch=10 n=36 v=95
110400 On ch=10 n=42 v=95
110520 Off ch=10 n=36 v=80
110520 Off ch=10 n=42 v=80
110640 On ch=10 n=36 v=95
110640 On ch=10 n=42 v=95
110760 Off ch=10 n=36 v=80
110760 Off ch=10 n=42 v=80
110880 On ch=10 n=38 v=95
110880 On ch=10 n=42 v=95
111000 Off ch=10 n=38 v=80
111000 Off ch=10 n=42 v=80
111120 On ch=10 n=42 v=95
111240 Off ch=10 n=42 v=80
111360 On ch=10 n=36 v=95
111360 On ch=10 n=42 v=95
111480 Off ch=10 n=36 v=80
111480 Off ch=10 n=42 v=80
111600 On ch=10 n=42 v=95
111720 Off ch=10 n=42 v=80
111840 On ch=10 n=38 v=95
111840 On ch=10 n=42 v=95
111960 Off ch=10 n=38 v=80
111960 Off ch=10 n=42 v=80
112080 On ch=10 n=36 v=95
112080 On ch=10 n=42 v=95
112200 Off ch=10 n=36 v=80
112200 Off ch=10 n=42 v=80
112320 On ch=10 n=42 v=95
112440 Off ch=10 n=42 v=80
112560 On ch=10 n=36 v=95
112560 On ch=10 n=42 v=95
112680 Off ch=10 n=36 v=80
112680 Off ch=10 n=42 v=80
112800 On ch=10 n=38 v=95
112800 On ch=10 n=42 v=95
112920 Off ch=10 n=38 v=80
112920 Off ch=10 n=42 v=80
112920 On ch=10 n=36 v=95
112920 On ch=10 n=49 v=95
113040 Off ch=10 n=36 v=80
113040 Off ch=10 n=49 v=80
113280 On ch=10 n=36 v=95
113280 On ch=10 n=42 v=95
113400 Off ch=10 n=36 v=80
113400 Off ch=10 n=42 v=80
113520 On ch=10 n=36 v=95
113520 On ch=10 n=42 v=95
113640 Off ch=10 n=36 v=80
113640 Off ch=10 n=42 v=80
113760 On ch=10 n=38 v=95
113760 On ch=10 n=42 v=95
113880 Off ch=10 n=38 v=80
113880 Off ch=10 n=42 v=80
114000 On ch=10 n=42 v=95
114120 Off ch=10 n=42 v=80
114240 On ch=10 n=36 v=95
114240 On ch=10 n=42 v=95
114360 Off ch=10 n=36 v=80
114360 Off ch=10 n=42 v=80
114480 On ch=10 n=36 v=95
114480 On ch=10 n=42 v=95
114600 Off ch=10 n=36 v=80
114600 Off ch=10 n=42 v=80
114720 On ch=10 n=38 v=95
114720 On ch=10 n=42 v=95
114840 Off ch=10 n=38 v=80
114840 Off ch=10 n=42 v=80
114960 On ch=10 n=42 v=95
115080 Off ch=10 n=42 v=80
115200 On ch=10 n=36 v=95
115200 On ch=10 n=42 v=95
115320 Off ch=10 n=36 v=80
115320 Off ch=10 n=42 v=80
115440 On ch=10 n=36 v=95
115560 Off ch=10 n=36 v=80
115680 On ch=10 n=38 v=95
115680 On ch=10 n=42 v=95
115800 Off ch=10 n=38 v=80
115800 Off ch=10 n=42 v=80
115920 On ch=10 n=36 v=95
116040 Off ch=10 n=36 v=80
116160 On ch=10 n=42 v=95
116280 Off ch=10 n=42 v=80
116400 On ch=10 n=36 v=95
116400 On ch=10 n=42 v=95
116520 Off ch=10 n=36 v=80
116520 Off ch=10 n=42 v=80
116640 On ch=10 n=38 v=95
116760 Off ch=10 n=38 v=80
116880 On ch=10 n=36 v=95
117000 Off ch=10 n=36 v=80
117120 On ch=10 n=36 v=95
117120 On ch=10 n=42 v=95
117240 Off ch=10 n=36 v=80
117240 Off ch=10 n=42 v=80
117360 On ch=10 n=36 v=95
117360 On ch=10 n=42 v=95
117480 Off ch=10 n=36 v=80
117480 Off ch=10 n=42 v=80
117600 On ch=10 n=38 v=95
117600 On ch=10 n=42 v=95
117600 On ch=10 n=49 v=95
117720 Off ch=10 n=38 v=80
117720 Off ch=10 n=42 v=80
117720 Off ch=10 n=49 v=80
117840 On ch=10 n=42 v=95
117960 Off ch=10 n=42 v=80
118080 On ch=10 n=36 v=95
118080 On ch=10 n=42 v=95
118200 Off ch=10 n=36 v=80
118200 Off ch=10 n=42 v=80
118320 On ch=10 n=36 v=95
118320 On ch=10 n=42 v=95
118440 Off ch=10 n=36 v=80
118440 Off ch=10 n=42 v=80
118560 On ch=10 n=38 v=95
118560 On ch=10 n=42 v=95
118680 Off ch=10 n=38 v=80
118680 Off ch=10 n=42 v=80
118800 On ch=10 n=42 v=95
118920 Off ch=10 n=42 v=80
119040 On ch=10 n=36 v=95
119040 On ch=10 n=42 v=95
119160 Off ch=10 n=36 v=80
119160 Off ch=10 n=42 v=80
119280 On ch=10 n=42 v=95
119400 Off ch=10 n=42 v=80
119520 On ch=10 n=38 v=95
119520 On ch=10 n=42 v=95
119640 Off ch=10 n=38 v=80
119640 Off ch=10 n=42 v=80
119760 On ch=10 n=36 v=95
119760 On ch=10 n=42 v=95
119880 Off ch=10 n=36 v=80
119880 Off ch=10 n=42 v=80
120000 On ch=10 n=42 v=95
120120 Off ch=10 n=42 v=80
120240 On ch=10 n=36 v=95
120240 On ch=10 n=42 v=95
120360 Off ch=10 n=36 v=80
120360 Off ch=10 n=42 v=80
120480 On ch=10 n=38 v=95
120480 On ch=10 n=42 v=95
120600 Off ch=10 n=38 v=80
120600 Off ch=10 n=42 v=80
120600 On ch=10 n=36 v=95
120600 On ch=10 n=49 v=95
120720 Off ch=10 n=36 v=80
120720 Off ch=10 n=49 v=80
120960 On ch=10 n=36 v=95
120960 On ch=10 n=42 v=95
121080 Off ch=10 n=36 v=80
121080 Off ch=10 n=42 v=80
121200 On ch=10 n=36 v=95
121200 On ch=10 n=42 v=95
121320 Off ch=10 n=36 v=80
121320 Off ch=10 n=42 v=80
121440 On ch=10 n=38 v=95
121440 On ch=10 n=42 v=95
121560 Off ch=10 n=38 v=80
121560 Off ch=10 n=42 v=80
121680 On ch=10 n=42 v=95
121800 Off ch=10 n=42 v=80
121920 On ch=10 n=36 v=95
121920 On ch=10 n=42 v=95
122040 Off ch=10 n=36 v=80
122040 Off ch=10 n=42 v=80
122160 On ch=10 n=36 v=95
122160 On ch=10 n=42 v=95
122280 Off ch=10 n=36 v=80
122280 Off ch=10 n=42 v=80
122400 On ch=10 n=38 v=95
122400 On ch=10 n=42 v=95
122520 Off ch=10 n=38 v=80
122520 Off ch=10 n=42 v=80
122640 On ch=10 n=42 v=95
122760 Off ch=10 n=42 v=80
122880 On ch=10 n=36 v=95
122880 On ch=10 n=42 v=95
123000 Off ch=10 n=36 v=80
123000 Off ch=10 n=42 v=80
123120 On ch=10 n=36 v=95
123240 Off ch=10 n=36 v=80
123360 On ch=10 n=38 v=95
123360 On ch=10 n=42 v=95
123480 Off ch=10 n=38 v=80
123480 Off ch=10 n=42 v=80
123600 On ch=10 n=36 v=95
123720 Off ch=10 n=36 v=80
123840 On ch=10 n=42 v=95
123960 Off ch=10 n=42 v=80
124080 On ch=10 n=36 v=95
124080 On ch=10 n=42 v=95
124200 Off ch=10 n=36 v=80
124200 Off ch=10 n=42 v=80
124320 On ch=10 n=38 v=95
124440 Off ch=10 n=38 v=80
124560 On ch=10 n=36 v=95
124680 Off ch=10 n=36 v=80
124800 On ch=10 n=36 v=95
124800 On ch=10 n=42 v=95
124920 Off ch=10 n=36 v=80
124920 Off ch=10 n=42 v=80
125040 On ch=10 n=36 v=95
125040 On ch=10 n=42 v=95
125160 Off ch=10 n=36 v=80
125160 Off ch=10 n=42 v=80
125280 On ch=10 n=38 v=95
125280 On ch=10 n=42 v=95
125280 On ch=10 n=49 v=95
125400 Off ch=10 n=38 v=80
125400 Off ch=10 n=42 v=80
125400 Off ch=10 n=49 v=80
125520 On ch=10 n=42 v=95
125640 Off ch=10 n=42 v=80
125760 On ch=10 n=36 v=95
125760 On ch=10 n=42 v=95
125880 Off ch=10 n=36 v=80
125880 Off ch=10 n=42 v=80
126000 On ch=10 n=36 v=95
126000 On ch=10 n=42 v=95
126120 Off ch=10 n=36 v=80
126120 Off ch=10 n=42 v=80
126240 On ch=10 n=38 v=95
126240 On ch=10 n=42 v=95
126360 Off ch=10 n=38 v=80
126360 Off ch=10 n=42 v=80
126480 On ch=10 n=42 v=95
126600 Off ch=10 n=42 v=80
126720 On ch=10 n=36 v=95
126720 On ch=10 n=42 v=95
126840 Off ch=10 n=36 v=80
126840 Off ch=10 n=42 v=80
126960 On ch=10 n=42 v=95
127080 Off ch=10 n=42 v=80
127200 On ch=10 n=38 v=95
127200 On ch=10 n=42 v=95
127320 Off ch=10 n=38 v=80
127320 Off ch=10 n=42 v=80
127440 On ch=10 n=36 v=95
127440 On ch=10 n=42 v=95
127560 Off ch=10 n=36 v=80
127560 Off ch=10 n=42 v=80
127680 On ch=10 n=42 v=95
127800 Off ch=10 n=42 v=80
127920 On ch=10 n=36 v=95
127920 On ch=10 n=42 v=95
128040 Off ch=10 n=36 v=80
128040 Off ch=10 n=42 v=80
128160 On ch=10 n=38 v=95
128160 On ch=10 n=42 v=95
128280 Off ch=10 n=38 v=80
128280 Off ch=10 n=42 v=80
128280 On ch=10 n=36 v=95
128280 On ch=10 n=49 v=95
128400 Off ch=10 n=36 v=80
128400 Off ch=10 n=49 v=80
128640 On ch=10 n=36 v=95
128640 On ch=10 n=42 v=95
128760 Off ch=10 n=36 v=80
128760 Off ch=10 n=42 v=80
128880 On ch=10 n=36 v=95
128880 On ch=10 n=42 v=95
129000 Off ch=10 n=36 v=80
129000 Off ch=10 n=42 v=80
129120 On ch=10 n=38 v=95
129120 On ch=10 n=42 v=95
129240 Off ch=10 n=38 v=80
129240 Off ch=10 n=42 v=80
129360 On ch=10 n=42 v=95
129480 Off ch=10 n=42 v=80
129600 On ch=10 n=36 v=95
129600 On ch=10 n=42 v=95
129720 Off ch=10 n=36 v=80
129720 Off ch=10 n=42 v=80
129840 On ch=10 n=36 v=95
129840 On ch=10 n=42 v=95
129960 Off ch=10 n=36 v=80
129960 Off ch=10 n=42 v=80
130080 On ch=10 n=38 v=95
130080 On ch=10 n=42 v=95
130200 Off ch=10 n=38 v=80
130200 Off ch=10 n=42 v=80
130320 On ch=10 n=42 v=95
130440 Off ch=10 n=42 v=80
130560 On ch=10 n=36 v=95
130560 On ch=10 n=42 v=95
130680 Off ch=10 n=36 v=80
130680 Off ch=10 n=42 v=80
130800 On ch=10 n=36 v=95
130920 Off ch=10 n=36 v=80
131040 On ch=10 n=38 v=95
131040 On ch=10 n=42 v=95
131160 Off ch=10 n=38 v=80
131160 Off ch=10 n=42 v=80
131280 On ch=10 n=36 v=95
131400 Off ch=10 n=36 v=80
131520 On ch=10 n=42 v=95
131640 Off ch=10 n=42 v=80
131760 On ch=10 n=36 v=95
131760 On ch=10 n=42 v=95
131880 Off ch=10 n=36 v=80
131880 Off ch=10 n=42 v=80
132000 On ch=10 n=38 v=95
132120 Off ch=10 n=38 v=80
132240 On ch=10 n=36 v=95
132360 Off ch=10 n=36 v=80
132480 On ch=10 n=36 v=95
132480 On ch=10 n=42 v=95
132600 Off ch=10 n=36 v=80
132600 Off ch=10 n=42 v=80
132720 On ch=10 n=36 v=95
132720 On ch=10 n=42 v=95
132840 Off ch=10 n=36 v=80
132840 Off ch=10 n=42 v=80
132960 On ch=10 n=38 v=95
132960 On ch=10 n=42 v=95
132960 On ch=10 n=49 v=95
133080 Off ch=10 n=38 v=80
133080 Off ch=10 n=42 v=80
133080 Off ch=10 n=49 v=80
133200 On ch=10 n=42 v=95
133320 Off ch=10 n=42 v=80
133440 On ch=10 n=36 v=95
133440 On ch=10 n=42 v=95
133560 Off ch=10 n=36 v=80
133560 Off ch=10 n=42 v=80
133680 On ch=10 n=36 v=95
133680 On ch=10 n=42 v=95
133800 Off ch=10 n=36 v=80
133800 Off ch=10 n=42 v=80
133920 On ch=10 n=38 v=95
133920 On ch=10 n=42 v=95
134040 Off ch=10 n=38 v=80
134040 Off ch=10 n=42 v=80
134160 On ch=10 n=42 v=95
134280 Off ch=10 n=42 v=80
134400 On ch=10 n=36 v=95
134400 On ch=10 n=42 v=95
134520 Off ch=10 n=36 v=80
134520 Off ch=10 n=42 v=80
134640 On ch=10 n=42 v=95
134760 Off ch=10 n=42 v=80
134880 On ch=10 n=38 v=95
134880 On ch=10 n=42 v=95
135000 Off ch=10 n=38 v=80
135000 Off ch=10 n=42 v=80
135120 On ch=10 n=36 v=95
135120 On ch=10 n=42 v=95
135240 Off ch=10 n=36 v=80
135240 Off ch=10 n=42 v=80
135360 On ch=10 n=42 v=95
135480 Off ch=10 n=42 v=80
135600 On ch=10 n=36 v=95
135600 On ch=10 n=42 v=95
135720 Off ch=10 n=36 v=80
135720 Off ch=10 n=42 v=80
135840 On ch=10 n=38 v=95
135840 On ch=10 n=42 v=95
135960 Off ch=10 n=38 v=80
135960 Off ch=10 n=42 v=80
135960 On ch=10 n=36 v=95
135960 On ch=10 n=49 v=95
136080 Off ch=10 n=36 v=80
136080 Off ch=10 n=49 v=80
136320 On ch=10 n=36 v=95
136320 On ch=10 n=42 v=95
136440 Off ch=10 n=36 v=80
136440 Off ch=10 n=42 v=80
136560 On ch=10 n=36 v=95
136560 On ch=10 n=42 v=95
136680 Off ch=10 n=36 v=80
136680 Off ch=10 n=42 v=80
136800 On ch=10 n=38 v=95
136800 On ch=10 n=42 v=95
136920 Off ch=10 n=38 v=80
136920 Off ch=10 n=42 v=80
137040 On ch=10 n=42 v=95
137160 Off ch=10 n=42 v=80
137280 On ch=10 n=36 v=95
137280 On ch=10 n=42 v=95
137400 Off ch=10 n=36 v=80
137400 Off ch=10 n=42 v=80
137520 On ch=10 n=36 v=95
137520 On ch=10 n=42 v=95
137640 Off ch=10 n=36 v=80
137640 Off ch=10 n=42 v=80
137760 On ch=10 n=38 v=95
137760 On ch=10 n=42 v=95
137880 Off ch=10 n=38 v=80
137880 Off ch=10 n=42 v=80
138000 On ch=10 n=42 v=95
138120 Off ch=10 n=42 v=80
138240 On ch=10 n=36 v=95
138240 On ch=10 n=42 v=95
138360 Off ch=10 n=36 v=80
138360 Off ch=10 n=42 v=80
138480 On ch=10 n=36 v=95
138600 Off ch=10 n=36 v=80
138720 On ch=10 n=38 v=95
138720 On ch=10 n=42 v=95
138840 Off ch=10 n=38 v=80
138840 Off ch=10 n=42 v=80
138960 On ch=10 n=36 v=95
139080 Off ch=10 n=36 v=80
139200 On ch=10 n=42 v=95
139320 Off ch=10 n=42 v=80
139440 On ch=10 n=36 v=95
139440 On ch=10 n=42 v=95
139560 Off ch=10 n=36 v=80
139560 Off ch=10 n=42 v=80
139680 On ch=10 n=38 v=95
139800 Off ch=10 n=38 v=80
139920 On ch=10 n=36 v=95
140040 Off ch=10 n=36 v=80
140160 On ch=10 n=36 v=95
140160 On ch=10 n=42 v=95
140160 On ch=10 n=49 v=95
140280 Off ch=10 n=36 v=80
140280 Off ch=10 n=42 v=80
140280 Off ch=10 n=49 v=80
140400 On ch=10 n=36 v=95
140400 On ch=10 n=42 v=95
140520 Off ch=10 n=36 v=80
140520 Off ch=10 n=42 v=80
140640 On ch=10 n=38 v=95
140640 On ch=10 n=42 v=95
140760 Off ch=10 n=38 v=80
140760 Off ch=10 n=42 v=80
140880 On ch=10 n=42 v=95
141000 Off ch=10 n=42 v=80
141120 On ch=10 n=36 v=95
141120 On ch=10 n=42 v=95
141240 Off ch=10 n=36 v=80
141240 Off ch=10 n=42 v=80
141360 On ch=10 n=36 v=95
141480 Off ch=10 n=36 v=80
141600 On ch=10 n=38 v=95
141720 Off ch=10 n=38 v=80
141840 On ch=10 n=42 v=95
141960 Off ch=10 n=42 v=80
142080 On ch=10 n=36 v=95
142080 On ch=10 n=42 v=95
142200 Off ch=10 n=36 v=80
142200 Off ch=10 n=42 v=80
142320 On ch=10 n=36 v=95
142320 On ch=10 n=42 v=95
142440 Off ch=10 n=36 v=80
142440 Off ch=10 n=42 v=80
142560 On ch=10 n=38 v=95
142680 Off ch=10 n=38 v=80
142800 On ch=10 n=42 v=95
142920 Off ch=10 n=42 v=80
143040 On ch=10 n=36 v=95
143040 On ch=10 n=42 v=95
143160 Off ch=10 n=36 v=80
143160 Off ch=10 n=42 v=80
143280 On ch=10 n=36 v=95
143280 On ch=10 n=42 v=95
143400 Off ch=10 n=36 v=80
143400 Off ch=10 n=42 v=80
143520 On ch=10 n=38 v=95
143520 On ch=10 n=42 v=95
143640 Off ch=10 n=38 v=80
143640 Off ch=10 n=42 v=80
143760 On ch=10 n=46 v=95
143880 Off ch=10 n=46 v=80
144000 On ch=10 n=36 v=95
144000 On ch=10 n=42 v=95
144120 Off ch=10 n=36 v=80
144120 Off ch=10 n=42 v=80
144240 On ch=10 n=36 v=95
144240 On ch=10 n=42 v=95
144360 Off ch=10 n=36 v=80
144360 Off ch=10 n=42 v=80
144480 On ch=10 n=38 v=95
144480 On ch=10 n=42 v=95
144600 Off ch=10 n=38 v=80
144600 Off ch=10 n=42 v=80
144720 On ch=10 n=42 v=95
144840 Off ch=10 n=42 v=80
144960 On ch=10 n=36 v=95
144960 On ch=10 n=42 v=95
145080 Off ch=10 n=36 v=80
145080 Off ch=10 n=42 v=80
145200 On ch=10 n=36 v=95
145320 Off ch=10 n=36 v=80
145440 On ch=10 n=38 v=95
145560 Off ch=10 n=38 v=80
145680 On ch=10 n=42 v=95
145800 Off ch=10 n=42 v=80
145920 On ch=10 n=36 v=95
145920 On ch=10 n=42 v=95
146040 Off ch=10 n=36 v=80
146040 Off ch=10 n=42 v=80
146160 On ch=10 n=36 v=95
146160 On ch=10 n=42 v=95
146280 Off ch=10 n=36 v=80
146280 Off ch=10 n=42 v=80
146400 On ch=10 n=38 v=95
146400 On ch=10 n=42 v=95
146520 Off ch=10 n=38 v=80
146520 Off ch=10 n=42 v=80
146640 On ch=10 n=42 v=95
146760 Off ch=10 n=42 v=80
146880 On ch=10 n=36 v=95
147000 Off ch=10 n=36 v=80
147120 On ch=10 n=36 v=95
147120 On ch=10 n=42 v=95
147240 Off ch=10 n=36 v=80
147240 Off ch=10 n=42 v=80
147360 On ch=10 n=38 v=95
147360 On ch=10 n=42 v=95
147480 Off ch=10 n=38 v=80
147480 Off ch=10 n=42 v=80
147600 On ch=10 n=46 v=95
147720 Off ch=10 n=46 v=80
147840 On ch=10 n=36 v=95
147840 On ch=10 n=42 v=95
147960 Off ch=10 n=36 v=80
147960 Off ch=10 n=42 v=80
148080 On ch=10 n=36 v=95
148080 On ch=10 n=42 v=95
148200 Off ch=10 n=36 v=80
148200 Off ch=10 n=42 v=80
148320 On ch=10 n=38 v=95
148320 On ch=10 n=42 v=95
148440 Off ch=10 n=38 v=80
148440 Off ch=10 n=42 v=80
148560 On ch=10 n=42 v=95
148680 Off ch=10 n=42 v=80
148800 On ch=10 n=36 v=95
148920 Off ch=10 n=36 v=80
149040 On ch=10 n=36 v=95
149160 Off ch=10 n=36 v=80
149280 On ch=10 n=38 v=95
149400 Off ch=10 n=38 v=80
149520 On ch=10 n=42 v=95
149640 Off ch=10 n=42 v=80
149760 On ch=10 n=36 v=95
149760 On ch=10 n=42 v=95
149880 Off ch=10 n=36 v=80
149880 Off ch=10 n=42 v=80
150000 On ch=10 n=36 v=95
150000 On ch=10 n=42 v=95
150120 Off ch=10 n=36 v=80
150120 Off ch=10 n=42 v=80
150240 On ch=10 n=38 v=95
150240 On ch=10 n=42 v=95
150360 Off ch=10 n=38 v=80
150360 Off ch=10 n=42 v=80
150480 On ch=10 n=42 v=95
150600 Off ch=10 n=42 v=80
150720 On ch=10 n=36 v=95
150720 On ch=10 n=42 v=95
150840 Off ch=10 n=36 v=80
150840 Off ch=10 n=42 v=80
150960 On ch=10 n=36 v=95
150960 On ch=10 n=42 v=95
151080 Off ch=10 n=36 v=80
151080 Off ch=10 n=42 v=80
151200 On ch=10 n=38 v=95
151200 On ch=10 n=42 v=95
151320 Off ch=10 n=38 v=80
151320 Off ch=10 n=42 v=80
151440 On ch=10 n=42 v=95
151560 Off ch=10 n=42 v=80
151680 On ch=10 n=36 v=95
151680 On ch=10 n=42 v=95
151680 On ch=10 n=49 v=95
151800 Off ch=10 n=36 v=80
151800 Off ch=10 n=42 v=80
151800 Off ch=10 n=49 v=80
151920 On ch=10 n=36 v=95
152040 Off ch=10 n=36 v=80
152160 On ch=10 n=38 v=95
152160 On ch=10 n=42 v=95
152280 Off ch=10 n=38 v=80
152280 Off ch=10 n=42 v=80
152400 On ch=10 n=42 v=95
152520 Off ch=10 n=42 v=80
152640 On ch=10 n=36 v=95
152760 Off ch=10 n=36 v=80
152880 On ch=10 n=36 v=95
153000 Off ch=10 n=36 v=80
153120 On ch=10 n=38 v=95
153120 On ch=10 n=42 v=95
153240 Off ch=10 n=38 v=80
153240 Off ch=10 n=42 v=80
153360 On ch=10 n=42 v=95
153480 Off ch=10 n=42 v=80
153600 On ch=10 n=36 v=95
153600 On ch=10 n=42 v=95
153720 Off ch=10 n=36 v=80
153720 Off ch=10 n=42 v=80
153840 On ch=10 n=36 v=95
153840 On ch=10 n=42 v=95
153960 Off ch=10 n=36 v=80
153960 Off ch=10 n=42 v=80
154080 On ch=10 n=38 v=95
154080 On ch=10 n=42 v=95
154200 Off ch=10 n=38 v=80
154200 Off ch=10 n=42 v=80
154320 On ch=10 n=42 v=95
154440 Off ch=10 n=42 v=80
154560 On ch=10 n=36 v=95
154560 On ch=10 n=42 v=95
154680 Off ch=10 n=36 v=80
154680 Off ch=10 n=42 v=80
154800 On ch=10 n=36 v=95
154920 Off ch=10 n=36 v=80
155040 On ch=10 n=38 v=95
155040 On ch=10 n=42 v=95
155160 Off ch=10 n=38 v=80
155160 Off ch=10 n=42 v=80
155280 On ch=10 n=46 v=95
155400 Off ch=10 n=46 v=80
155520 On ch=10 n=36 v=95
155520 On ch=10 n=42 v=95
155640 Off ch=10 n=36 v=80
155640 Off ch=10 n=42 v=80
155760 On ch=10 n=36 v=95
155880 Off ch=10 n=36 v=80
156000 On ch=10 n=38 v=95
156000 On ch=10 n=42 v=95
156120 Off ch=10 n=38 v=80
156120 Off ch=10 n=42 v=80
156240 On ch=10 n=42 v=95
156360 Off ch=10 n=42 v=80
156480 On ch=10 n=36 v=95
156480 On ch=10 n=42 v=95
156600 Off ch=10 n=36 v=80
156600 Off ch=10 n=42 v=80
156720 On ch=10 n=36 v=95
156840 Off ch=10 n=36 v=80
156960 On ch=10 n=38 v=95
156960 On ch=10 n=42 v=95
157080 Off ch=10 n=38 v=80
157080 Off ch=10 n=42 v=80
157200 On ch=10 n=42 v=95
157320 Off ch=10 n=42 v=80
157440 On ch=10 n=36 v=95
157440 On ch=10 n=42 v=95
157560 Off ch=10 n=36 v=80
157560 Off ch=10 n=42 v=80
157680 On ch=10 n=36 v=95
157680 On ch=10 n=42 v=95
157800 Off ch=10 n=36 v=80
157800 Off ch=10 n=42 v=80
157920 On ch=10 n=38 v=95
157920 On ch=10 n=42 v=95
158040 Off ch=10 n=38 v=80
158040 Off ch=10 n=42 v=80
158160 On ch=10 n=42 v=95
158280 Off ch=10 n=42 v=80
158400 On ch=10 n=36 v=95
158400 On ch=10 n=42 v=95
158520 Off ch=10 n=36 v=80
158520 Off ch=10 n=42 v=80
158640 On ch=10 n=36 v=95
158640 On ch=10 n=42 v=95
158760 Off ch=10 n=36 v=80
158760 Off ch=10 n=42 v=80
158880 On ch=10 n=38 v=95
158880 On ch=10 n=42 v=95
159000 Off ch=10 n=38 v=80
159000 Off ch=10 n=42 v=80
159120 On ch=10 n=42 v=95
159240 Off ch=10 n=42 v=80
159360 On ch=10 n=36 v=95
159360 On ch=10 n=42 v=95
159480 Off ch=10 n=36 v=80
159480 Off ch=10 n=42 v=80
159600 On ch=10 n=36 v=95
159600 On ch=10 n=42 v=95
159720 Off ch=10 n=36 v=80
159720 Off ch=10 n=42 v=80
159840 On ch=10 n=38 v=95
159960 Off ch=10 n=38 v=80
160080 On ch=10 n=42 v=95
160200 Off ch=10 n=42 v=80
160320 On ch=10 n=36 v=95
160320 On ch=10 n=42 v=95
160440 Off ch=10 n=36 v=80
160440 Off ch=10 n=42 v=80
160560 On ch=10 n=36 v=95
160560 On ch=10 n=42 v=95
160680 Off ch=10 n=36 v=80
160680 Off ch=10 n=42 v=80
160800 On ch=10 n=38 v=95
160800 On ch=10 n=42 v=95
160920 Off ch=10 n=38 v=80
160920 Off ch=10 n=42 v=80
161040 On ch=10 n=42 v=95
161160 Off ch=10 n=42 v=80
161280 On ch=10 n=36 v=95
161280 On ch=10 n=42 v=95
161400 Off ch=10 n=36 v=80
161400 Off ch=10 n=42 v=80
161520 On ch=10 n=36 v=95
161520 On ch=10 n=42 v=95
161640 Off ch=10 n=36 v=80
161640 Off ch=10 n=42 v=80
161760 On ch=10 n=38 v=95
161760 On ch=10 n=42 v=95
161880 Off ch=10 n=38 v=80
161880 Off ch=10 n=42 v=80
162000 On ch=10 n=42 v=95
162120 Off ch=10 n=42 v=80
162240 On ch=10 n=38 v=95
162240 On ch=10 n=42 v=95
162360 Off ch=10 n=38 v=80
162360 Off ch=10 n=42 v=80
162480 On ch=10 n=38 v=95
162600 Off ch=10 n=38 v=80
162720 On ch=10 n=38 v=95
162720 On ch=10 n=42 v=95
162840 Off ch=10 n=38 v=80
162840 Off ch=10 n=42 v=80
162960 On ch=10 n=38 v=95
162960 On ch=10 n=43 v=95
163080 Off ch=10 n=38 v=80
163080 Off ch=10 n=43 v=80
163200 On ch=10 n=36 v=95
163200 On ch=10 n=42 v=95
163320 Off ch=10 n=36 v=80
163320 Off ch=10 n=42 v=80
163440 On ch=10 n=36 v=95
163440 On ch=10 n=42 v=95
163560 Off ch=10 n=36 v=80
163560 Off ch=10 n=42 v=80
163680 On ch=10 n=38 v=95
163680 On ch=10 n=49 v=95
163800 Off ch=10 n=38 v=80
163800 Off ch=10 n=49 v=80
163920 On ch=10 n=42 v=95
164040 Off ch=10 n=42 v=80
164160 On ch=10 n=36 v=95
164280 Off ch=10 n=36 v=80
164400 On ch=10 n=36 v=95
164400 On ch=10 n=42 v=95
164520 Off ch=10 n=36 v=80
164520 Off ch=10 n=42 v=80
164640 On ch=10 n=38 v=95
164760 Off ch=10 n=38 v=80
164880 On ch=10 n=42 v=95
165000 Off ch=10 n=42 v=80
165120 On ch=10 n=36 v=95
165120 On ch=10 n=42 v=95
165240 Off ch=10 n=36 v=80
165240 Off ch=10 n=42 v=80
165360 On ch=10 n=42 v=95
165480 Off ch=10 n=42 v=80
165600 On ch=10 n=38 v=95
165600 On ch=10 n=42 v=95
165720 Off ch=10 n=38 v=80
165720 Off ch=10 n=42 v=80
165840 On ch=10 n=42 v=95
165960 Off ch=10 n=42 v=80
166080 On ch=10 n=42 v=95
166200 Off ch=10 n=42 v=80
166320 On ch=10 n=36 v=95
166320 On ch=10 n=42 v=95
166440 Off ch=10 n=36 v=80
166440 Off ch=10 n=42 v=80
166560 On ch=10 n=38 v=95
166560 On ch=10 n=42 v=95
166680 Off ch=10 n=38 v=80
166680 Off ch=10 n=42 v=80
166680 On ch=10 n=36 v=95
166680 On ch=10 n=49 v=95
166800 Off ch=10 n=36 v=80
166800 Off ch=10 n=49 v=80
167040 On ch=10 n=36 v=95
167040 On ch=10 n=42 v=95
167160 Off ch=10 n=36 v=80
167160 Off ch=10 n=42 v=80
167280 On ch=10 n=36 v=95
167280 On ch=10 n=42 v=95
167400 Off ch=10 n=36 v=80
167400 Off ch=10 n=42 v=80
167520 On ch=10 n=38 v=95
167520 On ch=10 n=42 v=95
167640 Off ch=10 n=38 v=80
167640 Off ch=10 n=42 v=80
167760 On ch=10 n=42 v=95
167880 Off ch=10 n=42 v=80
168000 On ch=10 n=36 v=95
168000 On ch=10 n=42 v=95
168120 Off ch=10 n=36 v=80
168120 Off ch=10 n=42 v=80
168240 On ch=10 n=36 v=95
168360 Off ch=10 n=36 v=80
168480 On ch=10 n=38 v=95
168480 On ch=10 n=42 v=95
168600 Off ch=10 n=38 v=80
168600 Off ch=10 n=42 v=80
168720 On ch=10 n=42 v=95
168840 Off ch=10 n=42 v=80
168960 On ch=10 n=36 v=95
168960 On ch=10 n=42 v=95
169080 Off ch=10 n=36 v=80
169080 Off ch=10 n=42 v=80
169200 On ch=10 n=36 v=95
169320 Off ch=10 n=36 v=80
169440 On ch=10 n=38 v=95
169440 On ch=10 n=42 v=95
169560 Off ch=10 n=38 v=80
169560 Off ch=10 n=42 v=80
169680 On ch=10 n=36 v=95
169680 On ch=10 n=42 v=95
169800 Off ch=10 n=36 v=80
169800 Off ch=10 n=42 v=80
169920 On ch=10 n=42 v=95
170040 Off ch=10 n=42 v=80
170160 On ch=10 n=36 v=95
170280 Off ch=10 n=36 v=80
170400 On ch=10 n=38 v=95
170520 Off ch=10 n=38 v=80
170640 On ch=10 n=36 v=95
170640 On ch=10 n=42 v=95
170760 Off ch=10 n=36 v=80
170760 Off ch=10 n=42 v=80
170880 On ch=10 n=36 v=95
170880 On ch=10 n=42 v=95
171000 Off ch=10 n=36 v=80
171000 Off ch=10 n=42 v=80
171120 On ch=10 n=36 v=95
171120 On ch=10 n=42 v=95
171240 Off ch=10 n=36 v=80
171240 Off ch=10 n=42 v=80
171360 On ch=10 n=38 v=95
171360 On ch=10 n=42 v=95
171480 Off ch=10 n=38 v=80
171480 Off ch=10 n=42 v=80
171600 On ch=10 n=42 v=95
171720 Off ch=10 n=42 v=80
171840 On ch=10 n=36 v=95
171840 On ch=10 n=42 v=95
171960 Off ch=10 n=36 v=80
171960 Off ch=10 n=42 v=80
172080 On ch=10 n=36 v=95
172080 On ch=10 n=42 v=95
172200 Off ch=10 n=36 v=80
172200 Off ch=10 n=42 v=80
172320 On ch=10 n=38 v=95
172320 On ch=10 n=42 v=95
172440 Off ch=10 n=38 v=80
172440 Off ch=10 n=42 v=80
172560 On ch=10 n=42 v=95
172680 Off ch=10 n=42 v=80
172800 On ch=10 n=36 v=95
172800 On ch=10 n=42 v=95
172920 Off ch=10 n=36 v=80
172920 Off ch=10 n=42 v=80
173040 On ch=10 n=36 v=95
173040 On ch=10 n=42 v=95
173160 Off ch=10 n=36 v=80
173160 Off ch=10 n=42 v=80
173280 On ch=10 n=42 v=95
173400 Off ch=10 n=42 v=80
173520 On ch=10 n=36 v=95
173520 On ch=10 n=42 v=95
173640 Off ch=10 n=36 v=80
173640 Off ch=10 n=42 v=80
173760 On ch=10 n=42 v=95
173880 Off ch=10 n=42 v=80
174000 On ch=10 n=36 v=95
174000 On ch=10 n=42 v=95
174120 Off ch=10 n=36 v=80
174120 Off ch=10 n=42 v=80
174240 On ch=10 n=38 v=95
174240 On ch=10 n=42 v=95
174360 Off ch=10 n=38 v=80
174360 Off ch=10 n=42 v=80
174360 On ch=10 n=36 v=95
174360 On ch=10 n=49 v=95
174480 Off ch=10 n=36 v=80
174480 Off ch=10 n=49 v=80
174720 On ch=10 n=36 v=95
174720 On ch=10 n=42 v=95
174840 Off ch=10 n=36 v=80
174840 Off ch=10 n=42 v=80
174960 On ch=10 n=36 v=95
175080 Off ch=10 n=36 v=80
175200 On ch=10 n=38 v=95
175200 On ch=10 n=42 v=95
175320 Off ch=10 n=38 v=80
175320 Off ch=10 n=42 v=80
175440 On ch=10 n=42 v=95
175560 Off ch=10 n=42 v=80
175680 On ch=10 n=36 v=95
175680 On ch=10 n=42 v=95
175800 Off ch=10 n=36 v=80
175800 Off ch=10 n=42 v=80
175920 On ch=10 n=36 v=95
176040 Off ch=10 n=36 v=80
176160 On ch=10 n=38 v=95
176280 Off ch=10 n=38 v=80
176400 On ch=10 n=42 v=95
176520 Off ch=10 n=42 v=80
176640 On ch=10 n=36 v=95
176640 On ch=10 n=42 v=95
176760 Off ch=10 n=36 v=80
176760 Off ch=10 n=42 v=80
176880 On ch=10 n=36 v=95
176880 On ch=10 n=42 v=95
177000 Off ch=10 n=36 v=80
177000 Off ch=10 n=42 v=80
177120 On ch=10 n=38 v=95
177120 On ch=10 n=42 v=95
177240 Off ch=10 n=38 v=80
177240 Off ch=10 n=42 v=80
177360 On ch=10 n=36 v=95
177360 On ch=10 n=42 v=95
177480 Off ch=10 n=36 v=80
177480 Off ch=10 n=42 v=80
177600 On ch=10 n=36 v=95
177600 On ch=10 n=49 v=95
177720 Off ch=10 n=36 v=80
177720 Off ch=10 n=49 v=80
178080 On ch=10 n=36 v=95
178080 On ch=10 n=49 v=95
178200 Off ch=10 n=36 v=80
178200 Off ch=10 n=49 v=80
178560 On ch=10 n=36 v=95
178560 On ch=10 n=42 v=95
178560 On ch=10 n=49 v=95
178680 Off ch=10 n=36 v=80
178680 Off ch=10 n=42 v=80
178680 Off ch=10 n=49 v=80
178800 On ch=10 n=36 v=95
178920 Off ch=10 n=36 v=80
179040 On ch=10 n=38 v=95
179040 On ch=10 n=42 v=95
179160 Off ch=10 n=38 v=80
179160 Off ch=10 n=42 v=80
179280 On ch=10 n=42 v=95
179400 Off ch=10 n=42 v=80
179520 On ch=10 n=36 v=95
179640 Off ch=10 n=36 v=80
179760 On ch=10 n=36 v=95
179880 Off ch=10 n=36 v=80
180000 On ch=10 n=38 v=95
180000 On ch=10 n=42 v=95
180120 Off ch=10 n=38 v=80
180120 Off ch=10 n=42 v=80
180240 On ch=10 n=42 v=95
180360 Off ch=10 n=42 v=80
180480 On ch=10 n=36 v=95
180480 On ch=10 n=42 v=95
180600 Off ch=10 n=36 v=80
180600 Off ch=10 n=42 v=80
180720 On ch=10 n=36 v=95
180720 On ch=10 n=42 v=95
180840 Off ch=10 n=36 v=80
180840 Off ch=10 n=42 v=80
180960 On ch=10 n=38 v=95
180960 On ch=10 n=42 v=95
181080 Off ch=10 n=38 v=80
181080 Off ch=10 n=42 v=80
181200 On ch=10 n=42 v=95
181320 Off ch=10 n=42 v=80
181440 On ch=10 n=36 v=95
181440 On ch=10 n=42 v=95
181560 Off ch=10 n=36 v=80
181560 Off ch=10 n=42 v=80
181680 On ch=10 n=36 v=95
181800 Off ch=10 n=36 v=80
181920 On ch=10 n=38 v=95
182040 Off ch=10 n=38 v=80
182160 On ch=10 n=46 v=95
182280 Off ch=10 n=46 v=80
182400 On ch=10 n=36 v=95
182400 On ch=10 n=42 v=95
182520 Off ch=10 n=36 v=80
182520 Off ch=10 n=42 v=80
182640 On ch=10 n=36 v=95
182640 On ch=10 n=42 v=95
182760 Off ch=10 n=36 v=80
182760 Off ch=10 n=42 v=80
182880 On ch=10 n=38 v=95
183000 Off ch=10 n=38 v=80
183120 On ch=10 n=42 v=95
183240 Off ch=10 n=42 v=80
183360 On ch=10 n=36 v=95
183480 Off ch=10 n=36 v=80
183600 On ch=10 n=36 v=95
183600 On ch=10 n=42 v=95
183720 Off ch=10 n=36 v=80
183720 Off ch=10 n=42 v=80
183840 On ch=10 n=38 v=95
183840 On ch=10 n=42 v=95
183960 Off ch=10 n=38 v=80
183960 Off ch=10 n=42 v=80
184080 On ch=10 n=42 v=95
184200 Off ch=10 n=42 v=80
184320 On ch=10 n=36 v=95
184320 On ch=10 n=42 v=95
184440 Off ch=10 n=36 v=80
184440 Off ch=10 n=42 v=80
184560 On ch=10 n=36 v=95
184680 Off ch=10 n=36 v=80
184800 On ch=10 n=38 v=95
184800 On ch=10 n=42 v=95
184920 Off ch=10 n=38 v=80
184920 Off ch=10 n=42 v=80
185040 On ch=10 n=42 v=95
185160 Off ch=10 n=42 v=80
185280 On ch=10 n=36 v=95
185280 On ch=10 n=42 v=95
185400 Off ch=10 n=36 v=80
185400 Off ch=10 n=42 v=80
185520 On ch=10 n=36 v=95
185520 On ch=10 n=42 v=95
185640 Off ch=10 n=36 v=80
185640 Off ch=10 n=42 v=80
185760 On ch=10 n=38 v=95
185760 On ch=10 n=42 v=95
185880 Off ch=10 n=38 v=80
185880 Off ch=10 n=42 v=80
186000 On ch=10 n=46 v=95
186120 Off ch=10 n=46 v=80
186240 On ch=10 n=36 v=95
186240 On ch=10 n=42 v=95
186360 Off ch=10 n=36 v=80
186360 Off ch=10 n=42 v=80
186480 On ch=10 n=36 v=95
186480 On ch=10 n=42 v=95
186600 Off ch=10 n=36 v=80
186600 Off ch=10 n=42 v=80
186720 On ch=10 n=38 v=95
186840 Off ch=10 n=38 v=80
186960 On ch=10 n=42 v=95
187080 Off ch=10 n=42 v=80
187200 On ch=10 n=36 v=95
187200 On ch=10 n=42 v=95
187320 Off ch=10 n=36 v=80
187320 Off ch=10 n=42 v=80
187440 On ch=10 n=36 v=95
187440 On ch=10 n=42 v=95
187560 Off ch=10 n=36 v=80
187560 Off ch=10 n=42 v=80
187680 On ch=10 n=38 v=95
187680 On ch=10 n=42 v=95
187800 Off ch=10 n=38 v=80
187800 Off ch=10 n=42 v=80
187920 On ch=10 n=42 v=95
188040 Off ch=10 n=42 v=80
188160 On ch=10 n=36 v=95
188160 On ch=10 n=42 v=95
188280 Off ch=10 n=36 v=80
188280 Off ch=10 n=42 v=80
188400 On ch=10 n=36 v=95
188400 On ch=10 n=42 v=95
188520 Off ch=10 n=36 v=80
188520 Off ch=10 n=42 v=80
188640 On ch=10 n=38 v=95
188760 Off ch=10 n=38 v=80
188880 On ch=10 n=42 v=95
189000 Off ch=10 n=42 v=80
189120 On ch=10 n=36 v=95
189120 On ch=10 n=42 v=95
189240 Off ch=10 n=36 v=80
189240 Off ch=10 n=42 v=80
189360 On ch=10 n=36 v=95
189360 On ch=10 n=42 v=95
189480 Off ch=10 n=36 v=80
189480 Off ch=10 n=42 v=80
189600 On ch=10 n=38 v=95
189600 On ch=10 n=42 v=95
189720 Off ch=10 n=38 v=80
189720 Off ch=10 n=42 v=80
189840 On ch=10 n=46 v=95
189960 Off ch=10 n=46 v=80
190080 On ch=10 n=36 v=95
190080 On ch=10 n=42 v=95
190200 Off ch=10 n=36 v=80
190200 Off ch=10 n=42 v=80
190320 On ch=10 n=36 v=95
190320 On ch=10 n=42 v=95
190440 Off ch=10 n=36 v=80
190440 Off ch=10 n=42 v=80
190560 On ch=10 n=38 v=95
190560 On ch=10 n=42 v=95
190680 Off ch=10 n=38 v=80
190680 Off ch=10 n=42 v=80
190800 On ch=10 n=42 v=95
190920 Off ch=10 n=42 v=80
191040 On ch=10 n=36 v=95
191040 On ch=10 n=42 v=95
191160 Off ch=10 n=36 v=80
191160 Off ch=10 n=42 v=80
191280 On ch=10 n=36 v=95
191280 On ch=10 n=42 v=95
191400 Off ch=10 n=36 v=80
191400 Off ch=10 n=42 v=80
191520 On ch=10 n=38 v=95
191520 On ch=10 n=42 v=95
191640 Off ch=10 n=38 v=80
191640 Off ch=10 n=42 v=80
191760 On ch=10 n=42 v=95
191880 Off ch=10 n=42 v=80
192000 On ch=10 n=36 v=95
192000 On ch=10 n=42 v=95
192120 Off ch=10 n=36 v=80
192120 Off ch=10 n=42 v=80
192240 On ch=10 n=36 v=95
192360 Off ch=10 n=36 v=80
192480 On ch=10 n=38 v=95
192480 On ch=10 n=42 v=95
192600 Off ch=10 n=38 v=80
192600 Off ch=10 n=42 v=80
192720 On ch=10 n=42 v=95
192840 Off ch=10 n=42 v=80
192960 On ch=10 n=36 v=95
192960 On ch=10 n=42 v=95
193080 Off ch=10 n=36 v=80
193080 Off ch=10 n=42 v=80
193200 On ch=10 n=36 v=95
193200 On ch=10 n=42 v=95
193320 Off ch=10 n=36 v=80
193320 Off ch=10 n=42 v=80
193440 On ch=10 n=38 v=95
193440 On ch=10 n=42 v=95
193560 Off ch=10 n=38 v=80
193560 Off ch=10 n=42 v=80
193680 On ch=10 n=46 v=95
193800 Off ch=10 n=46 v=80
193920 On ch=10 n=36 v=95
193920 On ch=10 n=42 v=95
193920 On ch=10 n=49 v=95
194040 Off ch=10 n=36 v=80
194040 Off ch=10 n=42 v=80
194040 Off ch=10 n=49 v=80
194160 On ch=10 n=36 v=95
194160 On ch=10 n=42 v=95
194280 Off ch=10 n=36 v=80
194280 Off ch=10 n=42 v=80
194400 On ch=10 n=38 v=95
194400 On ch=10 n=42 v=95
194520 Off ch=10 n=38 v=80
194520 Off ch=10 n=42 v=80
194640 On ch=10 n=42 v=95
194760 Off ch=10 n=42 v=80
194880 On ch=10 n=36 v=95
194880 On ch=10 n=42 v=95
195000 Off ch=10 n=36 v=80
195000 Off ch=10 n=42 v=80
195120 On ch=10 n=36 v=95
195120 On ch=10 n=42 v=95
195240 Off ch=10 n=36 v=80
195240 Off ch=10 n=42 v=80
195360 On ch=10 n=38 v=95
195360 On ch=10 n=42 v=95
195480 Off ch=10 n=38 v=80
195480 Off ch=10 n=42 v=80
195600 On ch=10 n=42 v=95
195720 Off ch=10 n=42 v=80
195840 On ch=10 n=36 v=95
195840 On ch=10 n=42 v=95
195960 Off ch=10 n=36 v=80
195960 Off ch=10 n=42 v=80
196080 On ch=10 n=36 v=95
196080 On ch=10 n=42 v=95
196200 Off ch=10 n=36 v=80
196200 Off ch=10 n=42 v=80
196320 On ch=10 n=38 v=95
196320 On ch=10 n=42 v=95
196440 Off ch=10 n=38 v=80
196440 Off ch=10 n=42 v=80
196560 On ch=10 n=42 v=95
196680 Off ch=10 n=42 v=80
196800 On ch=10 n=36 v=95
196920 Off ch=10 n=36 v=80
197040 On ch=10 n=36 v=95
197040 On ch=10 n=42 v=95
197160 Off ch=10 n=36 v=80
197160 Off ch=10 n=42 v=80
197280 On ch=10 n=38 v=95
197280 On ch=10 n=42 v=95
197400 Off ch=10 n=38 v=80
197400 Off ch=10 n=42 v=80
197520 On ch=10 n=46 v=95
197640 Off ch=10 n=46 v=80
197760 On ch=10 n=36 v=95
197760 On ch=10 n=42 v=95
197880 Off ch=10 n=36 v=80
197880 Off ch=10 n=42 v=80
198000 On ch=10 n=36 v=95
198000 On ch=10 n=42 v=95
198120 Off ch=10 n=36 v=80
198120 Off ch=10 n=42 v=80
198240 On ch=10 n=38 v=95
198240 On ch=10 n=42 v=95
198360 Off ch=10 n=38 v=80
198360 Off ch=10 n=42 v=80
198480 On ch=10 n=42 v=95
198600 Off ch=10 n=42 v=80
198720 On ch=10 n=36 v=95
198720 On ch=10 n=42 v=95
198840 Off ch=10 n=36 v=80
198840 Off ch=10 n=42 v=80
198960 On ch=10 n=36 v=95
199080 Off ch=10 n=36 v=80
199200 On ch=10 n=38 v=95
199200 On ch=10 n=42 v=95
199320 Off ch=10 n=38 v=80
199320 Off ch=10 n=42 v=80
199440 On ch=10 n=42 v=95
199560 Off ch=10 n=42 v=80
199680 On ch=10 n=36 v=95
199680 On ch=10 n=42 v=95
199800 Off ch=10 n=36 v=80
199800 Off ch=10 n=42 v=80
199920 On ch=10 n=36 v=95
200040 Off ch=10 n=36 v=80
200160 On ch=10 n=38 v=95
200160 On ch=10 n=42 v=95
200280 Off ch=10 n=38 v=80
200280 Off ch=10 n=42 v=80
200400 On ch=10 n=42 v=95
200520 Off ch=10 n=42 v=80
200640 On ch=10 n=36 v=95
200640 On ch=10 n=42 v=95
200760 Off ch=10 n=36 v=80
200760 Off ch=10 n=42 v=80
200880 On ch=10 n=36 v=95
200880 On ch=10 n=42 v=95
201000 Off ch=10 n=36 v=80
201000 Off ch=10 n=42 v=80
201120 On ch=10 n=38 v=95
201120 On ch=10 n=42 v=95
201240 Off ch=10 n=38 v=80
201240 Off ch=10 n=42 v=80
201360 On ch=10 n=46 v=95
201480 Off ch=10 n=46 v=80
201600 On ch=10 n=36 v=95
201600 On ch=10 n=42 v=95
201600 On ch=10 n=49 v=95
201720 Off ch=10 n=36 v=80
201720 Off ch=10 n=42 v=80
201720 Off ch=10 n=49 v=80
201840 On ch=10 n=36 v=95
201840 On ch=10 n=42 v=95
201960 Off ch=10 n=36 v=80
201960 Off ch=10 n=42 v=80
202080 On ch=10 n=38 v=95
202080 On ch=10 n=42 v=95
202200 Off ch=10 n=38 v=80
202200 Off ch=10 n=42 v=80
202320 On ch=10 n=42 v=95
202440 Off ch=10 n=42 v=80
202560 On ch=10 n=36 v=95
202560 On ch=10 n=42 v=95
202680 Off ch=10 n=36 v=80
202680 Off ch=10 n=42 v=80
202800 On ch=10 n=36 v=95
202800 On ch=10 n=42 v=95
202920 Off ch=10 n=36 v=80
202920 Off ch=10 n=42 v=80
203040 On ch=10 n=38 v=95
203040 On ch=10 n=42 v=95
203160 Off ch=10 n=38 v=80
203160 Off ch=10 n=42 v=80
203280 On ch=10 n=42 v=95
203400 Off ch=10 n=42 v=80
203520 On ch=10 n=36 v=95
203520 On ch=10 n=42 v=95
203640 Off ch=10 n=36 v=80
203640 Off ch=10 n=42 v=80
203760 On ch=10 n=36 v=95
203760 On ch=10 n=42 v=95
203880 Off ch=10 n=36 v=80
203880 Off ch=10 n=42 v=80
204000 On ch=10 n=38 v=95
204000 On ch=10 n=42 v=95
204120 Off ch=10 n=38 v=80
204120 Off ch=10 n=42 v=80
204240 On ch=10 n=42 v=95
204360 Off ch=10 n=42 v=80
204480 On ch=10 n=36 v=95
204600 Off ch=10 n=36 v=80
204720 On ch=10 n=36 v=95
204720 On ch=10 n=42 v=95
204840 Off ch=10 n=36 v=80
204840 Off ch=10 n=42 v=80
204960 On ch=10 n=38 v=95
204960 On ch=10 n=42 v=95
205080 Off ch=10 n=38 v=80
205080 Off ch=10 n=42 v=80
205200 On ch=10 n=46 v=95
205320 Off ch=10 n=46 v=80
205440 On ch=10 n=36 v=95
205440 On ch=10 n=42 v=95
205560 Off ch=10 n=36 v=80
205560 Off ch=10 n=42 v=80
205680 On ch=10 n=36 v=95
205680 On ch=10 n=42 v=95
205800 Off ch=10 n=36 v=80
205800 Off ch=10 n=42 v=80
205920 On ch=10 n=38 v=95
205920 On ch=10 n=42 v=95
206040 Off ch=10 n=38 v=80
206040 Off ch=10 n=42 v=80
206160 On ch=10 n=42 v=95
206280 Off ch=10 n=42 v=80
206400 On ch=10 n=36 v=95
206400 On ch=10 n=42 v=95
206520 Off ch=10 n=36 v=80
206520 Off ch=10 n=42 v=80
206640 On ch=10 n=36 v=95
206760 Off ch=10 n=36 v=80
206880 On ch=10 n=38 v=95
206880 On ch=10 n=42 v=95
207000 Off ch=10 n=38 v=80
207000 Off ch=10 n=42 v=80
207120 On ch=10 n=42 v=95
207240 Off ch=10 n=42 v=80
207360 On ch=10 n=36 v=95
207360 On ch=10 n=42 v=95
207480 Off ch=10 n=36 v=80
207480 Off ch=10 n=42 v=80
207600 On ch=10 n=36 v=95
207720 Off ch=10 n=36 v=80
207840 On ch=10 n=38 v=95
207840 On ch=10 n=42 v=95
207960 Off ch=10 n=38 v=80
207960 Off ch=10 n=42 v=80
208080 On ch=10 n=42 v=95
208200 Off ch=10 n=42 v=80
208320 On ch=10 n=36 v=95
208320 On ch=10 n=42 v=95
208440 Off ch=10 n=36 v=80
208440 Off ch=10 n=42 v=80
208560 On ch=10 n=36 v=95
208560 On ch=10 n=42 v=95
208680 Off ch=10 n=36 v=80
208680 Off ch=10 n=42 v=80
208800 On ch=10 n=38 v=95
208800 On ch=10 n=42 v=95
208920 Off ch=10 n=38 v=80
208920 Off ch=10 n=42 v=80
209040 On ch=10 n=46 v=95
209160 Off ch=10 n=46 v=80
209280 On ch=10 n=36 v=95
209280 On ch=10 n=42 v=95
209280 On ch=10 n=49 v=95
209400 Off ch=10 n=36 v=80
209400 Off ch=10 n=42 v=80
209400 Off ch=10 n=49 v=80
209520 On ch=10 n=36 v=95
209520 On ch=10 n=42 v=95
209640 Off ch=10 n=36 v=80
209640 Off ch=10 n=42 v=80
209760 On ch=10 n=38 v=95
209760 On ch=10 n=42 v=95
209880 Off ch=10 n=38 v=80
209880 Off ch=10 n=42 v=80
210000 On ch=10 n=42 v=95
210120 Off ch=10 n=42 v=80
210240 On ch=10 n=36 v=95
210240 On ch=10 n=42 v=95
210360 Off ch=10 n=36 v=80
210360 Off ch=10 n=42 v=80
210480 On ch=10 n=36 v=95
210480 On ch=10 n=42 v=95
210600 Off ch=10 n=36 v=80
210600 Off ch=10 n=42 v=80
210720 On ch=10 n=38 v=95
210720 On ch=10 n=42 v=95
210840 Off ch=10 n=38 v=80
210840 Off ch=10 n=42 v=80
210960 On ch=10 n=42 v=95
211080 Off ch=10 n=42 v=80
211200 On ch=10 n=36 v=95
211200 On ch=10 n=42 v=95
211320 Off ch=10 n=36 v=80
211320 Off ch=10 n=42 v=80
211440 On ch=10 n=36 v=95
211440 On ch=10 n=42 v=95
211560 Off ch=10 n=36 v=80
211560 Off ch=10 n=42 v=80
211680 On ch=10 n=38 v=95
211680 On ch=10 n=42 v=95
211800 Off ch=10 n=38 v=80
211800 Off ch=10 n=42 v=80
211920 On ch=10 n=42 v=95
212040 Off ch=10 n=42 v=80
212160 On ch=10 n=36 v=95
212280 Off ch=10 n=36 v=80
212400 On ch=10 n=36 v=95
212400 On ch=10 n=42 v=95
212520 Off ch=10 n=36 v=80
212520 Off ch=10 n=42 v=80
212640 On ch=10 n=38 v=95
212640 On ch=10 n=42 v=95
212760 Off ch=10 n=38 v=80
212760 Off ch=10 n=42 v=80
212880 On ch=10 n=46 v=95
213000 Off ch=10 n=46 v=80
213120 On ch=10 n=36 v=95
213120 On ch=10 n=42 v=95
213240 Off ch=10 n=36 v=80
213240 Off ch=10 n=42 v=80
213360 On ch=10 n=36 v=95
213360 On ch=10 n=42 v=95
213480 Off ch=10 n=36 v=80
213480 Off ch=10 n=42 v=80
213600 On ch=10 n=38 v=95
213600 On ch=10 n=42 v=95
213720 Off ch=10 n=38 v=80
213720 Off ch=10 n=42 v=80
213840 On ch=10 n=42 v=95
213960 Off ch=10 n=42 v=80
214080 On ch=10 n=36 v=95
214080 On ch=10 n=42 v=95
214200 Off ch=10 n=36 v=80
214200 Off ch=10 n=42 v=80
214320 On ch=10 n=36 v=95
214440 Off ch=10 n=36 v=80
214560 On ch=10 n=38 v=95
214560 On ch=10 n=42 v=95
214680 Off ch=10 n=38 v=80
214680 Off ch=10 n=42 v=80
214800 On ch=10 n=42 v=95
214920 Off ch=10 n=42 v=80
215040 On ch=10 n=36 v=95
215040 On ch=10 n=42 v=95
215160 Off ch=10 n=36 v=80
215160 Off ch=10 n=42 v=80
215280 On ch=10 n=36 v=95
215400 Off ch=10 n=36 v=80
215520 On ch=10 n=38 v=95
215520 On ch=10 n=42 v=95
215640 Off ch=10 n=38 v=80
215640 Off ch=10 n=42 v=80
215760 On ch=10 n=42 v=95
215880 Off ch=10 n=42 v=80
216000 On ch=10 n=36 v=95
216000 On ch=10 n=42 v=95
216120 Off ch=10 n=36 v=80
216120 Off ch=10 n=42 v=80
216240 On ch=10 n=36 v=95
216240 On ch=10 n=42 v=95
216360 Off ch=10 n=36 v=80
216360 Off ch=10 n=42 v=80
216480 On ch=10 n=38 v=95
216480 On ch=10 n=42 v=95
216600 Off ch=10 n=38 v=80
216600 Off ch=10 n=42 v=80
216720 On ch=10 n=46 v=95
216840 Off ch=10 n=46 v=80
216960 On ch=10 n=36 v=95
216960 On ch=10 n=42 v=95
216960 On ch=10 n=49 v=95
217080 Off ch=10 n=36 v=80
217080 Off ch=10 n=42 v=80
217080 Off ch=10 n=49 v=80
217200 On ch=10 n=36 v=95
217200 On ch=10 n=42 v=95
217320 Off ch=10 n=36 v=80
217320 Off ch=10 n=42 v=80
217440 On ch=10 n=38 v=95
217440 On ch=10 n=42 v=95
217560 Off ch=10 n=38 v=80
217560 Off ch=10 n=42 v=80
217680 On ch=10 n=42 v=95
217800 Off ch=10 n=42 v=80
217920 On ch=10 n=36 v=95
217920 On ch=10 n=42 v=95
218040 Off ch=10 n=36 v=80
218040 Off ch=10 n=42 v=80
218160 On ch=10 n=36 v=95
218160 On ch=10 n=42 v=95
218280 Off ch=10 n=36 v=80
218280 Off ch=10 n=42 v=80
218400 On ch=10 n=38 v=95
218400 On ch=10 n=42 v=95
218520 Off ch=10 n=38 v=80
218520 Off ch=10 n=42 v=80
218640 On ch=10 n=42 v=95
218760 Off ch=10 n=42 v=80
218880 On ch=10 n=36 v=95
218880 On ch=10 n=42 v=95
219000 Off ch=10 n=36 v=80
219000 Off ch=10 n=42 v=80
219120 On ch=10 n=36 v=95
219120 On ch=10 n=42 v=95
219240 Off ch=10 n=36 v=80
219240 Off ch=10 n=42 v=80
219360 On ch=10 n=38 v=95
219360 On ch=10 n=42 v=95
219480 Off ch=10 n=38 v=80
219480 Off ch=10 n=42 v=80
219600 On ch=10 n=42 v=95
219720 Off ch=10 n=42 v=80
219840 On ch=10 n=36 v=95
219960 Off ch=10 n=36 v=80
220080 On ch=10 n=36 v=95
220080 On ch=10 n=42 v=95
220200 Off ch=10 n=36 v=80
220200 Off ch=10 n=42 v=80
220320 On ch=10 n=38 v=95
220320 On ch=10 n=42 v=95
220440 Off ch=10 n=38 v=80
220440 Off ch=10 n=42 v=80
220560 On ch=10 n=46 v=95
220680 Off ch=10 n=46 v=80
220680 Meta TrkEnd
TrkEnd";
*/
	require('classes/midi.class.php');

	$midi = new Midi();
	$midi->importTxt($txt);
	$midi->saveMidFile("../public/js/demo.mid", 0666);

?>
