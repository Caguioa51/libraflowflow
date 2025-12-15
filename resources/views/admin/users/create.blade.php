@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                    <i class="bi bi-people-fill me-1"></i>User Management
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-person-plus me-1"></i>Create New User
                            </li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="bi bi-person-plus me-2 text-primary"></i>Create New User
                    </h1>
                    <p class="text-muted mb-0 mt-1">Register a new user in the library system</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Create User Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-form me-2 text-primary"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person me-2"></i>Personal Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus
                                       placeholder="Enter full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="student_id" class="form-label fw-semibold">
                                    Student ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id') }}" 
                                       required
                                       placeholder="Enter student ID">
                                <div class="form-text">Unique identifier for the user</div>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">
                                    User Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Select a role</option>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>
                                        Student
                                    </option>
                                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>
                                        Teacher
                                    </option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                </select>
                                <div class="form-text">Determines user permissions and access levels</div>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-lock me-2"></i>Security Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password')"
                                            id="passwordToggle">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimum 8 characters</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password_confirmation')"
                                            id="confirmPasswordToggle">
                                        <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Must match the password above</div>
                            </div>

                            <!-- Auto-generated Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2"></i>System Information
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Auto-generated Information:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Barcode:</strong> Will be automatically generated as "STUDENT-{Student ID}"</li>
                                        <li><strong>Email Verification:</strong> User will need to verify their email before first login</li>
                                        <li><strong>Account Status:</strong> Account will be active immediately after creation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="previewUser()">
                                            <i class="bi bi-eye me-1"></i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="bi bi-check-circle me-1"></i>Create User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>User Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 80px; height: 80px;">
                            <span class="text-white fw-bold fs-4" id="previewAvatar">U</span>
                        </div>
                        <h6 class="fw-bold" id="previewName">User Name</h6>
                        <span class="badge bg-primary" id="previewRole">Role</span>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td id="previewEmail">email@example.com</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Student ID:</td>
                                <td id="previewStudentId">STUDENT123</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Barcode:</td>
                                <td id="previewBarcode">STUDENT-STUDENT123</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Created:</td>
                                <td>{{ now()->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="bi bi-check me-1"></i>Create This User
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Custom styles for enhanced UI */
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #007bff !important;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.input-group .btn {
    border-radius: 0 0.375rem 0.375rem 0;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.table td {
    border-color: #f1f3f4;
}

.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate barcode preview
    const studentIdInput = document.getElementById('student_id');
    const barcodePreview = document.getElementById('previewBarcode');
    
    studentIdInput.addEventListener('input', function() {
        if (this.value) {
            barcodePreview.textContent = 'STUDENT-' + this.value;
        } else {
            barcodePreview.textContent = 'STUDENT-{Student ID}';
        }
    });

    // Form validation
    const form = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Creating...';
        submitBtn.disabled = true;
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Passwords do not match');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePassword);
    passwordConfirmation.addEventListener('input', validatePassword);
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'Icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function previewUser() {
    const formData = new FormData(document.getElementById('createUserForm'));
    
    // Update preview with form data
    document.getElementById('previewName').textContent = formData.get('name') || 'User Name';
    document.getElementById('previewEmail').textContent = formData.get('email') || 'email@example.com';
    document.getElementById('previewStudentId').textContent = formData.get('student_id') || 'STUDENT123';
    document.getElementById('previewRole').textContent = (formData.get('role') || 'student').charAt(0).toUpperCase() + (formData.get('role') || 'student').slice(1);
    
    const name = formData.get('name') || 'U';
    document.getElementById('previewAvatar').textContent = name.charAt(0).toUpperCase();
    
    const studentId = formData.get('student_id') || 'STUDENT123';
    document.getElementById('previewBarcode').textContent = 'STUDENT-' + studentId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function submitForm() {
    document.getElementById('createUserForm').submit();
}

// Real-time validation feedback
document.querySelectorAll('.form-control, .form-select').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
    
    input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') && this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});
