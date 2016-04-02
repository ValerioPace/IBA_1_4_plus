<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Gent.le {{$company['name']}},
		<br><br>
		benvenuto su IBA!
		<br><br>
		Prendi nota delle tue credenziali:
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
		<b>Prima di eseguire l'accesso clicca <a href="{{$aURI}}">QUI</a> per verificare l'email ed attivare l'account.</b>
		<br><br>
		Cordiali saluti,
		<br><br>
		lo staff di IBA.
	</body>
</html>