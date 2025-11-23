<div class="list-group">
    <a href="{{ route('tickets.index') }}" class="list-group-item list-group-item-action">My Tickets</a>
    @can('viewAny', App\Models\Ticket::class) {{-- optional if you later add policies --}}
    @endcan

    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.tickets.index') }}" class="list-group-item list-group-item-action">All Tickets (Admin)</a>
        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action">Manage Roles</a>
        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">Categories</a>
        <a href="{{ route('admin.departments.index') }}" class="list-group-item list-group-item-action">Departments</a>
    @endif
</div>
