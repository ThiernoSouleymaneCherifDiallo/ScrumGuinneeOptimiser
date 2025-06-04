@extends('layouts.app')
@section('header')
    Ajouter un membre à {{ $project->name }}
    <button type="button" onclick="document.getElementById('addMemberModal').style.display='block'"
            class="ml-4 px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700">
        Ajouter un membre
    </button>
@endsection

@section('content')


<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="addMemberModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-white mb-2">Ajouter un membre</h3>
            <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-2">
                @csrf
                
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-300">Utilisateur</label>
                    <select name="user_id" id="user_id" required
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-300">Rôle</label>
                    <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="member">Membre</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('addMemberModal').style.display = 'none';
}

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    let modal = document.getElementById('addMemberModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

@endsection