@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Application Requests</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Account Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->accountstatus }}</td>
                <td>
                    <form action="{{ url('/admin/applicationrequest/' . $user->id) }}" method="POST">
                        @csrf
                        <select name="submited" class="form-control">
                            <option value="Pending" {{ $user->accountstatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $user->accountstatus == 'Approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
