@extends('Layouts.app')

@section('title', 'Add Employee Account')

@section('main')
    <div class="mx-auto max-w-2xl rounded-2xl border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Administration</p>
        <h1 class="mt-1 text-2xl font-bold text-stone-950">Add employee account</h1>
        <p class="mt-2 text-sm text-stone-600">Give a new team member their own name, email, password, and POS permissions.</p>
        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-7 space-y-5">
            @csrf
            @include('admin.users.form', ['user' => null])
            <div class="flex justify-end gap-3"><a href="{{ route('admin.users.index') }}" class="rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700">Cancel</a><button class="rounded-lg bg-stone-950 px-5 py-2.5 text-sm font-bold text-white hover:bg-stone-800">Create account</button></div>
        </form>
    </div>
@endsection
