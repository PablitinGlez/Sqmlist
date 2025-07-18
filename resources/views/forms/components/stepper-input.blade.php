<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: @entangle($attributes->wire('model')),
            min: {{ $getMinValue() }},
            max: @js($getMaxValue()),
            step: {{ $getStep() }},
            validateNumber(value) {
                if (value === null || value === '' || value === undefined) {
                    return this.min;
                }
                let num = parseInt(value);
                if (isNaN(num)) {
                    return this.min;
                }
                if (num < this.min) return this.min;
                if (this.max !== null && num > this.max) return this.max;
                return num;
            },
            updateState(newValue) {
                this.state = this.validateNumber(newValue);
            }
        }"
        x-init="state = validateNumber(state);"
        class="flex items-center space-x-2"
    >
        <button
            type="button"
            x-on:click="updateState(state - step)"
            x-bind:disabled="state <= min"
            class="p-2 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
        </button>

        <input
            type="text"
            x-model="state"
            x-on:input="
                let value = $el.value.replace(/[^0-9]/g, '');
                $el.value = value;
                updateState(value);
            "
            x-on:blur="updateState(state)"
            class="w-20 p-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white bg-white dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70"
            {{ $isDisabled() ? 'disabled' : '' }}
        />

        <button
            type="button"
            x-on:click="updateState(state + step)"
            x-bind:disabled="max !== null && state >= max"
            class="p-2 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
    </div>
</x-dynamic-component>