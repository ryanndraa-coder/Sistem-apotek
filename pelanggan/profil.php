<!-- pelanggan/profil.php -->

<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

login_required('pelanggan');

$pageTitle = 'Profil';

$pid = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // tambah no telp
    if (isset($_POST['add_telp'])) {

        $t = trim($_POST['no_telp']);

        $s = $conn->prepare("
            INSERT INTO pelanggan_no_telp
            (
                no_telp,
                id_user_pelanggan
            )
            VALUES (?, ?)
        ");

        $s->bind_param(
            'si',
            $t,
            $pid
        );

        $s->execute();

        flash(
            'success',
            'Nomor telepon berhasil ditambahkan'
        );
    }

    // hapus no telp
    elseif (isset($_POST['del_telp'])) {

        $notelp = $_POST['del_telp'];

        $s = $conn->prepare("
            DELETE FROM pelanggan_no_telp
            WHERE no_telp = ?
            AND id_user_pelanggan = ?
        ");

        $s->bind_param(
            'si',
            $notelp,
            $pid
        );

        $s->execute();

        flash(
            'success',
            'Nomor telepon berhasil dihapus'
        );
    }

    // update profil
    else {

        $n = trim($_POST['nama']);
        $e = trim($_POST['email']);

        $s = $conn->prepare("
            UPDATE user
            SET nama = ?,
                email = ?
            WHERE id_user = ?
        ");

        $s->bind_param(
            'ssi',
            $n,
            $e,
            $pid
        );

        $s->execute();

        $_SESSION['nama'] = $n;

        flash(
            'success',
            'Profil berhasil diupdate'
        );
    }

    header('Location: profil.php');
    exit;
}

$u = $conn->query("
    SELECT *
    FROM user
    WHERE id_user = $pid
")->fetch_assoc();

include __DIR__ . '/../includes/header.php';
?>

<h1>👤 Profil Saya</h1>

<p
    style="
        margin-bottom:20px;
        color:#64748b;
    "
>
    Kelola data akun dan nomor telepon Anda
</p>

<div class="stats">

    <div class="stat">

        <div class="label">
            Role
        </div>

        <div class="value">
            <?= ucfirst($u['role']) ?>
        </div>

    </div>

    <div class="stat">

        <div class="label">
            Email
        </div>

        <div
            class="value"
            style="font-size:1rem"
        >
            <?= htmlspecialchars($u['email']) ?>
        </div>

    </div>

</div>

<div class="card">

    <h2>Data Akun</h2>

    <form method="post" class="form-stack">

        <div>

            <label>Nama</label>

            <input
                type="text"
                name="nama"
                value="<?= htmlspecialchars($u['nama']) ?>"
                required
            >

        </div>

        <div>

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($u['email']) ?>"
                required
            >

        </div>

        <div>

            <button class="btn">
                Update Profil
            </button>

        </div>

    </form>

</div>

<div class="card">

    <h2>📱 Nomor Telepon</h2>

    <table>

        <thead>

            <tr>

                <th>No. Telepon</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $r = $conn->query("
            SELECT *
            FROM pelanggan_no_telp
            WHERE id_user_pelanggan = $pid
        ");

        if($r->num_rows == 0):
        ?>

        <tr>

            <td
                colspan="2"
                style="text-align:center"
            >
                Belum ada nomor telepon
            </td>

        </tr>

        <?php endif; ?>

        <?php while($x = $r->fetch_assoc()): ?>

        <tr>

            <td>
                <?= htmlspecialchars($x['no_telp']) ?>
            </td>

            <td>

                <form method="post">

                    <button
                        type="submit"
                        class="btn btn-danger btn-sm"
                        name="del_telp"
                        value="<?= $x['no_telp'] ?>"
                        onclick="return confirm('Hapus nomor telepon ini?')"
                    >
                        Hapus
                    </button>

                </form>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

    <form
        method="post"
        style="
            display:flex;
            gap:8px;
            margin-top:14px;
        "
    >

        <input
            type="text"
            name="no_telp"
            placeholder="08xxxxxxxxxx"
            required
        >

        <button
            type="submit"
            class="btn"
            name="add_telp"
            value="1"
        >
            Tambah
        </button>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>