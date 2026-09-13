@extends('Layouts.app')

@section('title', 'Employee Accounts')

@section('main')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Administration</p>
                <h1 class="mt-1 text-3xl font-bold text-stone-950">Employee accounts</h1>
                <p class="mt-2 text-sm text-stone-600">Create login accounts and assign each employee a POS role.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-stone-950 px-4 py-2.5 text-sm font-bold text-white hover:bg-stone-800">Add employee</a>
        </div>

        @if (session('success'))<div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">{{ session('error') }}</div>@endif

        <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500"><tr><th class="px-5 py-4">Employee</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Role</th><th class="px-5 py-4">Created</th><th class="px-5 py-4 text-right">Actions</th></tr></thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-stone-50"><td class="px-5 py-4 font-semibold text-stone-900">{{ $user->name }}</td><td class="px-5 py-4 text-stone-600">{{ $user->email }}</td><td class="px-5 py-4"><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold capitalize text-amber-800">{{ $user->role }}</span></td><td class="px-5 py-4 text-stone-500">{{ $user->created_at?->format('M d, Y') }}</td><td class="px-5 py-4 text-right"><a href="{{ route('admin.users.edit', $user) }}" class="font-semibold text-amber-800 hover:text-amber-950">Edit</a>@if (! auth()->user()->is($user))<form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="ml-4 inline" onsubmit="return confirm('Remove this employee account?')">@csrf @method('DELETE')<button class="font-semibold text-red-700 hover:text-red-900">Remove</button></form>@endif</td></tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-stone-500">No employee accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
