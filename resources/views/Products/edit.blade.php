@extends('Layouts.app')

@section('title', 'Edit Product')

@section('main')
    <div class="mx-auto max-w-3xl rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Catalog management</p>
        <h1 class="mt-1 text-2xl font-bold text-stone-950">Edit {{ $product->name }}</h1>
        <form method="POST" action="{{ route('Products.update', $product) }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')
            @include('Products.form')
            <div class="flex justify-end gap-3"><a href="{{ route('Products.index') }}" class="rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700">Cancel</a><button class="rounded-lg bg-stone-950 px-4 py-2.5 text-sm font-bold text-white">Save changes</button></div>
        </form>
    </div>
@endsection
