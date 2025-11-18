<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
                        
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Profile Pic</h2>
                        <p class="mt-1 text-sm text-gray-600">Upload your picture.</p>
                    </header>
                    @if ($user->avatar_path)
                        <img src="{{ config('filesystems.disks.s3.url') }}/{{ config('filesystems.disks.s3.bucket') }}/{{ $user->avatar_path }}"
                        alt="プロフィール画像" width="300">
                    @else
                        <p>まだ画像が登録されていません。</p>
                    @endif
                    <form method="POST" action="/profile/upload" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar">
                        <br>
                        <br>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">アップロード</button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">About Me</h2>
                        <p class="mt-1 text-sm text-gray-600">Text text.</p>
                    </header>
                    <br>
                    <form method="POST" action="/profile/bio">
                        @csrf
                        <textarea name="bio" rows="4" class="w-full border rounded-md text-left">@if ($user->bio){{ $user->bio }}@endif</textarea>
                        <br>
                        <br>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">SAVE</button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
