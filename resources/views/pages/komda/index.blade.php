@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola akun Komisariat Daerah (Komda)
            </p>
        </div>
        <div>
            <a href="{{ route('manajemen.komda.create') }}"
                class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Komda
            </a>
        </div>
    </div>
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 mb-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Username</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Email</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white/90">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $user->username ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('manajemen.komda.edit', $user->id) }}" class="text-brand-500 hover:underline">Edit</a>
                                <form action="{{ route('manajemen.komda.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Yakin ingin menghapus Komda ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada Komda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} Komda
                </div>
                <div>
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </div>
@endsection
