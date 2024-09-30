@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Complaints</h2>

    <form method="GET" action="{{ url('/admin/complaint') }}" class="form-inline mb-3">
        <select name="status" class="form-control mr-2">
            <option value="">Select Status</option>
            <option value="Success">Success</option>
            <option value="Progress">In Progress</option>
            <option value="Queue">Queue</option>
        </select>

        <select name="area" class="form-control mr-2">
            <option value="">Select Area</option>
            @foreach($database as $type)
            <option value="{{ $type->type }}">{{ $type->type }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Area</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payload as $complaint)
            <tr>
                <td>{{ $complaint->_id }}</td>
                <td>{{ $complaint->title }}</td>
                <td>{{ $complaint->description }}</td>
                <td>{{ $complaint->status }}</td>
                <td>{{ $complaint->area }}</td>
                <td>
                    <a href="{{ url('/admin/details/' . $complaint->_id) }}" class="btn btn-primary">View</a>
                    <a href="{{ url('/admin/delete/' . $complaint->_id) }}" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
