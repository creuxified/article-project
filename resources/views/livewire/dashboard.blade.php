<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citation Management Dashboard - UNS</title>
    <style>
        /* styles.css */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5em;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Citation Management Dashboard</h1>
        </header>

        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>
        @endif

    <script>
        // script.js
        const addCitationBtn = document.getElementById('add-citation-btn');
        const modal = document.getElementById('modal');
        const closeBtn = document.querySelector('.close-btn');
        const citationForm = document.getElementById('citation-form');
        const citationTableBody = document.getElementById('citation-table').getElementsByTagName('tbody')[0];

        // Open modal to add new citation
        addCitationBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Close modal
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Add new citation to the table
        citationForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const title = document.getElementById('title').value;
            const author = document.getElementById('author').value;
            const year = document.getElementById('year').value;

            // Add row to the citation table
            const row = citationTableBody.insertRow();
            row.innerHTML = `
                <td>${title}</td>
                <td>${author}</td>
                <td>${year}</td>
                <td><button class="delete-btn">Delete</button></td>
            `;

            // Clear form fields
            citationForm.reset();

            // Close modal
            modal.style.display = 'none';

            // Add delete functionality
            row.querySelector('.delete-btn').addEventListener('click', () => {
                row.remove();
            });
        });

        // Close modal if user clicks outside of the modal content
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
