<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Gent.le {{$company['name']}},
		<br><br>
		per poter effettuare l'accesso alla console di gestione IBA devi verificare il tuo indirizzo email.

		<br><br>

		<b>Clicca <a href="{{$aURI}}">QUI</a> per verificare l'email ed attivare l'account, oppure copia e incolla il seguente link nella barra dell'indirizzo del tuo browser:</b>
		<br><br>
		<blockquote>
			{{$aURI}}
		</blockquote>
		<br><br>
		Cordiali saluti,
		<br><br>
		lo staff di IBA.
	</body>
</html>