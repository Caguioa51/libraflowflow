@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Header -->
            <div class="mb-4">
                <h2 class="mb-1"><i class="bi bi-person-plus text-primary me-2"></i>Borrow for Student</h2>
                <p class="text-muted mb-0">Find a student to borrow books for them</p>
            </div>

            <!-- Student Search Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Find Student</h5>
                </div>
                <div class="card-body">
                    <!-- RFID Scan Section -->
                    <div class="mb-4">
                        <h6 class="text-muted">RFID Scan</h6>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" id="rfid_input"
                                   placeholder="Scan RFID card (auto-lookup enabled)..." autocomplete="off" autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="rfid_clear_btn">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            📷 <strong>RFID Scanner Ready!</strong> Auto-lookup enabled • Scan RFID card for instant user lookup
                        </div>
                        <!-- RFID Status Indicator -->
                        <div id="rfid_status" class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-circle-fill text-success me-1"></i>
                                Ready to scan RFID card...
                            </small>
                        </div>

                        <!-- User Info Display (Initially Hidden) -->
                        <div id="rfid_user_info" class="mt-3 d-none">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 text-center">
                                            <h6 id="rfid_user_name" class="mb-0"></h6>
                                            <small id="rfid_user_role" class="text-muted"></small>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p class="mb-1"><strong>Email:</strong></p>
                                                    <p id="rfid_user_email" class="mb-3"></p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-1"><strong>Student ID:</strong></p>
                                                    <p id="rfid_user_student_id" class="mb-3"></p>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-success" id="rfid_borrow_for_user">
                                                    <i class="bi bi-person-plus me-2"></i>Borrow Books for this User
                                                </button>
                                                <button class="btn btn-info" id="rfid_view_history">
                                                    <i class="bi bi-clock-history me-2"></i>View Borrowing History
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No User Found (Initially Hidden) -->
                        <div id="rfid_no_user" class="mt-3 d-none">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>No user found with this RFID card.</strong>
                                <br>
                                <span id="rfid_no_user_message"></span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Manual Search -->
                    <div class="mb-4">
                        <h6 class="text-muted">Manual Search (Alternative)</h6>
                        <form method="POST" action="{{ route('borrowings.admin_borrow.post') }}" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_query"
                                       placeholder="Enter student name, email, or ID..." required>
                                <button class="btn btn-info" type="submit">
                                    <i class="bi bi-search me-2"></i>Find Student
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($selectedUser)
                        <!-- Student Found - Show Borrowing Interface -->
                        <div class="card mt-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Student Selected</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($selectedUser->role) }}</small>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->email }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Student ID:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success" id="borrow-books-btn">
                                                <i class="bi bi-book me-2"></i>Borrow Books for {{ $selectedUser->name }}
                                            </button>
                                            <a href="{{ route('borrowings.admin_borrow') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-repeat me-2"></i>Find Different Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Selection Form (Initially Hidden) -->
                        <div id="book-selection-section" class="card mt-4" style="display: none;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-books me-2"></i>Select Books to Borrow</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('borrowings.store') }}" id="borrow-book-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" id="borrow_user_id">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="book_select" class="form-label">Select Book</label>
                                            <select class="form-select" name="book_id" id="book_select" required>
                                                <option value="">Choose a book...</option>
                                                @foreach($books as $book)
                                                    <option value="{{ $book->id }}">
                                                        {{ $book->title }} by {{ $book->author->name ?? 'Unknown' }}
                                                        (Available: {{ $book->available_quantity ?? ($book->quantity ?? 1) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-success d-block w-100">
                                                <i class="bi bi-check-circle me-2"></i>Borrow Book
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle me-2"></i>How to use:</h6>
                        <ol class="mb-0">
                            <li><strong>RFID Scan (Recommended):</strong> Simply scan RFID card - lookup happens automatically!</li>
                            <li><strong>Manual Search:</strong> Enter the student's name, email, or student ID in the search field</li>
                            <li>If student is found, click "Borrow Books" to proceed</li>
                            <li>Select a book from the dropdown and click "Borrow Book"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced RFID Scanner with instant auto-lookup
    class AdminRFIDScanner {
        constructor() {
            this.rfidInput = document.getElementById('rfid_input');
            this.clearBtn = document.getElementById('rfid_clear_btn');
            this.statusDiv = document.getElementById('rfid_status');
            this.userInfo = document.getElementById('rfid_user_info');
            this.noUser = document.getElementById('rfid_no_user');
            this.autoSubmitDelay = 250; // Reduced to 250ms for instant response
            this.minRfidLength = 8;
            this.autoSubmitTimer = null;
            this.lastScannedValue = '';

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.updateStatus('Ready to scan RFID card...', 'success');

            // Focus the input for immediate scanning
            this.rfidInput.focus();
        }

        setupEventListeners() {
            // RFID Input with instant auto-lookup
            this.rfidInput.addEventListener('input', (e) => {
                this.onRfidInput(e.target.value);
            });

            // Clear button
            this.clearBtn.addEventListener('click', () => {
                this.clearAll();
            });

            // Prevent form submission on Enter
            this.rfidInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    // Manual lookup on Enter as backup
                    this.manualLookup();
                }
            });
        }

        onRfidInput(value) {
            const cleanValue = value.replace(/[^0-9]/g, '');
            if (cleanValue !== value) {
                this.rfidInput.value = cleanValue;
                value = cleanValue;
            }

            // Update status with character count
            if (value.length > 0) {
                this.updateStatus(`📷 Scanning RFID: ${value.length} characters`, 'warning');
            } else {
                this.updateStatus('Ready to scan RFID card...', 'success');
                return;
            }

            // Clear previous timer
            if (this.autoSubmitTimer) {
                clearTimeout(this.autoSubmitTimer);
            }

            // INSTANT auto-lookup when RFID is complete
            if (value.length >= this.minRfidLength && value.length <= 20) {
                this.lastScannedValue = value;
                this.updateStatus('🔍 Auto-looking up user...', 'info');

                this.autoSubmitTimer = setTimeout(() => {
                    if (this.rfidInput.value === value && value === this.lastScannedValue) {
                        this.playSound('scan');
                        this.manualLookup();
                    }
                }, this.autoSubmitDelay);
            }

            if (value.length > 0 && value.length < this.minRfidLength) {
                this.updateStatus(`⏳ Need ${this.minRfidLength - value.length} more characters...`, 'info');
            }
        }

        async manualLookup() {
            const rfidCard = this.rfidInput.value.trim();

            if (!rfidCard || rfidCard.length < this.minRfidLength) {
                this.playSound('error');
                this.updateStatus(`❌ Need at least ${this.minRfidLength} characters`, 'danger');
                setTimeout(() => {
                    this.updateStatus('Ready to scan RFID card...', 'success');
                }, 2000);
                return;
            }

            this.updateStatus('🔄 Looking up user...', 'warning');
            this.playSound('processing');

            try {
                const response = await fetch('{{ route("admin.rfid.lookup") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: 'rfid_card=' + encodeURIComponent(rfidCard)
                });

                const data = await response.json();

                if (data.success) {
                    this.showUserInfo(data.user);
                    this.playSound('success');
                    this.updateStatus(`✅ User found: ${data.user.name}`, 'success');
                } else {
                    this.showNoUser(data.message);
                    this.playSound('error');
                    this.updateStatus('❌ User not found', 'danger');
                }
            } catch (error) {
                console.error('RFID Lookup Error:', error);
                this.playSound('error');
                this.updateStatus('❌ Lookup failed', 'danger');
                setTimeout(() => {
                    this.updateStatus('Ready to scan RFID card...', 'success');
                }, 3000);
            }
        }

        showUserInfo(user) {
            this.userInfo.classList.remove('d-none');
            this.noUser.classList.add('d-none');

            // Populate user data
            document.getElementById('rfid_user_name').textContent = user.name;
            document.getElementById('rfid_user_email').textContent = user.email;
            document.getElementById('rfid_user_student_id').textContent = user.student_id || 'N/A';

            const roleBadge = document.getElementById('rfid_user_role');
            const roleColors = {
                'admin': 'danger',
                'teacher': 'warning',
                'student': 'info'
            };
            roleBadge.className = `badge bg-${roleColors[user.role] || 'secondary'}`;
            roleBadge.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);

            // Setup button actions with direct URL construction
            document.getElementById('rfid_borrow_for_user').onclick = function() {
                window.location.href = '/admin/users/' + user.id + '/borrow';
            };

            document.getElementById('rfid_view_history').onclick = function() {
                window.location.href = '/admin/users/' + user.id + '/history';
            };
        }

        showNoUser(message) {
            this.userInfo.classList.add('d-none');
            this.noUser.classList.remove('d-none');
            document.getElementById('rfid_no_user_message').textContent = message;
        }

        clearAll() {
            this.rfidInput.value = '';
            this.lastScannedValue = '';
            this.rfidInput.focus();
            this.userInfo.classList.add('d-none');
            this.noUser.classList.add('d-none');
            this.updateStatus('Ready to scan RFID card...', 'success');
            this.playSound('clear');

            if (this.autoSubmitTimer) {
                clearTimeout(this.autoSubmitTimer);
            }
        }

        updateStatus(message, type) {
            const icons = {
                success: 'bi-circle-fill text-success',
                warning: 'bi-circle-fill text-warning',
                danger: 'bi-circle-fill text-danger',
                info: 'bi-circle-fill text-info'
            };

            const iconClass = icons[type] || icons.info;
            this.statusDiv.innerHTML = `<small class="text-muted"><i class="${iconClass} me-1"></i>${message}</small>`;
        }

        playSound(type) {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                const sounds = {
                    success: { freq: 800, duration: 200, volume: 0.1 },
                    error: { freq: 400, duration: 300, volume: 0.2 },
                    processing: { freq: 600, duration: 100, volume: 0.05 },
                    scan: { freq: 1000, duration: 150, volume: 0.08 },
                    clear: { freq: 200, duration: 100, volume: 0.05 }
                };

                const sound = sounds[type] || sounds.processing;

                oscillator.frequency.setValueAtTime(sound.freq, audioContext.currentTime);
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(sound.volume, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + sound.duration / 1000);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + sound.duration / 1000);
            } catch (e) {
                console.log('Audio not supported');
            }
        }
    }

    // Initialize RFID Scanner with instant auto-lookup
    const rfidScanner = new AdminRFIDScanner();

    // Borrow Books button click handler (existing functionality)
    const borrowBtn = document.getElementById('borrow-books-btn');
    if (borrowBtn) {
        borrowBtn.addEventListener('click', function() {
            const bookSelectionSection = document.getElementById('book-selection-section');
            if (bookSelectionSection) {
                bookSelectionSection.style.display = 'block';
                bookSelectionSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Make RFID scanner globally accessible for debugging
    window.adminRFIDScanner = rfidScanner;
});
</script>
