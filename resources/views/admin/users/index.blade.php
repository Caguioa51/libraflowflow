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
                <div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Create New User
                    </a>
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
                                                    <li>
                                                        <button class="dropdown-item text-info" type="button"
                                                                onclick="editRfid({{ $user->id }}, '{{ $user->barcode }}', '{{ $user->name }}')">
                                                            <i class="fas fa-id-card me-2"></i>Edit RFID Card
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

 
 < ! - -   E d i t   S t u d e n t   I D   M o d a l   - - > 
 < d i v   c l a s s = " m o d a l   f a d e "   i d = " e d i t S t u d e n t I d M o d a l "   t a b i n d e x = " - 1 " > 
         < d i v   c l a s s = " m o d a l - d i a l o g " > 
                 < d i v   c l a s s = " m o d a l - c o n t e n t " > 
                         < d i v   c l a s s = " m o d a l - h e a d e r " > 
                                 < h 5   c l a s s = " m o d a l - t i t l e " > 
                                         < i   c l a s s = " b i   b i - p e n c i l - s q u a r e   m e - 2 " > < / i > E d i t   S t u d e n t   I D 
                                 < / h 5 > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n - c l o s e "   d a t a - b s - d i s m i s s = " m o d a l " > < / b u t t o n > 
                         < / d i v > 
                         < d i v   c l a s s = " m o d a l - b o d y " > 
                                 < f o r m   i d = " e d i t S t u d e n t I d F o r m " > 
                                         < i n p u t   t y p e = " h i d d e n "   i d = " s t u d e n t I d U s e r I d "   n a m e = " u s e r _ i d " > 
                                         < d i v   c l a s s = " m b - 3 " > 
                                                 < l a b e l   c l a s s = " f o r m - l a b e l   f w - s e m i b o l d " > 
                                                         < i   c l a s s = " b i   b i - p e r s o n   m e - 1 " > < / i > U s e r   N a m e 
                                                 < / l a b e l > 
                                                 < i n p u t   t y p e = " t e x t "   c l a s s = " f o r m - c o n t r o l "   i d = " s t u d e n t I d U s e r N a m e "   r e a d o n l y > 
                                         < / d i v > 
                                         < d i v   c l a s s = " m b - 3 " > 
                                                 < l a b e l   f o r = " s t u d e n t I d I n p u t "   c l a s s = " f o r m - l a b e l   f w - s e m i b o l d " > 
                                                         S t u d e n t   I D   < s p a n   c l a s s = " t e x t - d a n g e r " > * < / s p a n > 
                                                 < / l a b e l > 
                                                 < i n p u t   t y p e = " t e x t "   c l a s s = " f o r m - c o n t r o l "   i d = " s t u d e n t I d I n p u t "   n a m e = " s t u d e n t _ i d "   r e q u i r e d > 
                                                 < d i v   c l a s s = " f o r m - t e x t " > E n t e r   t h e   n e w   s t u d e n t   I D   f o r   t h i s   u s e r < / d i v > 
                                         < / d i v > 
                                 < / f o r m > 
                         < / d i v > 
                         < d i v   c l a s s = " m o d a l - f o o t e r " > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n   b t n - s e c o n d a r y "   d a t a - b s - d i s m i s s = " m o d a l " > C a n c e l < / b u t t o n > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n   b t n - w a r n i n g "   o n c l i c k = " u p d a t e S t u d e n t I d ( ) " > 
                                         < i   c l a s s = " b i   b i - c h e c k - c i r c l e   m e - 1 " > < / i > U p d a t e   S t u d e n t   I D 
                                 < / b u t t o n > 
                         < / d i v > 
                 < / d i v > 
         < / d i v > 
 < / d i v > 
 
 < ! - -   E d i t   R F I D   C a r d   M o d a l   - - > 
 < d i v   c l a s s = " m o d a l   f a d e "   i d = " e d i t R f i d M o d a l "   t a b i n d e x = " - 1 " > 
         < d i v   c l a s s = " m o d a l - d i a l o g " > 
                 < d i v   c l a s s = " m o d a l - c o n t e n t " > 
                         < d i v   c l a s s = " m o d a l - h e a d e r " > 
                                 < h 5   c l a s s = " m o d a l - t i t l e " > 
                                         < i   c l a s s = " b i   b i - c r e d i t - c a r d   m e - 2 " > < / i > E d i t   R F I D   C a r d 
                                 < / h 5 > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n - c l o s e "   d a t a - b s - d i s m i s s = " m o d a l " > < / b u t t o n > 
                         < / d i v > 
                         < d i v   c l a s s = " m o d a l - b o d y " > 
                                 < f o r m   i d = " e d i t R f i d F o r m " > 
                                         < i n p u t   t y p e = " h i d d e n "   i d = " r f i d U s e r I d "   n a m e = " u s e r _ i d " > 
                                         < d i v   c l a s s = " m b - 3 " > 
                                                 < l a b e l   c l a s s = " f o r m - l a b e l   f w - s e m i b o l d " > 
                                                         < i   c l a s s = " b i   b i - p e r s o n   m e - 1 " > < / i > U s e r   N a m e 
                                                 < / l a b e l > 
                                                 < i n p u t   t y p e = " t e x t "   c l a s s = " f o r m - c o n t r o l "   i d = " r f i d U s e r N a m e "   r e a d o n l y > 
                                         < / d i v > 
                                         < d i v   c l a s s = " m b - 3 " > 
                                                 < l a b e l   f o r = " r f i d I n p u t "   c l a s s = " f o r m - l a b e l   f w - s e m i b o l d " > 
                                                         R F I D   C a r d   N u m b e r 
                                                 < / l a b e l > 
                                                 < d i v   c l a s s = " i n p u t - g r o u p " > 
                                                         < i n p u t   t y p e = " t e x t "   c l a s s = " f o r m - c o n t r o l "   i d = " r f i d I n p u t "   n a m e = " r f i d _ c a r d " > 
                                                         < b u t t o n   c l a s s = " b t n   b t n - o u t l i n e - s e c o n d a r y "   t y p e = " b u t t o n "   o n c l i c k = " g e n e r a t e R f i d ( ) " > 
                                                                 < i   c l a s s = " b i   b i - m a g i c " > < / i > 
                                                         < / b u t t o n > 
                                                 < / d i v > 
                                                 < d i v   c l a s s = " f o r m - t e x t " > E n t e r   R F I D   c a r d   n u m b e r   o r   l e a v e   e m p t y   t o   r e m o v e < / d i v > 
                                         < / d i v > 
                                 < / f o r m > 
                         < / d i v > 
                         < d i v   c l a s s = " m o d a l - f o o t e r " > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n   b t n - s e c o n d a r y "   d a t a - b s - d i s m i s s = " m o d a l " > C a n c e l < / b u t t o n > 
                                 < b u t t o n   t y p e = " b u t t o n "   c l a s s = " b t n   b t n - i n f o "   o n c l i c k = " u p d a t e R f i d ( ) " > 
                                         < i   c l a s s = " b i   b i - c h e c k - c i r c l e   m e - 1 " > < / i > U p d a t e   R F I D   C a r d 
                                 < / b u t t o n > 
                         < / d i v > 
                 < / d i v > 
         < / d i v > 
 < / d i v > 
 < / d i v > 
 
 < s c r i p t > 
 d o c u m e n t . a d d E v e n t L i s t e n e r ( " D O M C o n t e n t L o a d e d " ,   f u n c t i o n ( )   { 
         / /   C l e a r   s e a r c h   f u n c t i o n a l i t y 
         w i n d o w . c l e a r S e a r c h   =   f u n c t i o n ( )   { 
                 d o c u m e n t . g e t E l e m e n t B y I d ( " s e a r c h I n p u t " ) . v a l u e   =   " " ; 
                 d o c u m e n t . g e t E l e m e n t B y I d ( " f i l t e r F o r m " ) . s u b m i t ( ) ; 
         } ; 
 
         / /   C l e a r   a l l   f i l t e r s 
         w i n d o w . c l e a r A l l F i l t e r s   =   f u n c t i o n ( )   { 
                 w i n d o w . l o c a t i o n . h r e f   =   " { {   r o u t e ( " a d m i n . u s e r s . i n d e x " )   } } " ; 
         } ; 
 } ) ; 
 
 / /   E d i t   S t u d e n t   I D   f u n c t i o n 
 w i n d o w . e d i t S t u d e n t I d   =   f u n c t i o n ( u s e r I d ,   c u r r e n t S t u d e n t I d ,   u s e r N a m e )   { 
         d o c u m e n t . g e t E l e m e n t B y I d ( " s t u d e n t I d U s e r I d " ) . v a l u e   =   u s e r I d ; 
         d o c u m e n t . g e t E l e m e n t B y I d ( " s t u d e n t I d U s e r N a m e " ) . v a l u e   =   u s e r N a m e ; 
         d o c u m e n t . g e t E l e m e n t B y I d ( " s t u d e n t I d I n p u t " ) . v a l u e   =   c u r r e n t S t u d e n t I d   | |   " " ; 
         
         c o n s t   m o d a l   =   n e w   b o o t s t r a p . M o d a l ( d o c u m e n t . g e t E l e m e n t B y I d ( " e d i t S t u d e n t I d M o d a l " ) ) ; 
         m o d a l . s h o w ( ) ; 
 } ; 
 
 / /   E d i t   R F I D   C a r d   f u n c t i o n 
 w i n d o w . e d i t R f i d   =   f u n c t i o n ( u s e r I d ,   c u r r e n t R f i d ,   u s e r N a m e )   { 
         d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d U s e r I d " ) . v a l u e   =   u s e r I d ; 
         d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d U s e r N a m e " ) . v a l u e   =   u s e r N a m e ; 
         d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d I n p u t " ) . v a l u e   =   c u r r e n t R f i d   | |   " " ; 
         
         c o n s t   m o d a l   =   n e w   b o o t s t r a p . M o d a l ( d o c u m e n t . g e t E l e m e n t B y I d ( " e d i t R f i d M o d a l " ) ) ; 
         m o d a l . s h o w ( ) ; 
 } ; 
 
 / /   U p d a t e   S t u d e n t   I D 
 w i n d o w . u p d a t e S t u d e n t I d   =   f u n c t i o n ( )   { 
         c o n s t   u s e r I d   =   d o c u m e n t . g e t E l e m e n t B y I d ( " s t u d e n t I d U s e r I d " ) . v a l u e ; 
         c o n s t   s t u d e n t I d   =   d o c u m e n t . g e t E l e m e n t B y I d ( " s t u d e n t I d I n p u t " ) . v a l u e ; 
         
         i f   ( ! s t u d e n t I d . t r i m ( ) )   { 
                 a l e r t ( " P l e a s e   e n t e r   a   s t u d e n t   I D " ) ; 
                 r e t u r n ; 
         } 
         
         c o n s t   s u b m i t B t n   =   d o c u m e n t . q u e r y S e l e c t o r ( " # e d i t S t u d e n t I d M o d a l   . b t n - w a r n i n g " ) ; 
         s u b m i t B t n . i n n e r H T M L   =   " < i   c l a s s = \ " b i   b i - h o u r g l a s s - s p l i t   m e - 1 \ " > < / i > U p d a t i n g . . . " ; 
         s u b m i t B t n . d i s a b l e d   =   t r u e ; 
         
         f e t c h ( " { {   r o u t e ( " a d m i n . u s e r s . u p d a t e _ s t u d e n t _ i d " )   } } " ,   { 
                 m e t h o d :   " P O S T " , 
                 h e a d e r s :   { 
                         " C o n t e n t - T y p e " :   " a p p l i c a t i o n / j s o n " , 
                         " X - C S R F - T O K E N " :   d o c u m e n t . q u e r y S e l e c t o r ( " m e t a [ n a m e = \ " c s r f - t o k e n \ " ] " ) . c o n t e n t 
                 } , 
                 b o d y :   J S O N . s t r i n g i f y ( { 
                         u s e r _ i d :   u s e r I d , 
                         s t u d e n t _ i d :   s t u d e n t I d 
                 } ) 
         } ) 
         . t h e n ( r e s p o n s e   = >   r e s p o n s e . j s o n ( ) ) 
         . t h e n ( d a t a   = >   { 
                 i f   ( d a t a . s u c c e s s )   { 
                         a l e r t ( d a t a . m e s s a g e ) ; 
                         l o c a t i o n . r e l o a d ( ) ; 
                 }   e l s e   { 
                         a l e r t ( d a t a . m e s s a g e   | |   " F a i l e d   t o   u p d a t e   s t u d e n t   I D " ) ; 
                 } 
         } ) 
         . c a t c h ( e r r o r   = >   { 
                 c o n s o l e . e r r o r ( " E r r o r : " ,   e r r o r ) ; 
                 a l e r t ( " A n   e r r o r   o c c u r r e d   w h i l e   u p d a t i n g   s t u d e n t   I D " ) ; 
         } ) 
         . f i n a l l y ( ( )   = >   { 
                 s u b m i t B t n . i n n e r H T M L   =   " < i   c l a s s = \ " b i   b i - c h e c k - c i r c l e   m e - 1 \ " > < / i > U p d a t e   S t u d e n t   I D " ; 
                 s u b m i t B t n . d i s a b l e d   =   f a l s e ; 
                 b o o t s t r a p . M o d a l . g e t I n s t a n c e ( d o c u m e n t . g e t E l e m e n t B y I d ( " e d i t S t u d e n t I d M o d a l " ) ) . h i d e ( ) ; 
         } ) ; 
 } ; 
 
 / /   U p d a t e   R F I D   C a r d 
 w i n d o w . u p d a t e R f i d   =   f u n c t i o n ( )   { 
         c o n s t   u s e r I d   =   d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d U s e r I d " ) . v a l u e ; 
         c o n s t   r f i d C a r d   =   d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d I n p u t " ) . v a l u e ; 
         
         c o n s t   s u b m i t B t n   =   d o c u m e n t . q u e r y S e l e c t o r ( " # e d i t R f i d M o d a l   . b t n - i n f o " ) ; 
         s u b m i t B t n . i n n e r H T M L   =   " < i   c l a s s = \ " b i   b i - h o u r g l a s s - s p l i t   m e - 1 \ " > < / i > U p d a t i n g . . . " ; 
         s u b m i t B t n . d i s a b l e d   =   t r u e ; 
         
         f e t c h ( " { {   r o u t e ( " a d m i n . u s e r s . u p d a t e _ r f i d " )   } } " ,   { 
                 m e t h o d :   " P O S T " , 
                 h e a d e r s :   { 
                         " C o n t e n t - T y p e " :   " a p p l i c a t i o n / j s o n " , 
                         " X - C S R F - T O K E N " :   d o c u m e n t . q u e r y S e l e c t o r ( " m e t a [ n a m e = \ " c s r f - t o k e n \ " ] " ) . c o n t e n t 
                 } , 
                 b o d y :   J S O N . s t r i n g i f y ( { 
                         u s e r _ i d :   u s e r I d , 
                         r f i d _ c a r d :   r f i d C a r d 
                 } ) 
         } ) 
         . t h e n ( r e s p o n s e   = >   r e s p o n s e . j s o n ( ) ) 
         . t h e n ( d a t a   = >   { 
                 i f   ( d a t a . s u c c e s s )   { 
                         a l e r t ( d a t a . m e s s a g e ) ; 
                         l o c a t i o n . r e l o a d ( ) ; 
                 }   e l s e   { 
                         a l e r t ( d a t a . m e s s a g e   | |   " F a i l e d   t o   u p d a t e   R F I D   c a r d " ) ; 
                 } 
         } ) 
         . c a t c h ( e r r o r   = >   { 
                 c o n s o l e . e r r o r ( " E r r o r : " ,   e r r o r ) ; 
                 a l e r t ( " A n   e r r o r   o c c u r r e d   w h i l e   u p d a t i n g   R F I D   c a r d " ) ; 
         } ) 
         . f i n a l l y ( ( )   = >   { 
                 s u b m i t B t n . i n n e r H T M L   =   " < i   c l a s s = \ " b i   b i - c h e c k - c i r c l e   m e - 1 \ " > < / i > U p d a t e   R F I D   C a r d " ; 
                 s u b m i t B t n . d i s a b l e d   =   f a l s e ; 
                 b o o t s t r a p . M o d a l . g e t I n s t a n c e ( d o c u m e n t . g e t E l e m e n t B y I d ( " e d i t R f i d M o d a l " ) ) . h i d e ( ) ; 
         } ) ; 
 } ; 
 
 / /   G e n e r a t e   R F I D   f u n c t i o n 
 w i n d o w . g e n e r a t e R f i d   =   f u n c t i o n ( )   { 
         c o n s t   r f i d I n p u t   =   d o c u m e n t . g e t E l e m e n t B y I d ( " r f i d I n p u t " ) ; 
         c o n s t   t i m e s t a m p   =   D a t e . n o w ( ) . t o S t r i n g ( ) . s l i c e ( - 8 ) ; 
         c o n s t   r a n d o m   =   M a t h . f l o o r ( M a t h . r a n d o m ( )   *   1 0 0 0 ) . t o S t r i n g ( ) . p a d S t a r t ( 3 ,   " 0 " ) ; 
         r f i d I n p u t . v a l u e   =   " R F I D "   +   t i m e s t a m p   +   r a n d o m ; 
 } ; 
 < / s c r i p t > 
 @ e n d s e c t i o n 
  
 