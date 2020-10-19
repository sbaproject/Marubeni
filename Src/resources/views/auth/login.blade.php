<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login page</title>
</head>

<body>
	<p>this is login page</p>

	@error('login_fail')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror

	@error('throttle')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror

	<form action="{{route('authenticate')}}" method="POST">
		@csrf
		<div>
			<label for="email">E-Mail Address</label>
			<input type="text" name="email" id="email" value="{{ old('email') }}">
			@error('email')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
			@error('password')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
		<div>
			<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
			<label for="remember">Remember me</label>
		</div>
		<div>
			<input type="submit" value="Login">
			<a href="">Forgot your password ?</a>
		</div>
	</form>
</body>

</html>