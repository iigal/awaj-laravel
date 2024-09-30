@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Complaints</h5>
                    <p class="card-text">{{ $everyData }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Success</h5>
                    <p class="card-text">{{ $successData }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <p class="card-text">{{ $progressData }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Queue</h5>
                    <p class="card-text">{{ $queueData }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5">Recent Complaints</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Area</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($all as $complaint)
            <tr>
                <td>{{ $complaint->_id }}</td>
                <td>{{ $complaint->title }}</td>
                <td>{{ $complaint->status }}</td>
                <td>{{ $complaint->area }}</td>
                <td>
                    <a href="{{ url('/admin/details/' . $complaint->_id) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
