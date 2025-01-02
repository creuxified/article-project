<div class="bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg p-6">
        <!-- Header Card -->
        <div class="bg-gray-100 dark:bg-gray-800 text-black dark:text-white p-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i> Edit Study Program
            </h2>
            <a href="{{ route('programs-index') }}" wire:navigate class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-list mr-2"></i> Study Program List
            </a>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form wire:submit.prevent="update" class="space-y-4">
                <!-- Input Field for Study Program Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-graduation-cap mr-2"></i> Study Program Name
                    </label>
                    <input type="text" wire:model="name" id="name" name="name"
                        class="w-full bg-gray-50 border border-black text-black text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter study program name">
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Select Faculty -->
                <div>
                    <label for="selectedFaculty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-building mr-2"></i> Faculty
                    </label>
                    <select id="selectedFaculty" wire:model="selectedFaculty"
                        class="w-full bg-gray-50 border border-black text-black text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedFaculty')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('programs-index') }}" class="btn bg-gray-300 text-black hover:bg-gray-400 px-4 py-2 rounded-lg text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
