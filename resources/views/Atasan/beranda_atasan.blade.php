@extends('layouts.app')

@section('title', 'Beranda Atasan')

@section('content')
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
      <img src="https://cdn-icons-png.flaticon.com/128/8921/8921211.png" alt="tata"/> Tata Usaha
    </li>
    <li onclick="showTeam('produksi')">
      <img src="https://cdn-icons-png.flaticon.com/128/2257/2257185.png" alt="produksi"/> Produksi Primer
    </li>
    <li onclick="showTeam('panen')">
      <img src="https://cdn-icons-png.flaticon.com/128/88/88528.png" alt="panen"/> Pasca Panen
    </li>
    <li onclick="showTeam('labor')">
      <img src="https://cdn-icons-png.flaticon.com/128/10557/10557880.png" alt="labor"/> LABOR
    </li>
  </ul>
</div>
@endsection

@section('modals')
<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  overflow-y: auto;
  padding: 40px 20px;
}
.modal.show {
  display: block;
}
.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  margin: auto;
  max-width: 90%;
  position: relative;
}
.modal-content .close {
  position: absolute;
  right: 12px; top: 12px;
  font-size: 24px;
  cursor: pointer;
}
.profile-grid {
  display: flex; flex-wrap: wrap; gap: 20px;
}
.profile-grid .card {
  background: #f9f9f9;
  padding: 16px;
  border-radius: 8px;
}
.laporan-item {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  border-bottom: 1px solid #eee;
}
.status.success { color: green; }
.status.pending { color: orange; }
.status.rejected { color: red; }
</style>

<!-- MODAL LIST TEAM -->
<div class="modal" id="teamModal" aria-hidden="true">
  <div class="modal-content" style="width:520px;">
    <span class="close" onclick="closeModal()" title="Tutup">&times;</span>
    <h3 id="teamName" style="margin-top:0;">Daftar Anggota</h3>
    <ul id="teamMembers" style="list-style:none; padding:0; margin:0;"></ul>
  </div>
</div>

<!-- MODAL PROFIL DETAIL -->
<div class="modal" id="pegawaiDetailModal" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="detailNama">
    <span class="close" onclick="closePegawaiDetail()" title="Tutup">&times;</span>
    <h3 style="margin-top:0; margin-bottom:12px;">Profil Pegawai</h3>
    <div class="profile-grid">
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
      <div style="display:flex; flex-direction:column; gap:12px;">
        <div class="card" style="flex:1;">
          <h4 style="margin:0 0 8px 0;">Rangkuman Laporan</h4>
          <div class="laporan-list" id="laporanList"></div>
        </div>
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
const teamData = {
  tata: [{name:"Budi Susanto",img:"https://i.pravatar.cc/100?img=11",divisi:"Tata Usaha",phone:"081234567890",email:"budi.susanto@email.com",peran:"Pegawai",tingkat:"Level 1",laporan:[{title:"Laporan Bulanan - Nov 2024",status:"Diterima"}]}],
  produksi: [{name:"Rini Sari",img:"https://i.pravatar.cc/100?img=5",divisi:"Produksi Primer",phone:"08130001111",email:"rini@email.com",peran:"Staff",tingkat:"Level 2",laporan:[]}],
  panen: [{name:"Fitri Handayani",img:"https://i.pravatar.cc/100?img=7",divisi:"Pasca Panen",phone:"08130003333",email:"fitri@email.com",peran:"Staff",tingkat:"Level 1",laporan:[]}],
  labor: [{name:"Nisa Rahma",img:"https://i.pravatar.cc/100?img=9",divisi:"LABOR",phone:"08130004444",email:"nisa@email.com",peran:"Staff",tingkat:"Level 1",laporan:[]}]
};

function showTeam(teamKey){
  const modal = document.getElementById("teamModal");
  const members = document.getElementById("teamMembers");
  const name = document.getElementById("teamName");
  members.innerHTML = "";
  name.textContent = "Anggota " + capitalize(teamKey);
  (teamData[teamKey]||[]).forEach(member=>{
    const li=document.createElement("li");
    li.style.display="flex"; li.style.alignItems="center"; li.style.gap="12px"; li.style.padding="8px"; li.style.borderRadius="8px"; li.style.cursor="pointer";
    li.onmouseover=()=>li.style.background="#f7f8f8"; li.onmouseout=()=>li.style.background="transparent";
    const img=document.createElement("img"); img.src=member.img; img.width=40; img.height=40; img.style.borderRadius="50%";
    const span=document.createElement("span"); span.textContent=member.name+" — "+member.divisi;
    li.appendChild(img); li.appendChild(span);
    li.onclick=()=>openPegawaiDetail(member);
    members.appendChild(li);
  });
  modal.classList.add("show");
}

function closeModal(){document.getElementById("teamModal").classList.remove("show");}

function openPegawaiDetail(member){
  document.getElementById("detailNama").textContent=member.name;
  document.getElementById("detailDivisi").textContent="Divisi: "+(member.divisi||"-");
  document.getElementById("detailFoto").src=member.img||"https://i.pravatar.cc/120";
  document.getElementById("detailPhone").textContent=member.phone||"-";
  const emailEl=document.getElementById("detailEmail"); emailEl.textContent=member.email||"-"; emailEl.href=member.email?("mailto:"+member.email):"#";
  document.getElementById("peranNow").textContent=member.peran||"-";
  const tingkatSelect=document.getElementById("tingkat");
  for(let i=0;i<tingkatSelect.options.length;i++){if(tingkatSelect.options[i].value===member.tingkat){tingkatSelect.selectedIndex=i; break;}}
  const laporanList=document.getElementById("laporanList"); laporanList.innerHTML="";
  (member.laporan||[]).forEach(item=>{
    const row=document.createElement("div"); row.className="laporan-item";
    const title=document.createElement("div"); title.style.flex="1"; title.style.fontSize="14px"; title.textContent=item.title;
    const status=document.createElement("div"); status.className="status"; 
    const s=item.status.toLowerCase();
    if(s.includes("diterima")){status.classList.add("success"); status.textContent="Diterima";}
    else if(s.includes("tertunda")){status.classList.add("pending"); status.textContent="Tertunda";}
    else if(s.includes("reject")){status.classList.add("rejected"); status.textContent="Rejected";}
    else status.textContent=item.status||"-";
    row.appendChild(title); row.appendChild(status); laporanList.appendChild(row);
  });
  closeModal();
  document.getElementById("pegawaiDetailModal").classList.add("show");
}

function closePegawaiDetail(){document.getElementById("pegawaiDetailModal").classList.remove("show");}

function perbaruiTingkat(){
  const tingkat=document.getElementById("tingkat").value;
  alert("Tingkat berhasil diperbarui menjadi: "+tingkat);
}

function capitalize(s){return s.charAt(0).toUpperCase()+s.slice(1);}

window.addEventListener('click',function(e){
  if(e.target===document.getElementById("teamModal")) closeModal();
  if(e.target===document.getElementById("pegawaiDetailModal")) closePegawaiDetail();
});
</script>
@endsection
