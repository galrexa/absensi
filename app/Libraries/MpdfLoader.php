<?php
namespace App\Libraries;

class MpdfLoader
{
    public static function create($config = [])
    {
        // Skip FPDI jika tidak dibutuhkan
        if (!trait_exists('setasign\\Fpdi\\FpdiTrait')) {
            eval('namespace setasign\\Fpdi { trait FpdiTrait {} }');
        }
        
        $path = APPPATH . 'ThirdParty/mpdf/src/';
        require_once $path . 'Config/ConfigVariables.php';
        require_once $path . 'Config/FontVariables.php';
        require_once $path . 'Mpdf.php';
        
        return new \Mpdf\Mpdf($config);
    }
}
?>