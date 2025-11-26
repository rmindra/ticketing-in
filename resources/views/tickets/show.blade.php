@extends('layouts.app')

@section('main')
<div class="container-fluid">
    <!-- Ticket Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-1">{{ $ticket->title }}</h3>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge badge-{{ $ticket->status == 'Open' ? 'success' : ($ticket->status == 'In Progress' ? 'warning' : ($ticket->status == 'Resolved' ? 'info' : 'secondary')) }}">
                                    {{ $ticket->status }}
                                </span>
                                <span class="badge badge-{{ $ticket->priority == 'High' ? 'danger' : ($ticket->priority == 'Medium' ? 'warning' : 'success') }}">
                                    {{ $ticket->priority }} Priority
                                </span>
                                <small class="text-muted">Created: {{ $ticket->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            @if($ticket->isResolved() && !$ticket->isFullyConfirmed())
                                @php
                                    $user = auth()->user();
                                @endphp

                                @if($user->isAdmin() && !$ticket->isAdminConfirmed())
                                    <form action="{{ route('tickets.confirm-resolution-admin', $ticket) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check mr-2"></i>Confirm Resolution (Admin)
                                        </button>
                                    </form>
                                @elseif($ticket->canBeConfirmedByUser($user->id))
                                    <form action="{{ route('tickets.confirm-resolution', $ticket) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check mr-2"></i>Confirm Resolution
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ticket Details -->
        <div class="col-lg-8">
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📋 Description</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ticket->description }}</p>
                </div>
            </div>

           {{-- Bagian komentar --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Komentar</h5>
                </div>
                <div class="card-body">
                    {{-- List komentar --}}
                    @foreach($ticket->comments as $comment)
                        <div class="d-flex mb-4 {{ $comment->isFromAdmin() ? 'bg-light-primary' : 'bg-light' }} p-3 rounded comment-item" data-comment-id="{{ $comment->id }}">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm rounded-circle bg-primary text-white">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        {{ $comment->user->name }}
                                        @if($comment->isFromAdmin())
                                            <span class="badge bg-danger ms-1">Admin</span>
                                        @endif
                                        @if($comment->isSystemMessage())
                                            <span class="badge bg-secondary ms-1">System</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 mt-2 comment-content">{!! $comment->getDisplayContent() !!}</p>

                                @php $user = auth()->user(); @endphp
                                @if($user && ($user->isAdmin() || $user->id === $comment->user_id))
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-secondary btn-edit-comment" data-id="{{ $comment->id }}">Edit</button>
                                        <form action="{{ route('tickets.comments.destroy', [$ticket, $comment]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus komentar ini?')">Delete</button>
                                        </form>
                                    </div>

                                    {{-- Hidden edit form --}}
                                    <form action="{{ route('tickets.comments.update', [$ticket, $comment]) }}" method="POST" class="mt-2 d-none edit-form" id="edit-form-{{ $comment->id }}">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="content" class="form-control" rows="3">{{ $comment->content }}</textarea>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-primary">Save</button>
                                            <button type="button" class="btn btn-sm btn-secondary btn-cancel-edit" data-id="{{ $comment->id }}">Cancel</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Form tambah komentar --}}
                    @if($ticket->canAddComments())
                        <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="form-group">
                                <label for="content" class="form-label">Tambah Komentar</label>
                                <textarea name="content" id="content" rows="3" class="form-control"
                                        placeholder="Ketik komentar Anda di sini..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
                        </form>
                    @else
                        <div class="alert alert-info mt-3">
                            Ticket sudah closed. Tidak dapat menambah komentar baru.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div class="card mt-4">
                <div class="card-body">
                    @if($ticket->isResolved())
                        @if(!$ticket->isUserConfirmed() && $ticket->isOwnedBy(auth()->id()))
                            <form action="{{ route('tickets.confirm-resolution', $ticket) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Konfirmasi Masalah Selesai</button>
                            </form>
                        @endif

                        @if(!$ticket->isAdminConfirmed() && auth()->user()->isAdmin())
                            <form action="{{ route('tickets.confirm-resolution-admin', $ticket) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Konfirmasi Penyelesaian</button>
                            </form>
                        @endif
                    @endif

                    @if(($ticket->isResolved() || $ticket->isClosed()) &&
                        ($ticket->isOwnedBy(auth()->id()) || auth()->user()->isAdmin()))
                        <form action="{{ route('tickets.reopen', $ticket) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">Buka Kembali Ticket</button>
                        </form>
                    @endif
                </div>
            </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Ticket Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Ticket Info</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <strong>Category:</strong>
                        <span class="badge badge-info">{{ $ticket->category->name }}</span>
                    </div>
                    <div class="info-item mb-3">
                        <strong>Created By:</strong>
                        <span>{{ $ticket->user->name }}</span>
                    </div>
                    @if($ticket->assigned_to)
                        <div class="info-item mb-3">
                            <strong>Assigned To:</strong>
                            <span>{{ $ticket->assignedTo->name }}</span>
                        </div>
                    @endif
                    <div class="info-item mb-3">
                        <strong>Confirmation Status:</strong>
                        <div class="mt-1">
                            <span class="badge badge-{{ $ticket->user_confirmed ? 'success' : 'warning' }}">
                                User: {{ $ticket->user_confirmed ? 'Confirmed' : 'Pending' }}
                            </span>
                            <span class="badge badge-{{ $ticket->admin_confirmed ? 'success' : 'warning' }}">
                                Admin: {{ $ticket->admin_confirmed ? 'Confirmed' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.comment-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-comment').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var form = document.getElementById('edit-form-' + id);
            if (form) {
                form.classList.remove('d-none');
            }
        });
    });

    document.querySelectorAll('.btn-cancel-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var form = document.getElementById('edit-form-' + id);
            if (form) {
                form.classList.add('d-none');
            }
        });
    });
});
</script>
@endpush
