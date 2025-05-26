@extends('admin.layouts.app')

@section('title', 'Contact Messages') {{-- Translated title --}}

@section('content')
<div class="container-fluid">
    {{-- Translated comment --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Manage Contact Messages</h6> {{-- Translated card header --}}
            <div>
                {{-- Translated comment --}}
                <form action="{{ route('admin.contacts.index') }}" method="GET" class="d-inline-block">
                    <div class="input-group">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Messages</option> {{-- Translated option --}}
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option> {{-- Translated option --}}
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option> {{-- Translated option --}}
                            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option> {{-- Translated option --}}
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="submit">Filter</button> {{-- Translated button --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th> {{-- Translated header --}}
                            <th>Name</th> {{-- Translated header --}}
                            <th>Email</th> {{-- Translated header --}}
                            <th>Subject</th> {{-- Translated header --}}
                            <th>Status</th> {{-- Translated header --}}
                            <th>Sent Date</th> {{-- Translated header --}}
                            <th>Actions</th> {{-- Translated header --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ $message->status == 'unread' ? 'font-weight-bold bg-light' : '' }}">
                            <td>{{ $message->id }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ Str::limit($message->subject, 30) }}</td>
                            <td>
                                @if($message->status == 'unread')
                                    <span class="badge badge-danger">Unread</span> {{-- Translated badge text --}}
                                @elseif($message->status == 'read')
                                    <span class="badge badge-warning">Read</span> {{-- Translated badge text --}}
                                @else
                                    <span class="badge badge-success">Replied</span> {{-- Translated badge text --}}
                                @endif
                            </td>
                            <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-sm btn-outline-secondary" title="View"> {{-- Translated button text and added title --}}
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-confirm" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this message?');"> {{-- Translated tooltip and confirmation --}}
                                        <i class="fas fa-trash"></i> Delete {{-- Translated button text --}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No messages available.</td> {{-- Translated empty state message --}}
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Enable Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Delete confirmation
        $('.delete-confirm').click(function(e) {
            if (!confirm('Are you sure you want to delete this message?')) { {{-- Confirmation message already in English --}}
                e.preventDefault();
            }
        });

        // If you want to use DataTables
        // You might need to disable Laravel's pagination if you use DataTables
        // $('#dataTable').DataTable({
        //     "language": {
        //         "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json" {{-- Changed from Arabic.json to English.json --}}
        //     }
        // });
    });
</script>
@endsection
