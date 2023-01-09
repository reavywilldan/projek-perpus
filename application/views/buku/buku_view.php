<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> <?= $title_web; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                            <a href="data/bukutambah"><button class="btn btn-primary">
                                    <i class="fa fa-plus"> </i> Tambah Buku</button></a>
                        <?php } ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <br />
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Sampul</th>
                                        <th>ISBN</th>
                                        <th>Title</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Buku</th>
                                        <th>Stok Buku</th>
                                        <th>Dipinjam</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($buku->result_array() as $isi) { ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td>
                                                <center>
                                                    <?php if (!empty($isi['sampul'] !== "0")) { ?>
                                                        <img src="<?php echo base_url(); ?>assets_style/image/buku/<?php echo $isi['sampul']; ?>" alt="#" class="img-responsive" style="height:auto;width:100px;" />
                                                    <?php } else { ?>
                                                        <!--<img src="" alt="#" class="user-image" style="border:2px solid #fff;"/>-->
                                                        <i class="fa fa-book fa-3x" style="color:#333;"></i> <br /><br />
                                                        Tidak Ada Sampul
                                                    <?php } ?>
                                                </center>
                                            </td>
                                            <td><?= $isi['isbn']; ?></td>
                                            <td><?= $isi['title']; ?></td>
                                            <td><?= $isi['penerbit']; ?></td>
                                            <td><?= $isi['thn_buku']; ?></td>
                                            <td><?= $isi['jml']; ?></td>
                                            <td>
                                                <?php
                                                $id = $isi['buku_id'];
                                                $dd = $this->db->query("SELECT * FROM tbl_pinjam WHERE buku_id= '$id' AND status = 'Dipinjam'");
                                                if ($dd->num_rows() > 0) {
                                                    echo $dd->num_rows();
                                                } else {
                                                    echo '0';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $isi['tgl_masuk']; ?></td>
                                            <td <?php if ($this->session->userdata('level') == 'Petugas') { ?>style="width:17%;" <?php } ?>>

                                                <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                                                    <a href="<?= base_url('data/bukuedit/' . $isi['id_buku']); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
                                                    <a href="<?= base_url('data/bukudetail/' . $isi['id_buku']); ?>">
                                                        <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
                                                    <a href="<?= base_url('data/prosesbuku?buku_id=' . $isi['id_buku']); ?>" onclick="return confirm('Anda yakin Buku ini akan dihapus ?');">
                                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button></a>
                                                <?php } else { ?>
                                                    <a href="<?= base_url('data/bukudetail/' . $isi['id_buku']); ?>">
                                                        <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
                                                <?php } ?>
                                                <button class="btn btn-info" data-code="<?= $id ?>" onclick="printQr(this)"><i class="fa fa-qrcode"></i></button></a>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function printQr(el) {
        const bukuId = el.getAttribute('data-code') ?? 0

        const openPrint = window.open('<?php echo base_url(); ?>assets_style/assets/blank.html')
        openPrint.onload = function() {
            const doc = openPrint.document

            const css = `
                @media print and(width: 21 cm) and(height: 29.7 cm) {
                    @page {
                        margin: 3 cm;
                    }
                }

                /* style sheet for "letter" printing */
                @media print and(width: 8.5 in ) and(height: 11 in ) {
                    @page {
                        margin: 1 in ;
                    }
                }

                /* A4 Landscape*/
                @page {
                    size: A4 landscape;
                    margin: 10 % ;
                }
            `
            const head = doc.head || doc.getElementsByTagName('head')[0]
            const style = document.createElement('style')
            style.type = 'text/css'

            if (style.styleSheet) {
                style.styleSheet.cssText = css
            } else {
                style.appendChild(document.createTextNode(css))
            }

            head.appendChild(style)

            const div = document.createElement('div')
            div.id = "QRImage"
            new QRCode(div, bukuId);

            doc.body.appendChild(div);

            openPrint.document.close();
            openPrint.focus()
            openPrint.print()
        };

        // const div = document.createElement('div')
        // div.id = "QRImage"

        // new QRCode(div, "http://jindo.dev.naver.com/collie");

        // var printContents = div.innerHTML;
        // var originalContents = document.body.innerHTML;
        // document.body.innerHTML = printContents;
        // window.print();
        // document.body.innerHTML = originalContents;
    }
</script>