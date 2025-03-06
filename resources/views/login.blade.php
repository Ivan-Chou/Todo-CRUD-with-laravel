<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Hello, please login:</h1>
        <form method="POST" action="/login">
            @csrf
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <div>
            @php
                if (session('error')) {
                    echo "<span style='color: red;'> Error: ";

                    if(session('error') === 'UserDoesNotExist'){
                        echo "User does not exist.";
                    }
                    else if(session('error') === 'IncorrectPassword'){
                        echo "Incorrect Password.";
                    }
                    else {
                        echo "Unknown error.";
                    }

                    echo "</span>";
                }
            @endphp

        <br/>

        Not registered yet? You can <a href="/register">Register</a> first.
    </body>
</html>