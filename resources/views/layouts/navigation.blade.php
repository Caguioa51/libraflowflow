<nav class="navbar navbar-dark bg-dark border-bottom border-secondary py-2">
    <div class="container-fluid">
        <!-- Left side - LibraFlow logo -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand text-white fw-bold fs-6 mb-0" href="{{ route('dashboard') }}">
                <i class="bi bi-book me-2"></i>LibraFlow
            </a>
        </div>

        <!-- Right side - Navigation buttons -->
        <div class="d-flex align-items-center ms-auto">
            <ul class="navbar-nav flex-row mb-0">
                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1" href="{{ route('admin.announcements.index') }}">
                            <i class="bi bi-megaphone me-1"></i>Announcements
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1" href="{{ route('borrowings.admin_borrow') }}">
                            <i class="bi bi-person-plus me-1"></i>Borrow for User
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('admin.users.*') ? 'bg-primary rounded' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-1"></i>Users
                        </a>
                    </li>
                @endif






            </ul>

            <!-- User Account Dropdown -->
            <div class="dropdown ms-3">
                <button class="btn btn-outline-light border-0 dropdown-toggle d-flex align-items-center position-relative"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" id="userDropdown">
                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-person text-white" style="font-size: 12px;"></i>
                    </div>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    @if(!auth()->user()->isAdmin())
                        @php
                            $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->unread()->count();
                        @endphp
                        @if($unreadNotifications > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                            </span>
                        @endif
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end" style="width: 350px; max-width: 90vw;" aria-labelledby="userDropdown">
                    <div class="px-3 py-2 border-bottom">
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>

                    @if(!auth()->user()->isAdmin())
                        <!-- Notifications Section -->
                        @php
                            $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp

                        <div class="px-3 py-2 border-bottom bg-light">
                            <h6 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="bi bi-bell me-2"></i>Notifications
                                @if($unreadNotifications > 0)
                                    <span class="badge bg-danger ms-2">{{ $unreadNotifications }}</span>
                                @else
                                    <small class="text-muted ms-auto">All caught up</small>
                                @endif
                            </h6>
                        </div>

                        @if($recentNotifications->count() > 0)
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach($recentNotifications as $notification)
                                    <div class="px-3 py-2 border-bottom {{ $notification->isRead() ? '' : 'bg-primary bg-opacity-10' }}"
                                         style="cursor: pointer;"
                                         onclick="markAsRead({{ $notification->id }})">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <small class="text-{{ $notification->type === 'reminder' ? 'warning' : ($notification->type === 'overdue' ? 'danger' : 'info') }} fw-semibold">
                                                        <i class="bi bi-{{ $notification->type === 'reminder' ? 'clock' : ($notification->type === 'overdue' ? 'exclamation-triangle' : 'info-circle') }} me-1"></i>
                                                        {{ $notification->title }}
                                                    </small>
                                                    @if(!$notification->isRead())
                                                        <span class="badge bg-primary ms-2" style="font-size: 0.6rem;">New</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 small text-dark">{{ Str::limit($notification->message, 60) }}</p>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-3 py-3 text-center text-muted border-bottom">
                                <i class="bi bi-bell-slash mb-2 d-block"></i>
                                <small>No notifications yet<br>You'll receive reminders when books are due</small>
                            </div>
                        @endif

                        <div class="border-top my-1"></div>
                    @endif

                    <a class="dropdown-item" href="{{ route('settings') }}">
                        <i class="bi bi-gear me-2"></i>Account Settings
                    </a>

                    @if(auth()->user()->isAdmin())
                        <div class="border-top my-1"></div>
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="bi bi-gear-fill me-2"></i>Admin Settings
                        </a>
                    @endif

                    <div class="border-top my-1"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="logoutForm">
                        @csrf
                        <button type="button" class="dropdown-item text-danger" onclick="document.getElementById('logoutForm').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
