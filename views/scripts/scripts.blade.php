 <!-- Libs JS -->
 <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js?1738096685') }}" defer></script>
 <script src="{{ asset('dist/libs/jsvectormap/dist/jsvectormap.min.js?1738096685') }}" defer></script>
 <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js?1738096685') }}" defer></script>
 <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js?1738096685') }}" defer></script>
 <!-- Tabler Core -->
 <script src="{{ asset('dist/js/tabler.min.js?1738096685') }}" defer></script>
 <script src="{{ asset('dist/js/demo.min.js?1738096685') }}" defer></script>

 @if (session('status'))
     <script>
         Swal.fire({
             icon: 'success',
             title: 'Success!',
             text: '{{ session('status') }}',
         });
     </script>
 @endif

 @if (session('error'))
     <script>
         Swal.fire({
             icon: 'error',
             title: 'Error!',
             text: '{{ session('error') }}',
         });
     </script>
 @endif

 @if ($errors->any())
     <script>
         Swal.fire({
             icon: 'error',
             title: 'Validation Error!',
             html: `@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach`,
         });
     </script>
 @endif

 @if (session('columns'))
     <script>
         Swal.fire({
             icon: 'info',
             title: 'Table Columns',
             html: `@foreach (session('columns') as $column)<p>{{ $column->Field }}</p>@endforeach`,
         });
     </script>
 @endif



 <!-- Include DataTables CSS -->


 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
 <script
     src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.2.1/b-3.2.1/b-colvis-3.2.1/b-html5-3.2.1/b-print-3.2.1/r-3.0.3/datatables.min.js">
 </script>




 <script>
     document.addEventListener('DOMContentLoaded', function() {
         // Target only tables with class "tableme"
         const tables = document.querySelectorAll('table.tableme');

         tables.forEach(table => {
             $(table).DataTable({
                 dom: 'Bfrtip', // Add buttons to the DOM
                 buttons: [
                     'csv', // Export to CSV
                     {
                         extend: 'pdf', // Export to PDF
                         orientation: 'landscape', // PDF orientation
                         pageSize: 'A4' // PDF page size
                     },
                     'print' // Print table
                 ],
                 responsive: true, // Enable responsiveness
                 paging: true, // Enable pagination
                 searching: true, // Enable search
                 ordering: true, // Enable sorting
                 info: true, // Show table information
                 autoWidth: false, // Disable automatic column width calculation
                 language: {
                     paginate: {
                         next: 'Next', // Next button text
                         previous: 'Previous' // Previous button text
                     },
                     search: 'Search:', // Search input label
                     zeroRecords: 'No matching records found', // No records message
                     info: 'Showing _START_ to _END_ of _TOTAL_ entries', // Table info
                     infoEmpty: 'Showing 0 to 0 of 0 entries', // Info when table is empty
                     infoFiltered: '(filtered from _MAX_ total entries)' // Filtered info
                 }
             });
         });
     });

     document.addEventListener('DOMContentLoaded', function() {
         let intervalCount = 0; // Counter to track the number of intervals
         const maxIntervals = 7; // Maximum number of intervals (7 seconds)

         // Set up an interval to check for the search input every 1 second
         const interval = setInterval(() => {
             // Target the .dt-search element
             const searchWrapper = document.querySelector('.dt-search');

             if (searchWrapper) {
                 // If the search wrapper is found, style it and its children
                 searchWrapper.classList.add('ms-auto', 'float-end',
                     'px-3'); // Float the wrapper to the end

                 // Style the label
                 const searchLabel = searchWrapper.querySelector('label');
                 if (searchLabel) {
                     searchLabel.classList.add('me-2'); // Add margin to the right of the label
                 }

                 // Style the input
                 const searchInput = searchWrapper.querySelector('input');
                 if (searchInput) {
                     searchInput.classList.add('form-control',
                         'form-control'); // Add Tabler input styling
                 }

                 clearInterval(interval); // Stop the interval
                 console.log('Search input found and styled.');
             } else {
                 intervalCount++; // Increment the counter
                 if (intervalCount >= maxIntervals) {
                     // If the maximum intervals are reached, stop the interval
                     clearInterval(interval);
                     console.log('Search input not found after 7 seconds.');
                 }
             }
         }, 1000); // Run every 1 second
     });
 </script>



 <script>
     /***************************************************************
      * Initializes the Google Translate Element after the script loads.
      * This sets up translation functionality, but its default UI is hidden with CSS.
      ***************************************************************/
     function googleTranslateElementInit() {
         new google.translate.TranslateElement({
             pageLanguage: 'en',
             includedLanguages: 'en,fr,sw,pt,am',
             autoDisplay: false
         }, 'google_translate_element');
     }

     /**
      * Sets or clears the 'googtrans' cookie, which Google Translate uses
      * to determine the page translation language.
      */
     function setTranslateCookie(lang) {
         if (lang === 'en') {
             // Clear the translation cookie for English
             document.cookie = 'googtrans=;path=/;';
             document.cookie = 'googtrans=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
         } else {
             // Example: "googtrans=/auto/fr"
             document.cookie = `googtrans=/auto/${lang};path=/;`;
         }
     }

     /**
      * Applies translation based on the chosen language.
      * - Clears the cookie if 'en' is selected.
      * - Otherwise, sets the translation cookie for the chosen language.
      * - If the translation library is loaded, re-initialize to apply translation immediately.
      */
     function applyTranslation(lang) {
         if (lang === 'en') {
             setTranslateCookie('en');
             return;
         }
         setTranslateCookie(lang);

         // If Google Translate has loaded, re-initialize for an immediate effect
         if (window.google && google.translate) {
             googleTranslateElementInit();
         }
     }

     /***************************************************************
      * Main Logic on DOMContentLoaded:
      *  1) Check localStorage for a stored language.
      *  2) If not set, prompt user with SweetAlert.
      *  3) On confirmation, store and apply the new language, then reload.
      ***************************************************************/
     document.addEventListener('DOMContentLoaded', function() {
         const storedLang = localStorage.getItem('selectedLanguage');

         if (!storedLang) {
             Swal.fire({
                 title: 'Select Your Preferred Language',
                 text: 'Please choose one of the following:',
                 icon: 'info',
                 input: 'select',
                 inputOptions: {
                     en: 'English',
                     fr: 'French',
                     sw: 'Swahili',
                     pt: 'Portuguese',
                     am: 'Amharic'
                 },
                 inputPlaceholder: 'Pick a language...',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Confirm',
                 cancelButtonText: 'Keep English',
             }).then((result) => {
                 if (result.isConfirmed && result.value) {
                     // Store new choice
                     localStorage.setItem('selectedLanguage', result.value);
                     applyTranslation(result.value);

                     // Reload the page so the translation cookie is picked up immediately
                     location.reload();
                 } else {
                     // User canceled or kept English
                     localStorage.setItem('selectedLanguage', 'en');

                     // Reload in case we want a fresh state in English
                     location.reload();
                 }
             });
         } else {
             // If there's a stored language, apply it
             applyTranslation(storedLang);
         }
     });
 </script>
