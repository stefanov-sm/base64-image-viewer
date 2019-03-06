<?php

// Magic numbers
const PDFMAGIC = "%PDF",
      PNGMAGIC = "\x89PNG\x0D\x0A\x1A\x0A",
      JPEGMAGIC = "\xFF\xD8\xFF",
      BMPMAGIC = "BM",
      TIFFMAGICA = "II*",
      TIFFMAGICB = "MM*",
      GIFMAGICA = "GIF87a",
      GIFMAGICB = "GIF89a",
      ZIPMAGICA = "PK\x03\x04",
      ZIPMAGICB = "PK\x05\x06";

// Type headers
const PDFHEADER = 'Content-type: application/pdf',
      PNGHEADER = 'Content-Type: image/png',
      JPEGHEADER = 'Content-Type: image/jpg',
      BMPHEADER = 'Content-Type: image/bmp',
      TIFFHEADER = 'Content-Type: image/tiff',
      GIFHEADER = 'Content-Type: image/gif',
      ZIPHEADER = 'Content-Type: application/zip',
      OTHERSHEADER = 'Content-Type: application/octet-stream';

// Disposition headers
const INLINEHEADER = 'Content-Disposition: inline; filename="decoded.TIMESTAMP.image.FILEXT"',
      ZIPATTACH = 'Content-Disposition: attachment; filename="decoded.TIMESTAMP.zip"',
      TIFFATTACH = 'Content-Disposition: attachment; filename="decoded.TIMESTAMP.tif"',
      OTHERSATTACH = 'Content-Disposition: attachment; filename="decoded.TIMESTAMP.file"';

// string like magic%
function mlike($s, $magic)
{
	return (substr($s, 0, strlen($magic)) === $magic);
}
$now = date('ymd.His');
$inlineheader = str_replace('TIMESTAMP', $now, INLINEHEADER);

// Decode the inpust and send it back
if (($outputData = base64_decode($_POST['inputData'])) === FALSE)
{
    echo '<H2>Nope</H2>';
}
else
{
    $prefix = substr($outputData, 0, 16);

    // PDF
    if (mlike($prefix, PDFMAGIC))
    {
        header(PDFHEADER);
        header(str_replace('FILEXT', 'pdf', $inlineheader));
    }
    // PNG
    elseif (mlike($prefix, PNGMAGIC))
    {
        header(PNGHEADER);
        header(str_replace('FILEXT', 'png', $inlineheader));
    }
    // JPEG
    elseif (mlike($prefix, JPEGMAGIC))
    {
        header(JPEGHEADER);
        header(str_replace('FILEXT', 'jpg', $inlineheader));
    }
    // BMP
    elseif (mlike($prefix, BMPMAGIC))
    {
        header(BMPHEADER);
        header(str_replace('FILEXT', 'bmp', $inlineheader));
    }
    // GIF
    elseif (mlike($prefix, GIFMAGICA) || mlike($prefix, GIFMAGICB))
    {
        header(GIFHEADER);
        header(str_replace('FILEXT', 'gif', $inlineheader));
    }
    // TIFF
    elseif (mlike($prefix, TIFFMAGICA) || mlike($prefix, TIFFMAGICB))
    {
        header(TIFFHEADER);
        header(str_replace('TIMESTAMP', $now, TIFFATTACH));
    }
    // ZIP
    elseif (mlike($prefix, ZIPMAGICA) || mlike($prefix, ZIPMAGICB))
    {
        header(ZIPHEADER);
        header(str_replace('TIMESTAMP', $now, ZIPATTACH));
    }
    // All the rest
    else
    {
        header(OTHERSHEADER);
        header(str_replace('TIMESTAMP', $now, OTHERSATTACH));
    }
    echo $outputData;
}
