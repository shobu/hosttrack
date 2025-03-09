<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hosting Manager</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>

    @include('layouts.navigation') <!-- Φορτώνει το Bootstrap Navbar -->

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmAction(formId, message) {
            Swal.fire({
                title: 'Είσαι σίγουρος;',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ναι, προχώρησε!',
                cancelButtonText: 'Ακύρωση'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

    <!-- Αυτόματη μετατροπή ημερομηνίας σε μορφή dd/mm/yyyy -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("input[type=date]").forEach(function (input) {
                input.addEventListener("change", function () {
                    let date = new Date(this.value);
                    if (!isNaN(date.getTime())) {
                        let formattedDate = ("0" + date.getDate()).slice(-2) + "/" + 
                                            ("0" + (date.getMonth() + 1)).slice(-2) + "/" + 
                                            date.getFullYear();
                        this.setAttribute("data-formatted-date", formattedDate);
                    }
                });
            });
        });
    </script>

    <script>
        function toggleCustomMonths(clientId) {
            let select = document.getElementById("renewalMonths-" + clientId);
            let customContainer = document.getElementById("customMonthsContainer-" + clientId);
            let customInput = document.getElementById("customMonths-" + clientId);

            if (select.value === "custom") {
                customContainer.style.display = "block";
                customInput.setAttribute("required", "true");
            } else {
                customContainer.style.display = "none";
                customInput.removeAttribute("required");
                customInput.value = ""; // Καθαρίζουμε την τιμή
            }
        }
    </script>
    <script>
    function toggleCustomMonths(clientId) {
        var monthsSelect = document.getElementById("renewalMonths-" + clientId);
        var customMonthsContainer = document.getElementById("customMonthsContainer-" + clientId);

        if (monthsSelect.value === "custom") {
            customMonthsContainer.style.display = "block";
        } else {
            customMonthsContainer.style.display = "none";
        }
    }

    function toggleSupportCost(clientId) {
        var supportCheckbox = document.getElementById("supportService-" + clientId);
        var supportCostContainer = document.getElementById("supportCostContainer-" + clientId);

        if (supportCheckbox.checked) {
            supportCostContainer.style.display = "block";
        } else {
            supportCostContainer.style.display = "none";
        }
    }
    </script>



</body>
</html>
