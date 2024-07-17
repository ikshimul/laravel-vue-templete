@extends('layouts.app')
@section('page-title', 'Users')
@section('header')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Admin List</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item active">List</li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<user-list></user-list>
@endsection