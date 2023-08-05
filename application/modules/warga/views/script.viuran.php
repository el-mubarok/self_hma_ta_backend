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
      "url": "<?= site_url('api/user') ?>"
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
          console.log(row);
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
          return row.phone_number;
        }
      },
      {
        render: function(data, type, row) {
          return row.email;
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
  });
</script>