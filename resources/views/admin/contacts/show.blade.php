@extends('admin.layouts.app')

@section('title', 'View Message') {{-- Translated title --}}

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
            <h6 class="m-0 font-weight-bold text-primary">Message Details #{{ $contactMessage->id }}</h6> {{-- Translated card header --}}
            <div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-secondary" title="Back to List"> {{-- Translated button text and added title --}}
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="message-details">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $contactMessage->name }}</p> {{-- Translated label --}}
                        <p><strong>Email:</strong> <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></p> {{-- Translated label --}}
                        <p><strong>Sent Date:</strong> {{ $contactMessage->created_at->format('Y-m-d H:i') }}</p> {{-- Translated label --}}
                    </div>
                    <div class="col-md-6">
                        <p><strong>Subject:</strong> {{ $contactMessage->subject }}</p> {{-- Translated label --}}
                        <p><strong>Status:</strong>
                            @if($contactMessage->status == 'unread')
                                <span class="badge badge-danger">Unread</span> {{-- Translated badge text --}}
                            @elseif($contactMessage->status == 'read')
                                <span class="badge badge-warning">Read</span> {{-- Translated badge text --}}
                            @else
                                <span class="badge badge-success">Replied</span> {{-- Translated badge text --}}
                            @endif
                        </p>
                        @if($contactMessage->updated_at != $contactMessage->created_at)
                        <p><strong>Last Updated:</strong> {{ $contactMessage->updated_at->format('Y-m-d H:i') }}</p> {{-- Translated label --}}
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light font-weight-bold">Message Body</div> {{-- Translated card header --}}
                            <div class="card-body">
                                <p class="message-content">{{ $contactMessage->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <h5 class="mb-3">Change Message Status</h5> {{-- Translated section title --}}
                        <form action="{{ route('admin.contacts.status.update', $contactMessage->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="unread" {{ $contactMessage->status == 'unread' ? 'selected' : '' }}>Unread</option> {{-- Translated option --}}
                                    <option value="read" {{ $contactMessage->status == 'read' ? 'selected' : '' }}>Read</option> {{-- Translated option --}}
                                    <option value="replied" {{ $contactMessage->status == 'replied' ? 'selected' : '' }}>Replied</option> {{-- Translated option --}}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" title="Save Status"> {{-- Translated button text and added title --}}
                                <i class="fas fa-save"></i> Save Status
                            </button>
                        </form>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h5 class="mb-3">Quick Actions</h5> {{-- Translated section title --}}
                        <div class="d-flex flex-wrap">
                            <a href="mailto:{{ $contactMessage->email }}?subject=Reply: {{ $contactMessage->subject }}" class="btn btn-success me-2 mb-2" title="Reply by Email"> {{-- Translated button text and added title --}}
                                <i class="fas fa-reply"></i> Reply by Email
                            </a>
                            <form action="{{ route('admin.contacts.destroy', $contactMessage->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-2" onclick="return confirm('Are you sure you want to delete this message?')" title="Delete Message"> {{-- Translated confirmation and added title --}}
                                    <i class="fas fa-trash"></i> Delete Message {{-- Translated button text --}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .message-content {
        white-space: pre-wrap;
        line-height: 1.6;
    }
</style>
@endsection
