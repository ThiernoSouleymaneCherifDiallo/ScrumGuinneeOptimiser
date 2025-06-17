@extends('layouts.app')

@section('content')
    <h1>Summary</h1>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

@section('styles')
    <style>
        .bg-jira-dark {
            background-color: #1D2125;
        }
        .bg-jira-card {
            background-color: #22272B;
            border: 1px solid #3C3F42;
        }
        .bg-jira-card-hover:hover {
            background-color: #2C333A;
        }
        .text-jira-gray {
            color: #9FADBC;
        }
        .border-jira {
            border-color: #3C3F42;
        }
        .bg-jira-input {
            background-color: #22272B;
            border-color: #3C3F42;
        }
        .bg-jira-input:focus {
            background-color: #22272B;
            border-color: #85B8FF;
            box-shadow: 0 0 0 1px #85B8FF;
        }
    </style>
@endsection

@section('header')
    Summary
@endsection
