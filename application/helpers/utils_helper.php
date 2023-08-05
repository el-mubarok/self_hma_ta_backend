<?php

/**
 * Return as JSON format
 * 
 * @param context use $this in controller
 * @param data your data in array/object format
 * @return JSON
 */
function HelperUtilsReturnJSON($context, $code, $data) {
  $context->output
    ->set_status_header($code)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(
      json_encode([
        "code" => $code,
        "data" => $data
      ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );
}