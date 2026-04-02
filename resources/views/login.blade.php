<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/login">
    @csrf

    <label>Account</label><br>
    <input type="text" name="account"><br><br>

    <label>Password</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>