<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Form Penilaian Mahasiswa</title>
  <!-- Import Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow">
      <!-- membuat judul Form -->
      <div class="card-header text-white bg-primary text-center fs-4">
        Form Penilaian Mahasiswa
      </div>
      <div class="card-body">
        <form id="penilaianForm" novalidate>
          <!-- Input Nama -->
          <div class="mb-3">
            <label for="nama" class="form-label">Masukkan Nama</label>
            <input type="text" class="form-control" id="nama" name="nama">  <!-- menggunakan tipe teks karena berisi tulisan biasa -->
          </div>
          <!-- Input NIM -->
          <div class="mb-3">
            <label for="nim" class="form-label">Masukkan NIM</label>
            <input type="text" class="form-control" id="nim" name="nim"> <!-- menggunakan tipe teks karena berisi tulisan biasa -->
          </div>
          <!-- Input Nilai Kehadiran -->
          <div class="mb-3">
            <label for="kehadiran" class="form-label">Nilai Kehadiran (10%)</label>
            <input type="number" class="form-control" id="kehadiran" name="kehadiran" placeholder="0 - 100"> <!-- menggunakan tipe number karena input yang diinginkan berupa angka -->
          </div>
          <!-- Input Nilai Tugas -->
          <div class="mb-3">
            <label for="tugas" class="form-label">Nilai Tugas (20%)</label>
            <input type="number" class="form-control" id="tugas" name="tugas" placeholder="0 - 100">
          </div>
          <!-- Input Nilai UTS -->
          <div class="mb-3">
            <label for="uts" class="form-label">Nilai UTS (30%)</label>
            <input type="number" class="form-control" id="uts" name="uts" placeholder="0 - 100">
          </div>
          <!-- Input Nilai UAS -->
          <div class="mb-3">
            <label for="uas" class="form-label">Nilai UAS (40%)</label>
            <input type="number" class="form-control" id="uas" name="uas" placeholder="0 - 100">
          </div>

          <!-- Tombol Proses -->
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Proses</button>
          </div>

          <!-- Notifikasi Error -->
          <div id="alertBox" class="alert alert-danger d-none" role="alert"></div>
        </form>

        <!-- Tampilan dari Hasil Penilaian -->
        <div id="hasilPenilaian" class="mt-4 d-none">
          <div class="card border-0">
            <!-- Header Hasil -->
            <div id="hasilHeader" class="card-header text-white text-center fs-5 fw-semibold">
              Hasil Penilaian
            </div>
            <!-- Isi Nilai-nilai -->
            <div class='card-body'>
              <div class='row px-5 fs-4 mb-3'>
                <div class='col text-start'><strong>Nama:</strong> <span id="hasilNama"></span></div>
                <div class='col text-end'><strong>NIM:</strong> <span id="hasilNim"></span></div>
              </div>
              <p>Nilai Kehadiran: <span id="hasilKehadiran"></span>%</p>
              <p>Nilai Tugas: <span id="hasilTugas"></span></p>
              <p>Nilai UTS: <span id="hasilUts"></span></p>
              <p>Nilai UAS: <span id="hasilUas"></span></p>
              <p>Nilai Akhir: <span id="hasilAkhir"></span></p>
              <p>Grade: <span id="hasilGrade"></span></p>
              <p>Status: <strong id="hasilStatus" class="text-uppercase"></strong></p>
            </div>
            <!-- Footer dengan tombol selesai -->
            <div id="hasilFooter" class="card-footer text-center p-2">
              <button class="btn w-100 text-white" id="btnSelesai" onclick="window.location.reload()">Selesai</button> 
            </div> 
          </div> 
        </div>

      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("penilaianForm");
      const alertBox = document.getElementById("alertBox");

      // Saat form dikirim
      form.addEventListener("submit", function (e) {
        e.preventDefault(); // agar tidak terjadi reload halaman
        alertBox.classList.add("d-none"); // Sembunyikan alert

        // Daftar input yang harus diisi
        const fields = [
          { id: "nama", label: "Nama" },
          { id: "nim", label: "NIM" },
          { id: "kehadiran", label: "Nilai Kehadiran" },
          { id: "tugas", label: "Nilai Tugas" },
          { id: "uts", label: "Nilai UTS" },
          { id: "uas", label: "Nilai UAS" }
        ];

        let emptyFields = [];

        // mengecek apakah ada yang kosong
        fields.forEach(field => {
          const input = document.getElementById(field.id);
          if (!input.value.trim()) {
            emptyFields.push(field.label);
          }
        });

        // Jika ada input kosong maka fungsi akan menampilkan alert
        if (emptyFields.length > 0) {
          let formattedFields = emptyFields.slice(0, -1).join(", ") +
            (emptyFields.length > 1 ? " dan " : "") +
            emptyFields.slice(-1);
          alertBox.innerHTML = `Kolom ${formattedFields} harus diisi.`;
          alertBox.classList.remove("d-none");
        } else {
          // Jika lengkap, tampilkan hasil penilaian
          tampilkanHasilAkhir();
        }
      });

      // Fungsi untuk menampilkan hasil akhir penilaian
      function tampilkanHasilAkhir() {
        const nama = document.getElementById("nama").value;
        const nim = document.getElementById("nim").value;
        const kehadiran = parseFloat(document.getElementById("kehadiran").value) || 0;
        const tugas = parseFloat(document.getElementById("tugas").value) || 0;
        const uts = parseFloat(document.getElementById("uts").value) || 0;
        const uas = parseFloat(document.getElementById("uas").value) || 0;

        // Hitung nilai akhir dengan bobot
        const nilaiAkhir = (kehadiran * 0.1) + (tugas * 0.2) + (uts * 0.3) + (uas * 0.4);
        let grade, status;

        // Menentukan grade dan status
        if (nilaiAkhir >= 85) {
          grade = "A";
          status = "LULUS"; //jika nilai lebih dari atau sama dengan 85 maka grade nya A dan lulus
        } else if (nilaiAkhir >= 70) {
          grade = "B";
          status = "LULUS"; //jika nilai lebih dari atau sama dengan 70 maka grade nya B dan lulus 
        } else if (nilaiAkhir >= 60) {
          grade = "C";
          status = "TIDAK LULUS"; //jika nilai lebih dari atau sama dengan 60 maka grade nya C dan tidak lulus
        } else {
          grade = "D";
          status = "TIDAK LULUS"; //jika nilai dari selain diatas maka grade nya D dan tidak lulus
        }

        // Tampilkan hasil ke halaman
        document.getElementById("hasilNama").textContent = nama;
        document.getElementById("hasilNim").textContent = nim;
        document.getElementById("hasilKehadiran").textContent = kehadiran;
        document.getElementById("hasilTugas").textContent = tugas;
        document.getElementById("hasilUts").textContent = uts;
        document.getElementById("hasilUas").textContent = uas;
        document.getElementById("hasilAkhir").textContent = nilaiAkhir.toFixed(2);
        document.getElementById("hasilGrade").textContent = grade;

        // Atur warna status dan background
        const statusEl = document.getElementById("hasilStatus");
        statusEl.textContent = status;
        statusEl.classList.remove("text-success", "text-danger");
        statusEl.classList.add(status === "LULUS" ? "text-success" : "text-danger");

        const header = document.getElementById("hasilHeader");
        header.classList.remove("bg-success", "bg-danger");
        header.classList.add(status === "LULUS" ? "bg-success" : "bg-danger");

        const footer = document.getElementById("hasilFooter");
        const btn = document.getElementById("btnSelesai");
        if (status === "LULUS") {
          footer.classList.add("bg-success");
          btn.classList.add("bg-success");
        } else {
          footer.classList.add("bg-danger");
          btn.classList.add("bg-danger");
        }

        // Tampilkan kotak hasil
        document.getElementById("hasilPenilaian").classList.remove("d-none");
      }
    });
  </script>

</body>
</html>
