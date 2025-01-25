@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Master</a></li>
        <li class="breadcrumb-item"><a href="{{ route('master.contact.index') }}">Contact</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="POST" action="{{ route('master.contact.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
    });
</script>
@endsection