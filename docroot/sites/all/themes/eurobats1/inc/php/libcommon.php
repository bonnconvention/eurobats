<?php
function checkIE() {
?>
<!--[if IE]>
<script type="text/javascript">
isIE = true;
</script>
<![endif]-->
<!--[if IE 6]>
<script type="text/javascript">
IEVersion = 6;
</script>
<![endif]-->
<!--[if IE 7]>
<script type="text/javascript">
IEVersion = 7;
</script>
<![endif]-->
<!--[if IE 8]>
<script type="text/javascript">
IEVersion = 8;
</script>
<![endif]-->
<!--[if IE 9]>
<script type="text/javascript">
IEVersion = 9;
</script>
<![endif]-->
<!--[if IE 10]>
<script type="text/javascript">
IEVersion = 10;
</script>
<![endif]-->
<?php
}

function escapeString( $str ) {
    //if ( !get_magic_quotes_gpc() ) {
        return str_replace("\r\n", "\\n",
                    str_replace( "\r", "\\n",
                        str_replace( "\n","\\n",
                            str_replace( "'","\\'",
                                str_replace( "\"","\\\"",
                                    str_replace( "\\","\\\\", $str )
                                )
                            )
                        )
                    )
                );
    //}
    //return str_replace( "\n","\\n", $str );
    //Str.replace(/\r\n|\r|\n/g,"\\n");
}

function escapeStringExt( $str, $excludeSymbol = " ", $clearSymbol= " " ) {
    $patterns = Array(
        Array( "\\", "\\\\" ),
        Array( "\"","\\\"" ),
        Array( "'","\\'" ),
        Array( "\r\n","\\n" ),
        Array( "\r", "\\n" ),
        Array( "\n", "\\n" )
         );
    for ( $i = 0; $i < count( $patterns ); $i++ ) {
        if ( preg_match( "/(^|;)(" . $patterns[ $i ][ 0 ] . ")($|;)/", $clearSymbol ) )
            $str = str_replace( $patterns[ $i ][ 0 ], "", $str );
        if ( !preg_match( "/(^|;)(" . $patterns[ $i ][ 0 ] . ")($|;)/", $excludeSymbol ) )
            $str = str_replace( $patterns[ $i ][ 0 ], $patterns[ $i ][ 1 ], $str );
        }
    return $str;

}

function checkURL( $url ) {
    if ( $url != "" )
        if ( strpos( substr( $url, 0, 7 ), "http://" ) === false ) $url = "http://" . $url;
    return $url;
}

?>