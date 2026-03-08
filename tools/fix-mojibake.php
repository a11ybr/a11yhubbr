<?php
$root = __DIR__;
$map = [
  'á'=>'á','â'=>'â','ã'=>'ã','ê'=>'ê','é'=>'é','í'=>'í','ó'=>'ó','ô'=>'ô','õ'=>'õ','ú'=>'ú','ç'=>'ç',
  'É'=>'É','Á'=>'Á','À'=>'À','Ó'=>'Ó','Ú'=>'Ú','Ç'=>'Ç',
  'á'=>'á','â'=>'â','ã'=>'ã','ê'=>'ê','é'=>'é','í'=>'í','ó'=>'ó','ô'=>'ô','õ'=>'õ','ú'=>'ú','ç'=>'ç',
  'É'=>'É','Á'=>'Á','À'=>'À','Ó'=>'Ó','Ú'=>'Ú','Ç'=>'Ç',
  'º'=>'º','ª'=>'ª','°'=>'°',
  'conteúdo'=>'conteúdo','atualização'=>'atualização','crítica'=>'crítica','dúvida'=>'dúvida','página'=>'página',
];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$changed = 0;
foreach ($it as $file) {
  if (!$file->isFile()) continue;
  $path = $file->getPathname();
  if (strpos($path, DIRECTORY_SEPARATOR.'node_modules'.DIRECTORY_SEPARATOR) !== false) continue;
  if (substr($path, -4) !== '.php') continue;
  $txt = file_get_contents($path);
  $new = strtr($txt, $map);
  if ($new !== $txt) {
    file_put_contents($path, $new);
    $changed++;
  }
}
echo "changed_files={$changed}\n";
