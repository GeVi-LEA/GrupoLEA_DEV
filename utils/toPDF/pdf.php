<?php

require_once base . '/composer/vendor/autoload.php';
require_once base . '/vendor/autoload.php';

// require (base . '/utils/toPDF/fpdf/fpdf.php');
// use Dompdf\Dompdf;
// use Dompdf\Options;
use Spipu\Html2Pdf\Html2Pdf;

class PDF
{
    public static function crearPdfRequisicion($r, $doc, $mostrar = false)
    {
        $empresa = empresas[intval($r['empresa'])];

        if ($r['firmas'] != null) {
            $firmas = array();
            $firmas = json_decode($r['firmas'], TRUE);
        } else {
            $firmas = array(
                'firma1' => 0,
                'firma2' => 0,
                'firma3' => 0
            );
        }
        $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter-L', 'tempDir' => '/tmp']);
        ob_start();
        require_once views_root . 'compras/formato_req_imprimir.php';

        $html = ob_get_clean();

        ob_end_clean();
        $pdf->writeHTML($html);
        if (!is_dir(views_root . 'compras/requisiciones')) {
            mkdir(views_root . 'compras/requisiciones', 0777, true);
        }
        if (file_exists(views_root . 'compras/requisiciones/' . $r['folio'] . '.pdf')) {
            unlink(views_root . 'compras/requisiciones/' . $r['folio'] . '.pdf');
        }
        $pdf->Output(views_root . 'compras/requisiciones/' . $r['folio'] . '.pdf');
        if ($mostrar) {
            $pdf->output($r['folio'] . '.pdf', 'I');
        }
    }

    public static function crearPdfOrdenCompra($r, $oc, $doc, $cli, $mostrar = false)
    {
        $empresa = empresas[intval($r['empresa'])];
        $moneda  = monedas[intval($r['moneda'])];
        require_once utils_root . 'num2String/num2String.php';
        $totalLetra = Num2String::moneyToString(Utils::quitarComas($oc->total), $moneda);
        $pdf        = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter', 'tempDir' => '/tmp']);
        ob_start();
        require_once views_root . 'compras/formato_orden_imprimir.php';
        $html = ob_get_clean();

        ob_end_clean();
        $pdf->writeHTML($html);
        if (!is_dir(views_root . 'compras/ordenesCompra')) {
            mkdir(views_root . 'compras/ordenesCompra', 0777, true);
        }
        if (file_exists(views_root . 'compras/ordenesCompra/' . $oc->folio . '.pdf')) {
            unlink(views_root . 'compras/ordenesCompra/' . $oc->folio . '.pdf');
        }
        $pdf->Output(views_root . 'compras/ordenesCompra/' . $oc->folio . '.pdf');
        if ($mostrar) {
            $pdf->output($oc->folio . '.pdf', 'I');
        }
    }

    public static function crearPdfEvaluacionProveedor($prov, $eval, $fechaInicio, $fechaFinal, $doc)
    {
        $pregunta1    = 0;
        $pregunta2    = 0;
        $pregunta3    = 0;
        $pregunta4    = 0;
        $evaluaciones = count($eval);
        foreach ($eval as $e) {
            $arrayEval = json_decode($e->evaluacion);

            $pregunta1 += ($arrayEval->pregunta1);
            $pregunta2 += ($arrayEval->pregunta2);
            $pregunta3 += ($arrayEval->pregunta3);
            $pregunta4 += ($arrayEval->pregunta4);
        }

        $prom1 = round($pregunta1 / $evaluaciones);
        $prom2 = round($pregunta2 / $evaluaciones);
        $prom3 = round($pregunta3 / $evaluaciones);
        $prom4 = round($pregunta4 / $evaluaciones);

        $suma = $prom1 + $prom2 + $prom3 + $prom4;

        $promedio     = $suma / 4;
        $calificacion = $promedio * 20;

        $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter', 'tempDir' => '/tmp']);
        ob_start();
        require_once views_root . 'catalogos/formato_eval_prov_transporte_imprimir.php';
        $html = ob_get_clean();
        ob_end_clean();
        $pdf->writeHTML($html);

        $pdf->output();
    }

    public static function crearPdfSolictudServicio($s, $doc)
    {
        $empresa = empresas[intval($s['empresa'])];

        $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter-L', 'tempDir' => '/tmp']);
        ob_start();
        require_once views_root . 'sistemas/formato_solicitud_servicio_imprimir.php';

        $html = ob_get_clean();

        ob_end_clean();
        $pdf->writeHTML($html);
        $pdf->output($s['folio'] . '.pdf', 'I');
    }

    public static function crearPdfServicio($s, $mostrar = false)
    {
        $empresa = empresas[intval(1)];

        $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter-L', 'tempDir' => '/tmp']);
        ob_start();
        require_once views_root . 'servicios/formato_servicio.php';

        $html = ob_get_clean();

        ob_end_clean();
        $pdf->writeHTML($html);

        if ($mostrar) {
            $pdf->output($s->folio . '.pdf', 'I');
        }
    }

    public static function crearPdfEntrada($url = __DIR__, $path = '', $filename = 'temp', $mostrar = true)
    {
        /*
         * try {
         *     // // code...
         *     // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter-L', 'tempDir' => '/tmp']);
         *     // // $mpdf = new \Mpdf\Mpdf();
         *
         *     // // Fetch website content
         *     // $websiteContent = file_get_contents($url);
         *
         *     // $mpdf->WriteHTML($websiteContent);
         *     // // $mpdf->Output();
         *     // // $html = ob_get_clean();
         *
         *     // // ob_end_clean();
         *     // // $pdf->writeHTML($html);
         *     $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter', 'tempDir' => '/tmp']);
         *     // ob_start();
         *     // require_once views_root . 'servicios/formato_servicio.php';
         *     // $html = ob_get_contents();
         *     // ob_end_clean();
         *     // $pdf->writeHTML($html);
         *     $pdf->use_kwt   = true;
         *     $websiteContent = file_get_contents($url);
         *     $pdf->WriteHTML($websiteContent);
         *     if ($mostrar) {
         *         if (!is_dir($path)) {
         *             mkdir($path, 0777, true);
         *         }
         *         $pdf->output($path . '/' . $filename . '3.pdf', 'F');
         *         // $pdf->output($path . '/' . $filename . '.pdf', 'I');
         *     }
         *     return json_encode(['mensaje' => 'OK', 'urlfile' => $path . '/' . $filename . '.pdf', 'websiteContent' => $websiteContent]);
         * } catch (Exception $th) {
         *     return json_encode(['mensaje' => 'ERROR', 'detalle' => $th]);
         * }
         */

        // require_once base . '/vendor/spipu/html2pdf/src/Html2Pdf.php';
        // require_once base . '/vendor/spipu/html2pdf/src/Exception/Html2PdfException.php';
        // require_once base . '/vendor/spipu/html2pdf/src/Exception/ExceptionFormatter.php';

        try {
            // ob_start();
            // include views_root . 'servicios/formato_servicio.php';
            $websiteContent = file_get_contents($url);
            // $content        = ob_get_clean();
            // print_r('<pre>');
            // print_r($path . '/' . $filename . '.pdf');
            // print_r('</pre>');
            // die();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', array(5, 5, 5, 5));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            // $html2pdf->writeHTML($websiteContent);
            // $html2pdf->output(base . '/' . $path . '/' . $filename . '.pdf', 'F');
            // $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($websiteContent);

            $html2pdf->output(base . '/' . $path . '/' . $filename . '.pdf', 'F');
            return json_encode(['mensaje' => 'OK', 'urlfile' => $path . '/' . $filename . '.pdf']);
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            // echo $formatter->getHtmlMessage();
            return json_encode([
                                   'mensaje' => 'ERROR',
                                   'content' => $websiteContent,
                                   'detalle' => $formatter->getHtmlMessage(),
                               ]);
        }
    }
}
