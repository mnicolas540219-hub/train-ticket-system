<tr class="hover:bg-slate-50">
    <td class="px-5 py-4 font-semibold text-slate-950">{{ $employee->username }}</td>
    <td class="px-5 py-4 font-semibold text-slate-950">{{ $employee->name }}</td>
    <td class="px-5 py-4 text-slate-600">{{ $employee->email }}</td>
    <td class="px-5 py-4 text-right">
        <div class="flex justify-end gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="rounded-md border border-slate-200 px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a>
            <form method="POST" action="{{ route('employees.destroy', $employee) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-md border border-rose-200 px-3 py-1.5 font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
            </form>
        </div>
    </td>
</tr>
