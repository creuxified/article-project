<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel CRUD</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg p-6">
        <!-- Header Card -->
        <div class="bg-gray-100 dark:bg-gray-800 text-black dark:text-white p-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i> Edit Faculty
            </h2>
            <a href="/faculty" wire:navigate class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                <i class=""></i> Faculty List
            </a>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form wire:submit.prevent="update" class="space-y-4">
                <!-- Input Field -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-building mr-2"></i> Faculty Name
                    </label>
                    <input type="text" wire:model="name" id="name" name="name"
                        class="w-full bg-gray-50 border border-black text-black text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter faculty name">
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
            </form>
        </div>
    </div>
</body>
</html>
