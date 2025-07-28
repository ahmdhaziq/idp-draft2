<div class="max-w-xl mx-auto mt-10">
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <button
            type="submit"
            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
        >
            Submit
        </button>
</div>
