<table>
    <thead>
    <tr>
        <th>first_name</th>
        <th>last_name</th>
        <th>email</th>
        <th>registration_number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student['first_name'] }}</td>
            <td>{{ $student['last_name'] }}</td>
            <td>{{ $student['email'] }}</td>
            <td>{{ $student['registration_number'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
