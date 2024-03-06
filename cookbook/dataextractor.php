<?php

###########################################################
## Implement a copy form section button
###########################################################

Markup('cmeditor','directives','/\(:cmeditor\\s+([\\w]+)\\s?(.*?):\)/i', "LoadCMEditor");
function LoadCMEditor($m)
{
  global $HTMLHeaderFmt, $HTMLFooterFmt;
  $id = $m[1];
  $args = ParseArgs($m[2]);
  $class = isset($args['class']) ? $args['class'] : "";
  $style = isset($args['style']) ? $args['style'] : "";
  $mode = isset($args['mode']) ? '"'.$args['mode'].'"' : 'null';

  $HTMLHeaderFmt['cmeditor'] = '
    <script src="$FarmPubDirUrl/codemirror-5.65.16/lib/codemirror.js"></script>
    <script src="$FarmPubDirUrl/codemirror-5.65.16/addon/mode/simple.js"></script>
    <script xsrc="$FarmPubDirUrl/dataextractor/dataextractormode.js"></script>
    <script>var gCM = [];</script>
    <link rel="stylesheet" href="$FarmPubDirUrl/codemirror-5.65.16/lib/codemirror.css"/>
  ';
  $HTMLFooterFmt['cmeditor-'.$id] = '<script type="text/javascript">
    gCM["'.$id.'"] = CodeMirror(document.getElementById("'.$id.'"), {
      mode:  '.$mode.',
      lineWrapping: true
    });
  </script>';
  
  return '<div id="'.$id.'" class="'.$class.'" style="'.$style.'"></div>';
}

Markup('dataextractorbinder','directives','/\(:dataextractorbinder\\s?(.*?):\)/i', "DataExtractorBinder");
function DataExtractorBinder($m)
{
  global $HTMLHeaderFmt, $HTMLFooterFmt;
  $args = ParseArgs($m[1]);
  $inputsource = isset($args['inputsource']) ? $args['inputsource'] : "";
  $highlightedsource = isset($args['highlightedsource']) ? $args['highlightedsource'] : "";
  $resultingcsv = isset($args['resultingcsv']) ? $args['resultingcsv'] : "";

  SDV($HTMLFooterFmt['dataextractor'], 
  "<script type='text/javascript'>
    //gSource = '';
    var gHighlightedSourceID = '$highlightedsource';
    var gResultingCSVInputID = '$resultingcsv';

    document.getElementById('".$inputsource."').addEventListener('change', loadSourceInput);
    document.getElementById('fieldDefsRowcopybutton').addEventListener('click', formatNewRow);
    formatNewRow();
    
    // add oninput listener to all input changing fields
    /*var changingfields = document.getElementsByClassName('changingfield');
    Array.from(changingfields).forEach(function (element) {
      element.addEventListener('input', handleFieldChange);
    });
    */

    document.getElementById('section-list').addEventListener('sectionchange', handleFieldChange);

    gCM[gHighlightedSourceID].on('change', updateCSV);
  </script>");
  
  SDV($HTMLHeaderFmt['dataextractor'], "<script type='text/javascript' src='pub/dataextractor/dataextractor.js'></script>
  <link rel='stylesheet' type='text/css' href='pub/dataextractor/dataextractor.css'>");
  
  return "";
}
?>
