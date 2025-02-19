<style>
     /* button of alert */
     .my-alert-button {
        background-color: #28a745 !important; /* Green button color */
        color: white !important; /* Text color */
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        font-size: 16px !important;
        cursor: pointer !important;
    }
    .my-alert-button:hover {
        background-color: #218838 !important; /* Slightly darker green on hover */
    }
</style>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'my-alert-button' // Applique votre classe personnalisée
                },
                timer: 3000
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',,
                customClass: {
                    confirmButton: 'my-alert-button' // Applique votre classe personnalisée
                },
                timer: 3000
            });
        });
    </script>
@endif
