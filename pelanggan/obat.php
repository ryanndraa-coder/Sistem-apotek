<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('pelanggan');
$pageTitle='Beli Obat';
$pid = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $obats=$_POST['obat']??[]; $jumlahs=$_POST['jumlah']??[];
    $alamat=$_POST['alamat'] ?? '';
    $metode=$_POST['metode'] ?? 'COD';
    $hasItem=false; foreach($jumlahs as $j) if((int)$j>0){$hasItem=true;break;}
    if (!$hasItem) { flash('error','Pilih minimal 1 obat'); header('Location: obat.php'); exit; }
    $conn->begin_transaction();
    try {
        $tgl=date('Y-m-d');
        $s=$conn->prepare("INSERT INTO pesanan(tanggal_pesanan,status_pesanan,id_user_pelanggan) VALUES(?,'pending',?)");
        $s->bind_param('si',$tgl,$pid); $s->execute();
        $pesId=$conn->insert_id;
        foreach($obats as $i=>$oid) {
            $j=(int)($jumlahs[$i]??0); $oid=(int)$oid;
            if ($j>0 && $oid) {
                $d=$conn->prepare("INSERT INTO detail_pesanan(id_obat,id_pesanan,jumlah) VALUES(?,?,?)");
                $d->bind_param('iii',$oid,$pesId,$j); $d->execute();
                $conn->query("UPDATE obat SET stok=stok-$j WHERE id_obat=$oid");
            }
        }
        $ongkir='15000';
        $g=$conn->prepare("INSERT INTO pengiriman(alamat_ngirim,ongkir,status_ngirim,id_pesanan) VALUES(?,?,'diproses',?)");
        $g->bind_param('ssi',$alamat,$ongkir,$pesId); $g->execute();
        $b=$conn->prepare("INSERT INTO pembayaran(metode,tanggal_bayar,status_bayar,id_pesanan) VALUES(?,?,'belum',?)");
        $b->bind_param('ssi',$metode,$tgl,$pesId); $b->execute();
        $conn->commit();
        flash('success','Pesanan berhasil! ID #'.$pesId);
        header('Location: pesanan.php'); exit;
    } catch(Exception $e){ $conn->rollback(); flash('error','Gagal: '.$e->getMessage()); header('Location: obat.php'); exit; }
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Beli Obat</h1>
<form method="post">
<div class="card">
<h2>Pilih Obat</h2>
<table><thead><tr><th>Nama</th><th>Jenis</th><th>Harga</th><th>Stok</th><th>Jumlah</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM obat WHERE status_obat='tersedia' AND stok>0");
while($o=$r->fetch_assoc()): ?>
<tr>
<td><?=htmlspecialchars($o['nama_obat'])?><input type="hidden" name="obat[]" value="<?=$o['id_obat']?>"></td>
<td><?=htmlspecialchars($o['jenis_obat'])?></td>
<td>Rp <?=number_format($o['harga'],0,',','.')?></td>
<td><?=$o['stok']?></td>
<td><input type="number" name="jumlah[]" min="0" max="<?=$o['stok']?>" value="0" style="width:80px"></td>
</tr>
<?php endwhile;?>
</tbody></table>
</div>
<div class="card">
<h2>Pengiriman & Pembayaran</h2>
<div class="form-stack">
<div><label>Alamat Pengiriman</label><textarea name="alamat" rows="2" required></textarea></div>
<div><label>Metode Pembayaran</label><select name="metode">
<option>COD</option><option>Transfer BCA</option><option>Transfer Mandiri</option><option>OVO</option><option>GoPay</option>
</select></div>
<div><button class="btn">Buat Pesanan</button></div>
</div></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>

