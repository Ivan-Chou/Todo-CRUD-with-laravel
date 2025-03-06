<!DOCTYPE html>
<html>
    <head>
        <title>Todo List</title>
    </head>
    <body>
        <h1>Todo List of @php $username @endphp </h1>
        <form method="POST" action="/todolist/create">
            @csrf
            <input type="text" name="task" placeholder="What needs to be done?">
            <input type="date" name="deadline" placeholder="Deadline">
            <button type="submit">Add</button>
        </form>

        <br/>

        <table>
            <thead>
                <th>Task</th>
                <th>Deadline</th>
                <th>Action</th>
            </thead>
            @foreach ($todos as $todo)
                <tr>
                    <td>{{ $todo->task }}</td>
                    <td>{{ $todo->deadline }}</td>
                    <td>
                        <form method="POST" action="/todolist/delete/{{ $todo->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" name="task_id" value="">Delete</button>
                        </form>
                        <!-- can move $todo->id to button value instead of dynamic URL routes -->
                    </td>
                </tr>
            @endforeach
        </table>

        <br/>

        <a href="/logout">Logout</a>
    </body>
</html>

