<!-- filepath: resources/views/admin/users/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des utilisateurs</h1>
    @foreach($users as $user)
        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
            @csrf
            {{ $user->name }} ({{ $user->email }})
            <select name="role_id">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit">Changer</button>
        </form>
    @endforeach
</div>
@endsection