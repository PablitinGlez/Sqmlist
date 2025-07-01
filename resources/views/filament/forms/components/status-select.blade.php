@props([
    'state' => null,
    'name' => 'status',
])

<div
    x-data="{ value: '{{ $state }}' }"
    x-init="
        $watch('value', (val) => {
            const el = $refs.select;
            const parent = el.closest('.fi-fo-field');
            parent.classList.remove('ring-green-500', 'bg-green-50', 'ring-red-500', 'bg-red-50');

            if (val === 'approved') {
                parent.classList.add('ring-green-500', 'bg-green-50');
            } else if (val === 'rejected') {
                parent.classList.add('ring-red-500', 'bg-red-50');
            }
        });
    "
>
    <select
        x-ref="select"
        x-model="value"
        name="{{ $name }}"
        class="w-full px-3 py-2 border rounded-md focus:outline-none"
    >
        <option disabled value="">Seleccione una opción</option>
        <option value="pending" :selected="value === 'pending'">Pendiente</option>
        <option value="approved" :selected="value === 'approved'">Aprobada</option>
        <option value="rejected" :selected="value === 'rejected'">Rechazada</option>
    </select>
</div>
