<table border="1">
    <thead class="table-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama peminjam</th>
            <th scope="col">Judul buku</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col">Tgl pinjam</th>
            <th scope="col">Tenggat</th>
            <th scope="col" class="text-center">Status</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">

        <?php
        foreach ($loans as  $loan) :
            var_dump($loan['member_uid']);
        ?>
            <tr>


            </tr>
        <?php endforeach; ?>
    </tbody>
</table>