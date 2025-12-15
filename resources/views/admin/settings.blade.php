@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-cogs"></i> System Settings</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Library Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="borrowing_duration_days" class="form-label">Default Borrowing Period (Days)</label>
                                <input type="number" class="form-control" id="borrowing_duration_days"
                                       name="borrowing_duration_days"
                                       value="{{ $settings['borrowing_duration_days']->value ?? 14 }}"
                                       min="1" max="365" required>
                                <div class="form-text">Standard loan period for books (students can return earlier)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_renewals" class="form-label">Maximum Renewals Allowed</label>
                                <input type="number" class="form-control" id="max_renewals"
                                       name="max_renewals"
                                       value="{{ $settings['max_renewals']->value ?? 2 }}"
                                       min="0" max="10" required>
                                <div class="form-text">How many times a book can be renewed before it must be returned</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fine_per_day" class="form-label">Overdue Fine Rate (₱ per day)</label>
                                <input type="number" class="form-control" id="fine_per_day"
                                       name="fine_per_day"
                                       value="{{ $settings['fine_per_day']->value ?? 5.00 }}"
                                       min="0" max="1000" step="0.01" required>
                                <div class="form-text">Penalty charged for each day a book is overdue</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_books_per_user" class="form-label">Maximum Books Per Student</label>
                                <input type="number" class="form-control" id="max_books_per_user"
                                       name="max_books_per_user"
                                       value="{{ $settings['max_books_per_user']->value ?? 3 }}"
                                       min="1" max="20" required>
                                <div class="form-text">Maximum number of books a student can borrow simultaneously</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="grace_period_days" class="form-label">Grace Period (Days)</label>
                                <input type="number" class="form-control" id="grace_period_days"
                                       name="grace_period_days"
                                       value="{{ $settings['grace_period_days']->value ?? 3 }}"
                                       min="0" max="30" required>
                                <div class="form-text">Days after due date before fines start accruing</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_overdue_days" class="form-label">Maximum Overdue Days</label>
                                <input type="number" class="form-control" id="max_overdue_days"
                                       name="max_overdue_days"
                                       value="{{ $settings['max_overdue_days']->value ?? 30 }}"
                                       min="1" max="365" required>
                                <div class="form-text">Maximum days a book can be overdue before special action is required</div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weekend_due_dates" class="form-label">Weekend Due Dates</label>
                                <select class="form-control" id="weekend_due_dates" name="weekend_due_dates" required>
                                    <option value="allow" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'allow' ? 'selected' : '' }}>Allow due dates on weekends</option>
                                    <option value="move_to_monday" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'move_to_monday' ? 'selected' : '' }}>Move weekend due dates to Monday</option>
                                    <option value="move_to_friday" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'move_to_friday' ? 'selected' : '' }}>Move weekend due dates to Friday</option>
                                </select>
                                <div class="form-text">How to handle due dates that fall on weekends</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="holiday_handling" class="form-label">Holiday Due Dates</label>
                                <select class="form-control" id="holiday_handling" name="holiday_handling" required>
                                    <option value="extend" {{ ($settings['holiday_handling']->value ?? 'extend') === 'extend' ? 'selected' : '' }}>Extend due date past holidays</option>
                                    <option value="strict" {{ ($settings['holiday_handling']->value ?? 'extend') === 'strict' ? 'selected' : '' }}>Keep original due date</option>
                                </select>
                                <div class="form-text">How to handle due dates that fall on school holidays</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="self_service_enabled" value="false">
                                    <input class="form-check-input" type="checkbox" id="self_service_enabled"
                                           name="self_service_enabled" value="true"
                                           {{ ($settings['self_service_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="self_service_enabled">
                                        Enable Self-Service Checkout
                                    </label>
                                    <div class="form-text">Allow students to borrow books without librarian assistance</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="email_notifications_enabled" value="false">
                                    <input class="form-check-input" type="checkbox" id="email_notifications_enabled"
                                           name="email_notifications_enabled" value="true"
                                           {{ ($settings['email_notifications_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications_enabled">
                                        Enable Email Notifications
                                    </label>
                                    <div class="form-text">Send email notifications for overdue books</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Settings:</strong>
                        <ul class="list-unstyled mt-2">
                            <li><i class="fas fa-calendar text-primary"></i> Borrowing Duration: {{ $settings['borrowing_duration_days']->value ?? 14 }} days</li>
                            <li><i class="fas fa-redo text-info"></i> Max Renewals: {{ $settings['max_renewals']->value ?? 2 }}</li>
                            <li><i class="fas fa-dollar-sign text-warning"></i> Fine Rate: ₱{{ number_format($settings['fine_per_day']->value ?? 5.00, 2) }}/day</li>
                            <li><i class="fas fa-book text-success"></i> Max Books/User: {{ $settings['max_books_per_user']->value ?? 3 }}</li>
                            <li><i class="fas fa-clock text-secondary"></i> Grace Period: {{ $settings['grace_period_days']->value ?? 3 }} days</li>
                            <li><i class="fas fa-exclamation-triangle text-danger"></i> Max Overdue: {{ $settings['max_overdue_days']->value ?? 30 }} days</li>
                            <li><i class="fas fa-calendar-alt text-info"></i> Weekend Due Dates: {{ ucwords(str_replace('_', ' ', $settings['weekend_due_dates']->value ?? 'move_to_monday')) }}</li>
                            <li><i class="fas fa-plane text-warning"></i> Holiday Handling: {{ ucfirst($settings['holiday_handling']->value ?? 'extend') }}</li>
                            <li><i class="fas fa-shopping-cart text-secondary"></i> Self-Service: {{ ($settings['self_service_enabled']->value ?? 'true') === 'true' ? 'Enabled' : 'Disabled' }}</li>
                            <li><i class="fas fa-envelope text-danger"></i> Email Notifications: {{ ($settings['email_notifications_enabled']->value ?? 'true') === 'true' ? 'Enabled' : 'Disabled' }}</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Changes to these settings will affect all users immediately.
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('borrowings.update_fines') }}" class="btn btn-outline-warning"
                           onclick="return confirm('Update fines for all overdue books?')">
                            <i class="fas fa-calculator"></i> Update All Fines
                        </a>
                        {{-- Analytics removed --}}
                        <a href="{{ route('borrowings.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const saveBtn = document.getElementById('saveBtn');

    form.addEventListener('submit', function(e) {
        // Prevent default form submission to handle it manually
        e.preventDefault();

        // Add loading state to button
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        // Basic client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Settings';
            saveBtn.disabled = false;
            alert('Please fill in all required fields.');
            return false;
        }

        // Manually submit the form to prevent duplicate submissions
        form.submit();
    });

    // Reset button functionality
    const resetBtn = document.querySelector('a[href*="admin/settings"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            if (form.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"])').some(input => input.value !== input.defaultValue) ||
                form.querySelectorAll('select').some(select => select.value !== select.querySelector('option[selected]')?.value) ||
                form.querySelectorAll('input[type="checkbox"]').some(checkbox => checkbox.checked !== checkbox.defaultChecked)) {

                if (!confirm('You have unsaved changes. Are you sure you want to reset?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }

    // Real-time validation feedback
    form.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection
