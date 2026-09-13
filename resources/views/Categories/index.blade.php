@extends('Layouts.app')
@section('title', 'Category')

@push('style')
    <style>
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        thead { background: #f1f5f9; }
        th, td { padding: 12px 14px; border: 1px solid #e2e8f0; text-align: left; font-size: 0.9rem; }
        tr:hover td { background: #f8fafc; }
        
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-info { background: #0891b2; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
    </style>
@section('main')
    @if (in_array(auth()->user()->role, ['manager', 'admin'], true))
        <a href="{{route('Categories.create')}}" class="btn btn-primary">Create New</a>
    @endif
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                @if (in_array(auth()->user()->role, ['manager', 'admin'], true))
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name}}</td>
                    <td>{{ $category->slug}}</td>
                    @if (in_array(auth()->user()->role, ['manager', 'admin'], true))
                        <td class="nowrap">
                            <a href="{{ route('Categories.edit', $category->id)}}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('Categories.destroy', $category->id)}}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection