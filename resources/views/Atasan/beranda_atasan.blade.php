<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beranda Atasan - SIOPLAS</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    :root{
      --accent:#48CAE4;
      --bg-card:#f1f4f5;
      --text:#2a3b27;
      --muted:#7a7f83;
      --success:#2ea44f;
      --warning:#e49d1b;
      --danger:#e04b3a;
    }
    *{box-sizing: border-box;}
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      min-height: 100vh;
      color: var(--text);
      background: #fff;
    }

    /* SIDEBAR */
    aside {
      width: 230px;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid #e6e6e6;
    }

    .sidebar-top {
      text-align: center;
      padding: 20px 0;
      border-bottom: 1px solid #e6e6e6;
    }

    .logo {
      font-size: 22px;
      font-weight: 700;
    }

    .logo span:first-child { color: var(--accent); }

    .menu { padding: 20px; list-style: none; margin: 0; }
    .menu li { margin: 12px 0; }
    .menu a {
      text-decoration: none;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px;
      border-radius: 8px;
      font-weight: 500;
    }
    .menu a.active { background-color: var(--accent); color: #fff; }

    .sidebar-bottom { border-top: 1px solid #e6e6e6; padding: 16px; }
    .sidebar-bottom a { display:flex; align-items:center; gap:8px; text-decoration:none; color:var(--text); margin-bottom:8px; }

    /* MAIN */
    main {
      flex: 1;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }

    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 15px 30px;
      border-bottom: 1px solid #e6e6e6;
    }

    .notif { position: relative; margin-right: 15px; cursor:pointer; font-size:20px; }
    .notif::after {
      content:'1';
      position:absolute; top:-6px; right:-8px; background:red; color:#fff; font-size:10px; padding:2px 5px; border-radius:50%;
    }

    .profile { display:flex; align-items:center; gap:10px; }
    .profile img { width:35px; height:35px; border-radius:50%; border:1px solid #ccc; }

    .content { padding: 30px; }

    .stats { display:flex; gap:20px; margin: 20px 0; }
    .box {
      background: var(--bg-card);
      padding: 20px;
      border-radius: 10px;
      flex:1;
      text-align:center;
    }
    .box h3 { font-size:22px; margin-bottom:5px; }

    .button {
      background-color: var(--accent);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 25px;
      cursor: pointer;
      margin-top: 10px;
      font-weight:600;
    }

    .team { margin-top: 30px; }
    .team ul { list-style:none; padding:0; margin:0; display:grid; gap:8px; max-width:600px; }
    .team li {
      margin:0;
      display:flex;
      align-items:center;
      gap:12px;
      cursor:pointer;
      padding:10px;
      border-radius:8px;
      transition: background .12s;
    }
    .team li img { width:36px; height:36px; border-radius:50%; }
    .team li:hover { background:#f7f8f8; }

    /* MODAL BASE */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.45);
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .modal.show { display:flex; }

    .modal-content {
      background-color: #fff;
      padding: 22px;
      border-radius: 12px;
      width: 820px;
      max-width: 100%;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
      animation: fadeIn 0.18s ease;
      color: var(--text);
    }

    .modal .close {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: #888;
    }
    .modal .close:hover { color: #222; }

    @keyframes fadeIn { from{opacity:0; transform: translateY(6px);} to{opacity:1; transform:none;} }

    /* Profil detail layout (two columns) */
    .profile-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 18px; align-items:start; }
    .card { background: var(--bg-card); border-radius: 10px; padding: 16px; }

    .avatar {
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:8px;
      text-align:center;
    }
    .avatar img { width:120px; height:120px; border-radius:50%; border:6px solid #f0f3f4; object-fit:cover; }
    .avatar h2 { margin:8px 0 0; font-size:20px; }
    .avatar p { margin:4px 0 0; color:var(--muted); font-size:14px; }

    .contact p { margin:8px 0; font-size:14px; color:var(--muted); }

    .laporan-list { max-height:210px; overflow:auto; padding-right:6px; }
    .laporan-item { display:flex; justify-content:space-between; padding:8px 6px; border-radius:6px; align-items:center; }
    .laporan-item + .laporan-item { margin-top:6px; }

    .status {
      font-weight:600;
      font-size:13px;
      padding:4px 8px;
      border-radius:999px;
    }
    .status.success { color: #0b7a3b; background: rgba(46,164,79,0.08); }
    .status.pending { color: #b36a00; background: rgba(228,157,27,0.08); }
    .status.rejected { color: #a02b21; background: rgba(224,75,58,0.06); }

    /* Pembaruan card */
    .form-row { margin-top:8px; display:flex; flex-direction:column; gap:8px; }
    select, input[type="text"] {
      padding:10px; border-radius:8px; border:1px solid #d7dbe0; font-size:14px;
    }

    @media (max-width:900px){
      .profile-grid { grid-template-columns: 1fr; }
      .modal-content { padding:16px; }
    }
  </style>
</head>
<body>
  <aside>
    <div>
      <div class="sidebar-top">
        <div class="logo"><span>SIO</span><span>PLAS</span></div>
      </div>
      <ul class="menu">
        <li><a href="#" class="active">📊 Dashboard</a></li>
        <li><a href="#">📁 Laporan</a></li>
        <li><a href="#">📝 Tugas</a></li>
      </ul>
    </div>

    <div class="sidebar-bottom">
      <a href="/settings">
        <img src="https://img.icons8.com/?size=100&id=364&format=png" width="18" alt="settings" />
        Settings
      </a>
      <a href="/logout">
        <img src="https://img.icons8.com/?size=100&id=26215&format=png" width="18" alt="logout" />
        Logout
      </a>
    </div>
  </aside>

  <main>
    <div class="topbar">
  <div class="notif">🔔</div>
  <div class="profile">
    <!-- FOTO PROFIL ATASAN (default diganti dari placeholder ke avatar rapi) -->
    <img 
      src="https://i.pravatar.cc/100?u=atasan-ade-samsudin" 
      alt="Profile" 
      onerror="this.src='https://i.pravatar.cc/100?u=default';"
    />
    <div class="profile-info">
      <h4 style="margin:0; font-size:14px;">{{ session('atasan_nama', 'Atasan') }}</h4>
      <p style="margin:0; font-size:12px; color:var(--muted);">{{ session('atasan_jabatan', 'Atasan') }}</p>
    </div>
  </div>
</div>


    <div class="content">
      <h2>Selamat Datang, {{ session('atasan_nama', 'Atasan') }}</h2>

      <div class="stats">
        <div class="box">
          <h3>28</h3>
          <p>Total Laporan Terkirim</p>
        </div>
        <div class="box">
          <h3>15</h3>
          <p>Total Tugas Terkirim</p>
        </div>
      </div>

      <div class="team">
        <h3>Team Manajemen</h3>
        <ul class="team-list">
  <li onclick="showTeam('tata')">
    <img src="https://img.icons8.com/?size=160&id=2969&format=png" alt="tata"/> Tata Usaha
  </li>
  <li onclick="showTeam('produksi')">
    <img src="https://img.icons8.com/?size=160&id=12137&format=png" alt="produksi"/> Produksi Primer
  </li>
  <li onclick="showTeam('panen')">
    <img src="https://img.icons8.com/?size=160&id=44174&format=png" alt="panen"/> Pasca Panen
  </li>
  <li onclick="showTeam('labor')">
    <img src="https://img.icons8.com/?size=160&id=9720&format=png" alt="labor"/> LABOR
  </li>
</ul>
      </div>
    </div>
  </main>

  <!-- MODAL LIST TEAM -->
  <div class="modal" id="teamModal" aria-hidden="true">
    <div class="modal-content" style="width:520px;">
      <span class="close" onclick="closeModal()" title="Tutup">&times;</span>
      <h3 id="teamName" style="margin-top:0;">Daftar Anggota</h3>
      <ul id="teamMembers" style="list-style:none; padding:0; margin:0;"></ul>
    </div>
  </div>

  <!-- MODAL PROFIL DETAIL PEGAWAI (baru, lengkap seperti gambar) -->
  <div class="modal" id="pegawaiDetailModal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="detailNama">
      <span class="close" onclick="closePegawaiDetail()" title="Tutup">&times;</span>
      <h3 style="margin-top:0; margin-bottom:12px;">Profil Pegawai</h3>

      <div class="profile-grid">
        <!-- KIRI: foto + info kontak -->
        <div class="card">
          <div class="avatar">
            <img id="detailFoto" src="https://i.pravatar.cc/120?img=11" alt="Foto pegawai">
            <h2 id="detailNama">Nama Pegawai</h2>
            <p id="detailDivisi">Divisi: -</p>
          </div>

          <div style="margin-top:18px;" class="contact">
            <h4 style="margin:0 0 8px 0;">Informasi kontak</h4>
            <p style="margin:6px 0;"><strong>Phone</strong><br/><span id="detailPhone" style="color:var(--muted);">-</span></p>
            <p style="margin:6px 0;"><strong>Email</strong><br/><a id="detailEmail" href="#" style="color:var(--text); text-decoration:underline;"></a></p>
          </div>
        </div>

        <!-- KANAN: rangkuman laporan + pembaruan -->
        <div style="display:flex; flex-direction:column; gap:12px;">
          <!-- Rangkuman Laporan -->
          <div class="card" style="flex:1;">
            <h4 style="margin:0 0 8px 0;">Rangkuman Laporan</h4>
            <div class="laporan-list" id="laporanList">
              <!-- item laporan akan di-generate lewat JS -->
            </div>
          </div>

          <!-- Pembaruan (peran & tingkat) -->
          <div class="card" style="padding-bottom:14px;">
            <h4 style="margin:0 0 8px 0;">Pembaruan</h4>
            <p style="margin:0 0 8px 0;">Peran: <strong id="peranNow">Pegawai</strong></p>
            <div class="form-row">
              <label for="tingkat">Tingkat Saat Ini</label>
              <select id="tingkat">
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
                <option value="Level 3">Level 3</option>
              </select>
              <button class="button" onclick="perbaruiTingkat()" style="width:100%;">Perbarui Tingkat</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // data tim (contoh)
    const teamData = {
      tata: [
        { name: "Budi Susanto", img: "https://i.pravatar.cc/100?img=11", divisi: "Tata Usaha", phone: "081234567890", email: "budi.susanto@email.com", peran:"Pegawai", tingkat:"Level 1",
          laporan: [
            { title: "Laporan Bulanan - November 2024", status: "Diterima" },
            { title: "Laporan Bulanan - Desember 2024", status: "Diterima" },
            { title: "Laporan Bulanan - Januari 2025", status: "Diterima" },
            { title: "Laporan Bulanan - Februari 2025", status: "Diterima" },
            { title: "Laporan Bulanan - Maret 2025", status: "Tertunda" },
            { title: "Laporan Bulanan - April 2025", status: "Tertunda" },
            { title: "Laporan Bulanan - Mei 2025", status: "Tertunda" },
            { title: "Monthly Report - Juni 2025", status: "Rejected" }
          ]
        },
        { name: "Ahmad Dahan", img: "https://i.pravatar.cc/100?img=12", divisi: "Tata Usaha", phone: "081298765432", email: "ahmad.dahan@email.com", peran:"Pegawai", tingkat:"Level 2",
          laporan: [
            { title: "Laporan Bulanan - Mei 2025", status: "Diterima" },
            { title: "Laporan Bulanan - April 2025", status: "Tertunda" }
          ]
        },
        { name: "Dewi Lestari", img: "https://i.pravatar.cc/100?img=13", divisi: "Tata Usaha", phone: "081223344556", email: "dewi.lestari@email.com", peran:"Staff", tingkat:"Level 1",
          laporan: [
            { title: "Laporan Bulanan - Januari 2025", status: "Diterima" }
          ]
        },
      ],
      produksi: [
        { name: "Rini Sari", img: "https://i.pravatar.cc/100?img=5", divisi: "Produksi Primer", phone:"08130001111", email:"rini@email.com", peran:"Staff", tingkat:"Level 2", laporan:[] },
        { name: "Rudi Hartono", img: "https://i.pravatar.cc/100?img=6", divisi: "Produksi Primer", phone:"08130002222", email:"rudi@email.com", peran:"Staff", tingkat:"Level 1", laporan:[] }
      ],
      panen: [
        { name: "Fitri Handayani", img: "https://i.pravatar.cc/100?img=7", divisi: "Pasca Panen", phone:"08130003333", email:"fitri@email.com", peran:"Staff", tingkat:"Level 1", laporan:[] }
      ],
      labor: [
        { name: "Nisa Rahma", img: "https://i.pravatar.cc/100?img=9", divisi: "LABOR", phone:"08130004444", email:"nisa@email.com", peran:"Staff", tingkat:"Level 1", laporan:[] }
      ],
    };

    // ---- Modal List Team ----
    function showTeam(teamKey){
      const modal = document.getElementById("teamModal");
      const members = document.getElementById("teamMembers");
      const name = document.getElementById("teamName");
      members.innerHTML = "";
      name.textContent = "Anggota " + capitalize(teamKey);
      (teamData[teamKey] || []).forEach((member, idx) => {
        const li = document.createElement("li");
        li.style.display = "flex";
        li.style.alignItems = "center";
        li.style.gap = "12px";
        li.style.padding = "8px";
        li.style.borderRadius = "8px";
        li.style.cursor = "pointer";
        li.onmouseover = () => li.style.background = "#f7f8f8";
        li.onmouseout = () => li.style.background = "transparent";

        const img = document.createElement("img");
        img.src = member.img;
        img.width = 40; img.height = 40; img.style.borderRadius = "50%";
        const span = document.createElement("span");
        span.textContent = member.name + " — " + member.divisi;
        li.appendChild(img);
        li.appendChild(span);

        // saat klik anggota, buka modal detail dan isi datanya
        li.onclick = () => openPegawaiDetail(member);
        members.appendChild(li);
      });

      modal.classList.add("show");
    }

    function closeModal(){
      document.getElementById("teamModal").classList.remove("show");
    }

    // ---- Modal Profil Detail ----
    function openPegawaiDetail(member){
      // Isi data modal detail
      document.getElementById("detailNama").textContent = member.name;
      document.getElementById("detailDivisi").textContent = "Divisi: " + (member.divisi || "-");
      document.getElementById("detailFoto").src = member.img || "https://i.pravatar.cc/120";
      document.getElementById("detailPhone").textContent = member.phone || "-";
      const emailEl = document.getElementById("detailEmail");
      emailEl.textContent = member.email || "-";
      emailEl.href = member.email ? ("mailto:" + member.email) : "#";
      document.getElementById("peranNow").textContent = member.peran || "-";

      // set tingkat saat ini
      const tingkatSelect = document.getElementById("tingkat");
      if(member.tingkat){
        // pilih opsi jika ada
        let found = false;
        for(let i=0;i<tingkatSelect.options.length;i++){
          if(tingkatSelect.options[i].value === member.tingkat){
            tingkatSelect.selectedIndex = i; found = true; break;
          }
        }
        if(!found) tingkatSelect.value = tingkatSelect.options[0].value;
      } else {
        tingkatSelect.selectedIndex = 0;
      }

      // isi daftar laporan
      const laporanList = document.getElementById("laporanList");
      laporanList.innerHTML = "";
      const laporanArr = member.laporan || [];
      if(laporanArr.length === 0){
        const p = document.createElement("div");
        p.style.color = "var(--muted)";
        p.style.fontSize = "14px";
        p.textContent = "Belum ada laporan.";
        laporanList.appendChild(p);
      } else {
        laporanArr.forEach(item => {
          const row = document.createElement("div");
          row.className = "laporan-item";

          const title = document.createElement("div");
          title.style.flex = "1";
          title.style.fontSize = "14px";
          title.textContent = item.title;

          const status = document.createElement("div");
          status.className = "status";
          const sLower = (item.status || "").toLowerCase();
          if(sLower.includes("diterima") || sLower.includes("accepted") || sLower.includes("accept")){
            status.classList.add("success");
            status.textContent = "Diterima";
          } else if(sLower.includes("tertunda") || sLower.includes("pending") || sLower.includes("tertunda")){
            status.classList.add("pending");
            status.textContent = "Tertunda";
          } else if(sLower.includes("reject") || sLower.includes("ditolak") || sLower.includes("rejected")){
            status.classList.add("rejected");
            status.textContent = "Rejected";
          } else {
            status.textContent = item.status || "-";
          }

          row.appendChild(title);
          row.appendChild(status);
          laporanList.appendChild(row);
        });
      }

      // show modal (tutup team modal dulu kalau masih terbuka)
      closeModal();
      const pegModal = document.getElementById("pegawaiDetailModal");
      pegModal.classList.add("show");
    }

    function closePegawaiDetail(){
      document.getElementById("pegawaiDetailModal").classList.remove("show");
    }

    // tombol perbarui tingkat (sementara hanya notifikasi)
    function perbaruiTingkat(){
      const tingkat = document.getElementById("tingkat").value;
      alert("Tingkat berhasil diperbarui menjadi: " + tingkat);
      // di sini kamu bisa panggil API / fetch utk menyimpan perubahan ke backend
    }

    // helper
    function capitalize(s){ if(!s) return s; return s.charAt(0).toUpperCase() + s.slice(1); }

    // close modal ketika klik di luar konten
    window.addEventListener('click', function(e){
      const teamModal = document.getElementById("teamModal");
      const pegModal = document.getElementById("pegawaiDetailModal");
      if(e.target === teamModal) closeModal();
      if(e.target === pegModal) closePegawaiDetail();
    });

    // contoh: buka langsung profil ketika halaman dibuka (opsional)
    // openPegawaiDetail(teamData.tata[0]);
  </script>
</body>
</html>
    
