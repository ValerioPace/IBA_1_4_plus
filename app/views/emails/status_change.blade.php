<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Gent.le {{$company['name']}},
		<br><br>
		La procedura per l'attivazione del servizio IBA è ora nello status: <b>{{ $status }}</b>.

		<br><br>
		@if ($msg != '')
		<b>Messaggio da parte del customer care:</b>
		<br><br>
		<blockquote>
			"{{$msg}}"
		</blockquote>
		<br><br>
		@endif
		Cordiali saluti,
		<br><br>
		lo staff di IBA.
	</body>
</html>