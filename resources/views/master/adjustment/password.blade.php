@extends('main')

@section('content')
    <div class="container">
        <center>
            <div class="card" style="width: 20%; margin-top: 100px;">
                <div class="card-body text-center">
                    <div class="mt-2">
                        <label for="passwordInput"><b>PASSWORD ???</b></label>
                    </div>
                    <div class="mt-2">
                        <form id="passwordForm" method="POST" onsubmit="return validatePassword()">
                            @csrf
                            <input type="password" autofocus id="passwordInput" name="password" class="form-control" required>
                        </form>
                    </div>
                    <div class="mt-3">
                        <button type="submit" form="passwordForm" class="btn btn-danger">SELESAI</button>
                    </div>
                </div>
            </div>
        </center>
    </div>

    <script>
        function validatePassword() {
            var userPass = @json($passUser); // Mengambil nilai password dari controller
            var enteredPass = document.getElementById('passwordInput').value.toUpperCase();

            if (enteredPass !== userPass) {
                alert('Password yang Anda masukkan salah!');
                document.getElementById('passwordInput').value = '';
                return false; // Mencegah form untuk disubmit jika password salah
            }

            // Password benar, arahkan form ke route tertentu
            var form = document.getElementById('passwordForm');
            form.action = "{{ route('master.adjustment.edit') }}";

            // Setelah aksi diubah, submit form
            form.submit();
            return false; // Mencegah submit default
        }
    </script>
@endsection
