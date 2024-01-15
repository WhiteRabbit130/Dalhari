<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use SnappyPDF;
// use Illuminate\Support\Facades\Storage;
// use Barryvdh\Snappy\Facades\SnappyPDF;
use PDF;
class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Retrieve Editor.js content from your storage
        // $editorJSData = $request->all(); // Fetch from your database or any storage
        // $htmlContent = $this->handleEditorJS($editorJSData);
        // $pdf = PDF::loadHTML($htmlContent);
        // return $pdf->download('editorjs-document.pdf');
        $html = '<h1>Generate html to PDF</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry<p>';
        
        $pdf= PDF::loadHTML($html);
        // $pdf->download('download.pdf');
        return $pdf->download('download.pdf');
        // return true;
    }

    // Implement your logic to handle Editor.js data and convert it to HTML
    private function handleEditorJS($editorJSData)
    {
        // Your logic to handle Editor.js data and convert it to HTML
        // Replace the next line with your actual implementation
        // $obj = json_decode($editorJSData);
        // dd($editorJSData['blocks']);
        $html = '';
        foreach ($editorJSData['blocks'] as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $html .= '<p>' . $block['data']['text'] . '</p>';
                    break;
                
                case 'header':
                    $html .= '<h'. $block['data']['level'] .'>' . $block['data']['text'] . '</h'. $block['data']['level'] .'>';
                    break;

                case 'raw':
                    $html .= $block['data']['html'];
                    break;

                case 'list':
                    $lsType = ($block['data']['style'] == 'ordered') ? 'ol' : 'ul';
                    $html .= '<' . $lsType . '>';
                    foreach($block['data']['items'] as $item) {
                        $html .= '<li>' . $item . '</li>';
                    }
                    $html .= '</' . $lsType . '>';
                    break;
                
                case 'code':
                    $html .= '<pre><code class="language-'. $block['data']['lang'] .'">'. $block['data']['code'] .'</code></pre>';
                    break;
                
                case 'image':
                    $html .= '<div class="img_pnl"><img src="'. $block-['data']['file']['url'] .'" /></div>';
                    break;
                
                default:
                    break;
            }
        }
        
        return $html;
    }
}
