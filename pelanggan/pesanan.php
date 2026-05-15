<!-- pelanggan/pesanan.php -->

<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

login_required('pelanggan');

$pageTitle = 'Pesanan Saya';

$pid = $_SESSION['id_user'];

$detail = isset($_GET['detail'])
    ? (int)$_GET['detail']
    : 0;

include __DIR__ . '/../includes/header.php';
?>

<h1>📦 Pesanan Saya</h1>

<p
    style="
        margin-bottom:20px;
        color:#64748b;
    "
>
    Riwayat pesanan obat Anda
</p>

<?php if($detail): ?>

<?php

$p = $conn->query("
    SELECT *
    FROM pesanan
    WHERE id_pesanan = $detail
    AND id_user_pelanggan = $pid
")->fetch_assoc();

if(!$p):
?>

<div class="alert error">
    Pesanan tidak ditemukan
</div>

<p>

    <a
        class="btn btn-secondary"
        href="pesanan.php"
    >
        ← Kembali
    </a>

</p>

<?php

else:

$pay = $conn->query("
    SELECT *
    FROM pembayaran
    WHERE id_pesanan = $detail
")->fetch_assoc();

$ship = $conn->query("
    SELECT *
    FROM pengiriman
    WHERE id_pesanan = $detail
")->fetch_assoc();

?>

<div class="card">

    <h2>
        Detail Pesanan #<?= $p['id_pesanan'] ?>
    </h2>

    <p>

        <b>Tanggal:</b>
        <?= $p['tanggal_pesanan'] ?>

        |

        <b>Status:</b>

        <span
            class="badge <?= $p['status_pesanan'] == 'selesai'
                ? 'green'
                : 'yellow'
            ?>"
        >
            <?= $p['status_pesanan'] ?>
        </span>

    </p>

    <table>

        <thead>

            <tr>

                <th>Obat</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $d = $conn->query("
            SELECT
                o.nama_obat,
                o.harga,
                dp.jumlah
            FROM detail_pesanan dp
            JOIN obat o
            ON dp.id_obat = o.id_obat
            WHERE dp.id_pesanan = $detail
        ");

        $tot = 0;

        while($x = $d->fetch_assoc()):

        $sub = $x['harga'] * $x['jumlah'];
        $tot += $sub;

        ?>

        <tr>

            <td>
                <?= htmlspecialchars($x['nama_obat']) ?>
            </td>

            <td>
                Rp <?= number_format($x['harga'],0,',','.') ?>
            </td>

            <td>
                <?= $x['jumlah'] ?>
            </td>

            <td>
                Rp <?= number_format($sub,0,',','.') ?>
            </td>

        </tr>

        <?php endwhile; ?>

        <tr>

            <td colspan="3" style="text-align:right">

                Ongkir

            </td>

            <td>

                Rp <?= number_format(
                    $ship['ongkir'] ?? 0,
                    0,
                    ',',
                    '.'
                ) ?>

            </td>

        </tr>

        <tr>

            <td colspan="3" style="text-align:right">

                <b>Total</b>

            </td>

            <td>

                <b>

                    Rp <?= number_format(
                        $tot + ($ship['ongkir'] ?? 0),
                        0,
                        ',',
                        '.'
                    ) ?>

                </b>

            </td>

        </tr>

        </tbody>

    </table>

    <h2 style="margin-top:18px">
        💳 Pembayaran
    </h2>

    <p>

        Metode:
        <?= htmlspecialchars($pay['metode'] ?? '-') ?>

        |

        Status:

        <span
            class="badge <?= ($pay['status_bayar'] ?? '') === 'lunas'
                ? 'green'
                : 'yellow'
            ?>"
        >
            <?= $pay['status_bayar'] ?? '-' ?>
        </span>

    </p>

    <h2>
        🚚 Pengiriman
    </h2>

    <p>

        Alamat:
        <?= htmlspecialchars($ship['alamat_ngirim'] ?? '-') ?>

        |

        Status:

        <span class="badge blue">
            <?= $ship['status_ngirim'] ?? '-' ?>
        </span>

    </p>

    <p style="margin-top:14px">

        <a
            class="btn btn-secondary"
            href="pesanan.php"
        >
            ← Kembali
        </a>

    </p>

</div>

<?php endif; ?>

<?php else: ?>

<div class="card">

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $r = $conn->query("
            SELECT *
            FROM pesanan
            WHERE id_user_pelanggan = $pid
            ORDER BY id_pesanan DESC
        ");

        if($r->num_rows == 0):
        ?>

        <tr>

            <td
                colspan="4"
                style="text-align:center"
            >

                Belum ada pesanan

            </td>

        </tr>

        <?php endif; ?>

        <?php while($x = $r->fetch_assoc()): ?>

        <tr>

            <td>
                #<?= $x['id_pesanan'] ?>
            </td>

            <td>
                <?= $x['tanggal_pesanan'] ?>
            </td>

            <td>

                <span
                    class="badge <?= $x['status_pesanan']=='selesai'
                        ? 'green'
                        : 'yellow'
                    ?>"
                >
                    <?= $x['status_pesanan'] ?>
                </span>

            </td>

            <td>

                <a
                    class="btn btn-sm"
                    href="?detail=<?= $x['id_pesanan'] ?>"
                >
                    Detail
                </a>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>