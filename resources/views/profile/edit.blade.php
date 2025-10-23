<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Account Summary</h5>
                        <div class="row mb-2">
                            <div class="col-md-6"><b>Role:</b> {{ ucfirst($user->role) }}</div>
                            <div class="col-md-6"><b>Member Since:</b> {{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><b>Total Books Borrowed:</b> {{ $totalBorrowed }}</div>
                            <div class="col-md-6"><b>Outstanding Fines:</b> ₱{{ number_format($totalFine, 2) }}</div>
                        </div>
                        <a href="{{ route('profile.download_data') }}" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-download"></i> Download My Data</a>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Recent Borrowing Activity</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Borrowed At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentBorrowings as $borrowing)
                                        <tr>
                                            <td>{{ $borrowing->book->title ?? 'N/A' }}</td>
                                            <td>{{ $borrowing->created_at->format('M d, Y') }}</td>
                                            <td>{{ ucfirst($borrowing->status) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center">No recent borrowings.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form', ['user' => $user])
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form', ['user' => $user])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
