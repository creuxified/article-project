 <!-- Publications Table -->
 <div class="overflow-x-auto">
     <table id="publicationTable" class="table-auto w-full border-collapse border border-gray-300">
         <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
             <tr>
                 <th class="px-6 py-3">#</th>
                 @if (auth()->user()->role_id == 3)
                     <th class="px-6 py-3">Lecturer</th>
                 @endif
                 <th class="px-6 py-3">Title</th>
                 <th class="px-6 py-3">Journal</th>
                 <th class="px-6 py-3">Publication Date</th>
                 <th class="px-6 py-3">Citations</th>
                 <th class="px-6 py-3">Source</th>
                 <th class="px-6 py-3">Link</th>
             </tr>
         </thead>
         <tbody>
             @foreach ($publications as $index => $publication)
                 <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200" data-source="{{ $publication->source }}"
                     data-lecturer="{{ $publication->user->name ?? 'N/A' }}"
                     data-year="{{ \Carbon\Carbon::parse($publication->publication_date)->year }}">
                     <td class="px-6 py-4">{{ $index + 1 }}</td>
                     @if (auth()->user()->role_id == 3)
                         <td class="px-6 py-4">{{ $publication->user->name ?? 'N/A' }}</td>
                     @endif
                     <td class="px-6 py-4">{{ $publication->title }}</td>
                     <td class="px-6 py-4">{{ $publication->journal_name }}</td>
                     <td class="px-6 py-4">{{ $publication->publication_date }}</td>
                     <td class="px-6 py-4">{{ $publication->citations }}</td>
                     <td class="px-6 py-4">{{ $publication->source }}</td>
                     <td class="px-6 py-4">
                         @if ($publication->link)
                             <a href="{{ $publication->link }}" target="_blank" class="text-blue-500">View</a>
                         @else
                             -
                         @endif
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
 </div>
