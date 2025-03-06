<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
    </head>
    <body>
        <h1>Register with your username and password:</h1>
        <form method="POST" action="/register">
            @csrf
            <span>username should only contains letters and numbers, 3-20 characters.</span>
            <br/>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Register</button>
        </form>

        <div>
            @if (session('error'))
                <span style="color: red;">Error: 
                    @if (session('error') === 'UserAlreadyExists')
                        User already exists.
                    @elseif (session('error') === 'InvalidUsername')
                        Invalid username.
                    @elseif (session('error') === 'InvalidPassword')
                        Invalid password.
                    @else
                        Unknown error: {{ session('error') }}
                    @endif
                </span>
            @elseif (session('success'))
                <span style="color: green;">Registered successfully, please go to the login page.</span>
            @endif
        </div>

        <br/>

        Already had an account? Turns to <a href="/login">Login</a> page.
    </body>
</html>