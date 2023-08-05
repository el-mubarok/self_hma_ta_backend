<?php

function requestPostData($context, $data, $isStream = false)
{
	$req = $context->input;

	if ($isStream) {
		$rawData = $req->input_stream();
	}else{
		$rawData = $req->post();
	}

	$rawData = array_intersect_key($rawData, array_flip($data));

	return $rawData;
}
