@extends('master.layout')

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>User Management</strong></h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            + Add User
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Matric / Staff No.</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->matric_or_staff_no ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'officer' ? 'warning' : 'primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            {{-- Edit Button --}}
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal{{ $user->id }}">
                                Edit
                            </button>

                            {{-- Delete Button --}}
                            <form method="POST"
                                action="{{ route('admin.users.destroy', $user->id) }}"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Matric / Staff No.</label>
                                            <input type="text" name="matric_or_staff_no" class="form-control"
                                                value="{{ $user->matric_or_staff_no }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password <span class="text-muted">(leave blank to keep current)</span></label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select name="role" class="form-select" required>
                                                <option value="admin" {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                                                <option value="officer" {{ $user->role === 'officer' ? 'selected' : '' }}>Officer</option>
                                                <option value="user" {{ $user->role === 'user'    ? 'selected' : '' }}>User</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="is_active" class="form-select">
                                                <option value="1" {{ $user->is_active  ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Edit Modal --}}

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matric / Staff No.</label>
                        <input type="text" name="matric_or_staff_no"
                            class="form-control @error('matric_or_staff_no') is-invalid @enderror"
                            value="{{ old('matric_or_staff_no') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="" disabled selected>Select role</option>
                            <option value="admin" {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="officer" {{ old('role') === 'officer' ? 'selected' : '' }}>Officer</option>
                            <option value="user" {{ old('role') === 'user'    ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') === '0'      ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Re-open add modal if validation failed --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addUserModal')).show();
    });
</script>
@endif

@endsection