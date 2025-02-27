<?php
// Aquí se muestra el resultado del PDF 
require_once 'vendor/autoload.php';
require_once 'plantillaToners.php';
ini_set("pcre.backtrack_limit", "5000000");
ini_set('max_execution_time', '300');
if(isset($_GET['imprimir'])){
    $css = file_get_contents("mpdf-bootstrap.css");
    $plantilla = getPlantilla($_GET['imprimir']);

    // Dividir el HTML en fragmentos más pequeños (500,000 caracteres)
    $fragmentosHTML = str_split($plantilla, 500000);

    // Crear la instancia de mPDF
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P',
        'pagenumPrefix' => 'Página número ',
        'pagenumSuffix' => ' - ',
        'nbpgPrefix' => ' de ',
        'nbpgSuffix' => ' páginas'
    ]);

    $mpdf->SetHTMLFooter('
    <table width="100%" class="tabla3">
        <tr>
            <td width="50%" style="text-align: right;">Programa de Mantenimiento a Equipos Computo y Revisión de Políticas  2023 - Ver 1.0/{PAGENO}/{nbpg} </td>
        </tr>
    </table>');

    $mpdf->writeHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Escribir cada fragmento de HTML
    foreach ($fragmentosHTML as $fragmento) {
        $mpdf->writeHTML($fragmento, \Mpdf\HTMLParserMode::HTML_BODY);
    }


    $mpdf->output("COMPROBANTE DE RECEPCION DE INSUMOS.pdf", "D");
}
?>
