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
      "url": "<?= site_url('api/billing/session?detail=true') ?>"
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
          return row.name != '' ? row.name : 'No name';
        }
      },
      {
        render: function(data, type, row) {
          return row.from_date;
        }
      },
      {
        render: function(data, type, row) {
          return row.to_date;
        }
      },
      {
        render: function(data, type, row) {
          let value = new Intl.NumberFormat(
            'id', { style: 'currency', currency: 'IDR' }
          ).format(row.price);
          return value;
        }
      },
      {
        render: function(data, type, row) {
          return row.payments.length;
        }
      },
      {
        render: function(data, type, row) {
          var $el = $("<button>").attr({
            "type": "button",
            "class": "btn btn-primary btn-sm btn-detail",
            "data-id": row.id
          }).html("Detail");
          return $el.prop("outerHTML");
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
    window.open(url, '_self');
  });
</script>