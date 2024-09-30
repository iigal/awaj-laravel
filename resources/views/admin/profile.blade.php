@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>User Profile</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $userdata->name }}</h5>
            <p class="card-text">Email: {{ $userdata->email }}</p>
            <p class="card-text">Account Status: {{ $userdata->accountstatus }}</p>
        </div>
    </div>

    <h3>User Complaints</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allComplaints as $complaint)
            <tr>
                <td>{{ $complaint->_id }}</td>
                <td>{{ $complaint->title }}</td>
                <td>{{ $complaint->description }}</td>
                <td>{{ $complaint->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
