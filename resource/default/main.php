<?php
/** @var \App\GD\DMI $dmi */
$dmi->main->drawText(
    'aaaa',
    $dmi->Point(0,0),
    48,
    $dmi->Font('msyh.ttf'),
    $dmi->Color(0,255,0),
    0
);
$ap=$dmi->Image->fromString($dmi->resData('map.png','image'));
$dmi->main->drawImage($ap,
    $dmi->Box($dmi->Point(0,0),
    $dmi->Vector(256,256)),
    $dmi->Box($dmi->Point(0,0),
        $dmi->Vector(100,100)),
    100
    );
