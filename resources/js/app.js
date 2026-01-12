import './bootstrap';

// Import Alpine.js (Wajib untuk Sidebar)
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import CSS Tom Select (Style bawaan)
import 'tom-select/dist/css/tom-select.default.css';

// Import JS Tom Select dan pasang ke window agar bisa dipanggil di Blade
import TomSelect from 'tom-select';
window.TomSelect = TomSelect;

// IMPORT APEXCHARTS
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts; // Simpan ke window agar bisa dipanggil di Blade

// 1. IMPORT SWEETALERT2
import Swal from 'sweetalert2';
window.Swal = Swal;

// 2. LOGIKA KONFIRMASI HAPUS OTOMATIS
document.addEventListener('DOMContentLoaded', function() {
    
    // Tangkap semua event submit pada body
    document.body.addEventListener('submit', function(e) {
        
        // Cek apakah form yang disubmit memiliki kelas 'delete-form'
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault(); // Tahan dulu, jangan kirim
            
            const form = e.target;
            
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444', // Merah (Red-500)
                cancelButtonColor: '#6B7280', // Abu-abu
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-xl', // Biar rounded pas dengan tema kita
                    confirmButton: 'px-4 py-2 rounded-lg',
                    cancelButton: 'px-4 py-2 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan kirim jika user klik Yes
                }
            });
        }
    });
});