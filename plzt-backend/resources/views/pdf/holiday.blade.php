<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urlaubsantrag</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            font-size:11px;
            position: relative; /* damit das Unterschriftsfeld relativ zum body positioniert wird */
        }
        .header {
            text-align: right;
            margin-bottom: 400px;
        }
        .logo {
        	position: absolute;
            width: 200px; /* anpassen */
            height: auto;
            right: 50px;
            top: 40px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom:45px;
            border-left:3px solid #FF4c0a;
            border-bottom:1px solid #FF4c0a;
            padding-left:10px;
            padding-bottom:4px;
        }
        .title-space {
            font-size: 22px;
            font-weight: bold;
            margin-bottom:65px;
            height:155px;
            width:10px;

        }
        .content {
            /* Stil für den Inhalt */
        }
        .signaturepic {
            position: absolute;
            bottom: 140px; /* anpassen, um die vertikale Position der Signatur zu ändern */
            right: 60px; /* anpassen, um die horizontale Position der Signatur zu ändern */
            width: 200px;
            height: 150px;

            font-size:9px;
            /* Weitere Stiloptionen für das Unterschriftsfeld hier einfügen */
        }
        .signature {
            position: absolute;
            bottom: 50px; /* anpassen, um die vertikale Position der Signatur zu ändern */
            right: 50px; /* anpassen, um die horizontale Position der Signatur zu ändern */
            width: 200px;
            height: 150px;
            border-top: 1px dotted #000;
            font-size:9px;
            /* Weitere Stiloptionen für das Unterschriftsfeld hier einfügen */
        }
        table.holiday td {
    		padding: 5px;
    	}
    </style>
</head>
<body>
	
	<img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
	<div class="title-space">&nbsp;</div>
    <div class="title">
        Urlaubsantrag
    </div>
    <table class="holiday">
    	<tr>
    		<td>Mitarbeiter:</td>
    		<td>{{ $user_name }}</td>
    	</tr>
    	<tr>
    		<td>Beantragter Urlaub:</td>
    		<td>
    		@if($start != $end)
    			{{ \Carbon\Carbon::createFromFormat('Y-m-d', $start)->format('d.m.Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $end)->format('d.m.Y') }}
    			
    		@endif
    		@if($start == $end)
    			{{ \Carbon\Carbon::createFromFormat('Y-m-d', $end)->format('d.m.Y') }}
    		@endif
    		</td>
    	</tr>
    	<tr>
    		<td>Gesamt Urlaubstage:</td>
    		<td>{{ $alldays[0]['holidays'] }}</td>
    	</tr>
    	<tr>
    		<td>Verbleibende Urlaubstage im aktuellen Jahr:</td>
    		<td>{{ $alldays[0]['holidays'] - $days_approved }}</td>
    	</tr>
    	<tr>
    		<td>Tage beantragt:</td>
    		<td>{{ $days }}</td>
    	</tr>
    	<tr>
    		<td>Verbleibende Urlaubstage nach Genehmigung offener Anträge:</td>
    		<td>{{ $days_left }}</td>
    	</tr>    	
    </table>
    <div class="signaturepic">
    <img src="{{ $signature }}" alt="Signature" style="width: 200px; height: auto;">
    </div>
    <div class="signature">
    	Unterschrift {{ $user_name }}
    </div> <!-- Unterschriftsfeld -->
</body>
</html>
