@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="bi bi-people-fill me-2 text-primary"></i>User Management
                    </h1>
                    <p class="text-muted mb-0 mt-1">Manage and monitor all system users</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-primary mb-3 mx-auto">
                        <i class="bi bi-people fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-primary fw-bold">{{ number_format($stats['total']) }}</h2>
                    <p class="mb-0 text-muted small">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-info mb-3 mx-auto">
                        <i class="bi bi-mortarboard fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-info fw-bold">{{ number_format($stats['students']) }}</h2>
                    <p class="mb-0 text-muted small">Students</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-warning mb-3 mx-auto">
                        <i class="bi bi-person-workspace fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-warning fw-bold">{{ number_format($stats['teachers']) }}</h2>
                    <p class="mb-0 text-muted small">Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-danger mb-3 mx-auto">
                        <i class="bi bi-shield-check fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-danger fw-bold">{{ number_format($stats['admins']) }}</h2>
                    <p class="mb-0 text-muted small">Administrators</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-success mb-3 mx-auto">
                        <i class="bi bi-check-circle fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-success fw-bold">{{ number_format($stats['active_borrowers']) }}</h2>
                    <p class="mb-0 text-muted small">Active Borrowers</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-secondary mb-3 mx-auto">
                        <i class="bi bi-exclamation-triangle fs-4 text-white"></i>
                    </div>
                    <h2 class="mb-1 text-secondary fw-bold">{{ number_format($stats['overdue_borrowers']) }}</h2>
                    <p class="mb-0 text-muted small">Overdue Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters and Search -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <h5 class="mb-3 text-dark fw-semibold">
                        <i class="bi bi-funnel-fill me-2 text-primary"></i>Filters & Search
                    </h5>
                </div>
            </div>

            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-lg-5 col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Search Users
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, email, or student ID..."
                               id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()" title="Clear Search">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Role
                    </label>
                    <select class="form-select" name="role" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>
                            Student
                        </option>
                        <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>
                            Teacher
                        </option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-activity me-1"></i>Status
                    </label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>
                            Overdue
                        </option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar me-1"></i>Member Since
                    </label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" id="dateFromFilter">
                </div>
                <div class="col-lg-1 col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Apply
                        </button>
                    </div>
                </div>
            </form>

            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset All
                        </a>
                        @if(request()->hasAny(['search', 'role', 'status', 'date_from']))
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Filters applied •
                                <span class="fw-semibold">{{ $users->total() }}</span> users found
                            </div>
                        @endif
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        @if(request()->hasAny(['search', 'role', 'status', 'date_from']))
                            <div class="text-muted small me-2">
                                <span id="activeFiltersCount">0</span> filter(s) active
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Users Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-table me-2 text-primary"></i>Registered Users
                    <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                </h5>

            </div>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">#</th>
                                <th class="border-0 fw-semibold">User</th>
                                <th class="border-0 fw-semibold">Contact</th>
                                <th class="border-0 fw-semibold">Role</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Activity</th>
                                <th class="border-0 fw-semibold">Joined</th>
                                <th class="border-0 fw-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="user-row" data-user-id="{{ $user->id }}">
                                    <td class="fw-semibold text-muted">
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" id="avatar-{{ $user->id }}">
                                                @if($user->profile_photo)
                                                    <img src="{{ $user->profile_photo_url }}"
                                                         alt="{{ $user->name }}"
                                                         class="rounded-circle"
                                                         width="40"
                                                         height="40"
                                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=003366&color=fff&size=128';">
                                                @else
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-semibold">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->student_id ?? 'No ID' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $user->email }}</span>
                                            @if($user->barcode)
                                                <small class="text-success">
                                                    <i class="bi bi-upc-scan me-1"></i>
                                                    {{ $user->barcode }}
                                                </small>
                                            @else
                                                <small class="text-muted">No RFID card</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'warning' : 'info') }} rounded-pill">
                                            <i class="bi bi-{{ $user->role === 'admin' ? 'shield-check' : ($user->role === 'teacher' ? 'person-workspace' : 'mortarboard') }} me-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $activeBorrowings = $user->borrowings->count();
                                            $overdueCount = $user->borrowings->where('due_date', '<', now())->count();
                                        @endphp
                                        @if($overdueCount > 0)
                                            <span class="badge bg-danger rounded-pill" title="{{ $overdueCount }} overdue books">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Overdue
                                            </span>
                                        @elseif($activeBorrowings > 0)
                                            <span class="badge bg-success rounded-pill" title="{{ $activeBorrowings }} active borrowings">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-semibold text-primary">{{ $user->borrowings->count() }}</span>
                                            <small class="text-muted d-block">books</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <small class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</small>
                                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.users.borrow_for_user', $user->id) }}">
                                                            <i class="fas fa-book me-2"></i>Borrow Books
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.users.view_history', $user->id) }}">
                                                            <i class="fas fa-history me-2"></i>View History
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <li>
                                                        <button class="dropdown-item text-warning" type="button"
                                                                onclick="editStudentId({{ $user->id }}, '{{ $user->student_id }}', '{{ $user->name }}')">
                                                            <i class="fas fa-edit me-2"></i>Edit Student ID
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <div>
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h4 class="text-muted">No users found</h4>
                        <p class="text-muted mb-3">Try adjusting your search or filter criteria.</p>
                        <button class="btn btn-primary" onclick="clearAllFilters()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Clear All Filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<style>
/* Custom styles for enhanced UI */
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.user-avatar img {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.user-avatar:hover img {
    border-color: #007bff;
    transform: scale(1.05);
}

.empty-state {
    padding: 3rem 2rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.user-row {
    transition: all 0.2s ease;
}

.user-row:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}

/* Loading animation */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Enhanced form controls */
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .avatar-circle {
        width: 50px;
        height: 50px;
    }

    .user-avatar img {
        width: 35px;
        height: 35px;
    }
}

/* Animation for new rows */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.new-row {
    animation: fadeInUp 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeUserManagement();
});

function initializeUserManagement() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter toggle functionality
    const filterToggle = document.getElementById('filterToggle');
    const filterCollapse = document.getElementById('filterCollapse');

    if (filterToggle && filterCollapse) {
        filterToggle.addEventListener('click', function() {
            const isExpanded = filterCollapse.classList.contains('show');
            if (isExpanded) {
                filterToggle.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Show Filters';
            } else {
                filterToggle.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Hide Filters';
            }
            updateActiveFilterCount();
        });
    }

    // Auto-submit search on typing (debounced)
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 800);
        });
    }

    // Update active filter count when filters change
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('change', updateActiveFilterCount);
    }

    // Initialize active filter count
    updateActiveFilterCount();





    // Add loading states to buttons
    document.querySelectorAll('[data-loading]').forEach(button => {
        button.addEventListener('click', function() {
            showButtonLoading(this);
        });
    });
}

function updateActiveFilterCount() {
    const activeFiltersCount = document.getElementById('activeFiltersCount');
    if (!activeFiltersCount) return;

    let count = 0;
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFromFilter = document.getElementById('dateFromFilter');

    if (searchInput && searchInput.value.trim()) count++;
    if (roleFilter && roleFilter.value) count++;
    if (statusFilter && statusFilter.value) count++;
    if (dateFromFilter && dateFromFilter.value) count++;

    activeFiltersCount.textContent = count;

    // Update toggle button text based on filter state
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle && count > 0) {
        filterToggle.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Hide Filters';
    }
}

function editStudentId(userId, currentStudentId, userName) {
    // Create a more user-friendly modal for editing
    const modalHtml = `
        <div class="modal fade" id="studentIdModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil me-2"></i>Edit Student ID
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Editing Student ID for: <strong>${userName}</strong></p>
                        <div class="mb-3">
                            <label class="form-label">Current Student ID:</label>
                            <input type="text" class="form-control" value="${currentStudentId || 'Not set'}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Student ID:</label>
                            <input type="text" class="form-control" id="newStudentId" placeholder="Enter new Student ID (leave empty to remove)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="confirmStudentIdUpdate(${userId}, '${userName}')">
                            <i class="bi bi-check me-1"></i>Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('studentIdModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('studentIdModal'));
    modal.show();

    // Focus on input
    setTimeout(() => {
        document.getElementById('newStudentId').focus();
    }, 500);
}

function confirmStudentIdUpdate(userId, userName) {
    const newStudentId = document.getElementById('newStudentId').value.trim();

    if (newStudentId === '') {
        updateStudentId(userId, null, userName);
    } else {
        updateStudentId(userId, newStudentId, userName);
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('studentIdModal'));
    modal.hide();
}

function updateStudentId(userId, newStudentId, userName) {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Updating...';
    button.disabled = true;

    // Make AJAX request to update student ID
    fetch('{{ route("admin.users.update_student_id") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId,
            student_id: newStudentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `✅ Student ID updated successfully for ${userName}`);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', `❌ Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', '❌ Failed to update Student ID. Please try again.');
    })
    .finally(() => {
        // Reset button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}





function refreshData() {
    showAlert('info', '<i class="bi bi-arrow-clockwise me-1"></i>Refreshing data...');
    window.location.reload();
}

function refreshTable() {
    const table = document.getElementById('usersTable');
    table.classList.add('loading');

    setTimeout(() => {
        table.classList.remove('loading');
        showAlert('success', '<i class="bi bi-check-circle me-1"></i>Table refreshed!');
    }, 1000);
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterForm').submit();
}

function clearAllFilters() {
    window.location.href = '{{ route('admin.users.index') }}';
}

function showButtonLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Loading...';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}



function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert.position-fixed');
    existingAlerts.forEach(alert => alert.remove());

    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Add to page
    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds (unless it's an error)
    if (type !== 'danger') {
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Enhanced keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // ESC key to close any open alerts
    if (e.key === 'Escape') {
        const alerts = document.querySelectorAll('.alert.position-fixed');
        alerts.forEach(alert => alert.remove());
    }

    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }

    // Ctrl/Cmd + R to refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshData();
    }
});
</script>
