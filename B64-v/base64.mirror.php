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
const INLINEHEADER = 'Content-Disposition: inline; filename="B64-v image"',
      ZIPATTACH = 'Content-Disposition: attachment; filename="decoded.__TIMESTAMP__.zip"',
      TIFFATTACH = 'Content-Disposition: attachment; filename="decoded.__TIMESTAMP__.tif"',
      OTHERSATTACH = 'Content-Disposition: attachment; filename="decoded.__TIMESTAMP__.file"';

if (($inputData = base64_decode($_POST['inputData'], TRUE)) === FALSE)
{
    echo '<H2>Nope</H2>';
}
else
{
    $prefix = substr($inputData, 0, 16);

    // PDF
    if (substr($prefix, 0, strlen(PDFMAGIC)) == PDFMAGIC)
    {
        header(PDFHEADER);
        header(INLINEHEADER);
    }
    // PNG
    elseif (substr($prefix, 0, strlen(PNGMAGIC)) == PNGMAGIC)
    {
        header(PNGHEADER);
        header(INLINEHEADER);
    }
    // JPEG
    elseif (substr($prefix, 0, strlen(JPEGMAGIC)) == JPEGMAGIC)
    {
        header(JPEGHEADER);
        header(INLINEHEADER);
    }
    // BMP
    elseif (substr($prefix, 0, strlen(BMPMAGIC)) == BMPMAGIC)
    {
        header(BMPHEADER);
        header(INLINEHEADER);
    }
    // GIF
    elseif ((substr($prefix, 0, strlen(GIFMAGICA)) == GIFMAGICA) || 
            (substr($prefix, 0, strlen(GIFMAGICB)) == GIFMAGICB))
    {
        header(GIFHEADER);
        header(INLINEHEADER);
    }
    // TIFF
    elseif ((substr($prefix, 0, strlen(TIFFMAGICA)) == TIFFMAGICA) || 
            (substr($prefix, 0, strlen(TIFFMAGICB)) == TIFFMAGICB))
    {
        header(TIFFHEADER);
        header(str_replace('__TIMESTAMP__', date('Ymd.His'), TIFFATTACH));
    }
    // ZIP
    elseif ((substr($prefix, 0, strlen(ZIPMAGICA)) == ZIPMAGICA) ||
            (substr($prefix, 0, strlen(ZIPMAGICB)) == ZIPMAGICB))
    {
        header(ZIPHEADER);
        header(str_replace('__TIMESTAMP__', date('Ymd.His'), ZIPATTACH));
    }
    // All the rest
    else
    {
        header(OTHERSHEADER);
        header(str_replace('__TIMESTAMP__', date('Ymd.His'), OTHERSATTACH));
    }
    echo $inputData; // No ketchup just sauce raw sauce
}