<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Gent.le {{$user['name']}} {{$user['last_name']}},
		<br><br>
		hai effettuato un reset della password sulla piattaforma IBA.
		<br><br>
		Di seguito trovi il tuo nome utente e la tua password temporanea per accedere alla console di gestione:
		<blockquote>
			<p class="text-justify">
				<em>
					<ul>
						<li><b>Nome Utente: </b>{{{$user['user_name']}}}</li>
						<li><b>Password: </b>{{{$password}}}</li>
					</ul>
				</em>
			</p>
		</blockquote>
		<br><br>
		<b>Ti consigliamo di cambiare la password al primo accesso.</b>
		<br><br>
		Cordiali saluti,
		<br><br>
		lo staff di IBA.
	</body>
</html>