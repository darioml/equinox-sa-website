<?php

$text = array(
    '50'=>array(
        "name" => "Test Customer",
        "address" => "Imperial College Road, SW72AZ",
        "boxID" => "s00001",
        "codes" => array(
            "14241348","98574839","39481750"),
        "totalpay" => "1374",
        "freedays" => "0"
    )
);

echo json_encode($text);