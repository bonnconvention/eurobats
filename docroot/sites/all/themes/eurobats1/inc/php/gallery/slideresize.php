<?php
    @ $dHeight    = 0 + $_GET[ "p0" ];
    @ $uri        = ltrim($_GET[ "p1" ]);

    $dHeight = (($dHeight == 0)?246:$dHeight);
    $path   =   str_replace(' ', '%20', $uri);

    @list( $width, $height, $type, $attr ) = @getimagesize( $path );
    //echo $width . " # " . $height . " # " . $type . " # " . isset( $type );
    if ( !isset( $width ) || !isset( $height ) || !isset( $type ) ) {
        $width = $dHeight * 2; $height = $dHeight; $type = -1;
    }
    if ( $height != $dHeight ) {
        $dWidth = $width * ( $dHeight / $height );
    }
    else {
        $dWidth = $width;
    }

    $im = @imagecreatetruecolor( $dWidth, $dHeight );
    $sim = @imagecreatefromstring(file_get_contents($path));
    
    if ($sim != TRUE) {
        $sim = imagecreatetruecolor( $dWidth, $dHeight );
        $bgc = imagecolorallocate( $sim, 255, 255, 255 );
        $tc  = imagecolorallocate( $sim, 255, 0, 0 );
        imagefilledrectangle( $sim, 0, 0, $dWidth, $dHeight, $bgc );
        imagestring( $sim, 5, 5, 105, "Error loading ", $tc );
        imagestring( $sim, 2, 5, 120, $uri, $tc );
    }
    //imagecopyresampled( $im, $sim, 0, 0, 0, 0, $dWidth, $dHeight, $width, $height );
    imagecopyresized( $im, $sim, 0, 0, 0, 0, $dWidth, $dHeight, $width, $height );
    header( "Content-type: image/jpeg" );
    imagejpeg( $im );
?>