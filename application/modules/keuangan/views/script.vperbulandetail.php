<script>
  $.get("<?= site_url() ?>" + "/api/report/session/" + "<?= $_GET['id'] ?>", function(raw) {
    console.log(raw);
    let data = raw.data;

    $('#first-title').html(
      data.name != '' ? data.name : 'no name'
    );

    $('#second-title').html(
      `${data.from_date} sampai ${data.to_date}`
    );
  });

  function columnNumbers(datatable) {
    datatable.on('order.dt search.dt', function() {
      datatable.column(0, {
        search: 'applied',
        order: 'applied'
      }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1
      });
    }).draw();
  }

  // datatable init for table 'iurantable'
  var iurantable = $('#tbliuranbulanan').DataTable({
    // data: datas,
    ajax: {
      url: "<?= site_url() ?>" + "/api/report/session/" + "<?= $_GET['id'] ?>",
      dataSrc: 'data.user',
    },
    // 'dom': 'rtp',
    'pageLength': 10,
    'ordering': false,
    columns: [{
        "bSearchable": false,
        render: function(data, type, row) {
          return '';
        }
      },
      {
        render: function(data, type, row) {
          return row.full_name;
        }
      },
      {
        render: function(data, type, row) {
          return `Blok: ${row.house_block} - Nomor: ${row.house_number}`;
        }
      },
      {
        render: function(data, type, row) {
          if(row.payment) {
            return row.payment.method_name;
          }else{
            return '-';
          }
        }
      },
      {
        render: function(data, type, row) {
          if(row.payment) {
            return 'LUNAS';
          }else{
            return 'BELUM LUNAS';
          }
        }
      },
      {
        render: function(data, type, row) {
          if(row.payment) {
            let value = new Intl.NumberFormat(
              'id', { style: 'currency', currency: 'IDR' }
            ).format(row.payment.grand_total);
            return value;
          }else{
            return '-';
          }
        }
      },
    ],
    order: [
      [0, 'asc']
    ],
  });
  columnNumbers(iurantable);
  // custom search input element
  $("#inp-search").keyup(function() {
    iurantable.search($(this).val()).draw();
  });

  // click function for button in 'aksi' column 
  $(".table").on("click", ".btn-detail", function() {
    let id = $(this).data('id');

    var url = "<?= site_url() ?>" + `keuangan/perbulan/?id=${id}`;
    window.open(url);
  });
</script>