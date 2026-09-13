@extends('Layouts.app')
@section('title', 'Create Category')

@push('style')
<style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    .form-group{margin-bottom:12px}
    label{display: block; margin-bottom: 4px; font-weight: 600}
    input[type="text"],input[type="email"],input[type="date"],input[type="number"],select,textarea
    {width: 100%; padding: 8px;border: 1px solid #ccc;border-radius: 4px}
    .error{color: #b91c1c;font-size: 0.95rem;margin-top: 4px}
    .actions{margin-top: 16px}
    .btn-secondary{background: #6b7280;color: #fff}
    .btn-primary { background: #2563eb; color: #fff; }
</style>
@section('main')
    <form action="{{route('Categories.store')}}" method= "POST">
        @csrf
        <div class="form-group">
            <label for= "name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug:</label>
            <input type="text" name="slug" id="slug" required>
        </div>
        <div class="actions">
            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="{{ route('Categories.index')}}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
