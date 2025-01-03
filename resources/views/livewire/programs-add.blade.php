<div class="antialiased flex items-center justify-center px-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg p-6">
        <!-- Header Card -->
        <div class="bg-gray-100 dark:bg-gray-800 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold">
                <i class="fas fa-plus-circle mr-2"></i> Add Study Program
            </h2>
            <a href="{{ route('programs-index') }}" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-list mr-2"></i> Study Program List
            </a>
        </div>

        <!-- Form Content -->
        <div class="bg-white p-4 rounded-lg">
            <form wire:submit.prevent="saveStudyProgram" class="space-y-4">
                <!-- Input Field for Study Program Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-graduation-cap mr-2"></i> Study Program Name
                    </label>
                    <input type="text" wire:model.defer="name" id="name" name="name"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter study program name">

                    <!-- Validation Error -->
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Select Faculty -->
                <div>
                    <label for="selectedFaculty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-building mr-2"></i> Faculty
                    </label>
                    <select wire:model="selectedFaculty" id="selectedFaculty"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Select Faculty</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedFaculty')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Save
                </button>
            </form>
        </div>
    </div>
</div>
