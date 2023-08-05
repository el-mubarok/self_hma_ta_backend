<script>

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
      url: "<?= site_url() ?>" + "/api/report"
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
          return row.user.updated_at;
        }
      },
      {
        render: function(data, type, row) {
          return row.user.full_name;
        }
      },
      {
        render: function(data, type, row) {
          return `Blok: ${row.user.house_block} - Nomor: ${row.user.house_number}`;
        }
      },
      {
        render: function(data, type, row) {
          return row.method_name;
        }
      },
      {
        render: function(data, type, row) {
          return 'LUNAS';
        }
      },
      {
        render: function(data, type, row) {
          let value = new Intl.NumberFormat(
            'id', { style: 'currency', currency: 'IDR' }
          ).format(row.grand_total);
          return value;
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