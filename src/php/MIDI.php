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
	for ($i = 0; $i < count($partition["_instruments_list"]); $i++)
	{
		$txt += "MTrk
				0 Meta TrkName '"+$partition["_instruments_list"][$i]["_name_instrument"]+"'";
		$txt += "0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=100 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=101 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=6 v=12
				0 Pb ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" v=8192
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=101 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=100 v=1
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=6 v=64
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=38 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=101 v=127
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=100 v=127
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=100 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=101 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=6 v=12
				0 Pb ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" v=8192
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=101 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=100 v=1
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=6 v=64
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=38 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=101 v=127
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=100 v=127
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=101 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=100 v=2
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=6 v=64
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=101 v=127
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=100 v=127
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=101 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=100 v=2
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=6 v=64
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=101 v=127
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=100 v=127
				0 PrCh ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" p=25
				0 PrCh ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" p=25
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=0 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=0 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=7 v=104
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=7 v=104
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=10 v=64
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=10 v=64
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=93 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=93 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=91 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=91 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=92 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=92 v=0
				0 Par ch="+($partition["_instruments_list"][$i]["_midi_channel"]+1)+" c=95 v=0
				0 Par ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" c=95 v=0";
		$measures = $partition["_instruments_list"][$i]["_track_part"]["_measure_list"];
		for ($j = 0; $j < count($measures); $j++)
		{
			$chords = $measures[$j]["_chord_list"];
			for ($k = 0; $k < count($chords); $k++)
			{
				$notes = $chords[$k]["_note_list"];
				for ($h = 0; $h < count($notes); $h++)
				{
					$txt += $notes[$h]["_begin"]+" On ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" n="+$note_ref[$notes[$h]["_step_pitch"]+$notes[$h]["_octave_pitch"]]+" v=95";
					$txt += ($notes[$h]["_begin"]+$notes[$h]["_duration"])+" Off ch="+$partition["_instruments_list"][$i]["_midi_channel"]+" n="+$note_ref[$notes[$h]["_step_pitch"]+$notes[$h]["_octave_pitch"]]+" v=80";
				}
			}
			if ($j == count($measures) - 1)
			{
				$last_measure = $measures[$j];
				$last_chord = $last_measure["_chord_list"][count($last_measure["_chord_list"])-1];
				$last_note = $last_chord["_note_list"][count($last_chord["_note_list"])-1];
				$txt += ($last_note["_begin"]+$last_note["_duration"])+" Meta TrkEnd
						TrkEnd";
			}
		}
	}

	require('classes/midi.class.php');

	$midi = new Midi();
	$midi->importTxt($txt);
	$midi->saveMidFile("../public/js/demo.mid", 0666);
	
?>
