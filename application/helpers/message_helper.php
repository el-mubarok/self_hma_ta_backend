<?php

/**
 * @property M_Common $modelCommon
 */
function messageBill($context, $type, $status, $billName)
{
  $context->load->model('api/M_Common', 'modelCommon');
  $message = '';

  if ($type == 'va') {
    switch ($status) {
      case 'PENDING':
        $message = 'Permintaan pembayaran anda sedang diproses oleh bank.';
        break;
      case 'ACTIVE':
        $message = "Menunggu pembayaran tagihan $billName";
        break;
      case 'INACTIVE':
        $message = "Metode pembayaran ini telah expired.";
        break;
      case 'SUCCESS':
        $message = "Tagihan $billName berhasil dibayarkan, terima kasih.";
        break;
    }
  } else if ($type == 'ewallet') {
    switch ($status) {
      case 'REFUNDED':
        $message = "";
        break;
      case 'VOIDED':
        $message = "";
        break;
      case 'FAILED':
        $message = "Pembayaran gagal, silahkan coba kembali atau gunakan metode pembayaran lain.";
        break;
      case 'PENDING':
        $message = "Menunggu pembayaran tagihan $billName";
        break;
      case 'SUCCEEDED':
        $message = "Tagihan $billName berhasil dibayarkan, terima kasih.";
        break;
    }
  }

  return $message;
}

function messageBillSuccess($date)
{
  $month = date('M', strtotime($date));
  $year = date('Y', strtotime($date));

  return "Tagihan bulan $month $year berhasil dibayarkan, terima kasih.";
}
