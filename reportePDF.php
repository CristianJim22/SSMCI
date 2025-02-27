<?php

// Aquí se muestra el resultado del PDF 
require_once 'vendor/autoload.php';
require_once 'plantilla.php';
ini_set("pcre.backtrack_limit", "5000000");
ini_set('max_execution_time', '300');
if(isset($_GET['imprimir']) && isset($_GET['periodo'])){
    $css = file_get_contents("mpdf-bootstrap.css");
    $folio =$_GET['imprimir'];
    $periodo =$_GET['periodo'];
    $plantilla = getPlantilla($folio, $periodo);

    // Dividir el HTML en fragmentos más pequeños (500,000 caracteres)
    $fragmentosHTML = str_split($plantilla, 500000);

    // Crear la instancia de mPDF
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L',
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


    $mpdf->output("CHECKLIST DE EQUIPO DE CÓMPUTO.pdf", "D");
}
?>
